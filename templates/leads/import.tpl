<div class="container-fluid">

    <h2 class="mb-4">CSV Import</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Import Leads</h5>
        </div>
          <div class="card-body">
            <form method="post"
                enctype="multipart/form-data"
                action="index.php?page=leads:import">

                <div class="mb-3">
                    <label for="csv_file" class="form-label">
                        CSV File
                    </label>
                    <input type="file"
                           class="form-control"
                           id="csv_file"
                           name="csv_file">
                </div>

                <div class="mb-3">
                    <label for="preset_id" class="form-label">
                        Use Preset
                    </label>
                    <select class="form-select"
                            id="preset_id"
                            name="preset_id">
                        <option value="">(None)</option>
                        {foreach from=$presets item=p}
                            <option value="{$p.PRESET_ID}">
                                {$p.NAME|escape}
                            </option>
                        {/foreach}
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Import CSV
                </button>

            </form>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Manage Import Presets</h5>
        </div>

        <div class="card-body">

            <form method="post" id="preset-save">

                <div class="mb-3">
                    <label for="preset_name" class="form-label">
                        Preset Name
                    </label>
                    <input type="text"
                           class="form-control"
                           id="preset_name"
                           name="preset_name"
                           placeholder="Enter preset name">
                </div>

                <div class="mb-3">
                    <label for="mapping_json" class="form-label">
                        Mapping JSON
                    </label>

                    <textarea class="form-control font-monospace"
                              id="mapping_json"
                              name="mapping_json"
                              rows="8">{literal}[{"csv_column":0,"lead_field":"title"}]{/literal}</textarea>

                    <div class="form-text">
                        Define how CSV columns map to lead fields.
                    </div>
                </div>

                <input type="hidden" name="action" value="save_preset" />

                <button type="submit" class="btn btn-success">
                    Save Preset
                </button>

            </form>

        </div>
    </div>

</div>