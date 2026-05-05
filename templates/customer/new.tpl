<!-- Add New Customer -->
<!-- Toolbar -->
<div class="container-fluid mb-3">
    <div class="d-flex align-items-center">
        {include file="core/tool_bar.tpl"}
    </div>
</div>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{$translate_customer_add}</h4>
        <a href="http://www.citecrm.com/docs/#Customers" target="new" class="btn btn-sm btn-outline-secondary">
            Help
        </a>
    </div>

    {if $error_msg != ""}
        {include file="core/error.tpl"}
    {/if}

    {include file="customer/new.js"}

    {literal}
    <form action="index.php?page=customer:new" 
          method="POST" 
          name="new_customer" 
          id="new_customer"
          onsubmit="try { var myValidator = validate_new_customer; } catch(e) { return true; } return myValidator(this);">
    {/literal}

        <!-- Basic Info -->
        <div class="card mb-4">
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span> <b>{$translate_display}</b>
                    </label>
                    <input type="text" name="displayName" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> <b>{$translate_first}</b>
                        </label>
                        <input type="text" name="firstName" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> <b>{$translate_last}</b>
                        </label>
                        <input type="text" name="lastName" class="form-control">
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
                        <span class="text-danger">*</span> <b>{$translate_customer_home}</b>
                    </label>
                    <input type="text" name="homePhone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <b>{$translate_customer_work}</b>
                    </label>
                    <input type="text" name="workPhone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <b>{$translate_customer_mobile}</b>
                    </label>
                    <input type="text" name="mobilePhone" class="form-control">
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
                        <span class="text-danger">*</span> <b>{$translate_customer_address}</b>
                    </label>
                    <input type="text" name="address" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> <b>{$translate_customer_city}</b>
                        </label>
                        <input type="text" name="city" value="{$company_city}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> <b>{$translate_customer_state}</b>
                        </label>
                        <input type="text" name="state" value="{$company_state}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> <b>{$translate_customer_zip}</b>
                        </label>
                        <input type="text" name="zip" value="{$company_zip}" maxlength="20" class="form-control">
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
                            <b>{$translate_email}</b>
                        </label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> <b>{$translate_customer_type}</b>
                        </label>
                        <select name="customerType" class="form-select">
                            <option value="1">{$translate_customer_type_1}</option>
                            <option value="2">{$translate_customer_type_2}</option>
                            <option value="3">{$translate_customer_type_3}</option>
                            <option value="4">{$translate_customer_type_4}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <b>{$translate_customer_discount}</b>
                    </label>
                    <input type="text" name="discount" value="0.00" class="form-control">
                </div>

                <input type="hidden" name="page" value="customer:new">

                <div class="mt-4">
                    <input type="submit" name="submit" value="submit" class="btn btn-primary">
                        
                </div>

            </div>
        </div>

    </form>

</div>
