# Court Police Prison Form Reference

This note is for future modification work on `/court-police-prison`.

Source manual reviewed:

- `e:\A2J Cred\data\Formal\04092025_Data Capturing Form for Prison Court and Police Station.docx`

Current Laravel entry points:

- Route: `GET /mne/court-police-prison`
- Create view: `resources/views/dashboard/admin/formal1.blade.php`
- Edit view: `resources/views/dashboard/admin/edit-case.blade.php`
- Create action: `FormalController::courtPolicePrison`
- Edit action: `FormalController::editCourtPolicePrison`
- Main model: `App\Models\FormalCase`
- Main table: `formal_cases`
- Follow-up table/model: `follow_up_interventions`, `App\Models\FollowUpIntervention`
- File upload table/model: `file_uploads`, `App\Models\FileUpload`
- PDF output view: `resources/views/dashboard/report/formtest.blade.php`
- Report field lists: `app/Services/DbFields.php`, `app/Services/DbFields1.php`
- Intervention/counting logic: `app/Services/CommonService.php`, `FormalController::hasDataInFields`

## Manual Form Structure

The DOCX manual form contains these major sections:

1. Profile Information
2. General Information
3. Session / Interview Information
4. Personal, Family, Education, and Income Information
5. Female Prisoner Child Information
6. Guardian Information
7. Legal Representation
8. Details of Incident
9. Support in Court and Police Station
10. Nature of Assistance for Court/Police
11. Result for Court/Police Assistance
12. District Legal Aid Office Information
13. Description of Service Provided
14. Support in Prison
15. Basic Case Information for Prison
16. Imprisonment Information
17. Nature of Assistance in Prison
18. Result of Prison Assistance
19. File Closed
20. Signature fields
21. Follow-up Sheet

The current Laravel form broadly follows this sequence with accordion sections.

Current form ordering decision:

- The old standalone Section 10 `service_description` area has been removed from the user-facing form flow.
- Prison `Basic Case Information` is now Section 10.
- `Imprisonment Information` is now Section 11.
- `Nature of Assistance in Prison` is now Section 12.
- `Result of Prison Assistance` is now Section 13.
- `District Legal Aid Office Information` is now Section 14.
- `Description of Service Provided` uses `result_description` and is now Section 15.
- `File Closed Date` remains field label 16.2 and is now placed in its own Section 16 accordion.

## Current Database Pattern

Most form fields are stored in one wide table: `formal_cases`.

Business grouping is represented by column naming, not separate child tables. Examples:

- Court/Police support fields: `custody_status`, `charges_details`, `arrest_date`, `case_no`
- Court/Police assistance fields: `family_communication_date`, `legal_representation`, `collected_case_doc`, `referral_service`
- Court/Police result fields: `resolved_dispute_date`, `appoint_lawyer_date`, `release_status`, `fine_amount`
- Female-prisoner child fields: fixed two-child structure using `child_sex`, `child_age`, `child_2_sex`, and `child_2_age`; this is not a repeatable child table.
- Prison case fields: `source_of_interview`, `prison_reg_no`, `prison_case_no`, `section_no`, `present_court`, `entry_date`
- Prison assistance fields: `prison_family_communication`, `prison_legal_representation`, `bail_bond_submission`, `court_order_communication`
- Section 12 additions: `identify_sureties_prison_date` shares `12.5`; `ministerial_communication_details` shares `12.11`.
- Section 13 additions: `convicted_length_details` and `convicted_sentence_expire_details` share `13.3`; `result_of_appeal_date` shares `13.4`; `prison_case_resolved_date` is `13.5 Case Resolved`; existing `date_of_reliefe` is labeled `13.6`.
- Prison result fields: `released_on`, `send_to`, `convicted_length`, `result_of_appeal`, `date_of_reliefe`

Follow-up intervention data is stored separately in `follow_up_interventions`.

Uploaded files are stored separately in `file_uploads`.

## Field Addition Checklist

When adding a new manual-form field, check whether each item is needed:

1. Add a migration for the new `formal_cases` column, or a new related table if repeatable data is required.
2. Add the field to `FormalCase::$fillable`.
3. Add the input/select/textarea to `formal1.blade.php`.
4. Add the same field to `edit-case.blade.php`, including existing value handling.
5. Save the field in `FormalController::courtPolicePrison`.
6. Update the field in `FormalController::editCourtPolicePrison`.
7. Add validation if the field is required or has a strict type.
8. Add the field to `formtest.blade.php` if it must appear in generated PDF.
9. Add the field to `FormalCaseExport` if Excel export should include it.
10. Add the field to `FormalCaseImport` if bulk import should include it.
11. Add the field to `DbFields.php` if `/intervention-report` should allow reporting on it.
12. Add the field to `CommonService::buildCondition` if it should count as an intervention/result.
13. Add the field to `FormalController::hasDataInFields` if it should allow DPO/MNEO verification.
14. Check `/case-list`, search result, dashboard, and PDF modal behavior if the field affects display.

## Form Labeling Plan Before Logic Changes

Before adding new business logic, first clean and align the create/edit forms with the DOCX manual labels.

Preferred label pattern:

- Keep the current Laravel form structure and section flow.
- Add the manual numbering to field labels, such as `1.2`, `4.5`, `8.3`, `14.7`.
- Use Bangla and English together where helpful, but keep it compact:
  - Primary label: Bangla text
  - Secondary label: English text in smaller muted style
  - Manual number shown as a small prefix/badge
