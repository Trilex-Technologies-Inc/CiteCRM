<!-- -->
<div class="container-fluid">

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <table class="table-borderless m-0">
            <tr>
                {include file="core/tool_bar.tpl"}
            </tr>
        </table>
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
                <img src="images/icons/16x16/help.gif" border="0"
                     onMouseOver="ddrivetip('<b>New Invoice</b><hr><p></p>')"
                     onMouseOut="hideddrivetip()">
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
                    <form method="POST" action="">
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
                    <form method="POST" action="">
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
                    <form method="POST" action="">
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
                    <form method="POST" action="">
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
                                        <input type="text" name="gift_code" size="16" class="form-control olotd4">
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
                                    <input type="submit" name="submit" value="Submit Gift Certificate" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                {/if}

                <!-- PayPal payment -->
                {if $billing_options.paypal_billing == '1'}
                    <form method="POST" action="?page=billing:proc_paypal">
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

            </div>
        </div>
    </div>

</div>