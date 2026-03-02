<div class="container my-4">

    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/admin_tool_bar.tpl"}
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Edit Cat</h5>
        </div>

        <div class="card-body">

            {if $error_msg != ""}
                <div class="alert alert-danger">
                    {include file="core/error.tpl"}
                </div>
            {/if}

            {literal}
            <script type="text/javascript">
            function validate_edit_cat(frm) {
                var value = '';
                var errFlag = new Array();
                var _qfMsg = '';

                // Validate Description
                value = frm.elements['description'].value;
                if (value == '' && !errFlag['description']) {
                    errFlag['description'] = true;
                    _qfMsg = _qfMsg + '\n - Please enter the Cat Description';
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
                if (value != '' && value.length > 255 && !errFlag['description']) {
                    errFlag['description'] = true;
                    _qfMsg = _qfMsg + '\n - Description cannot be more than 255 characters';
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

            <form action="?page=cats:edit"
                  method="POST"
                  name="edit_cat"
                  id="edit_cat"
                  onsubmit="return validate_edit_cat(this);">

                <input type="hidden" name="cat_id" value="{$cat_details.ID}">

                <!-- Basic Info -->
                <div class="row mb-4">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span>
                            Cat ID
                        </label>
                        <input type="text" name="id"
                               value="{$cat_details.ID}"
                               class="form-control"
                               readonly>
                        <div class="form-text">ID cannot be changed</div>
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
                                  class="form-control">{$cat_details.DESCRIPTION}</textarea>
                        <div class="form-text">Enter a detailed description of the cat</div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-end">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary px-4">
                        Update Cat
                    </button>
                    <a href="?page=cats:main" class="btn btn-secondary px-4">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>