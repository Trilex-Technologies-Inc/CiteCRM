<div class="container my-4">

    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/admin_tool_bar.tpl"}
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Add New Subcategory</h5>
        </div>

        <div class="card-body">

            {if $error_msg != ""}
                <div class="alert alert-danger">
                    {$error_msg}
                </div>
            {/if}

            {literal}
            <script type="text/javascript">
            function validate_new_subcat(frm) {
                var value = '';
                var errFlag = new Array();
                var _qfMsg = '';

                // Validate Subcategory ID
                value = frm.elements['sub_category'].value;
                if (value == '' && !errFlag['sub_category']) {
                    errFlag['sub_category'] = true;
                    _qfMsg = _qfMsg + '\n - Please enter the Subcategory ID';
                    frm.elements['sub_category'].className = 'error';
                }

                // Validate Subcategory ID length
                value = frm.elements['sub_category'].value;
                if (value != '' && value.length > 10 && !errFlag['sub_category']) {
                    errFlag['sub_category'] = true;
                    _qfMsg = _qfMsg + '\n - Subcategory ID cannot be more than 10 characters';
                    frm.elements['sub_category'].className = 'error';
                }

                // Validate Description
                value = frm.elements['description'].value;
                if (value == '' && !errFlag['description']) {
                    errFlag['description'] = true;
                    _qfMsg = _qfMsg + '\n - Please enter the Subcategory Description';
                    frm.elements['description'].className = 'error';
                }

                // Validate Description length
                value = frm.elements['description'].value;
                if (value != '' && value.length < 3 && !errFlag['description']) {
                    errFlag['description'] = true;
                    _qfMsg = _qfMsg + '\n - Description must be at least 3 characters';
                    frm.elements['description'].className = 'error';
                }

                // Validate Description max length
                value = frm.elements['description'].value;
                if (value != '' && value.length > 100 && !errFlag['description']) {
                    errFlag['description'] = true;
                    _qfMsg = _qfMsg + '\n - Description cannot be more than 100 characters';
                    frm.elements['description'].className = 'error';
                }

                if (_qfMsg != '') {
                    _qfMsg = 'Invalid information entered.' + _qfMsg;
                    _qfMsg = _qfMsg + '\nPlease correct these fields.';
                    alert(_qfMsg);
                    return false;
                }
                return true;
            }
            </script>

            <style>
            .error {
                border: 2px solid #ff0000 !important;
                background-color: #fff0f0;
            }
            </style>
            {/literal}

            <form action="?page=cats:subcat_new&cat_id={$cat_id}"
                  method="POST"
                  name="new_subcat"
                  id="new_subcat"
                  onsubmit="return validate_new_subcat(this);">

                <!-- Hidden Cat ID -->
                <input type="hidden" name="cat_id" value="{$cat_id}">

                <!-- Subcategory ID -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            Subcategory ID
                        </label>
                        <input type="text" name="sub_category"
                               value="{$sub_category}"
                               class="form-control"
                               placeholder="Enter unique Subcategory ID">
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-4">
                    <div class="col-12 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            Description
                        </label>
                        <textarea name="description" 
                                  rows="5" 
                                  class="form-control"
                                  placeholder="Enter subcategory description">{$description}</textarea>
                    
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-end">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary px-4">
                        Add Subcategory
                    </button>
                    <a href="?page=cats:main" class="btn btn-secondary px-4">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>