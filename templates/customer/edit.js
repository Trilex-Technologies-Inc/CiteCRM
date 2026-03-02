{literal}
<script type="text/javascript">
	function validate_edit_customer(frm) {

	var errors = [];
	var firstErrorField = null;

	function markInvalid(field, message) {
	if (!field) return;

	field.classList.add("is-invalid");   // Bootstrap red border
	if (!firstErrorField) {
	firstErrorField = field;
}
	errors.push(message);
}

	function clearInvalid(field) {
	if (!field) return;
	field.classList.remove("is-invalid");
}

	function checkRequired(fieldName, message) {
	var field = frm.elements[fieldName];
	clearInvalid(field);

	if (!field || field.value.trim() === '') {
	markInvalid(field, message);
}
}

	function checkMaxLength(fieldName, maxLength, message) {
	var field = frm.elements[fieldName];
	if (!field) return;

	if (field.value.length > maxLength) {
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

	// Phones
	checkRequired('homePhone', 'Please enter the Home Phone');
	checkRequired('workPhone', 'Please enter the Work Phone');
	checkRequired('mobilePhone', 'Please enter the Mobile Phone');

	// Address
	checkRequired('address', 'Please enter the Address');
	checkMaxLength('address', 100, 'Address cannot be more than 100 characters');

	checkRequired('city', 'Please enter the City');
	checkMaxLength('city', 50, 'City cannot be more than 50 characters');

	checkRequired('state', 'Please enter the State');
	checkMaxLength('state', 20, 'State cannot be more than 20 characters');

	checkRequired('zip', 'Please enter the Zip');
	checkMaxLength('zip', 10, 'Zip cannot be more than 10 characters');

	// Email
	var emailField = frm.elements['email'];
	clearInvalid(emailField);
	if (emailField.value.trim() === '') {
	markInvalid(emailField, 'Please enter the Email');
} else {
	var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	if (!emailPattern.test(emailField.value)) {
	markInvalid(emailField, 'Please enter a valid email address');
}
}

	// Customer Type
	var typeField = frm.elements['customerType'];
	clearInvalid(typeField);
	if (typeField.value === '') {
	markInvalid(typeField, 'Please select the Customer Type');
}

	// Discount
	var discountField = frm.elements['discount'];
	clearInvalid(discountField);
	if (discountField.value !== '') {
	if (isNaN(discountField.value) ||
	discountField.value < 0 ||
	discountField.value > 100) {
	markInvalid(discountField, 'Discount must be between 0 and 100');
}
}

	if (errors.length > 0) {
	alert("Invalid information entered:\n\n- " + errors.join("\n- "));
	firstErrorField.focus();
	return false;
}

	return true;
}

	// Remove red border automatically when typing
	document.addEventListener("DOMContentLoaded", function () {
	var form = document.getElementById("edit_customer");
	var inputs = form.querySelectorAll("input, select");

	inputs.forEach(function (input) {
	input.addEventListener("input", function () {
	this.classList.remove("is-invalid");
});
});
});
</script>
{/literal}
