<!-- gift certificate -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		plugins : "advlink,iespell,insertdatetime,preview",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center", 
		content_css : "themes/default/style.css",
	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		flash_external_list_url : "example_flash_list.js",
		file_browser_callback : "fileBrowserCallBack",
		width : "100%"
	});
</script>

</script>
<link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
<script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>

<script type="text/javascript">
//<![CDATA[
function validate_gift(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';

value = frm.elements['expire'].value;
  if (value == '' && !errFlag['expire']) {
    errFlag['expire'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_billing_error_date}{literal}';
	frm.elements['expire'].className = 'error';
  }

value = frm.elements['amount'].value;
  if (value == '' && !errFlag['amount']) {
    errFlag['amount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_billing_error_gift_amount}{literal}';
	frm.elements['amount'].className = 'error';
  }

if (_qfMsg != '') {
    _qfMsg = '{/literal}{$translate_billing_error_invalid}{literal}' + _qfMsg;
    _qfMsg = _qfMsg + '\n{/literal}{$translate_billing_error_fix}{literal}';
    alert(_qfMsg);
    return false;
  }
  return true;
}
//]]>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">&nbsp;{$translate_billing_new_gift} {$customer_name}</span>
            </div>

            <div class="card-body">
                <p class="mb-4">
                    {$translate_billing_gift_note_3} {$customer_name} {$translate_billing_gift_note_4}
                </p>

                {literal}
                <form method="POST"
                      action="index.php?page=billing:new_gift"
                      name="gift"
                      id="gift"
                      onsubmit="try { var myValidator = validate_gift; } catch(e) { return true; } return myValidator(this);">
                {/literal}

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">
                            {$translate_billing_customer_name}
                        </label>
                        <div class="col-sm-8 d-flex align-items-center">
                            {$customer_name}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="due_date" class="col-sm-4 col-form-label fw-bold">
                            {$translate_billing_exp}
                        </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="expire"
                                    id="due_date"
                                    size="10"
                                    class="form-control olotd5"
                                    value=""
                                />
                                <button type="button"
                                        id="trigger_due_date"
                                        class="btn btn-outline-secondary">
                                    +
                                </button>
                            </div>
                            {literal}
                            <script type="text/javascript">
                                Calendar.setup(
                                {
                                    inputField  : "due_date",
                                    ifFormat    : "%m/%d/%Y",
                                    button      : "trigger_due_date"
                                }
                                );
                            </script>
                            {/literal}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-bold">
                            {$translate_billing_amount}
                        </label>
                        <div class="col-sm-8">
                            <div class="input-group" style="max-width: 200px;">
                                <span class="input-group-text">$</span>
                                <input type="text"
                                       name="amount"
                                       class="form-control olotd5"
                                       size="6">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 fw-bold">
                        {$translate_billing_memo}
                    </div>
                    <div class="mb-3">
                        <textarea
                            class="form-control olotd5"
                            rows="15"
                            cols="70"
                            mce_editable="true"
                            name="memo"></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <input type="hidden" name="customer_id" value="{$customer_id}">
                            <input type="hidden" name="action" value="add">
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
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