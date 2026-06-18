<h2>{if $lead}Edit Lead{else}Create Lead{/if}</h2>

<form method="post" action="index.php?page=leads:save">
    <input type="hidden" name="lead_id" value="{$lead.LEAD_ID|default:0}">
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input class="form-control" type="text" name="title" value="{$lead.LEAD_TITLE|default:''}">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description">{$lead.LEAD_DESCRIPTION|default:''}</textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <input class="form-control" type="text" name="status" value="{$lead.LEAD_STATUS|default:'New'}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Priority</label>
            <input class="form-control" type="text" name="priority" value="{$lead.LEAD_PRIORITY|default:'Normal'}">
        </div>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" value="Save"> <a class="btn btn-secondary" href="index.php?page=leads:list">Cancel</a>
    </div>
</form>
