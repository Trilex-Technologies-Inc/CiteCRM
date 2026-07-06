<div class="container-fluid p-3">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{$form_heading|escape}</h5>
        </div>

        <div class="card-body">
            {if $error_msg != ''}
                <div class="alert alert-danger">{$error_msg|escape}</div>
            {/if}

            <form method="post" action="index.php?page=tasks:{$form_action|escape}">
                {if $form_action == 'edit'}
                    <input type="hidden" name="id" value="{$task_id|escape}">
                {/if}

                <div class="mb-3">
                    <label class="form-label" for="task-title">
                        <span class="text-danger">*</span> Title
                    </label>
                    <input class="form-control"
                           id="task-title"
                           name="title"
                           maxlength="255"
                           value="{$task.TITLE|default:''|escape}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="task-description">Description</label>
                    <textarea class="form-control"
                              id="task-description"
                              name="description"
                              rows="5">{$task.DESCRIPTION|default:''|escape}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="task-priority">Priority</label>
                        <select class="form-select" id="task-priority" name="priority">
                            <option value="Low" {if $task.PRIORITY == 'Low'}selected{/if}>Low</option>
                            <option value="Normal" {if $task.PRIORITY == 'Normal'}selected{/if}>Normal</option>
                            <option value="High" {if $task.PRIORITY == 'High'}selected{/if}>High</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="task-due-date">Due date</label>
                        <input class="form-control"
                               type="date"
                               id="task-due-date"
                               name="due_date"
                               value="{$task.DUE_DATE|default:''|escape}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="task-assigned-to">Assigned to</label>
                        <select class="form-select" id="task-assigned-to" name="assigned_to">
                            <option value="">Unassigned</option>
                            {foreach from=$employees item=employee}
                                <option value="{$employee.EMPLOYEE_ID|escape}"
                                    {if $task.ASSIGNED_TO == $employee.EMPLOYEE_ID}selected{/if}>
                                    {$employee.EMPLOYEE_DISPLAY_NAME|escape}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary"
                        type="submit"
                        name="submit"
                        value="1">
                    {$submit_label|escape}
                </button>
                <a class="btn btn-secondary" href="index.php?page=tasks:main">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
