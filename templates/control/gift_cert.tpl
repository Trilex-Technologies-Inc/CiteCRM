<div class="container my-4">

    <div class="mb-3">
        {include file="core/admin_tool_bar.tpl"}
    </div>

    {if $error_msg != ""}
        <div class="mb-3">
            {include file="core/error.tpl"}
        </div>
    {/if}

    {if $msg != ""}
        <div class="alert alert-success">{$msg}</div>
    {/if}

    {if $created_code != ""}
        <div class="alert alert-primary d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold">Generated Gift Code</div>
                <div class="fs-4 font-monospace">{$created_code|escape}</div>
            </div>
            <button type="button" class="btn btn-outline-primary" onclick="navigator.clipboard && navigator.clipboard.writeText('{$created_code|escape:'javascript'}')">
                Copy
            </button>
        </div>
    {/if}

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    Create Gift Certificate
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="create">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Amount</label>
                            <div class="input-group" style="max-width: 240px;">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Expire Date (optional)</label>
                            <input type="date" name="expire" class="form-control" style="max-width: 240px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer ID (optional)</label>
                            <input type="number" min="0" name="customer_id" class="form-control" style="max-width: 240px;">
                            <div class="form-text">Leave empty for unassigned.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Memo (optional)</label>
                            <textarea name="memo" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" name="submit" value="1" class="btn btn-primary">
                            Generate Gift Code
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-semibold">
                    Search / Recent Gift Certificates
                </div>
                <div class="card-body">
                    <form class="row g-3 align-items-end" method="get" action="">
                        <input type="hidden" name="page" value="control:gift_cert">
                        <input type="hidden" name="page_title" value="Gift Certificates">

                        <div class="col-md-5">
                            <label class="form-label fw-bold">Gift Code</label>
                            <input type="text" name="search_code" maxlength="13" inputmode="numeric" pattern="[0-9]{13}" value="{$search_code|escape}" class="form-control" placeholder="13 digits">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Customer ID</label>
                            <input type="number" min="0" name="search_customer_id" value="{$search_customer_id|escape}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Active</label>
                            <select name="search_active" class="form-select">
                                <option value="" {if $search_active == ""}selected{/if}>Any</option>
                                <option value="1" {if $search_active == "1"}selected{/if}>Active</option>
                                <option value="0" {if $search_active == "0"}selected{/if}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Gift Code</th>
                            <th>Amount</th>
                            <th>Active</th>
                            <th>Customer</th>
                            <th>Expire</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {if $gifts|@count == 0}
                            <tr><td colspan="7" class="text-muted p-3">No results.</td></tr>
                        {else}
                            {foreach from=$gifts item=g}
                                <tr>
                                    <td>{$g.GIFT_ID}</td>
                                    <td class="fw-semibold">{$g.GIFT_CODE|escape}</td>
                                    <td>${$g.AMOUNT|string_format:"%.2f"}</td>
                                    <td>
                                        {if $g.ACTIVE == 1}
                                            <span class="badge bg-success">Yes</span>
                                        {else}
                                            <span class="badge bg-secondary">No</span>
                                        {/if}
                                    </td>
                                    <td>{$g.CUSTOMER_ID}</td>
                                    <td>
                                        {if $g.EXPIRE > 0}
                                            {$g.EXPIRE|date_format:"%Y-%m-%d"}
                                        {else}
                                            -
                                        {/if}
                                    </td>
                                    <td class="text-end">
                                        {if $g.ACTIVE == 1}
                                            <form method="post" action="" class="d-inline">
                                                <input type="hidden" name="action" value="deactivate">
                                                <input type="hidden" name="gift_id" value="{$g.GIFT_ID}">
                                                <button type="submit" name="submit" value="1" class="btn btn-sm btn-outline-danger">
                                                    Deactivate
                                                </button>
                                            </form>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

