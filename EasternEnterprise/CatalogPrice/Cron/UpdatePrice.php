<?php

declare(strict_types=1);

namespace EasternEnterprise\CatalogPrice\Cron;

use EasternEnterprise\CatalogPrice\Helper\Data;
use EasternEnterprise\CatalogPrice\Model\Api\HttpRequest;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class UpdatePrice
{
	/**
	 * @var CollectionFactory
	 */
	private $productCollectionFactory;
	
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
		Data $data,
		HttpRequest $request,
		array $data = []
	) {    
		$this->productCollectionFactory = $productCollectionFactory;
		$this->helper = $data;
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
				$this->request->execute($product->getSku());
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