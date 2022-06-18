<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Getnet\SplitExampleMagento\Helper;

use Getnet\SplitExampleMagento\Model\Config;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\Store;

/**
 * Sub Seller Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Config
     */
    protected $configSplit;

    /**
     * Constructor
     *
     * @param Config $configSplit
     */
    public function __construct(
        Config $configSplit
    ) {
        $this->configSplit = $configSplit;
    }

    /**
     * Get List Commissions Formated.
     *
     * @param null|string|bool|int|Store $store
     * @return array
     */
    public function getSplitCommissions($store = null)
    {
        $list = $this->configSplit->getSplitCommissions($store);
        $listCommissions = [];

        foreach ($list as $commission) {
            $sellerId = $commission["sub_seller_id"];

            $listCommissions[$sellerId] = [
                "commission_percentage" => $commission["commission_percentage"],
                "include_freight" => ($commission["include_freight"] === "full") ? true : false,
                "include_interest" => ($commission["include_interest"] === "full") ? true : false,
            ];
        }
        return $listCommissions;
    }

    /**
     * Get Split Commission By Sub Seller Id.
     *
     * @param string $subSellerId
     * @param null|string|bool|int|Store $store
     *
     * @return array
     */
    public function getSplitCommissionsBySubSellerId(string $subSellerId, $store = null): ?array
    {
        $commission = $this->getSplitCommissions($store);

        if (!isset($commission[$subSellerId])) {
            return $commission['any'];
        }
        
        return $commission[$subSellerId];
    }
}
