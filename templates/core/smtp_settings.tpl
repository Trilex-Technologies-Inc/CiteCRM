<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">
            <h2 class="mb-0">SMTP Settings</h2>
        </div>

        <div class="card-body">

            {if $msg}
                <div class="alert alert-success">
                    {$msg|escape}
                </div>
            {/if}

            <form method="post" action="index.php?page=core:smtp_settings">

                <div class="mb-3">
                    <label class="form-label">SMTP Host</label>
                    <input type="text"
                           name="smtp_host"
                           class="form-control"
                           value="{$setup.SMTP_HOST|default:''|escape}">
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Port</label>
                    <input type="text"
                           name="smtp_port"
                           class="form-control"
                           value="{$setup.SMTP_PORT|default:'25'|escape}">
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Username</label>
                    <input type="text"
                           name="smtp_user"
                           class="form-control"
                           value="{$setup.SMTP_USER|default:''|escape}">
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Password</label>
                    <input type="password"
                           name="smtp_pass"
                           class="form-control"
                           value="">

                    {if $setup.SMTP_PASS}
                        <div class="form-text text-muted">
                            Password stored. Leave blank to keep current.
                        </div>
                    {/if}
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Secure</label>
                    <select name="smtp_secure" class="form-select">
                        <option value="" {if $setup.SMTP_SECURE==''}selected{/if}>None</option>
                        <option value="ssl" {if $setup.SMTP_SECURE=='ssl'}selected{/if}>SSL</option>
                        <option value="tls" {if $setup.SMTP_SECURE=='tls'}selected{/if}>TLS</option>
                    </select>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input"
                           type="checkbox"
                           name="smtp_auth"
                           value="1"
                           id="smtp_auth"
                           {if $setup.SMTP_AUTH==1}checked{/if}>

                    <label class="form-check-label" for="smtp_auth">
                        Require authentication
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                            name="submit"
                            class="btn btn-primary">
                        Save SMTP Settings
                    </button>
                </div>

            </form>

        </div>

        <div class="card-footer">
            <a href="index.php?page=core:company"
               class="btn btn-outline-secondary">
                Back to Company
            </a>
        </div>

    </div>

</div>