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



class SpicepayCallbackModuleFrontController extends ModuleFrontController

{

    public $ssl = true;



    public function postProcess()

    {


        $order_id = Order::getOrderByCartId(Tools::getValue('order_id'));

        $order = new Order($order_id);



        try {

            if (!$order) {

                throw new Exception('Order #' . Tools::getValue('order_id') . ' does not exists');

            }



            $cgConfig = array(

              'app_id' => Configuration::get('SPICEPAY_APP_ID'),

              'api_key' => Configuration::get('SPICEPAY_API_KEY'),

              'user_agent' => 'SpicePay - Prestashop v'._PS_VERSION_

                .' Extension v'.SPICEPAY_PRESTASHOP_EXTENSION_VERSION

            );



            \SpicePay\SpicePay::config($cgConfig);

            $cgOrder = \SpicePay\Merchant\Order::find(Tools::getValue('id'));



            if (!$cgOrder) {

                throw new Exception('SpicePay Order #' . Tools::getValue('id') . ' does not exists');

            }



            $order_status = false;



            if (((float) $order->getOrdersTotalPaid()) > ((float) $cgOrder->price)) {

                $order_status = 'SPICEPAY_INVALID';

            } else {



                if (isset($_POST['paymentId']) && isset($_POST['orderId']) && isset($_POST['hash']) 
                && isset($_POST['paymentCryptoAmount']) && isset($_POST['paymentAmountUSD']) 
                && isset($_POST['receivedCryptoAmount']) && isset($_POST['receivedAmountUSD'])) {
                    
            		$paymentId = addslashes(filter_input(INPUT_POST, 'paymentId', FILTER_SANITIZE_STRING));
                    $order->id = $orderId = addslashes(filter_input(INPUT_POST, 'orderId', FILTER_SANITIZE_STRING));
                    $hash = addslashes(filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));    
                    $clientId = addslashes(filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_STRING));
                    $paymentAmountBTC = addslashes(filter_input(INPUT_POST, 'paymentAmountBTC', FILTER_SANITIZE_NUMBER_INT));
                    $paymentAmountUSD = addslashes(filter_input(INPUT_POST, 'paymentAmountUSD', FILTER_SANITIZE_STRING));
                    $receivedAmountBTC = addslashes(filter_input(INPUT_POST, 'receivedAmountBTC', FILTER_SANITIZE_NUMBER_INT));
                    $receivedAmountUSD = addslashes(filter_input(INPUT_POST, 'receivedAmountUSD', FILTER_SANITIZE_STRING));
                    $status_s = $status = addslashes(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING));
                    
                    if(isset($_POST['paymentCryptoAmount']) && isset($_POST['receivedCryptoAmount'])) {
                        $paymentCryptoAmount = addslashes(filter_input(INPUT_POST, 'paymentCryptoAmount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
                        $receivedCryptoAmount = addslashes(filter_input(INPUT_POST, 'receivedCryptoAmount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
                    }
                    else {
                        $paymentCryptoAmount = addslashes(filter_input(INPUT_POST, 'paymentAmountBTC', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
                        $receivedCryptoAmount = addslashes(filter_input(INPUT_POST, 'receivedAmountBTC', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
                    }
                    $secretCode = Configuration::get('SPICEPAY_API_KEY');
            		$hashString = $secretCode . $paymentId . $orderId . $clientId . $paymentCryptoAmount . $paymentAmountUSD . $receivedCryptoAmount . $receivedAmountUSD . $status;
                }

                switch ($status_s) {

                    case 'paid':

                        $order_status = 'PS_OS_PAYMENT';

                        break;

                    case 'expired':

                        $order_status = 'SPICEPAY_EXPIRED';

                        break;

                    case 'invalid':

                        $order_status = 'SPICEPAY_INVALID';

                        break;

                    case 'canceled':

                        $order_status = 'PS_OS_CANCELED';

                        break;

                    case 'refunded':

                        $order_status = 'PS_OS_REFUND';

                        break;

                }

            }

//die('xx '.md5($hashString).' - '. $hash);

            if ($order_status !== false) {


                if (isset($hashString) && isset($hash) && 0 == strcmp(md5($hashString), $hash)) {

                    $history = new OrderHistory();

                    $history->id_order = $order->id;

                    $history->changeIdOrderState((int)Configuration::get($order_status), $order->id);

                    $history->addWithemail(true, array(

                        'order_name' => Tools::getValue('order_id'),

                    ));



                    $this->context->smarty->assign(array(

                        'text' => 'OK'

                    ));
                } else {
                    echo "Not right!";
                }
                

            } else {

                $this->context->smarty->assign(array(

                    'text' => 'Order Status '.$cgOrder->status.' not implemented'

                ));

            }

        } catch (Exception $e) {

            $this->context->smarty->assign(array(

                'text' => get_class($e) . ': ' . $e->getMessage()

            ));

        }

        if (_PS_VERSION_ >= '1.7') {

            $this->setTemplate('module:spicepay/views/templates/front/payment_callback.tpl');

        } else {

            $this->setTemplate('payment_callback.tpl');

        }

    }



    private function generateToken($order_id)

    {

        return hash('sha256', $order_id + $this->module->api_secret);

    }

}

