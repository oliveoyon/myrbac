# Report Print Rework - 2026-06-09

## Reason
The live cPanel server was intermittently converting or resolving report PDF POST requests as GET requests for:

- `/mne/generate-pdf`
- `/mne/generate-pdf-chart`

Local development worked, but production returned `MethodNotAllowedHttpException`.

## What Changed
New GET-based print routes were added for the three affected reports:

- `/mne/lsid-report/print`
- `/mne/district-summery/print`
- `/mne/pngo-summery/print`

These routes generate PDFs directly from controller data and dedicated Blade PDF views. They no longer POST large HTML fragments or chart images through hidden forms.

## Files Changed
- `routes/web.php`
- `app/Http/Controllers/ReportController.php`
- `app/Http/Controllers/LsidRegisterController.php`
- `resources/views/dashboard/report/district-summery.blade.php`
- `resources/views/dashboard/report/pngo-summery.blade.php`
- `resources/views/dashboard/report/lsid-report.blade.php`
- `resources/views/dashboard/report/summary-report-pdf.blade.php`
- `resources/views/dashboard/report/lsid-report-pdf.blade.php`

## Fallback Kept
The old POST routes were not removed:

- `generate-pdf`
- `generate-pdf-chart`
- `lsid-register.report.pdf`

They remain available for older report pages and rollback comparison.
