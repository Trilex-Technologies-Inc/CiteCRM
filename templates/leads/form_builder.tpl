<div class="container-fluid">

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h2 class="mb-0">Form Builder</h2>
        </div>

        <div class="card-body">

            <form id="form-save"
                  method="post"
                  action="index.php?page=leads:form_builder_save">

                <input type="hidden" name="form_id" value="{$form.FORM_ID}" />

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Form Name</label>
                        <input type="text"
                               name="form_name"
                               class="form-control"
                               value="{$form.FORM_NAME|escape}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text"
                               name="form_slug"
                               class="form-control"
                               value="{$form.FORM_SLUG|escape}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mapping (JSON)</label>
                    <textarea name="form_mapping"
                              rows="4"
                              class="form-control font-monospace">{$form.FORM_MAPPING|escape}</textarea>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" checked id="regen_html" name="regen_html">
                    <label class="form-check-label" for="regen_html">
                        Regenerate embed snippet on save
                    </label>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Fields</h4>

                    <button id="add-field"
                            type="button"
                            class="btn btn-success">
                        Add Field
                    </button>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Load Preset</label>

                        <div class="input-group">
                            <select id="preset-list" class="form-select">
                                <option value="">(none)</option>
                            </select>

                            <button id="load-preset"
                                    type="button"
                                    class="btn btn-outline-primary">
                                Load
                            </button>
                        </div>
                    </div>
                </div>

                <div id="fields">

                    {foreach from=$fields item=f}
                    <div class="field-row card mb-3">
                        <div class="card-body">
                            <div class="row g-2 align-items-end">

                                <div class="col-md-4">
                                    <label class="form-label">Label</label>
                                    <input class="form-control field-name"
                                           value="{$f.FIELD_NAME|escape}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Key</label>
                                    <input class="form-control field-key"
                                           value="{$f.FIELD_KEY|escape}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-select field-type">
                                        <option value="text"{if $f.FIELD_TYPE=='text'} selected{/if}>Text</option>
                                        <option value="email"{if $f.FIELD_TYPE=='email'} selected{/if}>Email</option>
                                        <option value="textarea"{if $f.FIELD_TYPE=='textarea'} selected{/if}>Textarea</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="button"
                                            class="btn btn-danger w-100 remove">
                                        Remove
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    {/foreach}

                </div>

                <input type="hidden"
                       name="fields_json"
                       id="fields_json" />

                <button type="submit"
                        class="btn btn-primary">
                    Save Form
                </button>

            </form>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Embed Snippet</h4>
        </div>

        <div class="card-body">

            <p class="text-muted">
                Copy this HTML snippet to embed the form on your website.
            </p>

                        <pre class="bg-light border rounded p-3"><code id="embed-snippet">{if $form.FORM_HTML}
{$form.FORM_HTML|escape}
{else}
&lt;form action="{$CONF.SITE_URL}/modules/leads/forms_submit.php" method="post"&gt;
    &lt;input type="hidden" name="form_token" value="{$form.PUBLIC_TOKEN}" /&gt;
    &lt;!-- Add fields here --&gt;
&lt;/form&gt;
{/if}</code></pre>

        </div>
    </div>

</div>

{literal}
<script>
document.getElementById('add-field').addEventListener('click', function () {

    var container = document.getElementById('fields');

    var div = document.createElement('div');
    div.className = 'field-row card mb-3';

    div.innerHTML =
        '<div class="card-body">' +
        '<div class="row g-2 align-items-end">' +

        '<div class="col-md-4">' +
        '<label class="form-label">Label</label>' +
        '<input class="form-control field-name" value="New Field">' +
        '</div>' +

        '<div class="col-md-3">' +
        '<label class="form-label">Key</label>' +
        '<input class="form-control field-key" value="new_field">' +
        '</div>' +

        '<div class="col-md-3">' +
        '<label class="form-label">Type</label>' +
        '<select class="form-select field-type">' +
        '<option value="text">Text</option>' +
        '<option value="email">Email</option>' +
        '<option value="textarea">Textarea</option>' +
        '</select>' +
        '</div>' +

        '<div class="col-md-2">' +
        '<button type="button" class="btn btn-danger w-100 remove">Remove</button>' +
        '</div>' +

        '</div>' +
        '</div>';

    container.appendChild(div);
});

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove')) {
        e.target.closest('.field-row').remove();
    }
});

document.getElementById('form-save').addEventListener('submit', function() {

    var rows = document.querySelectorAll('#fields .field-row');
    var out = [];

    rows.forEach(function(r) {
        out.push({
            name: r.querySelector('.field-name').value,
            key: r.querySelector('.field-key').value,
            type: r.querySelector('.field-type').value
        });
    });

    document.getElementById('fields_json').value = JSON.stringify(out);
});

fetch('index.php?page=leads:form_presets')
.then(r => r.json())
.then(function(data) {

    var sel = document.getElementById('preset-list');

    data.forEach(function(p, i) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.textContent = p.name;
        sel.appendChild(opt);
    });

    document.getElementById('load-preset').addEventListener('click', function() {

        var idx = sel.value;
        if (idx === '') return;

        var preset = data[idx];
        var container = document.getElementById('fields');

        container.innerHTML = '';

        preset.fields.forEach(function(f) {

            var div = document.createElement('div');
            div.className = 'field-row card mb-3';

            div.innerHTML =
                '<div class="card-body">' +
                '<div class="row g-2 align-items-end">' +

                '<div class="col-md-4">' +
                '<label class="form-label">Label</label>' +
                '<input class="form-control field-name" value="' + f.name + '">' +
                '</div>' +

                '<div class="col-md-3">' +
                '<label class="form-label">Key</label>' +
                '<input class="form-control field-key" value="' + f.key + '">' +
                '</div>' +

                '<div class="col-md-3">' +
                '<label class="form-label">Type</label>' +
                '<select class="form-select field-type">' +
                '<option value="text">Text</option>' +
                '<option value="email">Email</option>' +
                '<option value="textarea">Textarea</option>' +
                '</select>' +
                '</div>' +

                '<div class="col-md-2">' +
                '<button type="button" class="btn btn-danger w-100 remove">Remove</button>' +
                '</div>' +

                '</div>' +
                '</div>';

            container.appendChild(div);
            div.querySelector('.field-type').value = f.type;
        });
    });
});
</script>
{/literal}