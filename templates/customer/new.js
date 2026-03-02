{literal}
<script type="text/javascript">
    function validate_new_customer(frm) {

    var errors = [];
    var firstError = null;

    function markInvalid(field, message) {
    if (!field) return;

    field.classList.add("is-invalid");

    if (!firstError) {
    firstError = field;
}

    errors.push(message);
}

    function clearInvalid(field) {
    if (!field) return;
    field.classList.remove("is-invalid");
}

    function checkRequired(name, message) {
    var field = frm.elements[name];
    clearInvalid(field);

    if (!field || field.value.trim() === '') {
    markInvalid(field, message);
}
}

    function checkMaxLength(name, max, message) {
    var field = frm.elements[name];
    if (!field) return;

    if (field.value.length > max) {
    markInvalid(field, message);
}
}

    // Display Name
    checkRequired('displayName', 'Please enter the Customer Display Name');
    checkMaxLength('displayName', 80, 'Display Name cannot be more than 80 characters');

    // First Name
    checkRequired('firstName', 'Please enter the Customer First Name');
    checkMaxLength('firstName', 50, 'First Name cannot be more than 50 characters');

    // Last Name
    checkRequired('lastName', 'Please enter the Customer Last Name');
    checkMaxLength('lastName', 50, 'Last Name cannot be more than 50 characters');

    // Home Phone (required)
    checkRequired('homePhone', 'Please enter the Home Phone');

    // Address
    checkRequired('address', 'Please enter the Address');
    checkMaxLength('address', 100, 'Address cannot be more than 100 characters');

    checkRequired('city', 'Please enter the City');
    checkMaxLength('city', 50, 'City cannot be more than 50 characters');

    checkRequired('state', 'Please enter the State');
    checkMaxLength('state', 20, 'State cannot be more than 20 characters');

    checkRequired('zip', 'Please enter the Zip');
    checkMaxLength('zip', 10, 'Zip cannot be more than 10 characters');

    // Email (optional but validated if filled)
    var email = frm.elements['email'];
    clearInvalid(email);

    if (email.value.trim() !== '') {

    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(email.value)) {
    markInvalid(email, 'Please enter a valid email address');
}

    if (email.value.length > 50) {
    markInvalid(email, 'Email cannot be more than 50 characters');
}
}

    // Customer Type (always selected but kept for safety)
    var typeField = frm.elements['customerType'];
    clearInvalid(typeField);

    if (!typeField.value) {
    markInvalid(typeField, 'Please select the customer type');
}

    if (errors.length > 0) {
    alert("Invalid information entered:\n\n- " + errors.join("\n- "));
    firstError.focus();
    return false;
}

    return true;
}

    // Remove red border automatically when user types
    document.addEventListener("DOMContentLoaded", function () {

    var form = document.getElementById("new_customer");
    var inputs = form.querySelectorAll("input, select");

    inputs.forEach(function (input) {
    input.addEventListener("input", function () {
    this.classList.remove("is-invalid");
});

    input.addEventListener("change", function () {
    this.classList.remove("is-invalid");
});
});

});
</script>
{/literal}
