{literal}
<script type="text/javascript">
//<![CDATA[
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
//]]>
</script>

<style>
.error {
    border: 2px solid #ff0000 !important;
    background-color: #fff0f0;
}
</style>
{/literal}