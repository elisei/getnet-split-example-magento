<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Getnet\SplitExampleMagento\Plugin\Getnet\PaymentMagento\Gateway\Request;

use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Store\Model\ScopeInterface;
use Getnet\PaymentMagento\Gateway\Config\Config;
use Getnet\PaymentMagento\Gateway\Config\ConfigCc;
use Getnet\PaymentMagento\Gateway\Data\Order\OrderAdapterFactory;
use Getnet\PaymentMagento\Gateway\Request\SplitPaymentDataRequest;
use Getnet\PaymentMagento\Gateway\SubjectReader;

class FetchSubSellerIdToSplitPayment
{
    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var OrderAdapterFactory
     */
    private $orderAdapterFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var configCc
     */
    private $configCc;

    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param SubjectReader        $subjectReader
     * @param OrderAdapterFactory  $orderAdapterFactory
     * @param Config               $config
     * @param ConfigCc             $configCc
     * @param PriceHelper          $checkoutHelper
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        SubjectReader $subjectReader,
        OrderAdapterFactory $orderAdapterFactory,
        Config $config,
        ConfigCc $configCc,
        PriceHelper $checkoutHelper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->subjectReader = $subjectReader;
        $this->orderAdapterFactory = $orderAdapterFactory;
        $this->config = $config;
        $this->configCc = $configCc;
        $this->priceHelper = $checkoutHelper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Around method Build.
     *
     * @param SplitPaymentDataRequest $subject
     * @param \Closure $proceed,
     * @param mixin $buildSubject
     *
     * @return array
     */
    public function aroundBuild(
        SplitPaymentDataRequest $subject,
        \Closure $proceed,
        $buildSubject
    ) {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        $payment = $paymentDO->getPayment();

        $result = [];

        $orderAdapter = $this->orderAdapterFactory->create(
            ['order' => $payment->getOrder()]
        );

        $order = $paymentDO->getOrder();

        $storeId = $order->getStoreId();
        
        $grandTotal = $order->getGrandTotalAmount();

        $items = $order->getItems();

        $itemcount = count($items);
        foreach ($items as $item) {
            if ($item->getParentItem()) {
                continue;
            }

            if (!$item->getProduct()->getGetnetSubSellerId()) {
                continue;
            }

            $sellerId = $item->getProduct()->getGetnetSubSellerId();
            $price =  $item->getPrice() * $item->getQtyOrdered();
            $commision = $price * 0.9;
            $productBySeller[$sellerId]['product'][] = [
                SplitPaymentDataRequest::BLOCK_NAME_AMOUNT => $this->config->formatPrice($price),
                SplitPaymentDataRequest::BLOCK_NAME_CURRENCY => $order->getCurrencyCode(),
                SplitPaymentDataRequest::BLOCK_NAME_ID => $item->getSku(),
                SplitPaymentDataRequest::BLOCK_NAME_DESCRIPTION => $item->getQtyOrdered().'x '. $item->getName(),
                SplitPaymentDataRequest::BLOCK_NAME_TAX_AMOUNT => $this->config->formatPrice($commision),
            ];
        }

        foreach ($productBySeller as $sellerId => $products) {
            $result[SplitPaymentDataRequest::BLOCK_NAME_MARKETPLACE_SUBSELLER_PAYMENTS][] = [
                SplitPaymentDataRequest::BLOCK_NAME_SUB_SELLER_ID => $sellerId,
                SplitPaymentDataRequest::BLOCK_NAME_SUBSELLER_SALES_AMOUNT =>  100,
                SplitPaymentDataRequest::BLOCK_NAME_ORDER_ITEMS => $products['product'],
            ];
        }

        return $result;
    }
}
