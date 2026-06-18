<div class="container my-4">

  <div class="mb-3">
    {include file="core/admin_tool_bar.tpl"}
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
      <i class="bi bi-person-circle me-2 text-secondary"></i> SSO Settings
    </div>
    <div class="card-body">

      <form method="post" action="?page=auth:sso_settings">

        <div class="mb-3">
          <label class="form-label">Google Client ID</label>
          <input type="text" name="OAUTH_GOOGLE_CLIENT_ID" value="{$setup.OAUTH_GOOGLE_CLIENT_ID|default:''|escape}" class="form-control">
        </div>

        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" id="google_enabled" name="OAUTH_GOOGLE_ENABLED" value="1" {if $setup.OAUTH_GOOGLE_ENABLED == 1}checked{/if}>
          <label class="form-check-label" for="google_enabled">Enable Google SSO</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Google Client Secret</label>
          <input type="password" name="OAUTH_GOOGLE_CLIENT_SECRET" value="{$setup.OAUTH_GOOGLE_CLIENT_SECRET|default:''|escape}" class="form-control">
          <div class="form-text">Stored encrypted. Paste new value to update.</div>
        </div>

        <hr>

        <div class="mb-3">
          <label class="form-label">Microsoft Client ID</label>
          <input type="text" name="OAUTH_MS_CLIENT_ID" value="{$setup.OAUTH_MS_CLIENT_ID|default:''|escape}" class="form-control">
        </div>

        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" id="ms_enabled" name="OAUTH_MS_ENABLED" value="1" {if $setup.OAUTH_MS_ENABLED == 1}checked{/if}>
          <label class="form-check-label" for="ms_enabled">Enable Microsoft SSO</label>
        </div>

        <div class="mb-3">
          <label class="form-label">Microsoft Client Secret</label>
          <input type="password" name="OAUTH_MS_CLIENT_SECRET" value="{$setup.OAUTH_MS_CLIENT_SECRET|default:''|escape}" class="form-control">
          <div class="form-text">Stored encrypted. Paste new value to update.</div>
        </div>

        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary">Save SSO Settings</button>
        </div>

      </form>

    </div>
  </div>

</div>