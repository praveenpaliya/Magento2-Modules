<?php

declare(strict_types=1);

namespace EasternEnterprise\CatalogPrice\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class FetchPrice implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {

        return [
            ['value' => 0, 'label' => __('By Cron Job')],
            ['value' => 1, 'label' => __('Runtime')],
        ];
    }
}