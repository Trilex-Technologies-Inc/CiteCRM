<script type="text/javascript">
//<![CDATA[
  function validate_install(frm) {
  var errors = [];
  var touched = { };

  function setError(name, message) {
    if (!touched[name]) {
    touched[name] = true;
  errors.push(message);
  if (frm.elements[name]) {
    frm.elements[name].classList.add('is-invalid');
      }
    }
  }

  function clearErrorClasses() {
    var inputs = frm.querySelectorAll('.is-invalid');
  for (var i = 0; i < inputs.length; i++) {
    inputs[i].classList.remove('is-invalid');
    }
  }

  clearErrorClasses();

  var value;

  // Database settings
  value = frm.elements['db_user'].value.trim();
  if (!value) {setError('db_user', 'Root database user is required.'); }

  value = frm.elements['db_password'].value.trim();
  if (!value) {setError('db_password', 'Root database password is required.'); }

  value = frm.elements['db_host'].value.trim();
  if (!value) {setError('db_host', 'Database host is required.'); }

  value = frm.elements['db_name'].value.trim();
  if (!value) {setError('db_name', 'Database name is required.'); }

  value = frm.elements['db_prefix'].value.trim();
  if (!value) {setError('db_prefix', 'Table prefix is required.'); }

  value = frm.elements['crm_db_user'].value.trim();
  if (!value) {setError('crm_db_user', 'CRM database user is required.'); }

  value = frm.elements['crm_db_password'].value.trim();
  if (!value) {setError('crm_db_password', 'CRM database password is required.'); }

  // Admin settings
  var password = frm.elements['default_password'].value;
  var password2 = frm.elements['default_password2'].value;

  if (!password) {
    setError('default_password', 'Administrator password is required.');
  } else {
    if (password.length < 6) {setError('default_password', 'Password must be at least 6 characters.'); }
    if (password.length > 50) {setError('default_password', 'Password cannot exceed 50 characters.'); }
  if (!/^[A-Za-z0-9]+$/.test(password)) {setError('default_password', 'Password may only contain letters and numbers.'); }
  }

  if (!password2) {
    setError('default_password2', 'Please confirm the administrator password.');
  } else if (password && password !== password2) {
    setError('default_password2', 'Passwords do not match.');
  }

  value = frm.elements['first_name'].value.trim();
  if (!value) {setError('first_name', 'Administrator first name is required.'); }

  value = frm.elements['last_name'].value.trim();
  if (!value) {setError('last_name', 'Administrator last name is required.'); }

  value = frm.elements['display_name'].value.trim();
  if (!value) {setError('display_name', 'Administrator display name is required.'); }

  value = frm.elements['address'].value.trim();
  if (!value) {setError('address', 'Administrator address is required.'); }

  value = frm.elements['city'].value.trim();
  if (!value) {setError('city', 'Administrator city is required.'); }

  value = frm.elements['state'].value.trim();
  if (!value) {setError('state', 'Administrator state is required.'); }

  value = frm.elements['zip'].value.trim();
  if (!value) {setError('zip', 'Administrator ZIP code is required.'); }

  value = frm.elements['default_email'].value.trim();
  if (!value) {
    setError('default_email', 'Administrator email address is required.');
  } else {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(value)) {setError('default_email', 'Enter a valid email address.'); }
    if (value.length > 100) {setError('default_email', 'Email cannot exceed 100 characters.'); }
  }

  // Company settings
  value = frm.elements['COMPANY_NAME'].value.trim();
  if (!value) {setError('COMPANY_NAME', 'Company name is required.'); }

  value = frm.elements['COMPANY_ADDRESS'].value.trim();
  if (!value) {setError('COMPANY_ADDRESS', 'Company address is required.'); }

  value = frm.elements['COMPANY_CITY'].value.trim();
  if (!value) {setError('COMPANY_CITY', 'Company city is required.'); }

  value = frm.elements['COMPANY_STATE'].value.trim();
  if (!value) {setError('COMPANY_STATE', 'Company state is required.'); }

  value = frm.elements['COMPANY_ZIP'].value.trim();
  if (!value) {setError('COMPANY_ZIP', 'Company ZIP code is required.'); }

  value = frm.elements['default_path'].value.trim();
  if (!value) {setError('default_path', 'Installation path is required.'); }

  value = frm.elements['default_site_name'].value.trim();
  if (!value) {
    setError('default_site_name', 'Site URL is required.');
  } else if (!/^https?:\/\/.+/.test(value)) {
    setError('default_site_name', 'Site URL must begin with http:// or https://');
  }

  if (errors.length) {
    alert('Please fix the following fields:\n' + errors.join('\n'));
  return false;
  }
  return true;
}
//]]>
</script>
