<div id="member_form">
<form method="post" id="product_form" action="">
    <?php echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />'; ?>
    <table>       
        <tbody>
            <tr><td class="label"><label for="product_name">Product Name:</label><a class="tooltip" title="Name of Your Product will come here. Eg: Yoga Series." href="#"></a></td><td><input type="text" name="product_name" id="product_name"/></td></tr>            
            <tr><td><label>Billing Option:</label><a class="tooltip" title="Choose whether you want to offer product for One Time Payment or Recurring Payment." href="#"></a></td><td><input type="radio" checked="checked" id="one_time" name="billing_option" value="one_time"/>&nbsp;One Time Purchase or&nbsp;&nbsp;&nbsp;<input type="radio" id="recurring" name="billing_option" value="recurring"/>&nbsp;Recurring Subscription</td></tr>
<!--            <tr class="payment_putton"><td><label for="payment_button">Payment Button Image(Optional):</label><a class="tooltip" title="You may use html and/or javascript code provided by Google AdSense." href="#"></a></td><td><input type="text" name="payment_button" id="payment_button"/></td></tr>-->
            <tr class="currency"><td class="label"><label for="currency">Currency:</label><a class="tooltip" title="Choose Your Payment Currency." href="#"></a></td>
                <td><select name="currency" id="currency">
                        <?php
                        $currencys = im_currency();
                        foreach ($currencys as $key => $currency) {
                            echo '<option value="' . $key . '">', $currency . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="product_price"><td class="label"><label class="product_price_label" for="product_price">Product Price:</label><a class="tooltip tip_onetime" title="Enter Pricing for your product." href="#"></a><a class="tooltip tip_recurring" title="This is the amount to be charged for the first time payment." href="#"></a></td><td><input type="text" class="small" name="product_price" id="product_price"/></td></tr>
            <tr class="payment p_period"><td class="label"><label for="payment_period">Payment Period:</label><a class="tooltip" title="Time period of first payment after which the second price will be charged." href="#"></a></td><td><input class="small" type="text" name="payment_period" id="payment_period"/>&nbsp;<select name="payment_period_cycle" id="payment_period_cycle"><option value="D">day(s)</option><option value="W">week(s)</option><option value="M">month(s)</option><option value="Y">years(s)</option></select></td></tr>            
<!--            <tr class="payment trial_select"><td><label for="trial_select">Offer a Subscription Trial:</label></td><td><input id="trial_select" class="trial_yes" type="radio" name="trial_select" value="1" />&nbsp;Yes   or&nbsp;&nbsp;&nbsp;<input class="trial_no" id="trial_select" type="radio" checked="checked" name="trial_select" value="0"/>&nbsp;No</td></tr>-->
            <tr class="payment trial_price"><td class="label"><label for="trial_price">Second Price:</label><a class="tooltip" title="This is the amount to be charged for second time and further subsequent payments." href="#"></a></td><td><input type="text" class="small" name="trial_price" id="trial_price"/></td></tr>
            <tr class="payment trial_period"><td class="label"><label for="trial_period">Second Period:</label><a class="tooltip" title="Set the time period after which the second price will be charged again." href="#"></a></td><td><input type="text" class="small" name="trial_period" id="trial_period"/>&nbsp;<select name="trial_period_cycle" id="trial_period_cycle"><option value="D">day(s)</option><option value="W">week(s)</option><option value="M">month(s)</option><option value="Y">years(s)</option></select></td></tr> 
            <tr class="payment no_of_payment"><td class="label"><label for="no_of_payment">Number of Payments:</label><a class="tooltip" title="Number of times the Second Recurring Payment will be charged. Enter 0 for unlimited." href="#"></a></td><td><input class="small" type="text" name="no_of_payment" id="no_of_payment"/></td></tr>
            <tr class="subs_period"><td class="label"><label for="subs_period">Subscription Period:</label><a class="tooltip" title="Time period till the users can access the product." href="#"></a></td><td><input class="small" type="text" name="subs_period" id="subs_period"/>&nbsp;<select name="subs_period_cycle" id="subs_period_cycle"><option value="D">day(s)</option><option value="W">week(s)</option><option value="M">month(s)</option><option value="Y">years(s)</option><option value="U">Lifetime</option></select></td></tr>
        </tbody>
    </table>
    <input type="submit" name="add" value="Add Product &raquo;" class="button-primary" />
    <input type="hidden" name="prevent_redunt" value="<?php echo rand(); ?>"/>
    <input type="hidden" name="member_key" value="<?php echo substr(md5(uniqid(rand(), true)), 0, 10); ?>" />
</form>
</div>