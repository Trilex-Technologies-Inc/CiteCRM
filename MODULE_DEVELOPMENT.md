# Creating a CiteCRM Module

This guide explains how to create a simple module for this CiteCRM codebase.

The examples use a module named `tasks`. Replace `tasks`, `Tasks`, and the
database table names with values appropriate for your module.

## 1. How modules work

CiteCRM routes requests through `index.php` with this URL format:

```text
index.php?page=module:action
```

For example:

```text
index.php?page=tasks:main
index.php?page=tasks:new
index.php?page=tasks:edit&id=12
```

The route `tasks:new` loads:

```text
modules/tasks/new.php
```

PHP page files prepare data and Smarty renders the HTML from:

```text
templates/tasks/
```

## 2. Recommended directory structure

Create these directories and files:

```text
modules/tasks/
├── module.json
├── install.php
├── uninstall.php
├── include.php
├── main.php
├── new.php
├── edit.php
└── delete.php

templates/tasks/
├── main.tpl
└── form.tpl
```

The Module Manager can create a basic skeleton from:

```text
Control > Modules > Scaffold New Module
```

The generated skeleton is only a starting point. Templates should be placed in
the root `templates/tasks/` directory, not in `modules/tasks/templates/`.

Use a lowercase directory name containing only letters, numbers, underscores,
or hyphens. A simple lowercase name such as `tasks` is preferred.

## 3. Create the manifest

Create `modules/tasks/module.json`:

```json
{
    "name": "Tasks",
    "version": "1.0.0",
    "author": "CiteCRM",
    "description": "Employee tasks and reminders"
}
```

The Module Manager reads this file when displaying and installing the module.

## 4. Create the database installer

Create `modules/tasks/install.php`:

```php
<?php
if (!defined('PRFX')) {
    exit;
}

$sql = "CREATE TABLE IF NOT EXISTS " . PRFX . "TASKS (
    TASK_ID INT AUTO_INCREMENT PRIMARY KEY,
    TITLE VARCHAR(255) NOT NULL,
    DESCRIPTION TEXT,
    PRIORITY VARCHAR(20) NOT NULL DEFAULT 'Normal',
    DUE_DATE DATE DEFAULT NULL,
    IS_COMPLETE TINYINT(1) NOT NULL DEFAULT 0,
    CREATED_BY INT DEFAULT NULL,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

if (!$db->Execute($sql)) {
    throw new RuntimeException(
        'Could not create the tasks table: ' . $db->ErrorMsg()
    );
}

echo 'Tasks table created successfully.';
```

Always use the `PRFX` constant when naming a table. The database prefix may be
different between installations.

The installer receives the existing `$db` database connection from the Module
Manager. Throw an exception when installation fails so the manager can report
the failure instead of marking a broken module as installed.

### SQL installer alternative

The Module Manager also supports:

```text
modules/tasks/install.sql
modules/tasks/uninstall.sql
```

However, SQL files cannot build table names with the PHP `PRFX` constant.
For that reason, PHP installers are recommended for portable modules.

Avoid putting an `uninstall.sql` file inside `modules/tasks/sql/`: the current
installer scans all `.sql` files in that directory during installation.

## 5. Create the uninstaller

Create `modules/tasks/uninstall.php`:

```php
<?php
if (!defined('PRFX')) {
    exit;
}

$sql = "DROP TABLE IF EXISTS `" . PRFX . "TASKS`";

if (!$db->Execute($sql)) {
    throw new RuntimeException(
        'Could not remove the tasks table: ' . $db->ErrorMsg()
    );
}

echo 'Tasks table removed successfully.';
```

Uninstalling permanently removes module data. Only remove tables owned by your
module. Do not remove shared CiteCRM tables.

## 6. Add shared module functions

Create `modules/tasks/include.php`:

```php
<?php

function tasks_get_all($db)
{
    $sql = "SELECT *
            FROM " . PRFX . "TASKS
            ORDER BY IS_COMPLETE ASC, DUE_DATE ASC, TASK_ID DESC";

    $result = $db->Execute($sql);

    if (!$result) {
        return array();
    }

    return $result->GetArray();
}
```

Keep reusable queries and business logic in `include.php`. Page files should
mainly validate input, call shared functions, assign Smarty values, and select
a template.

