<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Configuration paths storage
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Getnet\SplitExampleMagento\Model;

use Magento\Store\Model\Store;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class to set flags for sub seller display setting
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Config
{
    /**
     * List Commissions
     */
    public const XML_PATH_GETNET_SPLIT_COMMISSIONS ='getnet_split/general/split_commisions/commisions';

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Core Json
     *
     * @var Json
     */
    protected $json;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Json $json
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Json $json
    ) {
        $this->json = $json;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Get Split Commissions.
     *
     * @param   null|string|bool|int|Store $store
     * @return  array
     */
    public function getSplitCommissions($store = null)
    {
        $listCommissions = $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_SPLIT_COMMISSIONS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        return $this->json->unserialize($listCommissions);
    }
}
