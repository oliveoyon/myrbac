# Monitoring and Verification Workflow

## Purpose

This application tracks formal legal-aid case information, follow-up interventions, verification status, and summary reports by district and PNGO.

## Current Case Statuses

| Status | Meaning | Current reporting use |
| --- | --- | --- |
| `1` | Submitted by field/user level | Not counted in official dashboard summaries |
| `2` | Verified by DPO | Currently counted in dashboard/report summaries |
| `3` | Verified by MNEO | Available for future six-eyes/final verification logic |

## Current Business Workflow

1. An authorized user submits a formal case form.
2. The case is saved with `status = 1`.
3. A DPO verifies the case after checking required support/intervention information.
4. DPO verification changes the case to `status = 2`.
5. Current reports and dashboard summaries may count DPO-verified records.
6. MNEO verification is available and changes the case to `status = 3`.

## Current Verification Rules

- DPO verification is controlled by the `Verified by DPO` permission.
- MNEO verification is controlled by the `Verified by MNEO` permission.
- DPO verification currently requires the case to be in submitted status.
- MNEO verification requires the case to already be DPO verified.
- A case must have at least one relevant court, police, or prison support field populated before verification.

## Current Reporting Rule

For the time being, operational reporting may count DPO-verified records. In code, the dashboard summary logic currently uses `status > 1`, which includes:

- `status = 2`: DPO verified
- `status = 3`: MNEO verified

This is intentional for the current phase. Do not change reporting to only `status = 3` until the six-eyes control procedure is formally activated.

## Current Transition Rules

| Action | Required starting status | Ending status |
| --- | --- | --- |
| Submit case | New record | `1` |
| DPO verify | `1` | `2` |
| MNEO verify | `2` | `3` |

## Future Six-Eyes Direction

The intended long-term control model is:

1. Paralegal/user submits data.
2. DPO verifies the submitted data.
3. MNEO verifies the DPO-approved data.
4. Only MNEO-verified records are counted as final official results.

This future rule is not fully enabled yet because the current operational reporting rule counts DPO-verified records.

## Key Code Areas

- Case submission and verification: `app/Http/Controllers/FormalController.php`
- Dashboard summary counting: `app/Services/CommonService.php`
- Reports and PDF generation: `app/Http/Controllers/ReportController.php`
- Roles and permissions: Spatie Laravel Permission tables and route middleware in `routes/web.php`
- Case data model: `app/Models/FormalCase.php`
- Follow-up interventions: `app/Models/FollowUpIntervention.php`

## Future Change Note

When the organization is ready to enforce full six-eyes counting, update and test the reporting rule first. The expected future change is to count only MNEO-verified records, likely by using `status = 3` or the `FormalCase::STATUS_MNEO_VERIFIED` constant in reporting queries.
