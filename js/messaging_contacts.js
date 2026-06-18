// Client-side validation for Messaging contacts and businesses
function validateEmail(email) {
    if (!email) return true;
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validateContactForm(form) {
    var first = form.first_name.value.trim();
    var last = form.last_name.value.trim();
    var email = form.email.value.trim();
    var phone = form.phone.value.trim();

    if (!first && !last && !email) {
        alert('Please provide a name or email for the contact.');
        return false;
    }
    if (email && !validateEmail(email)) {
        alert('Please enter a valid email address.');
        return false;
    }
    if (phone && phone.length > 100) {
        alert('Phone number is too long.');
        return false;
    }
    return true;
}

function validateBusinessForm(form) {
    var name = form.business_name.value.trim();
    if (!name) {
        alert('Please provide a business name.');
        return false;
    }
    var phone = form.business_phone.value.trim();
    if (phone && phone.length > 100) {
        alert('Business phone is too long.');
        return false;
    }
    return true;
}

// Expose to global
window.validateContactForm = validateContactForm;
window.validateBusinessForm = validateBusinessForm;

// Validate leads contact form (legacy leads module uses different field names)
function validateLeadsContactForm(form) {
    var name = form.contact_name ? form.contact_name.value.trim() : '';
    var email = form.contact_email ? form.contact_email.value.trim() : '';
    if (!name && !email) {
        alert('Please provide a name or email for the contact.');
        return false;
    }
    if (email && !validateEmail(email)) {
        alert('Please enter a valid email address.');
        return false;
    }
    return true;
}
window.validateLeadsContactForm = validateLeadsContactForm;
