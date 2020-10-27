<?php

declare(strict_types=1);

namespace EasternEnterprise\CatalogPrice\Plugin;

use EasternEnterprise\CatalogPrice\Helper\Data;
use EasternEnterprise\CatalogPrice\Model\Api\HttpRequest;
use Magento\Catalog\Model\Product as CatalogProduct;

class Product
{
	/**
	 * @var Data
	 */
	private $helper;
	
	/**
	 * @var HttpRequest
	 */
	private $request;
	
	/**
	 * Constructor Class
	 */
	public function __construct(
		Data $data,
		HttpRequest $request
	) {
        $this->helper = $data;
		$this->request = $request;
    }
	
	/**
	 * After Plugin on the method getPrice
	 * @return price
	 */
    public function afterGetPrice(CatalogProduct $product, $result)
    {
		$sku = $product->getData('sku');
		if ($this->helper->isEnabled() && $this->helper->fetchLivePrice()) {
			return $this->request->execute($sku);
		}
        else {
			return $result;
		}
    }
}