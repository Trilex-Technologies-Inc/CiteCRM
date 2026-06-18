# CiteCRM
CiteCRM Software

## Leads Module Setup

If you get a "Table 'CRM_LEADS' doesn't exist" error when using the leads module, you need to install the leads module tables.

### Option 1: Using Admin UI (Recommended)
1. Log in to CiteCRM as an admin user.
2. Visit `/admin/install_leads_module.php`
3. Click "Create Leads Tables" to create all required tables.

### Option 2: Using Migration Runner
Run this command from the project root:
```bash
php scripts/run_migrations.php
```

This will create all required leads tables including:
- CRM_LEADS
- CRM_LEAD_CONTACTS
- CRM_LEAD_ACCOUNTS
- CRM_LEAD_BOARDS
- CRM_LEAD_BOARD_ITEMS
- CRM_LEAD_FORMS
- CRM_LEAD_FORM_FIELDS
- CRM_LEAD_FORM_SUBMISSIONS
- CRM_LEAD_FORM_KEYS
- CRM_LEAD_IMPORT_PRESETS
- CRM_LEAD_IMPORT_SCHEDULES

After installation, you can create and manage leads from the Leads module.

## OAuth Single Sign-On

To enable Google and Microsoft SSO:

- Go to the admin OAuth settings page: `modules/auth/oauth_settings.php` (must be logged in).
- Enter `OAUTH_GOOGLE_CLIENT_ID` and `OAUTH_GOOGLE_CLIENT_SECRET` for Google.
- Enter `OAUTH_MS_CLIENT_ID` and `OAUTH_MS_CLIENT_SECRET` for Microsoft.
- Ensure the following redirect URIs are registered with the providers:
	- `https://your-host/modules/auth/google_callback.php`
	- `https://your-host/modules/auth/microsoft_callback.php`

The settings page will add missing columns to the `SETUP` table if needed.
