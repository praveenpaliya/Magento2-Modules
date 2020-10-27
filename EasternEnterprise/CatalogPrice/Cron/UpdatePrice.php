<?php

declare(strict_types=1);

namespace EasternEnterprise\CatalogPrice\Cron;

use EasternEnterprise\CatalogPrice\Helper\Data;
use EasternEnterprise\CatalogPrice\Model\Api\HttpRequest;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ProductFactory;

class UpdatePrice
{
	/**
	 * @var CollectionFactory
	 */
	private $productCollectionFactory;
	
	/**
	 * @var ProductFactory
	 */
	private $productFactory;
	
	/**
	 * @var Data
	 */
	private $helper;
	
	/**
	 * @var HttpRequest
	 */
	private $request;
	
	public function __construct(
		Context $context,
		CollectionFactory $productCollectionFactory,
		ProductFactory $productFactory,
		Data $helperData,
		HttpRequest $request,
		array $data = []
	) {    
		$this->productCollectionFactory = $productCollectionFactory;
		$this->productFactory = $productFactory;
		$this->helper = $helperData;
		$this->request = $request;
		
		parent::__construct($context, $data);
	}
	
	public function execute()
	{
		if ($this->helper->isEnabled() && $this->helper->updateByCron()) {
			$collection = $this->getProductCollection();
			/**
			 * @Todo - If there are limited skus then run the loop else can create the batch for few skus together to process
			*/
			foreach ($collection as $product) {
				$price = $this->request->execute($product->getSku());
				$productModel = $this->productFactory->create()->load($product->getId());
				$productModel->setPrice($price);
				$productModel->save();
			}
		}
	}
	
	/**
	 * function to get Products data
	 */
	private function getProductCollection()
	{
		$collection = $this->productCollectionFactory->create();
		$collection->addAttributeToSelect('*');
		return $collection;
	}
}