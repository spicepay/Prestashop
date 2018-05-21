<?php

/**

* NOTICE OF LICENSE

*

* The MIT License (MIT)

*

* Copyright (c) 2017 SpicePay

*

* Permission is hereby granted, free of charge, to any person obtaining a copy of

* this software and associated documentation files (the "Software"), to deal in

* the Software without restriction, including without limitation the rights to use,

* copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,

* and to permit persons to whom the Software is furnished to do so, subject

* to the following conditions:

*

* The above copyright notice and this permission notice shall be included in all

* copies or substantial portions of the Software.

*

* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR

* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,

* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE

* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,

* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR

* IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*

*  @author    SpicePay <info@spicepay.com>

*  @copyright 2017 SpicePay

*  @license   https://github.com/spicepay/prestashop-plugin/blob/master/LICENSE  The MIT License (MIT)

*/



require_once(_PS_MODULE_DIR_ . '/spicepay/vendor/spicepay/init.php');

require_once(_PS_MODULE_DIR_ . '/spicepay/vendor/version.php');

// exit();

class SpicepayRedirectModuleFrontController extends ModuleFrontController

{

    public $ssl = true;



    public function initContent()

    {

        parent::initContent();



        $cart = $this->context->cart;



        if (!$this->module->checkCurrency($cart)) {

            Tools::redirect('index.php?controller=order');

        }



        $total = (float)number_format($cart->getOrderTotal(true, 3), 2, '.', '');

        $currency = Context::getContext()->currency;



        $token = $this->generateToken($cart->id);



        $description = array();

        foreach ($cart->getProducts() as $product) {

            $description[] = $product['cart_quantity'] . ' × ' . $product['name'];

        }



        $customer = new Customer($cart->id_customer);



        if (_PS_VERSION_ >= '1.7') {

            $success_url = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://')

            . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8')

            . __PS_BASE_URI__ . 'order-confirmation?id_cart='

            . $cart->id . '&key=' . $customer->secure_key;

        } else {

            $success_url = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://')

            . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8')

            . __PS_BASE_URI__ . 'index.php?controller=order-confirmation&id_cart='

            . $cart->id . '&key=' . $customer->secure_key;

        }





        $cgConfig = array(

          'app_id' => Configuration::get('SPICEPAY_APP_ID'),

          'api_key' => Configuration::get('SPICEPAY_API_KEY'),

          'curr' => Configuration::get('SPICEPAY_RECEIVE_CURRENCY'),

          // 'api_secret' => Configuration::get('SPICEPAY_API_SECRET'),

          // 'environment' => (int)(Configuration::get('SPICEPAY_TEST')) == 1 ? 'sandbox' : 'live',

          'user_agent' => 'SpicePay - Prestashop v'._PS_VERSION_.' Extension v'.SPICEPAY_PRESTASHOP_EXTENSION_VERSION

        );



        \SpicePay\SpicePay::config($cgConfig);



        $order = \SpicePay\Merchant\Order::create(array(

            'order_id'         => $cart->id,

            'price'            => $total,

            'currency'         => $currency->iso_code,

            'receive_currency' => $this->module->receive_currency,

            'cancel_url'       => $this->context->link->getModuleLink('spicepay', 'cancel'),

            'callback_url'     => $this->context->link->getModuleLink(

                'spicepay',

                'callback',

                array('cg_token' => $token)

            ),

            'success_url'      => $success_url,

            'title'            => Configuration::get('PS_SHOP_NAME') . ' Order #' . $cart->id,

            'description'      => join($description, ', ')

        ));



        if ($order) {

            // if (!$order->payment_url) {

            //     Tools::redirect('index.php?controller=order&step=3');

            // }



            $customer = new Customer($cart->id_customer);

            $this->module->validateOrder(

                $cart->id,

                Configuration::get('SPICEPAY_PENDING'),

                $total,

                $this->module->displayName,

                null,

                null,

                (int)$currency->id,

                false,

                $customer->secure_key

            );

        // echo $total;

        // exit();

global $smarty;

global $cookie;

include(dirname(__FILE__).'/shop/config/config.inc.php');

include(dirname(__FILE__).'/shop/header.php');

$amount_curr="";
if ($cgConfig['curr']=="gbp") {
    $amount_curr="GBP";
} elseif ($cgConfig['curr']=="eur") {
    $amount_curr="EUR";
} elseif ($cgConfig['curr']=="usd") {
    $amount_curr="USD";
}


$form=<<<HTML

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<form action="https://www.spicepay.com/p.php" method="POST" id="form_submit">

<input type="hidden" name="amount{$amount_curr}" value="{$total}">

<input type="hidden" name="orderId" value="{$cart->id}">

<input type="hidden" name="siteId" value="{$cgConfig['app_id']}">

<input type="hidden" name="language" value="en">

<input type="submit" value="Pay"></form>

<style>

#form_submit {

display: none;

}

</style>

<script>

jQuery( document ).ready(function() {

    jQuery( "#form_submit" ).submit();

});

</script>

HTML;



echo $form;

        } else {

            Tools::redirect('index.php?controller=order&step=3');

        }

    }



    private function generateToken($order_id)

    {

        return hash('sha256', $order_id + $this->module->api_secret);

    }

}

