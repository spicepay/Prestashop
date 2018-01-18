{*

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

*}

<div class="row">

    <div class="col-xs-12">

        <p class="payment_module">

            <a class="cheque" style="background-image: url('{$this_path|escape:'htmlall':'UTF-8'}views/img/bitcoin-logo.png'); padding-left:150px;  background-size: 100px; background-position: 20px; 50%; background-repeat: no-repeat;" href="{$link->getModuleLink('spicepay', 'payment')|escape:'htmlall':'UTF-8'}">



                {l s='Pay with Cryptocurrency via SpicePay.com' mod='spicepay'}

                <br><span>({l s='order processing will be faster' mod='spicepay'})</span>

            </a>

        </p>

    </div>

</div>

