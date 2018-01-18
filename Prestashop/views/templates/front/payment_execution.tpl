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

{capture name=path}

    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='spicepay'}">

        {l s='Checkout' mod='spicepay'}

    </a>

    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>

    {l s='SpicePay payment' mod='spicepay'}

{/capture}



<h1 class="page-heading">

    {l s='Order summary' mod='spicepay'}

</h1>



{assign var='current_step' value='payment'}

{include file="$tpl_dir./order-steps.tpl"}



{if $nbProducts <= 0}

    <p class="alert alert-warning">

        {l s='Your shopping cart is empty.' mod='spicepay'}

    </p>

{else}

    <form action="{$link->getModuleLink('spicepay', 'redirect', [], true)|escape:'html':'UTF-8'}" method="post">

        <div class="box cheque-box">

            <h3 class="page-subheading">

                {l s='SpicePay payment' mod='spicepay'}

            </h3>



            <p class="cheque-indent">

                <strong class="dark">

                    {l s='You have chosen to pay by Cryptocurrency via SpicePay.' mod='spicepay'} {l s='Here is a short summary of your order:' mod='spicepay'}

                </strong>

            </p>



            <p>

                - {l s='The total amount of your order is' mod='spicepay'}

                <span id="amount" class="price">{displayPrice price=$total}</span>

                {if $use_taxes == 1}

                    {l s='(tax incl.)' mod='spicepay'}

                {/if}

            </p>



            <p>

                - {l s='You will be redirected to SpicePay for payment with Cryptocurrency.' mod='spicepay'}

                <br/>

                - {l s='Please confirm your order by clicking "I confirm my order".' mod='spicepay'}

            </p>

        </div>

        <p class="cart_navigation clearfix" id="cart_navigation">

            <a class="button-exclusive btn btn-default" href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}">

                <i class="icon-chevron-left"></i>{l s='Other payment methods' mod='spicepay'}

            </a>

            <button class="button btn btn-default button-medium" type="submit">

                <span>{l s='I confirm my order' mod='spicepay'}<i class="icon-chevron-right right"></i></span>

            </button>

        </p>

    </form>

{/if}