## 7. Create the list page

Create `modules/tasks/main.php`:

```php
<?php
require_once 'modules' . SEP . 'tasks' . SEP . 'include.php';

$tasks = tasks_get_all($db);

$smarty->assign('tasks', $tasks);
$smarty->assign('page_title', 'Tasks');
$smarty->display('tasks' . SEP . 'main.tpl');
```

Create `templates/tasks/main.tpl`:

```smarty
<div class="container-fluid p-3">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tasks</h5>
            <a class="btn btn-primary btn-sm"
               href="index.php?page=tasks:new&page_title=New%20Task">
                New Task
            </a>
        </div>

        <div class="card-body">
            {if $tasks|@count > 0}
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Due date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$tasks item=task}
                                <tr>
                                    <td>{$task.TITLE|escape}</td>
                                    <td>{$task.PRIORITY|escape}</td>
                                    <td>{$task.DUE_DATE|escape}</td>
                                    <td>
                                        {if $task.IS_COMPLETE == 1}
                                            <span class="badge bg-success">Complete</span>
                                        {else}
                                            <span class="badge bg-warning text-dark">Open</span>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            {else}
                <div class="alert alert-info mb-0">No tasks have been created.</div>
            {/if}
        </div>
    </div>
</div>
```

Use `|escape` whenever displaying database or request values in a template.

## 8. Create the new-task page

Create `modules/tasks/new.php`:

```php
<?php
$error_msg = '';
$title = isset($VAR['title']) ? trim($VAR['title']) : '';
$description = isset($VAR['description']) ? trim($VAR['description']) : '';
$priority = isset($VAR['priority']) ? $VAR['priority'] : 'Normal';
$due_date = isset($VAR['due_date']) ? trim($VAR['due_date']) : '';

$allowed_priorities = array('Low', 'Normal', 'High');

if (isset($VAR['submit'])) {
    if ($title === '') {
        $error_msg = 'Title is required.';
    } elseif (!in_array($priority, $allowed_priorities, true)) {
        $error_msg = 'Invalid priority.';
    } else {
        $created_by = isset($_SESSION['login_id'])
            ? $_SESSION['login_id']
            : null;

        $sql = "INSERT INTO " . PRFX . "TASKS SET
                TITLE=" . $db->qstr($title) . ",
                DESCRIPTION=" . $db->qstr($description) . ",
                PRIORITY=" . $db->qstr($priority) . ",
                DUE_DATE=" . (
                    $due_date === ''
                        ? "NULL"
                        : $db->qstr($due_date)
                ) . ",
                CREATED_BY=" . (
                    $created_by === null
                        ? "NULL"
                        : $db->qstr($created_by)
                );

        if (!$db->Execute($sql)) {
            $error_msg = 'Database error: ' . $db->ErrorMsg();
        } else {
            force_page('tasks', 'main&msg=Task%20created');
            exit;
        }
    }
}

$smarty->assign('error_msg', $error_msg);
$smarty->assign('title', $title);
$smarty->assign('description', $description);
$smarty->assign('priority', $priority);
$smarty->assign('due_date', $due_date);
$smarty->assign('page_title', 'New Task');
$smarty->display('tasks' . SEP . 'form.tpl');
```

Create `templates/tasks/form.tpl`:

```smarty
<div class="container-fluid p-3">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">New Task</h5>
        </div>

        <div class="card-body">
            {if $error_msg != ''}
                <div class="alert alert-danger">{$error_msg|escape}</div>
            {/if}

            <form method="post" action="index.php?page=tasks:new">
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input class="form-control"
                           id="title"
                           name="title"
                           value="{$title|escape}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control"
                              id="description"
                              name="description"
                              rows="4">{$description|escape}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="priority">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="Low" {if $priority == 'Low'}selected{/if}>Low</option>
                            <option value="Normal" {if $priority == 'Normal'}selected{/if}>Normal</option>
                            <option value="High" {if $priority == 'High'}selected{/if}>High</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="due_date">Due date</label>
                        <input class="form-control"
                               type="date"
                               id="due_date"
                               name="due_date"
                               value="{$due_date|escape}">
                    </div>
                </div>

                <button class="btn btn-primary"
                        type="submit"
                        name="submit"
                        value="1">
                    Save Task
                </button>

                <a class="btn btn-secondary" href="index.php?page=tasks:main">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
```

