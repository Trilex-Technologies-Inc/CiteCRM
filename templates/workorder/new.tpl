<!-- Add New Work Order tpl -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
  selector: 'textarea',  
   license_key: 'gpl',
   menubar: true,
        plugins: " preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons",

        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        paste_as_text: false, // allow full HTML
        valid_elements: '*[*]', // allow any tag and attribute
        extended_valid_elements: '*[*]',
        verify_html: false,
        cleanup: false,
        height: 400,
        code_dialog_height: 500,
        code_dialog_width: 800,
        toolbar_mode: 'sliding',
        setup: function (editor) {
            editor.on('PastePreProcess', function (e) {
                // allow raw HTML paste
                e.content = e.content;
            });
        },
        content_css: false // keep user CSS classes intact
    });
</script>

<script type="text/javascript">
//<![CDATA[
function validate_new_workorder(frm) {
  var value = '';
  var errFlag = new Array();
  _qfMsg = '';

  value = frm.elements['scope'].value;
  if (value == '' && !errFlag['scope']) {
    errFlag['scope'] = true;
    _qfMsg += '\n - Please enter the Work Order Scope';
    frm.elements['scope'].className = 'form-control is-invalid';
  }

  if (value != '' && value.length > 40 && !errFlag['scope']) {
    errFlag['scope'] = true;
    _qfMsg += '\n - The Work Order Scope cannot be more than 40 characters';
    frm.elements['scope'].className = 'form-control is-invalid';
  }

  if (_qfMsg != '') {
    alert('Invalid information entered.' + _qfMsg + '\nPlease correct these fields.');
    return false;
  }
  return true;
}
//]]>
</script>
{/literal}

<div class="container my-4">
    <!-- Toolbar -->
    <div class="mb-3">
        {include file="core/tool_bar.tpl"}
    </div>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{$translate_workorder_new_title}</h3>
        <a href="http://www.citecrm.com/docs/#work_orders" target="_blank">
            <i class="bi bi-question-circle-fill fs-5 text-secondary" aria-hidden="true"></i>
        </a>
    </div>

    {if $error_msg != ""}
        {include file="core/error.tpl"}
    {/if}

    <!-- Customer Contact Info -->
    {section name=i loop=$customer_details}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <span>{$translate_workorder_cutomer_contact_title}</span>
            <a href="?page=customer:edit&customer_id={$customer_details[i].CUSTOMER_ID}">
                <i class="bi bi-pencil-square text-secondary fs-5"
                   aria-hidden="true"
                   onMouseOver="ddrivetip('Edit Customer')"
                   onMouseOut="hideddrivetip()"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><b>{$translate_workorder_contact}</b></div>
                <div class="col-md-3">{$customer_details[i].CUSTOMER_FIRST_NAME} {$customer_details[i].CUSTOMER_LAST_NAME}</div>
                <div class="col-md-3"><b>{$translate_workorder_email}</b></div>
                <div class="col-md-3">{$customer_details[i].CUSTOMER_EMAIL}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><b>{$translate_workorder_address}</b></div>
                <div class="col-md-3">{$customer_details[i].CUSTOMER_ADDRESS}</div>
                <div class="col-md-3"><b>{$translate_workorder_phone_1}</b></div>
                <div class="col-md-3">{$customer_details[i].CUSTOMER_PHONE}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><b>{$translate_workorder_type}</b></div>
                <div class="col-md-3">
                    {if $customer_details[i].CUSTOMER_TYPE ==1}{$translate_workorder_type_1}{/if}
                    {if $customer_details[i].CUSTOMER_TYPE ==2}{$translate_workorder_type_2}{/if}
                    {if $customer_details[i].CUSTOMER_TYPE ==3}{$translate_workorder_type_3}{/if}
                    {if $customer_details[i].CUSTOMER_TYPE ==4}{$translate_workorder_type_4}{/if}
                </div>
            </div>
        </div>
    </div>

    <!-- Work Order Form -->
    {$form.javascript}
    {literal}
    <form method="POST" action="index.php?page=workorder:new" name="new_workorder" id="new_workorder" onsubmit="try { var myValidator = validate_new_workorder; } catch(e) { return true; } return myValidator(this);">
    {/literal}
        <input type="hidden" name="customer_ID" value="{$customer_details[i].CUSTOMER_ID}">
        <input type="hidden" name="page" value="workorder:new">
        <input type="hidden" name="create_by" value="{$login_id}">

        <div class="card mb-4">
            <div class="card-header">{$translate_workorder_opened}</div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">
                        {$smarty.now|date_format:"%m/%d/%Y"}
                        <input type="hidden" name="date" id="date" value="{$smarty.now|date_format:"%m/%d/%Y"}">
                    </div>
                    <div class="col-md-2">{$customer_details[i].CUSTOMER_DISPLAY_NAME}</div>
                    <div class="col-md-3">
                        <input class="form-control" size="30" name="scope" type="text" placeholder="Work Order Scope"/>
                    </div>
                    <div class="col-md-2">{$translate_workorder_created}</div>
                    <div class="col-md-3">{$display_login}</div>
                </div>
            </div>
        </div>

        <!-- Work Order Description -->
        <div class="card mb-3">
            <div class="card-header">{$translate_workorder_description_title}</div>
            <div class="card-body">
                <textarea class="form-control" rows="8" name="work_order_discription"></textarea>
            </div>
        </div>

        <!-- Work Order Comments -->
        <div class="card mb-3">
            <div class="card-header">{$translate_workorder_comments_title}</div>
            <div class="card-body">
                <textarea class="form-control" rows="5" name="work_order_comments"></textarea>
            </div>
        </div>

        <!-- Work Order Notes -->
        <div class="card mb-3">
            <div class="card-header">{$translate_workorder_notes}</div>
            <div class="card-body">
                <textarea class="form-control" rows="5" name="work_order_notes"></textarea>
            </div>
        </div>

        <input type="submit" name="submit" class="btn btn-primary" value="{$translate_workorder_submit}" >
    </form>
    {/section}
</div>
