<!-- -->
<div class="container-fluid">

    {* If more than one payment method is enabled, show a selector and only expand the chosen method. *}
    {assign var=pm_count value=0}
    {if $billing_options.cc_billing == '1'}{assign var=pm_count value=$pm_count+1}{/if}
    {if $billing_options.check_billing == '1'}{assign var=pm_count value=$pm_count+1}{/if}
    {if $billing_options.cash_billing == '1'}{assign var=pm_count value=$pm_count+1}{/if}
    {if $billing_options.gift_billing == '1'}{assign var=pm_count value=$pm_count+1}{/if}
    {if $billing_options.paypal_billing == '1'}{assign var=pm_count value=$pm_count+1}{/if}
    {if $billing_options.stripe_billing == '1'}{assign var=pm_count value=$pm_count+1}{/if}

    {literal}
    <script>
        function citecrmShowPaymentMethod(method) {
            var ids = ['pm-cc', 'pm-check', 'pm-cash', 'pm-gift', 'pm-paypal', 'pm-stripe'];
            ids.forEach(function (id) {
                var el = document.getElementById(id);
                if (!el) return;
                el.style.display = 'none';
            });
            if (!method) return;
            var target = document.getElementById('pm-' + method);
            if (target) target.style.display = '';
        }

        document.addEventListener('DOMContentLoaded', function () {
            var sel = document.getElementById('payment_method');
            if (!sel) return;
            // Start with no payment form shown until user chooses a method.
            citecrmShowPaymentMethod(sel.value || '');
            sel.addEventListener('change', function () {
                citecrmShowPaymentMethod(sel.value);
            });
        });
    </script>
    {/literal}

    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/tool_bar.tpl"}
    </div>

    {if $error_msg != ""}
        <div class="mb-3">
            {include file="core/error.tpl"}
        </div>
    {/if}

    <div class="container" style="max-width: 700px;">
	        <div class="card">
	            <div class="card-header d-flex justify-content-between align-items-center">
	                <span class="fw-bold">&nbsp;{$translate_billing_title}{$wo_id}</span>
	                <i class="bi bi-question-circle-fill fs-5 text-secondary"
	                   aria-hidden="true"
	                   onMouseOver="ddrivetip('<b>New Invoice</b><hr><p></p>')"
	                   onMouseOut="hideddrivetip()"></i>
	            </div>

            <div class="card-body">

                <!-- Invoice summary -->
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{$translate_billing_invoice_id}</th>
                                <th>{$translate_billing_date}</th>
                                <th>{$translate_billing_due_date}</th>
                                <th>{$translate_billing_amount}</th>
                                <th>{$translate_billing_wo_id}</th>
                                <th>{$translate_billing_balance}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach item=item from=$invoice_details}
                                <tr>
                                    <td>{$item.INVOICE_ID}</td>
                                    <td>{$item.INVOICE_DATE|date_format:"%m/%d/%y"}</td>
                                    <td>{$item.INVOICE_DUE|date_format:"%m/%d/%y"}</td>
                                    <td>{$item.INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                    <td>{$item.WORKORDER_ID}</td>
                                    <td>
                                        {if $item.BALLANCE > 0}
                                            <span class="text-danger">{$item.BALLANCE|string_format:"%.2f"}</span>
                                        {else}
                                            {$item.INVOICE_AMOUNT|string_format:"%.2f"}
                                        {/if}
                                    </td>
                                </tr>
                                {assign var="invoice_amount" value=$item.INVOICE_AMOUNT}
                                {assign var="invoice_id"     value=$item.INVOICE_ID}
                                {assign var="workorder_id"   value=$item.WORKORDER_ID}
                                {assign var="ballance"       value=$item.BALLANCE}
                            {/foreach}
                        </tbody>
                    </table>
                </div>

                <!-- Customer details -->
                <div class="mb-4">
                    {foreach item=item from=$customer_details}
                        <div class="fw-bold">{$item.CUSTOMER_DISPLAY_NAME}</div>
                        <div>{$item.CUSTOMER_ADDRESS}</div>
                        <div>{$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}</div>
                        <div class="mt-2">
                            <span class="fw-bold">{$translate_billing_email}</span>
                            &nbsp;{$item.CUSTOMER_EMAIL}
                        </div>
                        <div>
                            <span class="fw-bold">{$translate_billing_phone}</span>
                            &nbsp;{$item.CUSTOMER_PHONE}
                        </div>

                        {if $pm_count > 1}
                            <div class="card mt-3">
                                <div class="card-header fw-bold">&nbsp;Payment Method</div>
                                <div class="card-body">
                                    <label for="payment_method" class="form-label fw-bold mb-2">Choose one</label>
                                    <select id="payment_method" class="form-select" onchange="citecrmShowPaymentMethod(this.value)">
                                        <option value="" selected="selected">-- Select a payment method --</option>
                                        {if $billing_options.cc_billing == '1'}<option value="cc">{$translate_billing_credit_card}</option>{/if}
                                        {if $billing_options.check_billing == '1'}<option value="check">{$translate_billing_check}</option>{/if}
                                        {if $billing_options.cash_billing == '1'}<option value="cash">{$translate_billing_cash}</option>{/if}
                                        {if $billing_options.gift_billing == '1'}<option value="gift">{$translate_billing_gift}</option>{/if}
                                        {if $billing_options.paypal_billing == '1'}<option value="paypal">{$translate_billing_paypal}</option>{/if}
                                        {if $billing_options.stripe_billing == '1'}<option value="stripe">{$translate_billing_stripe}</option>{/if}
                                    </select>
                                </div>
                            </div>
                        {/if}

                        {assign var="customer_id" value=$item.CUSTOMER_ID}
                    {/foreach}
                </div>

                <!-- Transaction log -->
                {if $ballance > 0}
                    <div class="card mb-4">
                        <div class="card-header fw-bold">
                            &nbsp;{$translate_billing_trans_log}
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{$translate_billing_trans}</th>
                                            <th>{$translate_billing_date}</th>
                                            <th>{$translate_billing_amount}</th>
                                            <th>{$translate_billing_type}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=r loop=$trans}
                                            <tr>
                                                <td>{$trans[r].TRANSACTION_ID}</td>
                                                <td>{$trans[r].DATE|date_format:"%m/%d/%y %r"}</td>
                                                <td><b>$</b>{$trans[r].AMOUNT|string_format:"%.2f"}</td>
                                                <td>
                                                    {if $trans[r].TYPE == 1}
                                                        {$translate_billing_credit_card}
                                                    {elseif $trans[r].TYPE == 2}
                                                        {$translate_billing_check}
                                                    {elseif $trans[r].TYPE == 3}
                                                        {$translate_billing_cash}
                                                    {elseif $trans[r].TYPE == 4}
                                                        {$translate_billing_gift}
                                                    {elseif $trans[r].TYPE == 5}
                                                        {$translate_billing_paypal}
                                                    {elseif $trans[r].TYPE == 6}
                                                        {$translate_billing_stripe}
                                                    {/if}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">{$translate_billing_memo}</td>
                                                <td colspan="3">{$trans[r].MEMO}</td>
                                            </tr>
                                        {/section}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                {/if}

                <!-- Credit card payment -->
                {if $billing_options.cc_billing == '1'}
                    <form method="POST" action="" id="pm-cc" {if $pm_count > 1}style="display:none"{/if}>
                        <div class="card mb-4">
                            <div class="card-header fw-bold">
                                &nbsp;{$translate_billing_credit_card}
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">{$translate_billing_type}</label>
                                        <select name="card_type" class="form-select olotd4">
                                            {foreach key=key item=item from=$cc_cards}
                                                <option value="{$key}">{$item}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">{$translate_billing_cc_num}</label>
                                        <input type="text" name="cc_number" size="20" max="16" class="form-control olotd4">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">{$translate_billing_ccv}</label>
                                        <input type="text" name="cc_ccv" size="4" class="form-control olotd4">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">{$translate_billing_exp}</label>
                                        <div>
                                            {html_select_date prefix="StartDate" time=$time month_format="%m"
                                            end_year="+7" display_days=false}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">{$translate_billing_amount}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text"
                                                   name="cc_amount"
                                                   class="form-control"
                                                   {if $ballance > 0} value="{$ballance}" {else} value="{$invoice_amount|string_format:"%.2f"}" {/if}
                                                   size="6">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <input type="hidden" name="customer_id"  value="{$customer_id}">
                                    <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                    <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                    <input type="hidden" name="page"         value="billing:proc_cc">
                                    <input type="submit" name="submit" value="Submit CC Payment" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                {/if}

                <!-- Check payment -->
                {if $billing_options.check_billing == '1'}
                    <form method="POST" action="" id="pm-check" {if $pm_count > 1}style="display:none"{/if}>
                        <div class="card mb-4">
                            <div class="card-header fw-bold">
                                &nbsp;{$translate_billing_check}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">{$translate_billing_check_no}</label>
                                        <input type="text" name="check_recieved" size="8" class="form-control olotd4">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">{$translate_billing_amount}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text"
                                                   name="check_amount"
                                                   size="8"
                                                   class="form-control olotd4"
                                                   {if $ballance > 0} value="{$ballance|string_format:"%.2f"}" {else} value="{$invoice_amount|string_format:"%.2f"}" {/if}>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label fw-bold">{$translate_billing_memo}</label>
                                    <textarea name="check_memo" class="form-control olotd4"></textarea>
                                </div>

                                <div class="mt-3">
                                    <input type="hidden" name="customer_id"  value="{$customer_id}">
                                    <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                    <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                    <input type="hidden" name="page"         value="billing:proc_check">
                                    <input type="submit" name="submit" value="Submit Check Payment" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                {/if}

                <!-- Cash payment -->
                {if $billing_options.cash_billing == '1'}
                    <form method="POST" action="" id="pm-cash" {if $pm_count > 1}style="display:none"{/if}>
                        <div class="card mb-4">
                            <div class="card-header fw-bold">
                                &nbsp;{$translate_billing_cash}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">{$translate_billing_cash}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text"
                                                   name="cash_amount"
                                                   size="8"
                                                   class="form-control olotd4"
                                                   {if $ballance > 0} value="{$ballance|string_format:"%.2f"}" {else} value="{$invoice_amount|string_format:"%.2f"}" {/if}>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label fw-bold">{$translate_billing_memo}</label>
                                    <textarea name="cash_memo" class="form-control olotd4"></textarea>
                                </div>

                                <div class="mt-3">
                                    <input type="hidden" name="customer_id"  value="{$customer_id}">
                                    <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                    <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                    <input type="hidden" name="page"         value="billing:proc_cash">
                                    <input type="submit" name="submit" value="Submit Cash Payment" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                {/if}

                <!-- Gift certificate payment -->
                {if $billing_options.gift_billing == '1'}
                    <form method="POST" action="" id="pm-gift" {if $pm_count > 1}style="display:none"{/if}>
                        <div class="card mb-4">
                            <div class="card-header fw-bold">
                                &nbsp;{$translate_billing_gift}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">{$translate_billing_gift}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text"
                                                   name="gift_amount"
                                                   size="8"
                                                   class="form-control olotd4"
                                                   {if $ballance > 0} value="{$ballance|string_format:"%.2f"}" {else} value="{$invoice_amount|string_format:"%.2f"}" {/if}>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold">{$translate_billing_gift_code}</label>
                                        <input type="text" name="gift_code" size="13" maxlength="13" inputmode="numeric" required class="form-control olotd4">
                                        <div class="form-text">
                                            {$translate_billing_gift_code_2}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <input type="hidden" name="customer_id"  value="{$customer_id}">
                                    <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                    <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                    <input type="hidden" name="page"         value="billing:proc_gift">
                                    <input type="submit" name="submit" value="Submit Gift Certificate" class="btn btn-primary" onclick="this.form.page.value='billing:proc_gift'">
                                </div>
                            </div>
                        </div>
                    </form>
                {/if}

                <!-- PayPal payment -->
                {if $billing_options.paypal_billing == '1'}
                    <form method="POST" action="?page=billing:proc_paypal" id="pm-paypal" {if $pm_count > 1}style="display:none"{/if}>
                        <div class="card mb-4">
                            <div class="card-header fw-bold">
                                &nbsp;{$translate_billing_paypal}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">{$translate_billing_paypal}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text"
                                                   name="paypal_amount"
                                                   size="8"
                                                   value="{$invoice_amount}"
                                                   class="form-control olotd4">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <input type="hidden" name="customer_id"  value="{$customer_id}">
                                    <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                    <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                    <input type="submit" name="submit" value="Submit PayPal Payment" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                {/if}

                <!-- Stripe payment -->
                {if $billing_options.stripe_billing == '1'}
                    <form method="POST" action="?page=billing:proc_stripe" id="pm-stripe" {if $pm_count > 1}style="display:none"{/if}>
                        <div class="card mb-4">
                            <div class="card-header fw-bold">
                                &nbsp;{$translate_billing_stripe}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">{$translate_billing_stripe}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text"
                                                   name="stripe_amount"
                                                   size="8"
                                                   value="{if $ballance > 0}{$ballance|string_format:"%.2f"}{else}{$invoice_amount|string_format:"%.2f"}{/if}"
                                                   class="form-control olotd4">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <input type="hidden" name="customer_id"  value="{$customer_id}">
                                    <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                    <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" checked name="stripe_email_link" id="stripe_email_link" value="1">
                                        <label class="form-check-label" for="stripe_email_link">
                                            Email Stripe payment link to customer
                                        </label>
                                    </div>
                                    <input type="submit" name="submit" value="Submit Stripe Payment" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                {/if}

            </div>
        </div>
    </div>

</div>
