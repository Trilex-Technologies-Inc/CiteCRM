<!-- gift certificate -->
{literal}

    <script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            tinymce.init({
                selector: 'textarea[name="memo"]',
                license_key: 'gpl',
                height: 400,
                menubar: true,
                plugins: 'lists link image table code preview fullscreen',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code preview fullscreen',
                toolbar_mode: 'sliding'
            });
        });

        function validate_gift(frm) {
            var value = '';
            var errFlag = {};
            var _qfMsg = '';

            value = frm.elements['expire'].value;
            if (value === '' && !errFlag['expire']) {
                errFlag['expire'] = true;
                _qfMsg += '\n - {$translate_billing_error_date}';
                frm.elements['expire'].classList.add('is-invalid');
            }

            value = frm.elements['amount'].value;
            if (value === '' && !errFlag['amount']) {
                errFlag['amount'] = true;
                _qfMsg += '\n - {$translate_billing_error_gift_amount}';
                frm.elements['amount'].classList.add('is-invalid');
            }

            if (_qfMsg !== '') {
                _qfMsg = '{$translate_billing_error_invalid}' + _qfMsg;
                _qfMsg += '\n{$translate_billing_error_fix}';
                alert(_qfMsg);
                return false;
            }

            return true;
        }
    </script>
{/literal}

<div class="container-fluid">

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
            <div class="card-header">
                <span class="fw-bold">
                    {$translate_billing_new_gift} {$customer_name}
                </span>
            </div>

            <div class="card-body">
                <p class="mb-4">
                    {$translate_billing_gift_note_3} {$customer_name} {$translate_billing_gift_note_4}
                </p>

                <form method="POST"
                      action="index.php?page=billing:new_gift"
                      name="gift"
                      id="gift"
                      onsubmit="return validate_gift(this);">

                    <!-- Customer Name -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">
                            {$translate_billing_customer_name}
                        </label>
                        <div class="col-sm-8 d-flex align-items-center">
                            {$customer_name}
                        </div>
                    </div>

                    <!-- Expire Date (HTML5) -->
                    <div class="mb-3 row">
                        <label for="expire" class="col-sm-4 col-form-label fw-bold">
                            {$translate_billing_exp}
                        </label>
                        <div class="col-sm-8">
                            <input type="date"
                                   name="expire"
                                   id="expire"
                                   class="form-control">
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">
                            {$translate_billing_amount}
                        </label>
                        <div class="col-sm-8">
                            <div class="input-group" style="max-width: 200px;">
                                <span class="input-group-text">$</span>
                                <input type="number"
                                       step="0.01"
                                       name="amount"
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Memo -->
                    <div class="mb-2 fw-bold">
                        {$translate_billing_memo}
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control"
                                  rows="10"
                                  name="memo"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <input type="hidden" name="customer_id" value="{$customer_id}">
                            <input type="hidden" name="action" value="add">
                        </div>
                        <div>
                            <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                            <a href="?page=customer:customer_details&customer_id={$customer_id}&page_title={$customer_name}"
                               class="btn btn-link">
                                {$translate_billing_cancel}
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