- Avoid forcing the full DOCX sentence into every visible label if it makes the form crowded.
- For long explanatory manual text, use short helper text under the field only when it helps data entry.

Implementation trial:

- Label rendering is currently handled by `public/dashboard/js/court-police-prison-labels.js`.
- The create and edit forms include this script and call `window.applyCourtPolicePrisonManualLabels()`.
- This keeps existing field names, IDs, route actions, and controller logic unchanged.
- The label display pattern is: number badge + Bangla main label + smaller English helper label.
- Accordion section headers also use the same bilingual display pattern.
- In the create form, Section 9 keeps the previous conditional behavior: it is shown only after Section 8 assistance fields have data.
- Dropdown options are also displayed bilingually through the same JS file. Existing option `value` attributes are preserved exactly; only the visible text is changed.
- First validation pass: manual fields `1.1` full name, `1.4` sex, `1.5` age, and `3.6` family informed are mandatory. They are validated in `FormalController` and marked with red stars in the form labels.
- Required-field UX: the form submit handler opens the accordion section containing the first missing required field, scrolls to it, and shows a SweetAlert warning when available.
- Section 3.7/3.8 current implementation: if `children_with_prisoner` is `Yes`, the form shows two fixed child rows/slots. Existing first-child fields remain `child_sex` and `child_age`; second-child fields are `child_2_sex` and `child_2_age`. Associated save, update, import, export, field catalog, and generated PDF output have been wired for these fields.
- Dropdown `Other` pattern: keep the original dropdown value for reporting, then store clarification in a separate nullable details field. Current details fields are `guardian_relation_details`, `lawyer_type_details`, `legal_representation_details`, `source_of_interview_details`, `special_condition_details`, `prison_legal_representation_details`, `other_legal_assistance_details`, and `send_to_details`.
- For manual field `8.2`, `legal_representation_details` appears when `legal_representation` is `NGO Panel Lawyer` or `Other`, so the actual panel lawyer/other destination can be recorded without changing the main dropdown value.
- For manual field `8.5`, `identify_sureties_date` stores the date for identifying sureties, while `identify_sureties` keeps the surety details.
- For manual field `8.10`, `referral_service_details` appears when `referral_service` is `NGOs/RJ/Mediation` or `Other`; the main dropdown value remains unchanged and the details field stores the specific NGO/RJ/mediation/other destination.
- Section 9 current numbering: `resolved_dispute_date` is `9.1 Resolved Dispute`; `case_resolved_date` is `9.2 Case Resolved`; `appoint_lawyer_date` is `9.3 Appoint Lawyer`; `release_status`, `fine_amount`, and `release_status_date` are `9.4 Released On`; `other_result_details` and `other_result_date` are `9.5 Other Result`.
- For manual field `7.1`, dependent fields open only for `Police Custody` and `Court Custody`; `Not Applicable` does not open `7.2` charges, `7.3` arrest date, or `7.4` case number. Court/police `case_no` is labeled as `7.4`; prison `case_no` remains `11.3`.
- For manual field `4.5`, `guardian_relation_details` appears when `guardian_relation` is `Family Member`, `Relative`, or `Other`, so the user can record Father/Mother/Brother, Uncle/Aunt, or another specific relation without changing the main dropdown values.

Fields with two related inputs:

- If the manual has one assistance item but the app needs both a value/dropdown and a date, keep them visually grouped.
- Example pattern:
  - Label: `8.2 Legal Representation`
  - Field A: referred-to option/dropdown
  - Field B: date
- Do not create confusing duplicate manual numbers for the date field unless the DOCX clearly gives a separate number.
- If the date is only a supporting date for the same manual item, show it as `Date` inside the same grouped row/card.

Bangla font note:

- `resources/fonts/SolaimanLipi.ttf` is available.
- Use local font support for PDF and, if needed, form UI labels.
- Be careful with mixed Bangla/English labels so the form does not become clumsy; use hierarchy, muted secondary text, and spacing rather than long inline label text.

## Known Mismatch / Risk Areas

These should be reviewed before major logic work:

- In `formal1.blade.php`, the institute select uses `id="sex"` even though its field name is `institute`; the actual sex field also uses `id="sex"`.
- `child_sex` and `child_age` are used as both wrapper IDs and field IDs.
- Prison arrest date appears in the form with `name="arrest_date"`, while the controller also has a separate `prison_arrest_date` assignment.
- In the edit form, one prison next court date field appears to use `name="next_court_date"` instead of `prison_next_court_date`.
- Create and edit controller methods duplicate almost the same field assignment logic.
- Counting/report eligibility lists are duplicated in `CommonService`, `CommontController`, and `FormalController::hasDataInFields`.
- Current dashboard summary logic uses `status > 1`; for the current business rule, intervention counting may need `status = 2` only.
- Follow-up `central_id` usage appears inconsistent: normal create/edit uses the case database ID, while import may use the textual central ID.

## Future Design Preference

For small changes, keep the current structure and update the checklist items carefully.

For larger logic redesign, consider centralizing:

- field definitions
- save/update mapping
- intervention-counting fields
- PDF/export/import field labels

This would reduce the risk of adding a field in the form but forgetting it in reports, PDF, import, or verification logic.
