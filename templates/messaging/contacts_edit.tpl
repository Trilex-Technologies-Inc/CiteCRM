<h2>{if $contact}{$translate_messaging_edit_contact}{else}{$translate_messaging_new_contact}{/if}</h2>

{if $error_msg}
  <div class="alert alert-danger">{$error_msg}</div>
{/if}

<form method="post" action="index.php?page=messaging:contacts&action={if $contact}edit{else}new{/if}" onsubmit="return validateContactForm(this);">
    {if $contact}<input type="hidden" name="contact_id" value="{$contact.CONTACT_ID}">{/if}
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_first_name}</label>
        <input class="form-control" type="text" name="first_name" value="{if $contact}{$contact.FIRST_NAME}{/if}">
    </div>
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_last_name}</label>
        <input class="form-control" type="text" name="last_name" value="{if $contact}{$contact.LAST_NAME}{/if}">
    </div>
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_email}</label>
        <input class="form-control" type="text" name="email" value="{if $contact}{$contact.EMAIL}{/if}">
    </div>
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_phone}</label>
        <input class="form-control" type="text" name="phone" value="{if $contact}{$contact.PHONE}{/if}">
    </div>
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_job_title}</label>
        <input class="form-control" type="text" name="job_title" value="{if $contact}{$contact.JOB_TITLE}{/if}">
    </div>
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_social}</label>
        <input class="form-control" type="text" name="social_handle" value="{if $contact}{$contact.SOCIAL_HANDLE}{/if}">
    </div>
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_business}</label>
        <div class="d-flex gap-2">
            <select class="form-select" name="business_id">
                <option value="">-- None --</option>
                {foreach from=$businesses item=b}
                    <option value="{$b.BUSINESS_ID}" {if $contact && $contact.BUSINESS_ID == $b.BUSINESS_ID}selected{/if}>{$b.BUSINESS_NAME|escape}</option>
                {/foreach}
            </select>
            <a class="btn btn-sm btn-outline-primary" href="index.php?page=messaging:contacts&action=business_new">{$translate_messaging_add_new_business}</a>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">{$translate_messaging_notes}</label>
        <textarea class="form-control" name="notes" rows="6">{if $contact}{$contact.NOTES}{/if}</textarea>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" name="submit" value="{$translate_messaging_save}">
        <a class="btn btn-secondary" href="index.php?page=messaging:contacts&action=list">{$translate_messaging_cancel}</a>
    </div>
</form>
<script src="js/messaging_contacts.js"></script>
