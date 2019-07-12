<?php
/**
 * Add fee on shopping cart and checkout
 *
 * @category    Astound
 * @package     Astound_CheckoutFee
 * @author      Oleg Onoshko <o.onoshko@astoundcommerce.com>
 */
declare(strict_types=1);
namespace Astound\CheckoutFee\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class FeeProvider
 *
 * @package Astound\CheckoutFee\Model\Config
 */
class FeeProvider
{
    /**
     * Custom fee config path
     */
    private const CONFIG_MODULE_IS_ENABLED = 'checkout/fee/module_status';
    private const CONFIG_FEE_AMOUNT = 'checkout/fee/amount';
    private const CONFIG_FEE_LABEL = 'checkout/fee/label';
    private const CONFIG_MINIMUM_ORDER_AMOUNT = 'checkout/fee/minimum_order_amount';

    /** @var \Magento\Store\Model\StoreManager */
    private $storeManager;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    private $scopeConfig;

    /**
     * FeeProvider constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isModuleEnabled(): bool
    {
        return (bool)$this->getWebsiteScopeConfig(self::CONFIG_MODULE_IS_ENABLED);
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFeeAmount(): int
    {
        return (int)$this->getWebsiteScopeConfig(self::CONFIG_FEE_AMOUNT);
    }

    /**
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFeeLabel(): string
    {
        return (string)$this->getWebsiteScopeConfig(self::CONFIG_FEE_LABEL);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMinimumOrderAmount(): int
    {

        return (int)$this->getWebsiteScopeConfig(self::CONFIG_MINIMUM_ORDER_AMOUNT);
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getWebsiteId(): int
    {
        return (int)$this->storeManager->getWebsite()->getId();
    }

    /**
     * @param string $path
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getWebsiteScopeConfig(string $path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_WEBSITES, $this->getWebsiteId());
    }
}
