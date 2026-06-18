<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">
            <h2 class="mb-0">
                {$translate_messaging_business_name}:
                {$business.BUSINESS_NAME|escape}
            </h2>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <strong>{$translate_messaging_business_phone}</strong>
                        <div class="mt-1">
                            {$business.BUSINESS_PHONE|escape}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <strong>{$translate_messaging_contract_renewal}</strong>
                        <div class="mt-1">
                            {$business.CONTRACT_RENEWAL_DATE|escape}
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <strong>{$translate_messaging_business_address}</strong>
                        <div class="mt-1">
                            {$business.BUSINESS_ADDRESS|escape|nl2br}
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <strong>{$translate_messaging_product_preferences}</strong>
                        <div class="mt-1">
                            {$business.PRODUCT_PREFERENCES|escape|nl2br}
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer">
            <div class="d-flex gap-2">
                <a class="btn btn-primary"
                   href="index.php?page=messaging:contacts&action=business_edit&business_id={$business.BUSINESS_ID}">
                    Edit
                </a>

                <a class="btn btn-outline-secondary"
                   href="index.php?page=messaging:contacts&action=business_list">
                    Back
                </a>
            </div>
        </div>

    </div>

</div>