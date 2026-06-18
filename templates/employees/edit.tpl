<div class="container my-4">

    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/tool_bar.tpl"}
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Edit Employee</h5>
        </div>

        <div class="card-body">

            {if $error_msg != ""}
                <div class="alert alert-danger">
                    {include file="core/error.tpl"}
                </div>
            {/if}

            {include file="employees/edit.js"}

            {section name="a" loop=$employee_details}

            {literal}
            <form action="?page=employees:edit"
                  method="POST"
                  name="new_employee"
                  id="new_employee"
                  onsubmit="try { var myValidator = validate_new_employee; } catch(e) { return true; } return myValidator(this);">
            {/literal}

                <input type="hidden" name="employee_id" value="{$employee_details[a].EMPLOYEE_ID}">

                <!-- Basic Info -->
                <div class="row mb-4">

                    <div class="col-12 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_display_name}
                        </label>
                        <input type="text" name="displayName"
                               value="{$employee_details[a].EMPLOYEE_DISPLAY_NAME}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_first_name}
                        </label>
                        <input type="text" name="firstName"
                               value="{$employee_details[a].EMPLOYEE_FIRST_NAME}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_last_name}
                        </label>
                        <input type="text" name="lastName"
                               value="{$employee_details[a].EMPLOYEE_LAST_NAME}"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {$translate_employee_password}
                        </label>
                        <input type="password" name="password" class="form-control">
                        <div class="form-text">Leave blank to keep current password</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {$translate_employee_password_confirm}
                        </label>
                        <input type="password" name="confirmPass" class="form-control">
                    </div>

                </div>

                <!-- Phone Numbers -->
                <h6 class="border-bottom pb-2 mb-3">
                    {$translate_employee_phone_numbers}
                </h6>

                <div class="row mb-4">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_home_phone_number}
                        </label>
                        <input type="text" name="homePhone"
                               value="{$employee_details[a].EMPLOYEE_HOME_PHONE}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            {$translate_employee_work_phone_number}
                        </label>
                        <input type="text" name="workPhone"
                               value="{$employee_details[a].EMPLOYEE_WORK_PHONE}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            {$translate_employee_mobile_phone_number}
                        </label>
                        <input type="text" name="mobilePhone"
                               value="{$employee_details[a].EMPLOYEE_MOBILE_PHONE}"
                               class="form-control">
                    </div>

                </div>

                <!-- Address -->
                <h6 class="border-bottom pb-2 mb-3">
                    {$translate_employee_address}
                </h6>

                <div class="row mb-4">

                    <div class="col-12 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_address}
                        </label>
                        <input type="text" name="address"
                               value="{$employee_details[a].EMPLOYEE_ADDRESS}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_city}
                        </label>
                        <input type="text" name="city"
                               value="{$employee_details[a].EMPLOYEE_CITY}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_state}
                        </label>
                        <input type="text" name="state"
                               value="{$employee_details[a].EMPLOYEE_STATE}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_zip}
                        </label>
                        <input type="text" name="zip"
                               value="{$employee_details[a].EMPLOYEE_ZIP}"
                               class="form-control">
                    </div>

                </div>

                <!-- Type / Email / Active -->
                <div class="row mb-4">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_employee_type}
                        </label>
                        <select name="type" class="form-select">
                            {section name=g loop=$employee_type}
                                <option value="{$employee_type[g].TYPE_ID}"
                                {if $employee_details[a].EMPLOYEE_TYPE == $employee_type[g].TYPE_ID} selected{/if}>
                                    {$employee_type[g].TYPE_NAME}
                                </option>
                            {/section}
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            {$translate_employee_email_address}
                        </label>
                        <input type="email" name="email"
                               value="{$employee_details[a].EMPLOYEE_EMAIL}"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Active
                        </label>
                        <select name="active" class="form-select">
                            <option value="0" {if $employee_details[a].EMPLOYEE_STATUS == '0'}selected{/if}>No</option>
                            <option value="1" {if $employee_details[a].EMPLOYEE_STATUS == '1'}selected{/if}>Yes</option>
                        </select>
                    </div>

                </div>

                <!-- Submit -->
                <div class="text-end">
                    <button type="submit" name="submit" class="btn btn-primary px-4">
                        {$translate_employee_submit}
                    </button>
                </div>

            </form>

            {/section}

        </div>
    </div>

</div>
