<div class="container-fluid p-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-0">Tasks</h2>
            <div class="text-muted">Employee tasks and reminders</div>
        </div>
        <a class="btn btn-primary"
           href="index.php?page=tasks:new&page_title=New%20Task">
            <i class="bi bi-plus-lg me-1"></i>
            New Task
        </a>
    </div>

    {if $msg|default:'' != ''}
        <div class="alert alert-success">{$msg|escape}</div>
    {/if}

    {if $error_msg != ''}
        <div class="alert alert-danger">{$error_msg|escape}</div>
    {/if}

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="btn-group flex-wrap" role="group" aria-label="Task filters">
                <a class="btn btn-sm {if $task_status == 'open'}btn-primary{else}btn-outline-primary{/if}"
                   href="index.php?page=tasks:main&status=open">Open</a>
                <a class="btn btn-sm {if $task_status == 'overdue'}btn-danger{else}btn-outline-danger{/if}"
                   href="index.php?page=tasks:main&status=overdue">Overdue</a>
                <a class="btn btn-sm {if $task_status == 'completed'}btn-success{else}btn-outline-success{/if}"
                   href="index.php?page=tasks:main&status=completed">Completed</a>
                <a class="btn btn-sm {if $task_status == 'all'}btn-secondary{else}btn-outline-secondary{/if}"
                   href="index.php?page=tasks:main&status=all">All</a>
            </div>
        </div>

        <div class="card-body p-0">
            {if $tasks}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Assigned to</th>
                                <th>Priority</th>
                                <th>Due date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$tasks item=task}
                                <tr class="{if $task.IS_COMPLETE == 0 && $task.DUE_DATE != '' && $task.DUE_DATE < $today}table-danger{/if}">
                                    <td>
                                        <div class="fw-semibold">{$task.TITLE|escape}</div>
                                        {if $task.DESCRIPTION != ''}
                                            <div class="small text-muted">
                                                {$task.DESCRIPTION|escape|truncate:100}
                                            </div>
                                        {/if}
                                    </td>
                                    <td>{$task.ASSIGNED_NAME|default:'Unassigned'|escape}</td>
                                    <td>
                                        {if $task.PRIORITY == 'High'}
                                            <span class="badge bg-danger">High</span>
                                        {elseif $task.PRIORITY == 'Low'}
                                            <span class="badge bg-secondary">Low</span>
                                        {else}
                                            <span class="badge bg-info text-dark">Normal</span>
                                        {/if}
                                    </td>
                                    <td>
                                        {if $task.DUE_DATE != ''}
                                            {$task.DUE_DATE|escape}
                                        {else}
                                            <span class="text-muted">No due date</span>
                                        {/if}
                                    </td>
                                    <td>
                                        {if $task.IS_COMPLETE == 1}
                                            <span class="badge bg-success">Complete</span>
                                        {else}
                                            <span class="badge bg-warning text-dark">Open</span>
                                        {/if}
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <form class="d-inline"
                                              method="post"
                                              action="index.php?page=tasks:complete">
                                            <input type="hidden" name="id" value="{$task.TASK_ID|escape}">
                                            {if $task.IS_COMPLETE == 1}
                                                <input type="hidden" name="complete" value="0">
                                                <button class="btn btn-sm btn-outline-warning"
                                                        type="submit"
                                                        title="Reopen task">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            {else}
                                                <input type="hidden" name="complete" value="1">
                                                <button class="btn btn-sm btn-outline-success"
                                                        type="submit"
                                                        title="Mark complete">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            {/if}
                                        </form>

                                        <a class="btn btn-sm btn-outline-primary"
                                           href="index.php?page=tasks:edit&id={$task.TASK_ID|escape}"
                                           title="Edit task">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a class="btn btn-sm btn-outline-danger"
                                           href="index.php?page=tasks:delete&id={$task.TASK_ID|escape}"
                                           title="Delete task">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            {else}
                <div class="p-4 text-center text-muted">
                    No tasks found for this filter.
                </div>
            {/if}
        </div>
    </div>
</div>
