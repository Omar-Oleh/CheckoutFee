<?php
/**
 * Add fee on shopping cart and checkout
 *
 * @category    Astound
 * @package     Astound_CheckoutFee
 * @author      Oleg Onoshko <o.onoshko@astoundcommerce.com>
 */
declare(strict_types=1);
namespace Astound\CheckoutFee\Model\Quote\Total;

use Astound\CheckoutFee\Model\Config\FeeProvider;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;

/**
 * Class Fee
 *
 * @package Astound\CheckoutFee\Model\Quote\Total
 */
class Fee extends AbstractTotal
{
    /**
     * @var \Astound\CheckoutFee\Model\Config\FeeProvider
     */
    private $feeProvider;

    /**
     * Fee constructor.
     *
     * @param \Astound\CheckoutFee\Model\Config\FeeProvider $feeProvider
     */
    public function __construct(
        FeeProvider $feeProvider
    ) {
        $this->feeProvider = $feeProvider;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|\Magento\Quote\Model\Quote\Address\Total\AbstractTotal
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, Total $total)
    {
        parent::collect($quote, $shippingAssignment, $total);

        $enabled = $this->feeProvider->isModuleEnabled();
        $minimumOrderAmount = $this->feeProvider->getMinimumOrderAmount();
        $subtotal = $total->getTotalAmount('subtotal');
        if ($enabled && $minimumOrderAmount <= $subtotal) {
            $fee = $this->feeProvider->getFeeAmount();
            /** @noinspection PhpUndefinedMethodInspection */
            $quote->setFee($fee);
            /** @noinspection PhpUndefinedMethodInspection */
            $quote->setBaseFee($fee);
            /** @noinspection PhpUndefinedMethodInspection */
            $total->setGrandTotal($total->getGrandTotal() + $fee);
            /** @noinspection PhpUndefinedMethodInspection */
            $total->setBaseGrandTotal($total->getBaseGrandTotal() + $fee);
        }

        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function fetch(Quote $quote, Total $total): array
    {
        $enabled = $this->feeProvider->isModuleEnabled();
        $minimumOrderAmount = $this->feeProvider->getMinimumOrderAmount();
        $subtotal = $quote->getSubtotal();
        /** @noinspection PhpUndefinedMethodInspection */
        $fee = $quote->getFee();

        $result = [];
        if ($enabled && ($minimumOrderAmount <= $subtotal) && $fee) {
            $result = [
                'code'  => 'fee',
                'title' => $this->feeProvider->getFeeLabel(),
                'value' => $fee
            ];
        }

        return $result;
    }
}
