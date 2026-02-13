<!-- Toolbar -->
<div class="container-fluid mb-3">
    <div class="d-flex align-items-center">
        {include file="core/tool_bar.tpl"}
    </div>
</div>

<div class="container my-4">

    <h4 class="mb-4">{$translate_customer_edit}</h4>

    {if $error_msg != ""}
        {include file="core/error.tpl"}
    {/if}

    {include file="customer/edit.js"}

    {literal}
    <form action="index.php?page=customer:edit"
          method="POST"
          name="edit_customer"
          id="edit_customer"
          onsubmit="try { var myValidator = validate_edit_customer; } catch(e) { return true; } return myValidator(this);">
    {/literal}

    {section name=q loop=$customer}

    <input type="hidden" name="customer_id" value="{$customer[q].CUSTOMER_ID}">
    <input type="hidden" name="page" value="customer:edit">

    <!-- Basic Info -->
    <div class="card mb-4">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">
                    <span class="text-danger">*</span>
                    <strong>{$translate_display}</strong>
                </label>
                <input type="text"
                       name="displayName"
                       value="{$customer[q].CUSTOMER_DISPLAY_NAME}"
                       class="form-control">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        <strong>{$translate_first}</strong>
                    </label>
                    <input type="text"
                           name="firstName"
                           value="{$customer[q].CUSTOMER_FIRST_NAME}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        <strong>{$translate_last}</strong>
                    </label>
                    <input type="text"
                           name="lastName"
                           value="{$customer[q].CUSTOMER_LAST_NAME}"
                           class="form-control">
                </div>
            </div>

        </div>
    </div>

    <!-- Phone -->
    <div class="card mb-4">
        <div class="card-header fw-bold">
            {$translate_phone}
        </div>
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">
                    <span class="text-danger">*</span>
                    <strong>{$translate_home}</strong>
                </label>
                <input type="text"
                       name="homePhone"
                       value="{$customer[q].CUSTOMER_PHONE}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <span class="text-danger">*</span>
                    <strong>{$translate_phone}</strong>
                </label>
                <input type="text"
                       name="workPhone"
                       value="{$customer[q].CUSTOMER_WORK_PHONE}"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <span class="text-danger">*</span>
                    <strong>{$translate_mobile}</strong>
                </label>
                <input type="text"
                       name="mobilePhone"
                       value="{$customer[q].CUSTOMER_MOBILE_PHONE}"
                       class="form-control">
            </div>

        </div>
    </div>

    <!-- Address -->
    <div class="card mb-4">
        <div class="card-header fw-bold">
            {$translate_customer_address}
        </div>
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">
                    <span class="text-danger">*</span>
                    <strong>{$translate_customer_address}</strong>
                </label>
                <input type="text"
                       name="address"
                       value="{$customer[q].CUSTOMER_ADDRESS}"
                       class="form-control">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        <strong>{$translate_customer_city}</strong>
                    </label>
                    <input type="text"
                           name="city"
                           value="{$customer[q].CUSTOMER_CITY}"
                           class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        <strong>{$translate_customer_state}</strong>
                    </label>
                    <input type="text"
                           name="state"
                           value="{$customer[q].CUSTOMER_STATE}"
                           class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        <strong>{$translate_customer_zip}</strong>
                    </label>
                    <input type="text"
                           name="zip"
                           value="{$customer[q].CUSTOMER_ZIP}"
                           class="form-control">
                </div>
            </div>

        </div>
    </div>

    <!-- Other Info -->
    <div class="card mb-4">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        <strong>{$translate_email}</strong>
                    </label>
                    <input type="email"
                           name="email"
                           value="{$customer[q].CUSTOMER_EMAIL}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        <strong>{$translate_type}</strong>
                    </label>
                    <select name="customerType" class="form-select">
                        <option value="1" {if $customer[q].CUSTOMER_TYPE == 1}selected{/if}>{$translate_customer_type_1}</option>
                        <option value="2" {if $customer[q].CUSTOMER_TYPE == 2}selected{/if}>{$translate_customer_type_2}</option>
                        <option value="3" {if $customer[q].CUSTOMER_TYPE == 3}selected{/if}>{$translate_customer_type_3}</option>
                        <option value="4" {if $customer[q].CUSTOMER_TYPE == 4}selected{/if}>{$translate_customer_type_4}</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <strong>{$translate_customer_discount}</strong>
                </label>
                <div class="input-group" style="max-width: 200px;">
                    <input type="text"
                           name="discount"
                           value="{$customer[q].DISCOUNT}"
                           class="form-control">
                    <span class="input-group-text">%</span>
                </div>
            </div>

            <div class="mt-4">
                <input name="submit" value="Submit" type="submit" class="btn btn-primary">
                  
            </div>

        </div>
    </div>

    {/section}

    </form>

</div>
