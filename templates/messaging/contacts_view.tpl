<h2>{$translate_messaging_contact_details}</h2>

<div class="card mb-3">
	<div class="card-body">
		<p><strong>{$translate_messaging_name}:</strong> {$contact.FIRST_NAME|escape} {$contact.LAST_NAME|escape}</p>
		<p><strong>{$translate_messaging_email}:</strong> {$contact.EMAIL|escape}</p>
		<p><strong>{$translate_messaging_phone}:</strong> {$contact.PHONE|escape}</p>
		<p><strong>{$translate_messaging_job_title}:</strong> {$contact.JOB_TITLE|escape}</p>
		<p><strong>{$translate_messaging_social}:</strong> {$contact.SOCIAL_HANDLE|escape}</p>
		{if $contact.BUSINESS_NAME}
		<hr>
		<h5>{$translate_messaging_business}</h5>
		<p><strong>{$translate_messaging_business_name}:</strong> {$contact.BUSINESS_NAME|escape}</p>
		<p><strong>{$translate_messaging_contract_renewal}:</strong> {$contact.CONTRACT_RENEWAL_DATE|escape}</p>
		<p><strong>{$translate_messaging_product_preferences}:</strong> {$contact.PRODUCT_PREFERENCES|escape}</p>
		{/if}
	</div>
</div>

<p>
	<a class="btn btn-sm btn-secondary" href="index.php?page=messaging:contacts&action=edit&contact_id={$contact.CONTACT_ID}">{$translate_messaging_edit_contact}</a>
	<a class="btn btn-sm btn-outline-secondary" href="index.php?page=messaging:contacts&action=list">{$translate_messaging_cancel}</a>
</p>
