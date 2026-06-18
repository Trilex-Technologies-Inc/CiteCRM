<h2>{if $contact}Edit Contact{else}Create Contact{/if}</h2>
<form method="post" action="index.php?page=leads:contacts_save" onsubmit="return validateLeadsContactForm(this);">
    <input type="hidden" name="contact_id" value="{$contact.CONTACT_ID|default:0}">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input class="form-control" type="text" name="contact_name" value="{$contact.CONTACT_NAME|default:''}">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" type="text" name="contact_email" value="{$contact.CONTACT_EMAIL|default:''}">
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input class="form-control" type="text" name="contact_phone" value="{$contact.CONTACT_PHONE|default:''}">
    </div>
    <div class="mb-3">
        <label class="form-label">Company</label>
        <input class="form-control" type="text" name="company" value="{$contact.COMPANY|default:''}">
    </div>
    <div>
        <input class="btn btn-primary" type="submit" value="Save"> <a class="btn btn-secondary" href="index.php?page=leads:contacts_list">Cancel</a>
    </div>
</form>
<script src="js/messaging_contacts.js"></script>
