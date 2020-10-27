<?php

declare(strict_types=1);

namespace EasternEnterprise\CatalogPrice\Model\Api;

use EasternEnterprise\CatalogPrice\Helper\Data;
use Magento\Framework\HTTP\ZendClient;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class HttpRequest
 * @package EasternEnterprise\CatalogPrice\Model\Api
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class HttpRequest
{
    /**
     * @var ZendClient
     */
    private $httpClient;

    /**
     * @var Helper
     */
    private $dataHelper;

    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * @var LoggerInterface
     */
    private $logger;
	
	public const DEMO_PRICE = 100;

    public function __construct(
        ZendClient $httpClient,
        Json $jsonSerializer,
        LoggerInterface $logger,
        Data $dataHelper
    ) {
        $this->httpClient = $httpClient;
        $this->jsonSerializer = $jsonSerializer;
        $this->logger = $logger;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Send an HTTP request
     *
     * @param  string $action to check call the API endpoint
     *
     * @return false|null|string
     */
    public function execute($sku = null)
    {
        $requestUrl = $this->dataHelper->getApiEndpoint();
		$this->method = ZendClient::GET;

        try
        {
            if (!$this->dataHelper->isEnabled()) {
                $errorMessage = 'Module EasternEnterprise CatalogPrice is not Enabled';
                $this->logger->error($errorMessage);
				return false;
            }
			
			if ($this->dataHelper->isDemoPriceEnabled()) {
				return self :: DEMO_PRICE;
			}
			
			$this->setHeaders();

			$this->httpClient
				->setUri($requestUrl)
				->setMethod($this->method)
				->setConfig([
					'timeout'     => 30,
					'curloptions' => [CURLOPT_FOLLOWLOCATION => true],
				]);
			if (is_array($sku)) {
				$parameters = 'sku=' . implode(",", $sku);
			}
			else {
				$parameters = 'sku=' . $sku;
			}
			$this->httpClient->setParameterPost($parameters);

            $responseObject = $this->httpClient->request();
            $responseBody = $responseObject->getBody();
            $apiResponse = $this->jsonSerializer->unserialize($responseBody);
			return $apiResponse;

        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage());
        }

        return false;
    }
	
	/**
     * Function to get Basic Authenticiation Header
     *
     * @return array
     */
	private function getHeaders()
    {
        $username = $this->dataHelper->getApiAuthUsername();
        $password = $this->dataHelper->getApiAuthPassword();
        $headers = [];
        $headers[] = "Authorization: Basic " . base64_encode($username . ':' . $password);
        return $headers;
    }
	
	/**
     * Function to return access token
     *
     * @return string
     */
	private function getAccessToken()
	{
		$this->httpClient
			->setUri($this->dataHelper->getApiAuthUrl())            
			->setMethod(Zend_Http_Client::POST)
			->setConfig(['timeout' => 30])
			->setHeaders($this->getHeaders())
			->setParameterGet([
				'grant_type' => 'Bearer',
			]);                         
		$responseObject = $this->httpClient->request();
		
		if ($apiResponse = $responseObject->getBody()) {
			$apiResponse = $this->jsonSerializer->unserialize($apiResponse);
			if ($apiResponse === null) {
				$this->logger->critical("EasternEnterprise CatalogPrice: Could not decode JSON response body");
				return false;
			}
			
			if (empty($apiResponse['access_token']) || empty($apiResponse['expires_in'])) {
                $this->logger->critical('EasternEnterprise CatalogPrice: Blank access token or expiry received during access token request');
            }
			
			return $apiResponse['access_token'];
		}
		else {
			$this->logger->critical('EasternEnterprise CatalogPrice: Error during the Access Token Request');
		}
	}

    /**
     * @return \Zend_Http_Client
     * @throws \Zend_Http_Client_Exception
     */
    private function setHeaders()
    {
        if ($this->method == ZendClient::GET) {
            $headers = ['Accept-encoding' => 'utf-8'];
        } else {
            $headers = [
                'Content-Type'    => 'application/json',
                'Accept-encoding' => 'utf-8'
            ];
        }
        if ($accessToken = $this->getAccessToken()) {
            $headers['Authorization'] = "Bearer {$accessToken}";
        }
        return $this->httpClient->setHeaders($headers);
    }
}

