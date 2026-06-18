<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">
            <h2 class="mb-0">Messaging — Email Logs</h2>
        </div>

        <div class="card-body">

            <p>Total entries: <strong>{$total}</strong></p>

            {if $rows}
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Direction</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Subject</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$rows item=r}
                            <tr>
                                <td>{$r.CREATED_AT_FMT}</td>
                                <td>{$r.DIRECTION|escape}</td>
                                <td>{$r.FROM_EMAIL|escape}</td>
                                <td>{$r.TO_EMAIL|escape}</td>
                                <td>{$r.SUBJECT_ESC|escape}</td>
                                <td><a href="index.php?page=messaging:logs&view={$r.LOG_ID}" class="btn btn-sm btn-outline-primary">View</a></td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>

                {assign var="prev" value=$page-1}
                {assign var="next" value=$page+1}

                <nav>
                    <ul class="pagination">
                        {if $page > 1}
                            <li class="page-item"><a class="page-link" href="index.php?page=messaging:logs&p={$prev}">Previous</a></li>
                        {/if}
                        <li class="page-item disabled"><span class="page-link">Page {$page}</span></li>
                        {if $total > $page * $per_page}
                            <li class="page-item"><a class="page-link" href="index.php?page=messaging:logs&p={$next}">Next</a></li>
                        {/if}
                    </ul>
                </nav>

            {else}
                <div class="alert alert-info">No email log entries found.</div>
            {/if}

            {if $view_row}
                <hr />
                <h4>Log Details (ID: {$view_row.ID})</h4>
                <p><strong>Time:</strong> {$view_row.CREATED_AT_FMT}</p>
                <p><strong>From:</strong> {$view_row.FROM_EMAIL|escape}</p>
                <p><strong>To:</strong> {$view_row.TO_EMAIL|escape}</p>
                <p><strong>CC:</strong> {$view_row.CC_EMAIL|escape}</p>
                <p><strong>BCC:</strong> {$view_row.BCC_EMAIL|escape}</p>
                <p><strong>Subject:</strong> {$view_row.SUBJECT|escape}</p>
                <p><strong>Linked Customer:</strong> {$view_row.LINKED_CUSTOMER_ID}</p>
                <h5>Body</h5>
                <pre style="white-space:pre-wrap;">{$view_row.BODY|escape}</pre>
                <h5>Raw / Error</h5>
                <pre style="white-space:pre-wrap;">{$view_row.RAW|escape}</pre>
            {/if}

        </div>

        <div class="card-footer">
            <a href="index.php?page=messaging:compose" class="btn btn-outline-secondary">Compose</a>
            <a href="index.php?page=messaging:list" class="btn btn-outline-secondary">Back to list</a>
        </div>

    </div>

</div>