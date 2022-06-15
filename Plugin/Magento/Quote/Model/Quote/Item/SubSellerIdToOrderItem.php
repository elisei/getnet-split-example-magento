<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace Getnet\SplitExampleMagento\Plugin\Magento\Quote\Model\Quote\Item;

class SubSellerIdToOrderItem
{
    public function aroundConvert(\Magento\Quote\Model\Quote\Item\ToOrderItem $subject, callable $proceed, $quoteItem, $data)
    {
        $orderItem = $proceed($quoteItem, $data);

        $subSellerId = $quoteItem->getGetnetSubSellerId();

        $orderItem->setGetnetSubSellerId($subSellerId);

        return $orderItem;
    }
}