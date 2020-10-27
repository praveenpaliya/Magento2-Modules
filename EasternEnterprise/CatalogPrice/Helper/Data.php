<?php

declare(strict_types=1);

namespace EasternEnterprise\CatalogPrice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context as HelperContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var HttpContext
     */
    private $httpContext;

    public const XML_PATH_CONFIG_CATALOGPRICE_ENABLED = 'easternenterprise_catalogprice/general/enabled';
	public const XML_PATH_CONFIG_CATALOGPRICE_IS_DEMO = 'easternenterprise_catalogprice/general/is_demo';
	public const XML_PATH_CONFIG_CATALOGPRICE_FETCH_PRICE = 'easternenterprise_catalogprice/general/fetch_price';
    public const XML_PATH_CONFIG_CATALOGPRICE_ENDPOINT = 'easternenterprise_catalogprice/general/endpoint';
    public const XML_PATH_CONFIG_CATALOGPRICE_OAUTH_URL = 'easternenterprise_catalogprice/general/oauth_url';
    public const XML_PATH_CONFIG_CATALOGPRICE_OAUTH_USERNAME = 'easternenterprise_catalogprice/general/oauth_username';
    public const XML_PATH_CONFIG_CATALOGPRICE_OAUTH_PASSWORD = 'easternenterprise_catalogprice/general/oauth_password';
	
	public function __construct(
        HelperContext $context,
        HttpContext $httpContext,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->httpContext = $httpContext;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }
	
	private function getStoreValue($key, $storeCode = null)
	{
		return $this->scopeConfig->getValue(
            $key,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
	}
	
	/**
     * Check if module is enabled
     *
     * @param int|string|null $storeCode
     * @return boolean
     */
    public function isEnabled($storeCode = null)
    {
        return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_ENABLED,
            $storeCode
        );
    }
	
	/**
     * Check if Demo Price is Enabled
     *
     * @param int|string|null $storeCode
     * @return boolean
     */
	public function isDemoPriceEnabled($storeCode = null)
	{
		return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_IS_DEMO,
            $storeCode
        );
	}
	
	/**
     * Check if Price needs to be udpated by Cron or fetch at runtime
     *
     * @param int|string|null $storeCode
     * @return boolean
     */
    public function updateByCron($storeCode = null)
    {
        return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_FETCH_PRICE,
            $storeCode
        )? 0 : 1;
    }
	
	/**
     * Check if Price needs to be fetched Live
     *
     * @param int|string|null $storeCode
     * @return boolean
     */
    public function fetchLivePrice($storeCode = null)
    {
        return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_FETCH_PRICE,
            $storeCode
        );
    }
	
	/**
     * fetch API End Point
     *
     * @param int|string|null $storeCode
     * @return boolean
     */
    public function getApiEndPoint($storeCode = null)
    {
        return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_ENDPOINT,
            $storeCode
        );
    }
	
	/**
     * fetch Oauth URL
     *
     * @param int|string|null $storeCode
     * @return string
     */
    public function getApiAuthUrl($storeCode = null)
    {
        return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_OAUTH_URL,
            $storeCode
        );
    }
	
	/**
     * fetch API Auth Username
     *
     * @param int|string|null $storeCode
     * @return string
     */
    public function getApiAuthUsername($storeCode = null)
    {
        return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_OAUTH_USERNAME,
            $storeCode
        );
    }
	
	/**
     * fetch API Auth Passwrod
     *
     * @param int|string|null $storeCode
     * @return string
     */
    public function getApiAuthPassword($storeCode = null)
    {
        return $this->getStoreValue(
            self::XML_PATH_CONFIG_CATALOGPRICE_OAUTH_PASSWORD,
            $storeCode
        );
    }
	
}
