<!-- -->
<div class="container-fluid">

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
                <span class="fw-bold">&nbsp;{$translate_billing_gift}</span>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h2 class="h4 mb-0">{$translate_billing_gift}</h2>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div>{$company_name}</div>
                        <div>{$company_phone}</div>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <p class="fw-bold mb-1">To:</p>
                        {section name=q loop=$customer}
                            <div>{$customer[q].CUSTOMER_DISPLAY_NAME}</div>
                            <div>{$customer[q].CUSTOMER_ADDRESS}</div>
                            <div>{$customer[q].CUSTOMER_CITY} {$customer[q].CUSTOMER_STATE} .{$customer[q].CUSTOMER_ZIP}</div>
                            <div><span class="fw-bold">Customer ID: </span>{$customer[q].CUSTOMER_ID}</div>
                            {assign var="customer_id" value=$customer[q].CUSTOMER_ID}
                        {/section}
                    </div>

                    <div class="col-md-6">
                        <div class="mb-2 row">
                            <div class="col-6 fw-bold">{$translate_billing_amount}</div>
                            <div class="col-6 text-end">
                                ${$amount|string_format:"%.2f"}
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-6 fw-bold">{$translate_billing_gift_code_3}</div>
                            <div class="col-6 text-end">
                                {$gift_code}
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-6 fw-bold">{$translate_billing_created}</div>
                            <div class="col-6 text-end">
                                {$create|date_format:"%m/%d/%Y"}
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-6 fw-bold">{$translate_billing_expires}</div>
                            <div class="col-6 text-end">
                                {$expire|date_format:"%m/%d/%Y"}
                            </div>
                        </div>

                        <div class="border rounded p-2">
                            {$memo}
                        </div>
                    </div>
                </div>

                <p class="mt-3 mb-0">
                    {$translate_billing_gift_note_1} ${$amount} {$translate_billing_gift_note_2}
                </p>

            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>
                    <a href="?page=billing:new_gift&gift_id={$gift_id}&customer_id={$customer_id}&action=print&submit=1&escape=1"
                       target="new"
                       class="btn btn-sm btn-secondary me-2"
                       onMouseOver="ddrivetip('Print')" onMouseOut="hideddrivetip()">
                        <img src="images/icons/16x16/fileprint.gif" alt="Print" border="0">
                    </a>

                    <a href="?page=customer:customer_details&customer_id={$customer_id}" class="btn btn-sm btn-link">
                        {$translate_billing_back}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
