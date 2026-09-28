@extends('dashboard.layouts.admin-layout')

@section('title', 'Case Summary Form')

@push('styles')
<style>
    .act-picker { position: relative; }
    .act-picker summary { cursor: pointer; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background: white; }
    .act-picker summary:focus-visible { outline: 2px solid #c30f08; outline-offset: 2px; }
    .act-picker-panel { padding: 10px; background: white; border: 1px solid #ced4da; border-radius: 6px; margin-top: 4px; }
    .act-picker-options { max-height: 260px; overflow-y: auto; margin-top: 8px; }
    .act-picker-option { display: flex; align-items: flex-start; gap: 9px; padding: 9px 6px; border-bottom: 1px solid #f1f3f5; cursor: pointer; font-size: 13px; }
    .act-picker-option[hidden] { display: none; }
    .act-picker-option input { flex-shrink: 0; margin-top: 3px; }
    .act-picker-option:has(input:checked) { background: #fdf3f2; }
    .act-selected { display: grid; gap: 5px; margin-top: 7px; }
    .act-selected button { display: flex; justify-content: space-between; gap: 10px; text-align: left; border: 1px solid #e4c7c5; border-radius: 5px; padding: 6px 9px; background: #fdf3f2; font-size: 12px; color: #57302d; }
    .case-summary-page {
        display: grid;
        gap: 16px;
        color: #1f2933;
        background: #f5f6f8;
        min-height: calc(100vh - 80px);
        padding-bottom: 36px;
    }

    .case-summary-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        padding: 18px 20px;
        border: 1px solid #e1e5ea;
        border-left: 4px solid #c30f08;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
    }

    .case-summary-hero h1 {
        margin: 4px 0 6px;
        color: #111827;
        font-size: 24px;
        font-weight: 700;
    }

    .case-summary-hero p {
        margin: 0;
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
    }

    .case-summary-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 6px;
        background: #f3f4f6;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .case-summary-pill.warning {
        background: #fff7ed;
        color: #9a3412;
    }

    .case-summary-card {
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .case-summary-card .accordion-button {
        gap: 10px;
        padding: 14px 18px;
        background: #fff;
        color: #1f2937;
        font-size: 15px;
        font-weight: 700;
        box-shadow: none;
    }

    .case-summary-card .accordion-button:hover,
    .case-summary-card .accordion-button:focus {
        color: #c30f08;
        background: #fff7f6;
        box-shadow: none;
    }

    .case-summary-card .accordion-button:not(.collapsed) {
        background: #fdf3f2;
        color: #c30f08;
        border-bottom: 1px solid #f0d2cf;
    }

    .case-summary-card .accordion-collapse.show .accordion-body {
        background: #fffbfa;
        box-shadow: inset 3px 0 0 #edc5c2;
    }

    .case-summary-badge {
        display: inline-flex;
        min-width: 38px;
        padding: 3px 8px;
        align-items: center;
        justify-content: center;
        border: 1px solid #f0d2cf;
        border-radius: 999px;
        background: #fff;
        color: #9d0c06;
        font-size: 12px;
        font-weight: 800;
        flex: 0 0 auto;
    }

    .case-summary-card .accordion-body {
        padding: 18px;
        background: #fff;
    }

    .field-label {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 6px;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
    }

    .field-label-text {
        display: grid;
        gap: 1px;
    }

    .field-label small {
        display: block;
        margin-top: 1px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 600;
    }

    .field-no {
        flex: 0 0 auto;
        min-width: 42px;
        padding: 2px 6px;
        border: 1px solid #f0d2cf;
        border-radius: 999px;
        background: #fff7f6;
        color: #9d0c06;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.35;
        text-align: center;
    }

    .section-guidance {
        margin-bottom: 12px;
        padding: 10px 12px;
        border: 1px solid #e6ebf0;
        border-left: 3px solid #c30f08;
        border-radius: 8px;
        background: #fbfcfd;
        color: #4b5563;
        font-size: 13px;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-radius: 6px;
        border: 1px solid #cfd6df;
        padding: 9px 10px;
        font-size: 14px;
        background-color: #fff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #c30f08 !important;
        box-shadow: 0 0 0 3px rgba(195, 15, 8, .12) !important;
        outline: none;
    }

    .section-note {
        margin-bottom: 14px;
        padding: 10px 12px;
        border: 1px dashed #d8dee6;
        border-radius: 8px;
        background: #fff;
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
    }

    .decision-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .decision-item {
        display: grid;
        gap: 10px;
        padding: 13px;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
    }

    .decision-item.is-active {
        border-color: #e3b8b4;
        background: #fffdfc;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .06);
    }

    .decision-head {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 138px;
        gap: 12px;
        align-items: start;
    }

    .decision-title {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        min-width: 0;
        color: #1f2937;
        font-size: 13px;
        font-weight: 800;
        line-height: 1.35;
    }

    .decision-title-text {
        display: grid;
        gap: 2px;
    }

    .decision-title-text small {
        color: #6b7280;
        font-size: 11px;
        font-weight: 600;
    }

    .decision-control {
        align-self: start;
    }

    .decision-check {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-height: 42px;
        padding: 8px 10px;
        border: 1px solid #d9e0e7;
        border-radius: 6px;
        background: #fff;
        color: #4b5563;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        user-select: none;
    }

    .decision-check input {
        width: 18px;
        height: 18px;
        accent-color: #c30f08;
        flex: 0 0 auto;
    }

    .decision-item.is-active .decision-check {
        border-color: #e3b8b4;
        background: #fff7f6;
        color: #9d0c06;
    }

    .decision-detail {
        margin-top: 2px;
        padding: 11px;
        border: 1px solid #edf0f4;
        border-radius: 8px;
        background: #fbfcfd;
    }

    .row-control-label {
        display: block;
        margin-bottom: 5px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .solution-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .solution-grid .decision-item {
        border-color: #dfe7e4;
    }

    .solution-grid .decision-item.is-active {
        border-color: #b9d6ca;
        background: #fbfefc;
    }

    .dependent-field {
        display: none;
        padding-top: 4px;
    }

    .follow-up-entry {
        display: grid;
        grid-template-columns: minmax(170px, .35fr) minmax(0, 1fr);
        gap: 12px;
        padding: 13px;
        border: 1px solid #e1e6ec;
        border-radius: 8px;
        background: #fff;
    }

    .demo-submit-bar {
        position: sticky;
        bottom: 0;
        z-index: 3;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 12px 0 0;
        background: linear-gradient(rgba(245, 246, 248, 0), #f5f6f8 35%);
    }

    .summary-field-card {
        height: 100%;
        padding: 12px;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        background: #fff;
    }

    .summary-field-card .field-label {
        min-height: 38px;
    }

    .summary-subtle-title {
        margin: 0 0 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
        color: #111827;
        font-size: 14px;
        font-weight: 800;
    }

    .summary-header-layout {
        display: grid;
        gap: 12px;
        padding: 14px 16px 16px;
    }

    .case-summary-form {
        display: grid;
        gap: 14px;
    }

    .summary-overview-panel {
        border: 1px solid #e0e5eb;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .summary-overview-heading {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 16px;
        border-bottom: 1px solid #ebeef2;
        background: linear-gradient(90deg, #fff7f6 0%, #fff 62%);
    }

    .summary-overview-icon {
        display: inline-flex;
        width: 32px;
        height: 32px;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #c30f08;
        color: #fff;
        flex: 0 0 auto;
    }

    .summary-overview-heading h2 {
        margin: 0;
        color: #1f2937;
        font-size: 15px;
        font-weight: 800;
    }

    .summary-overview-heading p {
        margin: 2px 0 0;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
    }

    .summary-type-row {
        display: grid;
        grid-template-columns: minmax(130px, .3fr) minmax(340px, 1fr);
        gap: 10px;
        align-items: center;
    }

    .summary-type-row .field-label {
        margin: 0;
    }

    .summary-type-group {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        max-width: 680px;
    }

    .summary-type-option {
        position: relative;
        margin: 0;
        cursor: pointer;
    }

    .summary-type-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .summary-type-option span {
        display: flex;
        min-height: 40px;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 7px 12px;
        border: 1px solid #d8dee6;
        border-radius: 7px;
        background: #f8fafc;
        color: #4b5563;
        font-size: 13px;
        font-weight: 800;
        text-align: center;
        transition: border-color .15s ease, background .15s ease, color .15s ease, box-shadow .15s ease;
    }

    .summary-type-option span::before {
        content: "";
        width: 12px;
        height: 12px;
        border: 2px solid #aeb7c2;
        border-radius: 50%;
        background: #fff;
        box-shadow: inset 0 0 0 2px #fff;
        flex: 0 0 auto;
    }

    .summary-type-option input:checked + span {
        border-color: #d89d98;
        background: #fff7f6;
        color: #9d0c06;
        box-shadow: 0 0 0 2px rgba(195, 15, 8, .07);
    }

    .summary-type-option input:checked + span::before {
        border-color: #c30f08;
        background: #c30f08;
    }

    .summary-type-option input:focus-visible + span {
        outline: 3px solid rgba(195, 15, 8, .15);
        outline-offset: 2px;
    }

    .summary-overview-grid {
        display: grid;
        grid-template-columns: minmax(190px, .85fr) minmax(165px, .7fr) minmax(280px, 1.3fr);
        gap: 9px;
        align-items: stretch;
    }

    .summary-overview-item {
        padding: 9px 10px;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        background: #fbfcfd;
    }

    .summary-overview-item .field-label {
        min-height: 0;
        margin-bottom: 5px;
    }

    .summary-date-control {
        background: #f3f4f6;
        color: #374151;
        font-weight: 700;
    }

    .duration-display-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 6px;
    }

    .duration-box {
        min-height: 38px;
        padding: 6px 8px;
        border: 1px solid #e0e6ed;
        border-radius: 6px;
        background: #f9fafb;
    }

    .duration-box strong {
        display: block;
        color: #111827;
        font-size: 15px;
        line-height: 1;
    }

    .duration-box span {
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .case-summary-hero {
            flex-direction: column;
            padding: 16px;
        }

        .case-summary-hero h1 {
            font-size: 21px;
        }

        .case-summary-card .accordion-body {
            padding: 13px;
        }

        .decision-grid,
        .solution-grid {
            grid-template-columns: 1fr;
        }

        .decision-head {
            grid-template-columns: 1fr;
        }

        .duration-display-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .summary-overview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .summary-duration-item {
            grid-column: 1 / -1;
        }

        .summary-type-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .follow-up-entry {
            grid-template-columns: 1fr;
        }

        .summary-header-layout {
            padding: 12px;
        }

        .demo-submit-bar {
            display: grid;
            grid-template-columns: 1fr;
        }

        .demo-submit-bar .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .summary-type-option span {
            min-height: 42px;
            padding: 6px 8px;
            font-size: 12px;
            line-height: 1.2;
        }

        .summary-overview-heading {
            padding: 10px 12px;
        }

        .summary-overview-grid {
            gap: 8px;
        }

        .summary-overview-item {
            padding: 9px;
        }
    }

    @media (max-width: 360px) {
        .summary-overview-grid {
            grid-template-columns: 1fr;
        }

        .summary-duration-item {
            grid-column: auto;
        }
    }
</style>
@endpush

@section('content')
<section class="case-summary-page">
    <div class="case-summary-hero">
        <div>
            <h1>Case Summary Form</h1>
            <p>মামলার সারসংক্ষেপ ও অগ্রগতি প্রতিবেদন</p>
        </div>
    </div>

    <form id="caseSummaryDemoForm" class="case-summary-form" action="javascript:void(0);" enctype="multipart/form-data">
        <div class="summary-overview-panel">
            <div class="summary-overview-heading">
                <span class="summary-overview-icon"><i class="fas fa-file-lines"></i></span>
                <div>
                    <h2>Case Summary Details</h2>
                    <p>মামলার সারসংক্ষেপের প্রাথমিক তথ্য</p>
                </div>
            </div>

            <div class="summary-header-layout">
                <div class="summary-type-row">
                    <label class="field-label">Summary Type <small>সামারির ধরণ</small></label>
                    <div class="summary-type-group" role="radiogroup" aria-label="Summary Type">
                        <label class="summary-type-option">
                            <input type="radio" name="summary_type" value="Paralegal Intervention" required>
                            <span>Paralegal Intervention</span>
                        </label>
                        <label class="summary-type-option">
                            <input type="radio" name="summary_type" value="Judicial Intervention" required>
                            <span>Judicial Intervention</span>
                        </label>
                    </div>
                </div>

                <div class="summary-overview-grid">
                    <div class="summary-overview-item">
                        <label class="field-label" for="district">District <small>জেলা</small></label>
                        <select id="district" name="district_id" class="form-select">
                            <option value="">Select district</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="summary-overview-item">
                        <label class="field-label" for="summary_date">Date <small>তারিখ</small></label>
                        <input type="date" id="summary_date" name="summary_date" class="form-control summary-date-control" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="summary-overview-item summary-duration-item">
                        <label class="field-label" for="case_pending_duration">
                            <span class="field-label-text">Duration of Case Pending in Court <small>মামলা বিচারাধীন থাকার সময়কাল</small></span>
                        </label>
                        <div class="duration-display-grid" id="case_pending_duration" aria-live="polite">
                            <div class="duration-box">
                                <strong id="pending_years_display">-</strong>
                                <span>Years</span>
                            </div>
                            <div class="duration-box">
                                <strong id="pending_months_display">-</strong>
                                <span>Months</span>
                            </div>
                            <div class="duration-box">
                                <strong id="pending_days_display">-</strong>
                                <span>Days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="caseSummaryAccordion">

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#summaryOne" aria-expanded="true" aria-controls="summaryOne">
                        <span class="case-summary-badge">1</span> Case Identification
                    </button>
                </h2>
                <div id="summaryOne" class="accordion-collapse collapse show" aria-labelledby="summaryHeaderOne" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="field-label" for="court_name">1.1 Court Name <small>আদালতের নাম</small></label>
                                <select id="court_name" name="court_id" class="form-select">
                                    <option value="">Select district first</option>
                                    @foreach ($courts as $court)
                                        <option value="{{ $court->id }}" data-district="{{ $court->district_id }}">{{ $court->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Court list comes from General Settings &gt; Court Management.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="case_number">1.2 Case Number <small>মামলা নাম্বার</small></label>
                                <input type="text" id="case_number" name="case_number" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" id="act-label">1.3 Act <small>আইন</small></label>
                                <details class="act-picker" id="act-picker">
                                    <summary id="act" aria-labelledby="act-label act-summary"><span id="act-summary">Select acts</span></summary>
                                    <div class="act-picker-panel">
                                        <label for="act-search" class="visually-hidden">Search acts by title, number, or year</label>
                                        <input type="search" id="act-search" class="form-control" placeholder="Type a few characters to search…" autocomplete="off">
                                        <div class="small text-muted mt-2" id="act-results" role="status"></div>
                                        <div class="act-picker-options" role="group" aria-labelledby="act-label">
                                            @foreach($acts as $act)
                                                <label class="act-picker-option">
                                                    <input type="checkbox" name="act_ids[]" value="{{ $act->id }}" @checked(in_array((string) $act->id, array_map('strval', (array) old('act_ids', [])), true))>
                                                    <span>{{ $act->title }} <small class="text-muted">(Act No. {{ $act->act_number ?? '—' }} · {{ $act->year }})</small></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </details>
                                <div class="act-selected" id="act-selected" aria-label="Selected acts"></div>
                                <small class="text-muted">Select one or more acts. Search or browse the full list.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="case_section">1.4 Case Section <small>মামলার ধারা</small></label>
                                <input type="text" id="case_section" name="case_section" class="form-control">
                            </div>
                            <div class="col-md-8">
                                <label class="field-label" for="case_title">1.5 Case Title <small>মামলার পক্ষগণের নাম</small></label>
                                <input type="text" id="case_title" name="case_title" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="case_type">1.6 Case Type <small>মামলার ধরন</small></label>
                                <select id="case_type" name="case_type" class="form-select">
                                    <option value="">Select case type</option>
                                    <option>Civil Case</option>
                                    <option>Criminal Case</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="case_filing_date">1.7 Date of Case Filing <small>মামলা দায়েরের তারিখ</small></label>
                                <input type="date" id="case_filing_date" name="case_filing_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="court_staff_name">1.8 Court Staff Name <small>কোর্ট স্টাফের নাম</small></label>
                                <input type="text" id="court_staff_name" name="court_staff_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="court_staff_phone">1.9 Court Staff Contact Number <small>কোর্ট স্টাফ এর মোবাইল নাম্বার</small></label>
                                <input type="text" id="court_staff_phone" name="court_staff_phone" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryTwo" aria-expanded="false" aria-controls="summaryTwo">
                        <span class="case-summary-badge">2</span> Case Profile
                    </button>
                </h2>
                <div id="summaryTwo" class="accordion-collapse collapse" aria-labelledby="summaryHeaderTwo" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label" for="offence_subject">2.2 Type of Offence / Subject Matter <small>অপরাধের ধরন/ বিষয়বস্তু</small></label>
                                <input type="text" id="offence_subject" name="offence_subject" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="field-label" for="current_case_status">2.3 Current Case Status <small>মামলার বর্তমান অবস্থা</small></label>
                                <select id="current_case_status" name="current_case_status" class="form-select" data-other-target="current_case_status_other_wrap">
                                    <option value="">Select status</option>
                                    <option>Police Report</option>
                                    <option>NBWW</option>
                                    <option>Witness Examination</option>
                                    <option>Defense Witness Examination</option>
                                    <option value="accused_submitted_petition_for_discharge">Accused submitted petition for discharge</option>
                                    <option value="appeal">Appeal</option>
                                    <option value="appeal_hearing_completed">Appeal hearing completed</option>
                                    <option value="appeal_transferred">Appeal transferred</option>
                                    <option value="argument">Argument</option>
                                    <option value="case_dismissed">Case Dismissed</option>
                                    <option value="case_is_stayed">Case is stayed</option>
                                    <option value="case_withdrawal">Case Withdrawal</option>
                                    <option value="charge_framed">Charge framed</option>
                                    <option value="charge_not_framed">Charge not framed</option>
                                    <option value="cognizance_not_taken_and_ordered">Cognizance not taken and ordered</option>
                                    <option value="cognizance_taken_and_issued">Cognizance taken and issued</option>
                                    <option value="complainant_change">Complainant Change</option>
                                    <option value="court_issued_warrant">Court issued warrant</option>
                                    <option value="court_issued_warrant_of_proclamation_and_attachment_wpa">Court issued warrant of Proclamation and Attachment (WP&amp;A)</option>
                                    <option value="court_not_held">Court not held</option>
                                    <option value="court_ordered">Court ordered</option>
                                    <option value="cross_examination">Cross examination</option>
                                    <option value="examination_in_chief">Examination in chief</option>
                                    <option value="examination_of_accused_342">Examination of accused (342)</option>
                                    <option value="fir_lodged_by_informant">FIR Lodged by Informant</option>
                                    <option value="hazira_given">Hazira given</option>
                                    <option value="hearing">Hearing</option>
                                    <option value="judgment">Judgment</option>
                                    <option value="naraji_petition">Naraji petition</option>
                                    <option value="order_pending">Order pending</option>
                                    <option value="paper_notification">Paper Notification</option>
                                    <option value="recall_witness">Recall witness</option>
                                    <option value="remain">Remain</option>
                                    <option value="report">Report</option>
                                    <option value="revision">Revision</option>
                                    <option value="revision_filed">Revision filed</option>
                                    <option value="revision_hearing">Revision hearing</option>
                                    <option value="service_return">Service Return</option>
                                    <option value="settling_date_for_ph">Settling Date for PH</option>
                                    <option value="summon_issue">Summon Issue</option>
                                    <option value="time_petition_by_accused">Time Petition by Accused</option>
                                    <option value="time_petition_by_complainant">Time Petition by Complainant</option>
                                    <option value="transfer_to_the_court">Transfer to the court</option>
                                    <option value="update_by_divisional_lawyer">Update by Divisional Lawyer</option>
                                    <option value="update_from_ho">Update from HO</option>
                                    <option value="update_from_lc_lawyer">Update from LC Lawyer</option>
                                    <option value="upload_case_document">Upload Case Document</option>
                                    <option value="witness">Witness</option>
                                    <option>Pending for Judgment</option>
                                    <option>Stayed</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 dependent-field" id="current_case_status_other_wrap">
                                <label class="field-label" for="current_case_status_other">2.3 Other Status <small>অন্যান্য, উল্লেখ করুন</small></label>
                                <input type="text" id="current_case_status_other" name="current_case_status_other" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="hearing_count">2.4 Number of Hearing Held <small>শুনানীর সংখ্যা</small></label>
                                <input type="number" min="0" id="hearing_count" name="hearing_count" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="last_hearing_date">2.5 Last Hearing Date <small>শেষ শুনানীর তারিখ</small></label>
                                <input type="date" id="last_hearing_date" name="last_hearing_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="next_hearing_date">2.6 Next Hearing Date <small>পরবর্তী শুনানির তারিখ</small></label>
                                <input type="date" id="next_hearing_date" name="next_hearing_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryThree" aria-expanded="false" aria-controls="summaryThree">
                        <span class="case-summary-badge">3</span> Basic Information of the Parties
                    </button>
                </h2>
                <div id="summaryThree" class="accordion-collapse collapse" aria-labelledby="summaryHeaderThree" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            @foreach ([
                                'complainant' => ['3.1', 'Complainant / Plaintiff', 'অভিযোগকারী/বাদী'],
                                'defendant' => ['3.2', 'Defendant / Respondent', 'প্রতিপক্ষ/বিবাদী'],
                            ] as $prefix => $party)
                                <div class="col-12">
                                    <div class="section-note"><strong>{{ $party[0] }} {{ $party[1] }}</strong> - {{ $party[2] }}</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" for="{{ $prefix }}_name">{{ $party[0] }}.1 Name</label>
                                    <input type="text" id="{{ $prefix }}_name" name="{{ $prefix }}_name" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" for="{{ $prefix }}_sex">{{ $party[0] }}.2 Sex</label>
                                    <select id="{{ $prefix }}_sex" name="{{ $prefix }}_sex" class="form-select">
                                        <option value="">Select sex</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Transgender Person</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" for="{{ $prefix }}_age">{{ $party[0] }}.3 Age</label>
                                    <input type="number" min="0" max="150" id="{{ $prefix }}_age" name="{{ $prefix }}_age" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" for="{{ $prefix }}_disability">{{ $party[0] }}.4 Disability</label>
                                    <select id="{{ $prefix }}_disability" name="{{ $prefix }}_disability" class="form-select">
                                        <option value="">Select</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" for="{{ $prefix }}_income">{{ $party[0] }}.5 Approximate Monthly Income</label>
                                    <select id="{{ $prefix }}_income" name="{{ $prefix }}_income" class="form-select">
                                        <option value="">Select income range</option>
                                        <option>None</option>
                                        <option>Up to 5000</option>
                                        <option>5,001 to 10,000</option>
                                        <option>10,001 to 25,000</option>
                                        <option>25,001 to 50,000</option>
                                        <option>More than 50,000</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" for="{{ $prefix }}_phone">{{ $party[0] }}.6 Phone Number</label>
                                    <input type="text" id="{{ $prefix }}_phone" name="{{ $prefix }}_phone" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" for="{{ $prefix }}_representation">{{ $party[0] }}.7 Representation</label>
                                    <select id="{{ $prefix }}_representation" name="{{ $prefix }}_representation" class="form-select" data-lawyer-target="{{ $prefix }}_lawyer_fields">
                                        <option value="">Select representation</option>
                                        <option>Self</option>
                                        <option>Lawyer</option>
                                        <option>NGO</option>
                                        <option>Legal Aid Office</option>
                                    </select>
                                </div>
                                <div class="col-md-4 dependent-field" data-lawyer-field="{{ $prefix }}_lawyer_fields">
                                    <label class="field-label" for="{{ $prefix }}_lawyer_name">{{ $party[0] }}.8 Lawyer Name</label>
                                    <input type="text" id="{{ $prefix }}_lawyer_name" name="{{ $prefix }}_lawyer_name" class="form-control">
                                </div>
                                <div class="col-md-4 dependent-field" data-lawyer-field="{{ $prefix }}_lawyer_fields">
                                    <label class="field-label" for="{{ $prefix }}_lawyer_phone">{{ $party[0] }}.9 Lawyer Phone</label>
                                    <input type="text" id="{{ $prefix }}_lawyer_phone" name="{{ $prefix }}_lawyer_phone" class="form-control">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryFour" aria-expanded="false" aria-controls="summaryFour">
                        <span class="case-summary-badge">4</span> Barriers Preventing Progress
                    </button>
                </h2>
                <div id="summaryFour" class="accordion-collapse collapse" aria-labelledby="summaryHeaderFour" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="section-guidance">
                            Select Yes only for barriers that affected this case. Details will open automatically when needed.
                        </div>
                        <div class="decision-grid">
                            @foreach ([
                                ['4.1', 'missing_documents', 'Missing Documents', ['Summon', 'Notice', 'Report', 'Other']],
                                ['4.2', 'absent_witnesses', 'Absent Witnesses', ['Defense Witness', 'IO', 'MO', 'Other']],
                                ['4.3', 'procedural_delays', 'Procedural Delays', ['Record Transfer', 'Scheduling', 'Other']],
                                ['4.4', 'adjournments', 'Adjournments by Parties / Court', []],
                                ['4.5', 'lawyer_absence', 'Lack of Lawyer / Prosecutor Presence', []],
                                ['4.6', 'administrative_issues', 'Other Administrative Issues', []],
                                ['4.7', 'evidence_notes', 'Notes / Evidence Supporting Observation', []],
                                ['4.8', 'other_barrier', 'Other, please specify', []],
                            ] as $barrier)
                                <div class="decision-item">
                                    <div class="decision-head">
                                        <div class="decision-title">
                                            <span class="field-no">{{ $barrier[0] }}</span>
                                            <span class="decision-title-text">
                                                <span>{{ $barrier[2] }}</span>
                                                <small>Record only if this barrier affected progress</small>
                                            </span>
                                        </div>
                                        <div class="decision-control">
                                            <label class="row-control-label" for="{{ $barrier[1] }}_status">Status</label>
                                            <input type="hidden" name="{{ $barrier[1] }}" value="No">
                                            <label class="decision-check" for="{{ $barrier[1] }}_status">
                                                <span>Yes</span>
                                                <input id="{{ $barrier[1] }}_status" type="checkbox" name="{{ $barrier[1] }}" value="Yes" data-yes-target="{{ $barrier[1] }}_details">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="dependent-field decision-detail" id="{{ $barrier[1] }}_details">
                                        <div class="row g-2">
                                            @if (!empty($barrier[3]))
                                                <div class="col-md-5">
                                                    <label class="field-label" for="{{ $barrier[1] }}_type">Type</label>
                                                    <select id="{{ $barrier[1] }}_type" name="{{ $barrier[1] }}_type" class="form-select" data-other-target="{{ $barrier[1] }}_other_wrap" data-required-when-active>
                                                        <option value="">Select type</option>
                                                        @foreach ($barrier[3] as $option)
                                                            <option>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-7 dependent-field" id="{{ $barrier[1] }}_other_wrap">
                                                    <label class="field-label" for="{{ $barrier[1] }}_other">Please specify</label>
                                                    <input type="text" id="{{ $barrier[1] }}_other" name="{{ $barrier[1] }}_other" class="form-control" data-required-when-visible>
                                                </div>
                                            @elseif ($barrier[1] === 'other_barrier')
                                                <div class="col-12">
                                                    <label class="field-label" for="{{ $barrier[1] }}_specify">Other barrier</label>
                                                    <input type="text" id="{{ $barrier[1] }}_specify" name="{{ $barrier[1] }}_specify" class="form-control" placeholder="Other barrier, please specify" data-required-when-active>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <label class="field-label" for="{{ $barrier[1] }}_remarks">Remarks</label>
                                                <textarea id="{{ $barrier[1] }}_remarks" name="{{ $barrier[1] }}_remarks" class="form-control" rows="2" placeholder="Brief note, date, source, or responsible office"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryFive" aria-expanded="false" aria-controls="summaryFive">
                        <span class="case-summary-badge">5</span> Proposed Disposal Route / Solutions
                    </button>
                </h2>
                <div id="summaryFive" class="accordion-collapse collapse" aria-labelledby="summaryHeaderFive" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="section-guidance">
                            Mark the proposed route for disposal or case movement. Reasons open only after selecting Yes.
                        </div>
                        <div class="solution-grid">
                            @foreach ([
                                ['5.1', 'option_route', 'Option'],
                                ['5.2', 'priority_hearing', 'Priority Hearing'],
                                ['5.3', 'mediation_settlement', 'Mediation / Settlement'],
                                ['5.4', 'diversion', 'Diversion'],
                                ['5.5', 'dismissal_withdrawal', 'Dismissal / Withdrawal'],
                                ['5.6', 'administrative_followup', 'Administrative Follow-up'],
                                ['5.7', 'other_solution', 'Other, please specify'],
                            ] as $solution)
                                <div class="decision-item">
                                    <div class="decision-head">
                                        <div class="decision-title">
                                            <span class="field-no">{{ $solution[0] }}</span>
                                            <span class="decision-title-text">
                                                <span>{{ $solution[2] }}</span>
                                                <small>Suggested route or action for this case</small>
                                            </span>
                                        </div>
                                        <div class="decision-control">
                                            <label class="row-control-label" for="{{ $solution[1] }}_status">Proposed</label>
                                            <input type="hidden" name="{{ $solution[1] }}" value="No">
                                            <label class="decision-check" for="{{ $solution[1] }}_status">
                                                <span>Yes</span>
                                                <input id="{{ $solution[1] }}_status" type="checkbox" name="{{ $solution[1] }}" value="Yes" data-yes-target="{{ $solution[1] }}_reason_wrap">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="dependent-field decision-detail" id="{{ $solution[1] }}_reason_wrap">
                                        <div class="row g-2">
                                            @if ($solution[1] === 'other_solution')
                                                <div class="col-12">
                                                    <label class="field-label" for="{{ $solution[1] }}_specify">Other route</label>
                                                    <input type="text" id="{{ $solution[1] }}_specify" name="{{ $solution[1] }}_specify" class="form-control" placeholder="Other solution, please specify" data-required-when-active>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <label class="field-label" for="{{ $solution[1] }}_reason">Reason / next action</label>
                                                <textarea id="{{ $solution[1] }}_reason" name="{{ $solution[1] }}_reason" class="form-control" rows="2" placeholder="Why this route is proposed, and what should happen next" data-required-when-active></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summarySix" aria-expanded="false" aria-controls="summarySix">
                        <span class="case-summary-badge">6</span> Intervention Taken by Paralegal
                    </button>
                </h2>
                <div id="summarySix" class="accordion-collapse collapse" aria-labelledby="summaryHeaderSix" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <h5 class="summary-subtle-title">প্যারালিগ্যাল কর্তৃক গৃহীত পদক্ষেপ (Intervention Taken by Paralegal)</h5>
                        <div class="row g-3">
                            @foreach ([
                                ['6.1', 'collect_case_documents', 'মামলার নথিপত্র সংগ্রহ', 'Collect Case Documents'],
                                ['6.2', 'communicate_witness', 'সাক্ষীর সাথে যোগাযোগ', 'Communicate with Witness'],
                                ['6.3', 'communicate_parties', 'পক্ষসমূহের সাথে যোগাযোগ', 'Communicate with Parties'],
                                ['6.4', 'refer_mediation', 'মধ্যস্থতার জন্য রেফার করতে সহায়তা', 'Support to Refer to Mediation'],
                                ['6.5', 'present_judge', 'বিচারকের সামনে উপস্থাপন', 'Present to Judge'],
                                ['6.6', 'present_judicial_conference', 'জুডিসিয়াল কনফারেন্সে উপস্থাপন', 'Present to Judicial Conference'],
                            ] as $intervention)
                                <div class="col-md-4">
                                    <div class="summary-field-card">
                                        <label class="field-label" for="{{ $intervention[1] }}_date">
                                            {{ $intervention[0] }} {{ $intervention[2] }}
                                            <small>{{ $intervention[3] }}</small>
                                        </label>
                                        <input type="date" id="{{ $intervention[1] }}_date" name="{{ $intervention[1] }}_date" class="form-control">
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-4">
                                <div class="summary-field-card">
                                    <label class="field-label" for="other_paralegal_intervention_details">
                                        6.7 অন্যান্য, উল্লেখ করুন
                                        <small>Other, please specify</small>
                                    </label>
                                    <input type="text" id="other_paralegal_intervention_details" name="other_paralegal_intervention_details" class="form-control mb-2" placeholder="Intervention details">
                                    <input type="date" id="other_paralegal_intervention_date" name="other_paralegal_intervention_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summarySeven" aria-expanded="false" aria-controls="summarySeven">
                        <span class="case-summary-badge">7</span> Judicial Intervention
                    </button>
                </h2>
                <div id="summarySeven" class="accordion-collapse collapse" aria-labelledby="summaryHeaderSeven" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <label class="field-label" for="judicial_intervention">7.1 Judicial Intervention <small>জুডিসিয়াল পদক্ষেপ</small></label>
                        <textarea id="judicial_intervention" name="judicial_intervention" class="form-control" rows="4"></textarea>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryEight" aria-expanded="false" aria-controls="summaryEight">
                        <span class="case-summary-badge">8</span> Comments and Observations
                    </button>
                </h2>
                <div id="summaryEight" class="accordion-collapse collapse" aria-labelledby="summaryHeaderEight" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <label class="field-label" for="overall_observation">8.1 Overall Case Situation, Cooperation, Systemic Challenges, or Good Practices</label>
                        <textarea id="overall_observation" name="overall_observation" class="form-control" rows="5"></textarea>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderNine">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryNine" aria-expanded="false" aria-controls="summaryNine">
                        <span class="case-summary-badge">9</span> Review and Endorsement
                    </button>
                </h2>
                <div id="summaryNine" class="accordion-collapse collapse" aria-labelledby="summaryHeaderNine" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="field-label" for="prepared_by">9.1 Paralegal Preparing Summary</label>
                                <input type="text" id="prepared_by" name="prepared_by" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="court_official_reviewing">9.2 Peshkar / Court Official Reviewing</label>
                                <input type="text" id="court_official_reviewing" name="court_official_reviewing" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="dpo_reviewed_by">9.3 Reviewed by the DPO</label>
                                <input type="text" id="dpo_reviewed_by" name="dpo_reviewed_by" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderTen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryTen" aria-expanded="false" aria-controls="summaryTen">
                        <span class="case-summary-badge">10</span> Case Progress / Follow-up
                    </button>
                </h2>
                <div id="summaryTen" class="accordion-collapse collapse" aria-labelledby="summaryHeaderTen" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="section-note">Previous follow-up records are preserved in chronological order.</div>
                        <div class="follow-up-entry">
                            <div>
                                <label class="field-label" for="follow_up_date">Date <small>তারিখ</small></label>
                                <input type="date" id="follow_up_date" name="follow_up_date" class="form-control">
                            </div>
                            <div>
                                <label class="field-label" for="follow_up_action">Progress / Action Taken <small>অগ্রগতি / গৃহীত পদক্ষেপ</small></label>
                                <input type="text" id="follow_up_action" name="follow_up_action" class="form-control" placeholder="Enter the latest progress or action taken">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderEvidence">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryEvidence" aria-expanded="false" aria-controls="summaryEvidence">
                        <span class="case-summary-badge">11</span> Evidence Attachments
                    </button>
                </h2>
                <div id="summaryEvidence" class="accordion-collapse collapse" aria-labelledby="summaryHeaderEvidence" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <label class="field-label" for="summary_attachments">Upload Supporting Documents <small>প্রমাণ/সহায়ক নথি</small></label>
                        <input type="file" id="summary_attachments" name="summary_attachments[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="text-muted d-block mt-2">Accepted files: PDF, JPG, PNG, DOC and DOCX.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="demo-submit-bar">
            <button type="button" class="btn btn-outline-secondary" id="caseSummaryDemoReset"><i class="fas fa-rotate-left"></i> Reset</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Submit Case Summary</button>
        </div>
    </form>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actPicker = document.getElementById('act-picker');
        const actSearch = document.getElementById('act-search');
        const actOptions = Array.from(actPicker.querySelectorAll('.act-picker-option')).map(label => ({
            label,
            checkbox: label.querySelector('input'),
            title: label.querySelector('span').textContent.trim(),
            search: label.textContent.normalize('NFKC').toLocaleLowerCase()
        }));
        function filterActs() {
            const terms = actSearch.value.normalize('NFKC').toLocaleLowerCase().trim().split(/\s+/).filter(Boolean);
            let visible = 0;
            actOptions.forEach(option => {
                option.label.hidden = !terms.every(term => option.search.includes(term));
                if (!option.label.hidden) visible++;
            });
            document.getElementById('act-results').textContent = visible ? visible + ' acts available' : 'No matching acts found.';
        }
        function syncSelectedActs() {
            const selected = actOptions.filter(option => option.checkbox.checked);
            document.getElementById('act-summary').textContent = selected.length ? selected.length + ' act' + (selected.length === 1 ? '' : 's') + ' selected' : 'Select acts';
            const chips = document.getElementById('act-selected');
            chips.replaceChildren();
            selected.forEach(option => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = option.title + ' ×';
                button.setAttribute('aria-label', 'Remove ' + option.title);
                button.addEventListener('click', () => {
                    option.checkbox.checked = false;
                    option.checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                    document.getElementById('act').focus();
                });
                chips.append(button);
            });
        }
        actSearch.addEventListener('input', filterActs);
        actSearch.addEventListener('keydown', event => {
            if (event.key === 'Enter') event.preventDefault();
        });
        actPicker.addEventListener('change', syncSelectedActs);
        actPicker.addEventListener('toggle', () => {
            if (actPicker.open) actSearch.focus();
        });
        actPicker.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                actPicker.open = false;
                document.getElementById('act').focus();
            }
        });
        document.addEventListener('click', event => {
            if (!actPicker.contains(event.target)) actPicker.open = false;
        });
        document.getElementById('caseSummaryDemoForm').addEventListener('reset', () => {
            setTimeout(() => { filterActs(); syncSelectedActs(); }, 0);
        });
        filterActs();
        syncSelectedActs();

        const districtSelect = document.getElementById('district');
        const courtSelect = document.getElementById('court_name');

        function filterCourtsByDistrict() {
            if (!districtSelect || !courtSelect) {
                return;
            }

            const districtId = districtSelect.value;
            const currentCourt = courtSelect.value;
            let currentCourtStillVisible = false;
            let visibleCourtCount = 0;

            Array.from(courtSelect.options).forEach(function(option) {
                if (!option.value) {
                    option.hidden = false;
                    option.textContent = districtId ? 'Select court' : 'Select district first';
                    return;
                }

                const visible = option.dataset.district === districtId;
                option.hidden = !visible;

                if (visible) {
                    visibleCourtCount++;
                }

                if (visible && option.value === currentCourt) {
                    currentCourtStillVisible = true;
                }
            });

            if (!districtId || !currentCourtStillVisible) {
                courtSelect.value = '';
            }

            courtSelect.disabled = !districtId || visibleCourtCount === 0;
        }

        districtSelect?.addEventListener('change', filterCourtsByDistrict);
        filterCourtsByDistrict();

        function applyFieldNumberBadges() {
            document.querySelectorAll('.field-label').forEach(function(label) {
                if (label.querySelector('.field-no')) {
                    return;
                }

                const firstTextNode = Array.from(label.childNodes).find(function(node) {
                    return node.nodeType === Node.TEXT_NODE && node.textContent.trim().match(/^\d+(?:\.\d+)*/);
                });

                if (!firstTextNode) {
                    return;
                }

                const match = firstTextNode.textContent.trim().match(/^(\d+(?:\.\d+)*)(\s*)(.*)$/);

                if (!match) {
                    return;
                }

                const badge = document.createElement('span');
                badge.className = 'field-no';
                badge.textContent = match[1];

                firstTextNode.textContent = match[3] ? ' ' + match[3] : '';
                label.insertBefore(badge, label.firstChild);
            });
        }

        function daysInMonth(year, monthIndex) {
            return new Date(year, monthIndex + 1, 0).getDate();
        }

        function calculateDateDifference(startDate, endDate) {
            let years = endDate.getFullYear() - startDate.getFullYear();
            let months = endDate.getMonth() - startDate.getMonth();
            let days = endDate.getDate() - startDate.getDate();

            if (days < 0) {
                months -= 1;
                const previousMonthIndex = (endDate.getMonth() - 1 + 12) % 12;
                const previousMonthYear = previousMonthIndex === 11 ? endDate.getFullYear() - 1 : endDate.getFullYear();
                days += daysInMonth(previousMonthYear, previousMonthIndex);
            }

            if (months < 0) {
                years -= 1;
                months += 12;
            }

            return {
                years: Math.max(0, years),
                months: Math.max(0, months),
                days: Math.max(0, days)
            };
        }

        function syncPendingDuration() {
            const filingInput = document.getElementById('case_filing_date');
            const summaryDateInput = document.getElementById('summary_date');
            const yearsOutput = document.getElementById('pending_years_display');
            const monthsOutput = document.getElementById('pending_months_display');
            const daysOutput = document.getElementById('pending_days_display');

            if (!filingInput || !yearsOutput || !monthsOutput || !daysOutput) {
                return;
            }

            if (!filingInput.value) {
                yearsOutput.textContent = '-';
                monthsOutput.textContent = '-';
                daysOutput.textContent = '-';
                return;
            }

            const startDate = new Date(filingInput.value + 'T00:00:00');
            const endDate = summaryDateInput && summaryDateInput.value
                ? new Date(summaryDateInput.value + 'T00:00:00')
                : new Date();

            if (Number.isNaN(startDate.getTime()) || startDate > endDate) {
                yearsOutput.textContent = '0';
                monthsOutput.textContent = '0';
                daysOutput.textContent = '0';
                return;
            }

            const diff = calculateDateDifference(startDate, endDate);
            yearsOutput.textContent = diff.years;
            monthsOutput.textContent = diff.months;
            daysOutput.textContent = diff.days;
        }

        document.getElementById('case_filing_date')?.addEventListener('change', syncPendingDuration);
        document.getElementById('summary_date')?.addEventListener('change', syncPendingDuration);
        syncPendingDuration();

        function syncLawyerFields(select) {
            const targetName = select.dataset.lawyerTarget;

            if (!targetName) {
                return;
            }

            document.querySelectorAll('[data-lawyer-field="' + targetName + '"]').forEach(function(field) {
                field.style.display = select.value === 'Lawyer' ? 'block' : 'none';
            });
        }

        document.querySelectorAll('[data-lawyer-target]').forEach(function(select) {
            select.addEventListener('change', function() {
                syncLawyerFields(select);
            });
            syncLawyerFields(select);
        });

        function syncYesField(select) {
            const target = document.getElementById(select.dataset.yesTarget);

            if (!target) {
                return;
            }

            const isActive = select.type === 'checkbox' ? select.checked : select.value === 'Yes';
            const decisionItem = select.closest('.decision-item');

            target.style.display = isActive ? 'block' : 'none';
            decisionItem?.classList.toggle('is-active', isActive);

            target.querySelectorAll('[data-required-when-active]').forEach(function(field) {
                field.required = isActive;
            });

            if (!isActive) {
                target.querySelectorAll('[data-required-when-active]').forEach(function(field) {
                    field.required = false;
                });
                target.querySelectorAll('[data-required-when-visible]').forEach(function(field) {
                    field.required = false;
                });
            }

            target.querySelectorAll('[data-other-target]').forEach(syncOtherField);
        }

        function syncOtherField(select) {
            const target = document.getElementById(select.dataset.otherTarget);

            if (!target) {
                return;
            }

            const decisionItem = select.closest('.decision-item');
            const isVisible = select.value === 'Other' && (!decisionItem || decisionItem.classList.contains('is-active'));

            target.style.display = isVisible ? 'block' : 'none';
            target.querySelectorAll('[data-required-when-visible]').forEach(function(field) {
                field.required = isVisible;
            });
        }

        document.querySelectorAll('[data-yes-target]').forEach(function(select) {
            select.addEventListener('change', function() {
                syncYesField(select);
            });
            syncYesField(select);
        });

        document.querySelectorAll('[data-other-target]').forEach(function(select) {
            select.addEventListener('change', function() {
                syncOtherField(select);
            });
            syncOtherField(select);
        });

        document.getElementById('caseSummaryDemoReset')?.addEventListener('click', function() {
            document.getElementById('caseSummaryDemoForm')?.reset();
            filterCourtsByDistrict();
            syncPendingDuration();

            document.querySelectorAll('[data-yes-target]').forEach(syncYesField);
            document.querySelectorAll('[data-other-target]').forEach(syncOtherField);
            document.querySelectorAll('[data-lawyer-target]').forEach(syncLawyerFields);
        });

        applyFieldNumberBadges();

        document.getElementById('caseSummaryDemoForm')?.addEventListener('submit', function(event) {
            event.preventDefault();

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Case Summary',
                    text: 'The entered information has been reviewed successfully.',
                    confirmButtonColor: '#2f7d62'
                });
            } else {
                alert('The entered information has been reviewed successfully.');
            }
        });
    });
</script>
@endpush
