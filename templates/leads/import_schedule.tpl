<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Import Schedules</h2>

        <a href="index.php?page=leads:import_schedule&sub=new"
           class="btn btn-primary">
            Create Schedule
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Preset</th>
                            <th>Source</th>
                            <th>Cron</th>
                            <th>Enabled</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    {foreach from=$schedules item=s}
                        <tr>
                            <td>{$s.SCHEDULE_ID}</td>
                            <td>{$s.PRESET_NAME|escape}</td>
                            <td>
                                <small class="text-break">
                                    {$s.SOURCE_PATH|escape}
                                </small>
                            </td>
                            <td>
                                <code>{$s.CRON_EXPRESSION|escape}</code>
                            </td>
                            <td>
                                {if $s.ENABLED}
                                    <span class="badge bg-success">Enabled</span>
                                {else}
                                    <span class="badge bg-secondary">Disabled</span>
                                {/if}
                            </td>
                            <td>
                                <a href="index.php?page=leads:import_schedule&sub=edit&id={$s.SCHEDULE_ID}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <a href="index.php?page=leads:import_schedule&sub=delete&id={$s.SCHEDULE_ID}"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Delete this schedule?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {if $smarty.get.sub == 'new' || $smarty.get.sub == 'edit'}
        {assign var=editing value=true}
    {/if}

    {if $smarty.get.sub == 'edit' && $smarty.get.id}
        {php}
            $id = intval($_GET['id']);
            $row = $db->GetRow(
                "SELECT * FROM " . PRFX . "LEAD_IMPORT_SCHEDULES WHERE SCHEDULE_ID = ?",
                array($id)
            );
            $smarty->assign('edit_row',$row);
        {/php}
    {/if}

    {if $smarty.get.sub == 'new' || $smarty.get.sub == 'edit'}

    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                {if $edit_row}
                    Edit Schedule
                {else}
                    New Schedule
                {/if}
            </h4>
        </div>

        <div class="card-body">

            <form method="post"
                action="index.php?page=leads:import_schedule&sub=save">

                <input type="hidden"
                       name="schedule_id"
                       value="{if $edit_row}{$edit_row.SCHEDULE_ID}{/if}" />

                <div class="mb-3">
                    <label class="form-label">
                        Preset
                    </label>

                    <select name="preset_id" class="form-select">
                        <option value="">Select Preset</option>

                        {foreach from=$presets item=p}
                            <option value="{$p.PRESET_ID}"
                                {if $edit_row && $edit_row.PRESET_ID==$p.PRESET_ID}
                                    selected
                                {/if}>
                                {$p.NAME|escape}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Source Path
                    </label>

                    <input type="text"
                           name="source_path"
                           class="form-control"
                           value="{if $edit_row}{$edit_row.SOURCE_PATH|escape}{/if}"
                           placeholder="File path, HTTP(S) URL or SFTP URL">

                    <div class="form-text">
                        Examples:
                        <code>/home/imports/leads.csv</code>,
                        <code>https://example.com/leads.csv</code>,
                        <code>sftp://server/path/file.csv</code>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Cron Expression
                    </label>

                    <input type="text"
                           name="cron"
                           class="form-control"
                           value="{if $edit_row}{$edit_row.CRON_EXPRESSION|escape}{/if}"
                           placeholder="0 * * * *">

                    <div class="form-text">
                        Leave blank to run manually.
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input"
                           type="checkbox"
                           name="enabled"
                           value="1"
                           id="enabled"
                           {if $edit_row && $edit_row.ENABLED}checked{/if}>

                    <label class="form-check-label" for="enabled">
                        Enable Schedule
                    </label>
                </div>

                <button type="submit" class="btn btn-success">
                    Save Schedule
                </button>

                <a href="index.php?page=leads:import_schedule"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

    {/if}

</div>