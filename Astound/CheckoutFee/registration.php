<?php
/**
 * Add fee on shopping cart and checkout
 *
 * @category    Astound
 * @package     Astound_CheckoutFee
 * @author      Oleg Onoshko <o.onoshko@astoundcommerce.com>
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Astound_CheckoutFee',
    __DIR__
);
