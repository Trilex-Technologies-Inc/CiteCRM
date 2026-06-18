<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="mb-0">
                {if $business}
                    Edit Business
                {else}
                    {$translate_messaging_add_new_business}
                {/if}
            </h2>
        </div>

        <div class="card-body">

            {if $error_msg}
                <div class="alert alert-danger" role="alert">
                    {$error_msg}
                </div>
            {/if}

            <form method="post"
                  action="index.php?page=messaging:contacts&action={if $business}business_edit{else}business_new{/if}"
                  onsubmit="return validateBusinessForm(this);">

                {if $business}
                    <input type="hidden" name="business_id" value="{$business.BUSINESS_ID}">
                {/if}

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {$translate_messaging_business_name}
                        </label>
                        <input class="form-control"
                               type="text"
                               name="business_name"
                               value="{if $business}{$business.BUSINESS_NAME}{/if}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {$translate_messaging_business_phone}
                        </label>
                        <input class="form-control"
                               type="text"
                               name="business_phone"
                               value="{if $business}{$business.BUSINESS_PHONE}{/if}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        {$translate_messaging_business_address}
                    </label>
                    <textarea class="form-control"
                              name="business_address"
                              rows="3">{if $business}{$business.BUSINESS_ADDRESS}{/if}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        {$translate_messaging_contract_renewal}
                    </label>
                    <input class="form-control"
                           type="date"
                           name="contract_renewal_date"
                           value="{if $business}{$business.CONTRACT_RENEWAL_DATE}{/if}">
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        {$translate_messaging_product_preferences}
                    </label>
                    <textarea class="form-control"
                              name="product_preferences"
                              rows="4">{if $business}{$business.PRODUCT_PREFERENCES}{/if}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                            name="submit"
                            class="btn btn-primary">
                        {$translate_messaging_submit}
                    </button>

                    <a href="index.php?page=messaging:contacts&action=business_list"
                       class="btn btn-outline-secondary">
                        {$translate_messaging_cancel}
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="js/messaging_contacts.js"></script>