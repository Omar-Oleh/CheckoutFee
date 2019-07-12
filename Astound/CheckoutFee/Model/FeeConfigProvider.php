<?php
/**
 * Add fee on shopping cart and checkout
 *
 * @category    Astound
 * @package     Astound_CheckoutFee
 * @author      Oleg Onoshko <o.onoshko@astoundcommerce.com>
 */
declare(strict_types=1);
namespace Astound\CheckoutFee\Model;

use Astound\CheckoutFee\Model\Config\FeeProvider;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;

/**
 * Class FeeConfigProvider
 *
 * @package Astound\CheckoutFee\Model
 */
class FeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Astound\CheckoutFee\Model\Config\FeeProvider
     */
    private $feeProvider;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * FeeConfigProvider constructor.
     *
     * @param \Astound\CheckoutFee\Model\Config\FeeProvider $feeProvider
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        FeeProvider $feeProvider,
        Session $checkoutSession

    ) {
        $this->feeProvider = $feeProvider;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getConfig(): array
    {
        $customFeeConfig = [];
        $enabled = $this->feeProvider->isModuleEnabled();
        $minimumOrderAmount = $this->feeProvider->getMinimumOrderAmount();
        $customFeeConfig['fee_label'] = $this->feeProvider->getFeeLabel();
        $quote = $this->checkoutSession->getQuote();
        $subtotal = $quote->getSubtotal();
        /** @noinspection PhpUndefinedMethodInspection */
        $customFeeConfig['show_hide_checkout_fee_block'] =
            ($enabled && ($minimumOrderAmount <= $subtotal) && $quote->getFee()) ? true : false;

        return $customFeeConfig;
    }
}
