<div class="container my-4">
  <div class="mb-3">
    {include file="core/admin_tool_bar.tpl"}
  </div>

  {if $error_msg != ""}
    {include file="core/error.tpl"}
  {/if}

  {if $msg != ""}
    <div class="alert alert-success">{$msg}</div>
  {/if}

  <div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
      <i class="bi bi-shield-check me-2 text-secondary"></i> Captcha Settings
    </div>
    <div class="card-body">
      <form method="post" action="?page=control:captcha&page_title=Captcha%20Settings" class="row g-3">
	        <div class="col-12">
	          <div class="form-check form-switch">
	            <input class="form-check-input" type="checkbox" role="switch" id="enabled" name="enabled" value="1" {if $captcha.ENABLED == 1}checked{/if}>
	            <label class="form-check-label" for="enabled">Enable captcha on login</label>
	          </div>
	          <div class="form-text">Choose Cloudflare Turnstile or Google reCAPTCHA.</div>
	        </div>

	        <div class="col-12 col-md-6">
	          <label class="form-label" for="provider">Provider</label>
	          <select class="form-select" id="provider" name="provider">
	            <option value="turnstile" {if $captcha.PROVIDER == 'turnstile'}selected{/if}>Cloudflare Turnstile</option>
	            <option value="recaptcha" {if $captcha.PROVIDER == 'recaptcha'}selected{/if}>Google reCAPTCHA (v2)</option>
	          </select>
	        </div>

        <div class="col-12 col-md-6"></div>

        <div class="col-12 col-md-6">
          <label class="form-label" for="site_key">Site key</label>
          <input class="form-control" type="text" id="site_key" name="site_key" value="{$captcha.SITE_KEY|escape}">
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label" for="secret_key">Secret key</label>
          <input class="form-control" type="password" id="secret_key" name="secret_key" value="{$captcha.SECRET_KEY|escape}">
        </div>

        <div class="col-12 d-flex gap-2">
          <button type="submit" name="submit" value="1" class="btn btn-primary">Save</button>
          <a href="?page=control:main&page_title=Control%20Center" class="btn btn-outline-secondary">Back</a>
        </div>
      </form>

	      <hr>
	      <div class="small text-muted">
	        For Turnstile: create a widget in Cloudflare and paste Site key + Secret key. For reCAPTCHA: create a reCAPTCHA v2 (Checkbox) key and paste Site key + Secret key.
	      </div>
	    </div>
	  </div>
	</div>
