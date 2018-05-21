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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<form action="https://www.spicepay.com/p.php" method="POST" id="form_submit">
<input type="hidden" name="amountUSD" value="{$total|escape:'htmlall':'UTF-8}">
<input type="hidden" name="orderId" value="{$cart_id|escape:'htmlall':'UTF-8}">
<input type="hidden" name="siteId" value="{$app_id|escape:'htmlall':'UTF-8}">
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