## 9. Add the module to navigation

Edit `templates/core/navagation.tpl` and add a link inside the main navigation:

```smarty
<a class="app-nav-item {if $current_module == 'tasks'}active{/if}"
   href="index.php?page=tasks:main&page_title=Tasks">
    <span class="app-nav-icon" aria-hidden="true">
        <i class="bi bi-check2-square"></i>
    </span>
    <span class="app-nav-label">Tasks</span>
</a>
```

The project already loads Bootstrap Icons, so a Bootstrap icon class can be
used for the menu icon.

## 10. Enforce installed and enabled status

At present, `index.php` enforces module status only for `leads` and
`messaging`. Add your directory name to that list:

```php
if (in_array($module, array('leads', 'messaging', 'tasks'), true)) {
```

Without this change, a user who knows the route may still open the module even
after it is disabled in the Module Manager.

For a long-term improvement, this check should eventually apply dynamically to
every optional module registered in the modules table.

## 11. Install and configure ACL permissions

1. Sign in as an administrator.
2. Open `Control > Modules`.
3. Find the new module.
4. Click `Install`.
5. Open `Control > ACL` or `Control > Roles`.
6. Review access for each module route.

ACL entries are created the first time a route is requested:

```text
tasks:main
tasks:new
tasks:edit
tasks:delete
```

New routes default to allowed for `Admin`, `Manager`, and `Supervisor`, and
denied for other roles. An administrator should explicitly review these
permissions.

## 12. Input and database safety

Follow these rules on every page:

- Validate all values received from `$VAR`, `$_GET`, and `$_POST`.
- Use `$db->qstr($value)` for values placed into SQL.
- Convert numeric IDs to integers or quote them with `$db->qstr()`.
- Allow-list values such as statuses and priorities.
- Escape output in Smarty with `|escape`.
- Perform destructive actions through POST forms, not ordinary GET links.
- Check that a record exists before editing or deleting it.
- Never trust a hidden form field as proof of authorization.
- Let `index.php` and `check_acl()` enforce route permissions.

The application currently combines request values into `$VAR`:

```php
$VAR = array_merge($_GET, $_POST);
```

When the same key exists in both places, the POST value wins.

## 13. PHP compatibility

This project supports older PHP syntax. For consistency:

- Use `array()` instead of short array syntax when editing legacy areas.
- Avoid typed properties, union types, attributes, and other modern-only syntax.
- Use `isset()` before reading optional array keys.
- Use the existing ADOdb connection in `$db`.
- Use `SEP` when joining application paths.

## 14. Test checklist

Before considering the module complete, verify:

- The manifest appears in `Control > Modules`.
- Installation creates every required table.
- Installation can be run without PHP warnings.
- The list and create routes work.
- Invalid input shows a useful error.
- Database values containing quotes save correctly.
- Template values are escaped.
- Unauthorized roles cannot access restricted routes.
- Disabled and uninstalled modules cannot be opened.
- Uninstall removes only the module's own tables.
- Reinstall works after uninstall.

Run PHP syntax checks from the project root:

```bash
find modules/tasks -name '*.php' -print0 | xargs -0 -n1 php -l
```

## 15. Useful existing examples

These files are useful references:

- `modules/cats/` — small CRUD-style module.
- `templates/cats/` — forms and list templates.
- `modules/leads/module.json` — module manifest.
- `modules/leads/install.php` — PHP database installer.
- `modules/leads/uninstall.php` — safe module uninstaller.
- `modules/control/modules.php` — Module Manager behavior.
- `include/acl.php` — route permission behavior.
- `templates/core/navagation.tpl` — sidebar navigation.

## Minimal completion order

For a first module, implement features in this order:

1. `module.json`
2. `install.php` and `uninstall.php`
3. `main.php` and `main.tpl`
4. `new.php` and `form.tpl`
5. Navigation link
6. Installed/enabled route protection
7. ACL review
8. Edit, complete, and delete actions

This produces a useful, testable module early and leaves the more destructive
operations until the basic structure is working.
