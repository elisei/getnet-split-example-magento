<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Configuration paths storage.
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */

namespace Getnet\SplitExampleMagento\Model;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\Store;

/**
 * Class to set flags for sub seller display setting.
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Config
{
    /**
     * List Commissions.
     */
    public const XML_PATH_GETNET_SPLIT_COMMISSIONS = 'getnet_split/general/split_commisions/commisions';

    /**
     * Guarantor Name.
     */
    public const XML_PATH_GETNET_SPLIT_GUARANTOR_NAME = 'getnet_split/general/addtional_boleto/guarantor_name';

    /**
     * Guarantor Document.
     */
    public const XML_PATH_GETNET_SPLIT_GUARANTOR_DOCUMENT = 'getnet_split/general/addtional_boleto/guarantor_document';

    /**
     * Core store config.
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Core Json.
     *
     * @var Json
     */
    protected $json;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Json                                               $json
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
     * @param null|string|bool|int|Store $store
     *
     * @return array
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

    /**
     * Get Guarantor Name.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string|null
     */
    public function getGuarantorName($store = null): ?string
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_SPLIT_GUARANTOR_NAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get Guarantor Document.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string|null
     */
    public function getGuarantorDocument($store = null): ?string
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_GETNET_SPLIT_GUARANTOR_DOCUMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
