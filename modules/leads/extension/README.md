CiteCRM Leads Capture Chrome Extension

Setup:
- Load the extension in Chrome via `chrome://extensions` → "Load unpacked" and select this folder.
- Configure `endpoint` and `api_key` via the extension's storage UI (not provided here). The extension will POST JSON to the endpoint.

Usage:
- Click the extension action to send the page title, URL and selected text to the configured CiteCRM endpoint.

Security:
- Use a restricted API key tied to a specific form in CiteCRM.
