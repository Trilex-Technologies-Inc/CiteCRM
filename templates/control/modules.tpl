<div class="container-fluid">

    <div class="card shadow-sm mb-3">

        <div class="card-header">
            <h2 class="mb-0">Module Manager</h2>
        </div>

        <div class="card-body">

            {if $msg}
                <div class="alert alert-{$msg_type|default:'success'|escape}">
                    {$msg|escape}
                </div>
            {/if}

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Available Modules</h3>

                <form method="post" class="m-0">
                    <button type="submit"
                            name="action"
                            value="register_all"
                            class="btn btn-warning btn-sm"
                            onclick="return confirm('Register all modules and enable them?');">
                        Register All Modules
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Directory</th>
                            <th>Name</th>
                            <th>Version</th>
                            <th>Author</th>
                            <th>Description</th>
                            <th>Installed</th>
                            <th>Enabled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        {foreach from=$modules item=m}
                        <tr>
                            <td>{$m.dir|escape}</td>
                            <td>{$m.name|escape}</td>
                            <td>{$m.version|escape}</td>
                            <td>{$m.author|escape}</td>
                            <td>{$m.description|escape}</td>
                            <td>
                                {if $m.installed}
                                    <span class="badge bg-success">Yes</span>
                                {else}
                                    <span class="badge bg-secondary">No</span>
                                {/if}
                            </td>
                            <td>
                                {if $m.enabled}
                                    <span class="badge bg-success">Yes</span>
                                {else}
                                    <span class="badge bg-secondary">No</span>
                                {/if}
                            </td>

                            <td>
                                <form method="post" class="d-flex flex-wrap gap-1">

                                    <input type="hidden" name="module_dir" value="{$m.dir|escape}">

                                    {if !$m.installed}
                                        {if $m.dir != 'leads' && $m.dir != 'messaging'}
                                            <button type="submit" name="action" value="register" class="btn btn-sm btn-outline-primary">
                                                Register
                                            </button>
                                        {/if}

                                        <button type="submit" name="action" value="install" class="btn btn-sm btn-primary">
                                            Install
                                        </button>
                                    {else}
                                        <input type="hidden" name="confirm_uninstall" value="yes">
                                        <button type="submit"
                                                name="action"
                                                value="uninstall"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Uninstall this module? This permanently deletes all data tables owned by it.');">
                                            Uninstall
                                        </button>

                                    {/if}

                                    {if $m.installed}
                                        {if $m.enabled}
                                            <button type="submit" name="action" value="disable" class="btn btn-sm btn-outline-secondary">
                                                Disable
                                            </button>
                                        {else}
                                            <button type="submit" name="action" value="enable" class="btn btn-sm btn-success">
                                                Enable
                                            </button>
                                        {/if}
                                    {/if}

                                </form>
                            </td>

                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <div class="card shadow-sm mb-3">

        <div class="card-header">
            <h3 class="mb-0">Scaffold New Module</h3>
        </div>

        <div class="card-body">

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Module Directory (no spaces)</label>
                    <input type="text" name="module_dir" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Module Name</label>
                    <input type="text" name="module_name" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" name="module_author" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="module_desc" class="form-control">
                </div>

                <button type="submit"
                        name="action"
                        value="scaffold"
                        class="btn btn-primary">
                    Create Module Skeleton
                </button>

            </form>

        </div>
    </div>


    {if $exec_log}
        <div class="card shadow-sm">

            <div class="card-header">
                <h3 class="mb-0">Execution Log</h3>
            </div>

            <div class="card-body">
                <pre class="bg-light border p-3" style="max-height:300px; overflow:auto;">
{foreach from=$exec_log item=l}{$l|escape}
{/foreach}
                </pre>
            </div>

        </div>
    {/if}

</div>
