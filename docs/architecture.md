# Architecture Notes

## Project Type

This is a Laravel 12 application for monitoring formal legal-aid case submissions, interventions, verifications, and reports.

## Main Modules

| Module | Purpose | Main files |
| --- | --- | --- |
| Authentication | Login, password reset, email verification, password change flow | `routes/auth.php`, `app/Http/Controllers/Auth/*` |
| Users and access control | User management, roles, permissions, direct permissions | `UserController`, `RoleController`, `PermissionController`, `RolePermissionController` |
| District and PNGO setup | Maintain district and PNGO reference data | `DashboardController`, `District`, `Pngo` |
| Case submission | Create/edit formal case records and follow-up interventions | `FormalController`, `FormalCase`, `FollowUpIntervention` |
| Case verification | DPO and MNEO status transitions | `FormalController::verifyCase`, `FormalController::verifyCaseMneo` |
| Reports and dashboard | Case list, custom intervention report, district/PNGO summaries, PDFs | `ReportController`, `CommonService` |
| File uploads | Store files attached to cases | `FileUpload`, `file_uploads` table |
| Audit logging | Record important user actions | `LogService`, `Log` |

## Roles and Permissions

The application uses Spatie Laravel Permission. Permission middleware is registered in `bootstrap/app.php` and applied directly in `routes/web.php`.

Known business roles from the SQL dump include:

- `Admin`
- `Paralegal`
- `DPO`
- `M&EO`
- `PNGO Focal`
- `Knowledge Management`
- `A2J`

Important permissions include:

- `Create Formal Case`
- `Verified by DPO`
- `Verified by MNEO`
- report view/generation permissions
- user, role, and permission management permissions

## Core Data Tables

| Table | Purpose |
| --- | --- |
| `users` | Application users with district and PNGO assignment |
| `districts` | District lookup data |
| `pngos` | PNGO lookup data |
| `formal_cases` | Main case, assistance, result, and closure data |
| `follow_up_interventions` | Follow-up intervention information connected to a case |
| `file_uploads` | Uploaded files attached to formal cases |
| `logs` | Audit records for user actions |
| Spatie tables | Roles, permissions, and role/user mappings |

## Reporting Logic Summary

Dashboard summary counts are implemented in `app/Services/CommonService.php`.

Current logic:

- Counts by district or PNGO.
- Breaks totals into adult male, adult female, adult transgender, and under 18.
- Counts records only when institute-specific assistance/result fields are populated.
- Uses the current operational rule: DPO-verified and above records may be counted.

Custom reports are implemented in `ReportController::generateCustomReport` and use field selections from `app/Services/DbFields.php`.

## Current Technical Notes

- Case statuses now have constants in `FormalCase`.
- MNEO verification requires DPO verification first.
- DPO verification requires submitted status first.
- File uploads store `file_name`, `file_path`, and `uploaded_by`.
- Excel import still has two paths: `FormalCaseImport` and manual import logic inside `FormalController::import`. Consolidation should be a future controlled change, not a quick cleanup.

## Known Risks for Future Review

- `formal_cases` is a very wide table and carries many business concepts in one record.
- Some relationships are represented by IDs but are not enforced with database foreign keys.
- Import logic is duplicated and should be consolidated later.
- Report eligibility rules should be centralized before enabling full six-eyes counting.
- UI labels should be reviewed when the organization switches from DPO-counted reporting to MNEO-only final reporting.

## Safe Future Work Order

1. Confirm current reporting rules with business stakeholders.
2. Add tests for case submission and verification transitions.
3. Add tests for DPO-counted reporting before changing reporting logic.
4. Consolidate import logic.
5. Add database constraints where existing data allows it.
6. Later, switch final reports to MNEO-only counting when six-eyes control is active.
