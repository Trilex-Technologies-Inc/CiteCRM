<h2>Lead Details</h2>

{if $lead}
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{$lead.LEAD_TITLE|escape}</h5>
                <p class="card-text">{$lead.LEAD_DESCRIPTION|nl2br}</p>
                <p><strong>Status:</strong> {$lead.LEAD_STATUS|escape}</p>
                <p><strong>Priority:</strong> {$lead.LEAD_PRIORITY|escape}</p>
            </div>
        </div>
        <p>
            <a class="btn btn-sm btn-secondary" href="index.php?page=leads:edit&lead_id={$lead.LEAD_ID}">Edit</a>
            <a class="btn btn-sm btn-outline-secondary" href="index.php?page=leads:list">Back</a>
        </p>
{else}
        <p>Lead not found.</p>
{/if}
