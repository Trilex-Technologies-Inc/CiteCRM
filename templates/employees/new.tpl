<div class="container my-4">

    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/tool_bar.tpl"}
    </div>

    {if $error_msg != ""}
        <div class="alert alert-danger">
            {include file="core/error.tpl"}
        </div>
    {/if}

    {include file="employees/new.js"}

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{$translate_employee_add_new_employee}</h5>
            <span class="text-muted small">Help</span>
        </div>

        <div class="card-body">

        {literal}
        <form action="index.php?page=employee:new"
              method="POST"
              name="new_employee"
              id="new_employee"
              onsubmit="try { var myValidator = validate_new_employee; } catch(e) { return true; } return myValidator(this);">
        {/literal}

            <input type="hidden" name="page" value="employees:new">

            <!-- Basic Info -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_display_name}
                    </label>
                    <input type="text" name="displayName" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_first_name}
                    </label>
                    <input type="text" name="firstName" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_last_name}
                    </label>
                    <input type="text" name="lastName" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_password}
                    </label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_password_confirm}
                    </label>
                    <input type="password" name="confirmPass" class="form-control">
                </div>
            </div>

            <!-- Phone Numbers -->
            <h6 class="border-bottom pb-2 mb-3">{$translate_employee_phone_numbers}</h6>

            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_home_phone_number}
                    </label>
                    <input type="text" name="homePhone" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        {$translate_employee_work_phone_number}
                    </label>
                    <input type="text" name="workPhone" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        {$translate_employee_mobile_phone_number}
                    </label>
                    <input type="text" name="mobilePhone" class="form-control">
                </div>
            </div>

            <!-- Address -->
            <h6 class="border-bottom pb-2 mb-3">{$translate_employee_address}</h6>

            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_address}
                    </label>
                    <input type="text" name="address" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_city}
                    </label>
                    <input type="text" name="city" class="form-control" value="{$company_city}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_state}
                    </label>
                    <input type="text" name="state" class="form-control" value="{$company_state}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_zip}
                    </label>
                    <input type="text" name="zip" class="form-control" value="{$company_zip}">
                </div>
            </div>

            <!-- Type & Email -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_employee_type}
                    </label>
                    <select name="type" class="form-select">
                        {section name=g loop=$employee_type}
                            <option value="{$employee_type[g].TYPE_ID}">
                                {$employee_type[g].TYPE_NAME}
                            </option>
                        {/section}
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        {$translate_employee_email_address}
                    </label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>

            <!-- Submit -->
            <div class="text-end">
                <button type="submit" name="submit" class="btn btn-primary px-4">
                    {$translate_employee_submit}
                </button>
            </div>

        </form>

        </div>
    </div>

</div>
