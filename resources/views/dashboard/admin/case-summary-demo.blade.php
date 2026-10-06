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
    .act-selected { display: grid; gap: 6px; margin-top: 7px; }
    .act-selected-item { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 6px 7px 6px 10px; border: 1px solid #e4c7c5; border-radius: 6px; background: #fdf3f2; color: #57302d; font-size: 12px; }
    .act-selected-item span { min-width: 0; overflow-wrap: anywhere; }
    .act-selected-remove { flex: 0 0 auto; min-height: 32px; padding: 5px 10px; border: 1px solid #d8a7a3; border-radius: 5px; background: #fff; color: #9d0c06; font-size: 12px; font-weight: 700; }
    .act-selected-remove:hover { border-color: #c30f08; background: #fff7f6; }
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

    .case-title-row {
        display: flex;
        align-items: center;
        gap: 11px;
        margin: 14px 16px 0;
        padding: 11px 13px;
        border: 1px solid #e3d3d1;
        border-radius: 7px;
        background: #fff8f7;
    }

    .case-title-row-icon {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #f4e5e3;
        color: #a72620;
        flex: 0 0 auto;
    }

    .case-title-row-label {
        display: block;
        margin-bottom: 2px;
        color: #7f1d1d;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .case-title-preview {
        color: #1f2937;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .case-title-preview.is-empty {
        color: #7b8794;
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

    .party-section { display: grid; gap: 10px; }
    .party-section + .party-section { margin-top: 18px; padding-top: 18px; border-top: 1px solid #e3e8ee; }
    .party-section-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .party-section-head .section-note { flex: 1 1 auto; margin: 0; }
    .party-add-row { flex: 0 0 auto; white-space: nowrap; }
    .party-rows { display: grid; gap: 10px; }
    .party-entry { padding: 12px; border: 1px solid #e1e6ec; border-radius: 8px; background: #fbfcfd; }
    .party-entry-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
    .party-entry-title { color: #374151; font-size: 13px; font-weight: 800; }
    .party-remove-row { min-height: 32px; padding: 5px 10px; }

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

    .barrier-multi-select {
        min-height: 190px;
        padding: 5px;
        border-color: #ccd9d2;
        background: #f9fbfa;
        color: #27313d;
        line-height: 1.35;
    }

    .barrier-multi-select:focus {
        border-color: #72a58e;
        box-shadow: 0 0 0 3px rgba(47, 125, 98, .11);
    }

    .barrier-multi-select option {
        margin: 2px 0;
        padding: 7px 9px;
        border-radius: 4px;
        background: #fff;
        color: #374151;
    }

    .barrier-multi-select option:checked {
        background: #dff2e7 linear-gradient(0deg, #dff2e7 0%, #dff2e7 100%);
        color: #17643a;
        font-weight: 700;
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

    .solution-checklist {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        overflow: hidden;
        border: 1px solid #dfe5eb;
        border-radius: 8px;
        background: #fff;
    }

    .solution-check-row {
        min-width: 0;
        padding: 11px 12px;
        border-bottom: 1px solid #edf0f3;
    }

    .solution-check-row:nth-child(odd) {
        border-right: 1px solid #edf0f3;
    }

    .solution-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 0;
        color: #27313d;
        cursor: pointer;
    }

    .solution-checkbox input {
        width: 18px;
        height: 18px;
        margin-top: 2px;
        accent-color: #2f7d62;
        flex: 0 0 auto;
    }

    .solution-check-copy {
        display: grid;
        gap: 2px;
        min-width: 0;
    }

    .solution-check-copy strong {
        color: #26313b;
        font-size: 13px;
        font-weight: 750;
    }

    .solution-check-copy small {
        color: #6b7280;
        font-size: 12px;
    }

    .solution-check-row:has(.solution-checkbox input:checked) {
        background: #f3faf6;
    }

    .solution-check-row .decision-detail {
        margin-top: 10px;
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

    .follow-up-card-title {
        display: block;
        margin: -2px -2px 12px;
        padding: 8px 10px;
        border-radius: 6px;
        background: #f3f7f5;
        color: #285d49;
        font-size: 14px;
        font-weight: 800;
        line-height: 1.35;
    }

    .follow-up-card-title small {
        display: block;
        margin-top: 2px;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
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
        .solution-checklist {
            grid-template-columns: 1fr;
        }

        .solution-check-row:nth-child(odd) {
            border-right: 0;
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

        .case-title-row {
            margin: 12px 12px 0;
            padding: 10px;
        }

        .case-title-preview {
            font-size: 14px;
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

            <div class="case-title-row">
                <span class="case-title-row-icon"><i class="fas fa-scale-balanced"></i></span>
                <div>
                    <span class="case-title-row-label">Case Title / মামলার পক্ষগণের নাম</span>
                    <div class="case-title-preview is-empty" id="case_title_preview" aria-live="polite">Case parties will appear here</div>
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
                        <span class="case-summary-badge">1</span> কেস চিহ্নিত করা (Case Identification)
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
                            <div class="col-md-6">
                                <label class="field-label" id="compoundable-sections-label">1.3 Compoundable Sections <small>আপসযোগ্য ধারা</small></label>
                                <details class="act-picker" id="compoundable-section-picker">
                                    <summary id="compoundable-section-control" aria-labelledby="compoundable-sections-label compoundable-section-summary"><span id="compoundable-section-summary">Select sections</span></summary>
                                    <div class="act-picker-panel">
                                        <label for="compoundable-section-search" class="visually-hidden">Search compoundable sections</label>
                                        <input type="search" id="compoundable-section-search" class="form-control" placeholder="Search section number" autocomplete="off">
                                        <div class="small text-muted mt-2" id="compoundable-section-results" role="status"></div>
                                        <div class="act-picker-options" role="group" aria-labelledby="compoundable-sections-label">
                                            @foreach (['11(c)', 143, 147, 148, 298, 323, 324, 325, 334, 335, 336, 337, 338, 341, 342, 343, 344, 346, 347, 348, 352, 354, 355, 356, 357, 358, 374, 379, 380, 381, 403, 406, 407, 408, 411, 414, 417, 418, 419, 420, 421, 422, 423, 424, 426, 427, 428, 429, 430, 447, 448, 451, 482, 483, 486, 490, 491, 492, 493, 494, 497, 498, 500, 501, 502, 504, 506, 508, 509, 511] as $sectionNumber)
                                                <label class="act-picker-option compoundable-section-option">
                                                    <input type="checkbox" name="compoundable_sections[]" value="{{ $sectionNumber }}">
                                                    <span>Section {{ $sectionNumber }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </details>
                                <div class="act-selected" id="compoundable-section-selected" aria-label="Selected compoundable sections"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="field-label" for="non_compoundable_section">1.3 Non-Compoundable Section <small>অন্যান্য আপস অযোগ্য ধারা</small></label>
                                <input type="text" id="non_compoundable_section" name="non_compoundable_section" class="form-control" placeholder="Specify non-compoundable section">
                            </div>
                            <div class="col-md-3">
                                <label class="field-label" for="case_section_comments">1.3 Comments <small>মন্তব্য</small></label>
                                <textarea id="case_section_comments" name="case_section_comments" class="form-control" rows="2" placeholder="Add comments, if any"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" id="act-label">1.4 Act <small>আইন</small></label>
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
                                <label class="field-label" for="case_filing_date">1.7 Date of Case Filing <small>মামলা দায়েরের তারিখ</small></label>
                                <input type="date" id="case_filing_date" name="case_filing_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryTwo" aria-expanded="false" aria-controls="summaryTwo">
                        <span class="case-summary-badge">2</span> কেস প্রোফাইল (Case Profile)
                    </button>
                </h2>
                <div id="summaryTwo" class="accordion-collapse collapse" aria-labelledby="summaryHeaderTwo" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label" for="offence_subject">2.2 Description of Offence <small>অপরাধের বিবরণ</small></label>
                                <input type="text" id="offence_subject" name="offence_subject" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="field-label" for="current_case_status">2.3 Current Case Status <small>মামলার বর্তমান অবস্থা</small></label>
                                <select id="current_case_status" name="current_case_status" class="form-select" data-other-target="current_case_status_other_wrap">
                                    <option value="">Select status</option>
                                    <option value="accused_submitted_petition_for_discharge">আসামি অব্যাহতির আবেদন দাখিল করেছে (Accused submitted petition for discharge)</option>
                                    <option value="appeal">আপিল (Appeal)</option>
                                    <option value="appeal_hearing_completed">আপিলের শুনানি সম্পন্ন (Appeal hearing completed)</option>
                                    <option value="appeal_transferred">আপিল স্থানান্তরিত (Appeal transferred)</option>
                                    <option value="argument">যুক্তিতর্ক (Argument)</option>
                                    <option value="case_dismissed">মামলা খারিজ (Case Dismissed)</option>
                                    <option value="case_is_stayed">মামলা স্থগিত (Case is stayed)</option>
                                    <option value="case_withdrawal">মামলা প্রত্যাহার (Case Withdrawal)</option>
                                    <option value="charge_framed">অভিযোগ গঠন (Charge framed)</option>
                                    <option value="charge_not_framed">অভিযোগ গঠন করা হয়নি (Charge not framed)</option>
                                    <option value="cognizance_not_taken_and_ordered">আমলে গ্রহণ করা হয়নি এবং আদেশ প্রদান করা হয়েছে (Cognizance not taken and ordered)</option>
                                    <option value="cognizance_taken_and_issued">আমলে গ্রহণ করা হয়েছে এবং সমন জারি করা হয়েছে (Cognizance taken and issued)</option>
                                    <option value="complainant_change">বাদী পরিবর্তন (Complainant Change)</option>
                                    <option value="court_issued_warrant">আদালত গ্রেপ্তারি পরোয়ানা জারি করেছে (Court issued warrant)</option>
                                    <option value="court_issued_warrant_of_proclamation_and_attachment_wpa">আদালত বিজ্ঞপ্তি ও ক্রোকের পরোয়ানা জারি করেছে (Court issued warrant of Proclamation and Attachment (WP&amp;A))</option>
                                    <option value="court_not_held">আদালত বসেনি (Court not held)</option>
                                    <option value="court_ordered">আদালতের আদেশ (Court ordered)</option>
                                    <option value="cross_examination">জেরা (Cross examination)</option>
                                    <option value="examination_in_chief">জবানবন্দি গ্রহণ (Examination in chief)</option>
                                    <option value="examination_of_accused_342">আসামির পরীক্ষা (৩৪২ ধারা) (Examination of accused (342))</option>
                                    <option value="fir_lodged_by_informant">তথ্যদাতা কর্তৃক এফআইআর দায়ের (FIR Lodged by Informant)</option>
                                    <option value="hazira_given">হাজিরা প্রদান (Hazira given)</option>
                                    <option value="hearing">শুনানি (Hearing)</option>
                                    <option value="judgment">রায় (Judgment)</option>
                                    <option value="naraji_petition">নারাজি আবেদন (Naraji petition)</option>
                                    <option value="order_pending">আদেশ অপেক্ষমাণ (Order pending)</option>
                                    <option value="paper_notification">পত্রিকায় বিজ্ঞপ্তি (Paper Notification)</option>
                                    <option value="recall_witness">সাক্ষী পুনরায় তলব (Recall witness)</option>
                                    <option value="remain">মুলতবি (Remain)</option>
                                    <option value="report">প্রতিবেদন (Report)</option>
                                    <option value="revision">রিভিশন (Revision)</option>
                                    <option value="revision_filed">রিভিশন দাখিল (Revision filed)</option>
                                    <option value="revision_hearing">রিভিশনের শুনানি (Revision hearing)</option>
                                    <option value="service_return">জারি ফেরত (Service Return)</option>
                                    <option value="settling_date_for_ph">পিএইচ-এর তারিখ নির্ধারণ (Settling Date for PH)</option>
                                    <option value="summon_issue">সমন জারি (Summon Issue)</option>
                                    <option value="time_petition_by_accused">আসামির সময়ের আবেদন (Time Petition by Accused)</option>
                                    <option value="time_petition_by_complainant">বাদীর সময়ের আবেদন (Time Petition by Complainant)</option>
                                    <option value="transfer_to_the_court">আদালতে স্থানান্তর (Transfer to the court)</option>
                                    <option value="upload_case_document">মামলার নথি আপলোড (Upload Case Document)</option>
                                    <option value="witness">সাক্ষী (Witness)</option>
                                    <option value="Other">অন্যান্য, উল্লেখ্ করুন (Other, please specify)</option>
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
                            <div class="col-md-4">
                                <label class="field-label" for="accused_status">2.7 Status of Accused <small>আসামির অবস্থা</small></label>
                                <select id="accused_status" name="accused_status" class="form-select" data-other-target="accused_status_other_wrap">
                                    <option value="">Select status</option>
                                    <option value="Custody - Prison">Custody - Prison</option>
                                    <option value="Custody - Court">Custody - Court</option>
                                    <option value="Custody - Fugitive">Custody - Fugitive</option>
                                    <option value="Custody - CDC">Custody - CDC</option>
                                    <option value="On bail">On bail</option>
                                    <option value="Deceased">Deceased</option>
                                    <option value="Other">Others</option>
                                </select>
                            </div>
                            <div class="col-md-8 dependent-field" id="accused_status_other_wrap">
                                <label class="field-label" for="accused_status_other">2.7 Other Status of Accused <small>অন্যান্য অবস্থা, উল্লেখ করুন</small></label>
                                <input type="text" id="accused_status_other" name="accused_status_other" class="form-control" placeholder="Please specify the status of accused" data-required-when-visible>
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="number_of_witnesses">2.8 Number of Witnesses <small>সাক্ষীর সংখ্যা</small></label>
                                <input type="text" id="number_of_witnesses" name="number_of_witnesses" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="field-label" for="witnesses_examined">2.9 Witnesses Examined <small>পরীক্ষিত সাক্ষী</small></label>
                                <input type="text" id="witnesses_examined" name="witnesses_examined" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryThree" aria-expanded="false" aria-controls="summaryThree">
                        <span class="case-summary-badge">3</span> পক্ষগণের প্রাথমিক তথ্য (Basic Information of the Parties)
                    </button>
                </h2>
                <div id="summaryThree" class="accordion-collapse collapse" aria-labelledby="summaryHeaderThree" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        @foreach ([
                            'complainant' => ['3.1', 'Complainant / Plaintiff', 'অভিযোগকারী/বাদীর তথ্য', 'অভিযোগকারী/বাদীর নাম', 'অভিযোগকারী/বাদীর ফোন নম্বর'],
                            'defendant' => ['3.2', 'Accused / Respondent', 'প্রতিপক্ষ/বিবাদীর তথ্য', 'প্রতিপক্ষ/বিবাদীর নাম', 'প্রতিপক্ষ/বিবাদীর ফোন নম্বর'],
                        ] as $prefix => $party)
                            <section class="party-section" data-party-prefix="{{ $prefix }}" data-party-label="{{ $party[1] }}">
                                <div class="party-section-head">
                                    <div class="section-note"><strong>{{ $party[0] }} {{ $party[2] }}</strong> - {{ $party[1] }}</div>
                                    <button type="button" class="btn btn-outline-success btn-sm party-add-row">
                                        <i class="fas fa-plus" aria-hidden="true"></i> Add Row
                                    </button>
                                </div>
                                <div class="party-rows">
                                    <div class="party-entry" data-party-index="0">
                                        <div class="party-entry-head">
                                            <span class="party-entry-title">{{ $party[1] }} 1</span>
                                            <button type="button" class="btn btn-outline-danger btn-sm party-remove-row" hidden>
                                                <i class="fas fa-trash-can" aria-hidden="true"></i> Delete
                                            </button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="field-label" for="{{ $prefix }}_0_name">{{ $party[0] }}.1 Name <small>{{ $party[3] }}</small></label>
                                                <input type="text" id="{{ $prefix }}_0_name" name="{{ $prefix }}[0][name]" class="form-control" data-party-field="name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="field-label" for="{{ $prefix }}_0_sex">{{ $party[0] }}.2 Sex <small>লিঙ্গ</small></label>
                                                <select id="{{ $prefix }}_0_sex" name="{{ $prefix }}[0][sex]" class="form-select" data-party-field="sex">
                                                    <option value="">Select sex</option><option>Male</option><option>Female</option><option>Transgender Person</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="field-label" for="{{ $prefix }}_0_age">{{ $party[0] }}.3 Age <small>বয়স</small></label>
                                                <input type="number" min="0" max="150" id="{{ $prefix }}_0_age" name="{{ $prefix }}[0][age]" class="form-control" data-party-field="age">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="field-label" for="{{ $prefix }}_0_disability">{{ $party[0] }}.4 Disability <small>প্রতিবন্ধিতা</small></label>
                                                <select id="{{ $prefix }}_0_disability" name="{{ $prefix }}[0][disability]" class="form-select" data-party-field="disability">
                                                    <option value="">Select</option><option>Yes</option><option>No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="field-label" for="{{ $prefix }}_0_income">{{ $party[0] }}.5 Approximate Monthly Income <small>আনুমানিক মাসিক আয়</small></label>
                                                <select id="{{ $prefix }}_0_income" name="{{ $prefix }}[0][income]" class="form-select" data-party-field="income">
                                                    <option value="">Select income range</option><option>None</option><option>Up to 5000</option><option>5,001 to 10,000</option><option>10,001 to 25,000</option><option>25,001 to 50,000</option><option>More than 50,000</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="field-label" for="{{ $prefix }}_0_phone">{{ $party[0] }}.6 Phone Number <small>{{ $party[4] }}</small></label>
                                                <input type="text" id="{{ $prefix }}_0_phone" name="{{ $prefix }}[0][phone]" class="form-control" data-party-field="phone">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="field-label" for="{{ $prefix }}_0_representation">{{ $party[0] }}.7 Representation <small>প্রতিনিধিত্ব</small></label>
                                                <select id="{{ $prefix }}_0_representation" name="{{ $prefix }}[0][representation]" class="form-select" data-party-field="representation" data-lawyer-target="{{ $prefix }}_lawyer_fields_0">
                                                    <option value="">Select representation</option><option>Self</option><option>Lawyer</option><option>NGO</option><option>Legal Aid Office</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 dependent-field" data-lawyer-field="{{ $prefix }}_lawyer_fields_0">
                                                <label class="field-label" for="{{ $prefix }}_0_lawyer_name">{{ $party[0] }}.8 Lawyer Name <small>মামলায় নিযুক্ত আইনজীবীর নাম</small></label>
                                                <input type="text" id="{{ $prefix }}_0_lawyer_name" name="{{ $prefix }}[0][lawyer_name]" class="form-control" data-party-field="lawyer_name">
                                            </div>
                                            <div class="col-md-4 dependent-field" data-lawyer-field="{{ $prefix }}_lawyer_fields_0">
                                                <label class="field-label" for="{{ $prefix }}_0_lawyer_phone">{{ $party[0] }}.9 Lawyer Phone <small>মামলায় নিযুক্ত আইনজীবীর ফোন নম্বর</small></label>
                                                <input type="text" id="{{ $prefix }}_0_lawyer_phone" name="{{ $prefix }}[0][lawyer_phone]" class="form-control" data-party-field="lawyer_phone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryFour" aria-expanded="false" aria-controls="summaryFour">
                        <span class="case-summary-badge">4</span> মামলার অগ্রগতির ক্ষেত্রে প্রতিবন্ধকতা (Barriers Preventing Progress)
                    </button>
                </h2>
                <div id="summaryFour" class="accordion-collapse collapse" aria-labelledby="summaryHeaderFour" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="section-guidance">
                            Select Yes only for barriers that affected this case. Details will open automatically when needed.
                        </div>
                        <div class="decision-grid">
                            @foreach ([
                                ['4.1', 'missing_documents', 'Missing Documents', ['Complaint petition', 'FIR', 'Ejahar', 'Forwarding letter', 'Summon / Warrant copy', 'Shown arrest application', 'Chargesheet', 'Final report', 'Inquest report', 'Post Mortem report', 'Medical report', 'Ballistic report', 'Legal Notice', 'AD slip', 'Cheque, Dishonor slip', 'Discharge petition', 'Bail Petition', 'Any other petition. Please specify'], 'নথিপত্রের ঘাটতি', ['Any other petition. Please specify'], true],
                                ['4.2', 'absent_witnesses', 'Absence of Witness', ['Government Witness', 'General Witness'], 'সাক্ষীর অনুপস্থিতি', []],
                                ['4.3', 'procedural_delays', 'Procedural Delays', ['Judge not appointed', 'Judge in training', 'Judge on leave', 'Case transferred', 'Pending in higher court', 'LCR transferred', 'Other (Please specify)', 'Long dates / Delayed scheduling'], 'পদ্ধতিগত বিলম্ব', ['Other (Please specify)']],
                                ['4.4', 'adjournment_by_parties', 'Adjournment by Parties (3+)', [], 'পক্ষগণ কর্তৃক মুলতবি (৩+)'],
                                ['4.5', 'adjournment_by_court', 'Adjournment by Court (3+)', [], 'আদালত কর্তৃক মুলতবি (৩+)'],
                                ['4.6', 'lack_of_lawyer', 'Lack of Lawyer', [], 'আইনজীবীর অনুপস্থিতি'],
                                ['4.7', 'lack_of_prosecutor', 'Lack of Prosecutor', [], 'প্রসিকিউটরের অনুপস্থিতি'],
                                ['4.8', 'settled_outside_court', 'Settled Outside Court and Not Reported', [], 'আদালতের বাইরে নিষ্পত্তি হয়েছে কিন্তু জানানো হয়নি'],
                                ['4.9', 'death_of_accused', 'Death of Accused', [], 'আসামির মৃত্যু'],
                                ['4.10', 'death_of_witness', 'Death of Witness', [], 'সাক্ষীর মৃত্যু'],
                                ['4.11', 'pending_for_argument', 'Pending for Argument', [], 'যুক্তিতর্কের জন্য অপেক্ষমাণ'],
                                ['4.12', 'pending_for_judgment', 'Pending for Judgment', [], 'রায়ের জন্য অপেক্ষমাণ'],
                                ['4.13', 'dismissed_but_pending', 'Case Ordered to Be Dismissed but Still Pending', [], 'খারিজের আদেশ হলেও মামলা এখনও বিচারাধীন'],
                                ['4.14', 'administrative_issues', 'Other Administrative Issues', [], 'অন্যান্য প্রশাসনিক বিষয়'],
                            ] as $barrier)
                                <div class="decision-item">
                                    <div class="decision-head">
                                        <div class="decision-title">
                                            <span class="field-no">{{ $barrier[0] }}</span>
                                            <span class="decision-title-text">
                                                <span>{{ $barrier[2] }}</span>
                                                <small>{{ $barrier[4] }}</small>
                                            </span>
                                        </div>
                                        <div class="decision-control">
                                            <label class="row-control-label" for="{{ $barrier[1] }}_status">Status / অবস্থা</label>
                                            <input type="hidden" name="{{ $barrier[1] }}" value="No">
                                            <label class="decision-check" for="{{ $barrier[1] }}_status">
                                                <span>Yes / হ্যাঁ</span>
                                                <input id="{{ $barrier[1] }}_status" type="checkbox" name="{{ $barrier[1] }}" value="Yes" data-yes-target="{{ $barrier[1] }}_details">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="dependent-field decision-detail" id="{{ $barrier[1] }}_details">
                                        <div class="row g-2">
                                            @if ($barrier[1] === 'absent_witnesses')
                                                <div class="col-md-5">
                                                    <label class="field-label" for="absent_witnesses_type">Witness Type <small>সাক্ষীর ধরন</small></label>
                                                    <select id="absent_witnesses_type" name="absent_witnesses_type" class="form-select" data-required-when-active>
                                                        <option value="">Select witness type</option>
                                                        <option value="Government Witness">Government Witness</option>
                                                        <option value="General Witness">General Witness</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-7 dependent-field" id="government_witness_type_wrap">
                                                    <label class="field-label" for="government_witness_type">Government Witness Type <small>সরকারি সাক্ষীর ধরন</small></label>
                                                    <select id="government_witness_type" name="government_witness_type" class="form-select">
                                                        <option value="">Select type</option>
                                                        <option value="IO">IO</option>
                                                        <option value="MO">MO</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-7 offset-md-5 dependent-field" id="government_witness_other_wrap">
                                                    <label class="field-label" for="government_witness_other">Other Government Witness <small>অন্যান্য সরকারি সাক্ষী উল্লেখ করুন</small></label>
                                                    <input type="text" id="government_witness_other" name="government_witness_other" class="form-control">
                                                </div>
                                                <div class="col-md-7 offset-md-5 dependent-field" id="general_witness_details_wrap">
                                                    <label class="field-label" for="general_witness_details">General Witness <small>সাধারণ সাক্ষী উল্লেখ করুন</small></label>
                                                    <input type="text" id="general_witness_details" name="general_witness_details" class="form-control">
                                                </div>
                                            @elseif (!empty($barrier[3]))
                                                <div class="col-md-5">
                                                    <label class="field-label" for="{{ $barrier[1] }}_type">Type <small>ধরন</small></label>
                                                    <select id="{{ $barrier[1] }}_type" name="{{ $barrier[1] }}_type{{ !empty($barrier[6]) ? '[]' : '' }}" class="form-select {{ !empty($barrier[6]) ? 'barrier-multi-select' : '' }}" data-other-target="{{ $barrier[1] }}_other_wrap" data-other-values="{{ implode('|', $barrier[5] ?? ['Other']) }}" data-required-when-active @if (!empty($barrier[6])) multiple size="7" @endif>
                                                        @if (empty($barrier[6]))
                                                            <option value="">Select type</option>
                                                        @endif
                                                        @foreach ($barrier[3] as $option)
                                                            <option>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-7 dependent-field" id="{{ $barrier[1] }}_other_wrap">
                                                    <label class="field-label" for="{{ $barrier[1] }}_other">Please specify <small>উল্লেখ করুন</small></label>
                                                    <input type="text" id="{{ $barrier[1] }}_other" name="{{ $barrier[1] }}_other" class="form-control" data-required-when-visible>
                                                </div>
                                            @elseif ($barrier[1] === 'administrative_issues')
                                                <div class="col-12">
                                                    <label class="field-label" for="{{ $barrier[1] }}_specify">Administrative issue <small>প্রশাসনিক বিষয় উল্লেখ করুন</small></label>
                                                    <input type="text" id="{{ $barrier[1] }}_specify" name="{{ $barrier[1] }}_specify" class="form-control" placeholder="Please specify the administrative issue" data-required-when-active>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <label class="field-label" for="{{ $barrier[1] }}_remarks">Remarks <small>মন্তব্য</small></label>
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
                        <span class="case-summary-badge">5</span> নিষ্পত্তির প্রস্তাবিত পথ/সমাধান (Proposed Disposal Route / Solutions)
                    </button>
                </h2>
                <div id="summaryFive" class="accordion-collapse collapse" aria-labelledby="summaryHeaderFive" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="section-guidance">
                            Mark the proposed route for disposal or case movement. Use the common remarks field for any relevant note.
                        </div>
                        <div class="solution-checklist">
                            @foreach ([
                                ['5.1', 'priority_hearing', 'Priority Hearing', 'অগ্রাধিকার ভিত্তিক শুনানী'],
                                ['5.2', 'mediation_settlement', 'Referred to Mediation / Settlement', 'মধ্যস্থতা/মীমাংসার জন্য রেফার'],
                                ['5.3', 'diversion_to_probation', 'Diversion to Probation', 'প্রবেশনে ডাইভারশন'],
                                ['5.4', 'withdrawal', 'Withdrawal', 'প্রত্যাহার'],
                                ['5.5', 'administrative_followup', 'Administrative Follow-up', 'প্রশাসনিক ফলোআপ'],
                                ['5.6', 'case_scheduling', 'Scheduling of Cases', 'মামলার সময়সূচি নির্ধারণ'],
                                ['5.7', 'other_solution', 'Other, please specify', 'অন্যান্য, উল্লেখ করুন'],
                            ] as $solution)
                                <div class="solution-check-row">
                                    <input type="hidden" name="{{ $solution[1] }}" value="No">
                                    <label class="solution-checkbox" for="{{ $solution[1] }}_status">
                                        <input id="{{ $solution[1] }}_status" type="checkbox" name="{{ $solution[1] }}" value="Yes" @if ($solution[1] === 'other_solution') data-yes-target="{{ $solution[1] }}_details" @endif>
                                        <span class="solution-check-copy">
                                            <strong>{{ $solution[0] }} {{ $solution[2] }}</strong>
                                            <small>{{ $solution[3] }}</small>
                                        </span>
                                    </label>
                                    @if ($solution[1] === 'other_solution')
                                        <div class="dependent-field decision-detail" id="{{ $solution[1] }}_details">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="field-label" for="{{ $solution[1] }}_specify">Other route <small>অন্যান্য পথ</small></label>
                                                    <input type="text" id="{{ $solution[1] }}_specify" name="{{ $solution[1] }}_specify" class="form-control" placeholder="Other solution, please specify" data-required-when-active>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <label class="field-label" for="solution_remarks">Remarks <small>মন্তব্য</small></label>
                            <textarea id="solution_remarks" name="solution_remarks" class="form-control" rows="3" placeholder="Add remarks, if any"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summarySix" aria-expanded="false" aria-controls="summarySix">
                        <span class="case-summary-badge">6</span> প্যারালিগ্যাল কর্তৃক গৃহীত পদক্ষেপ (Intervention Taken by Paralegal)
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
                                        <input type="date" id="{{ $intervention[1] }}_date" name="{{ $intervention[1] }}_date" class="form-control" @if ($intervention[1] === 'refer_mediation') data-date-target="refer_mediation_destination_wrap" @endif>
                                        @if ($intervention[1] === 'refer_mediation')
                                            <div class="dependent-field mt-2" id="refer_mediation_destination_wrap">
                                                <label class="field-label" for="refer_mediation_destination">Referred to <small>যেখানে রেফার করা হয়েছে</small></label>
                                                <select id="refer_mediation_destination" name="refer_mediation_destination" class="form-select">
                                                    <option value="">Select referral destination</option>
                                                    <option value="District Legal Aid Office">জেলা লিগ্যাল এইড অফিস (District Legal Aid Office)</option>
                                                    <option value="Court Annex Mediator">কোর্ট অ্যানেক্স মিডিয়েটর, উল্লেখ্ করুন (Court Annex Mediator, please specify)</option>
                                                    <option value="NGO">এনজিও, উল্লেখ্ করুন (NGO, please specify)</option>
                                                    <option value="Other">অন্যান্য, উল্লেখ্ করুন (Other, please specify)</option>
                                                </select>
                                                <div class="dependent-field mt-2" id="refer_mediation_destination_details_wrap">
                                                    <label class="field-label" for="refer_mediation_destination_details">Please specify <small>বিস্তারিত উল্লেখ করুন</small></label>
                                                    <input type="text" id="refer_mediation_destination_details" name="refer_mediation_destination_details" class="form-control" placeholder="Mediator, NGO, or other details">
                                                </div>
                                            </div>
                                        @endif
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
                        <span class="case-summary-badge">7</span> জুডিসিয়ারি কর্তৃক গৃহীত পদক্ষেপ (Intervention Taken by Judiciary)
                    </button>
                </h2>
                <div id="summarySeven" class="accordion-collapse collapse" aria-labelledby="summaryHeaderSeven" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label" for="judicial_mediation_destination">7.1 মেডিয়েশনের জন্য রেফার করছেন <small>Refer to Mediation</small></label>
                                <select id="judicial_mediation_destination" name="judicial_mediation_destination" class="form-select">
                                    <option value="">Select referral destination</option>
                                    <option value="District Legal Aid Office">জেলা লিগ্যাল এইড অফিস (District Legal Aid Office)</option>
                                    <option value="Court Annex Mediator">কোর্ট অ্যানেক্স মিডিয়েটর, উল্লেখ্ করুন (Court Annex Mediator, please specify)</option>
                                    <option value="NGO">এনজিও, উল্লেখ্ করুন (NGO, please specify)</option>
                                    <option value="Other">অন্যান্য, উল্লেখ্ করুন (Other, please specify)</option>
                                </select>
                            </div>
                            <div class="col-md-6 dependent-field" id="judicial_mediation_details_wrap">
                                <label class="field-label" for="judicial_mediation_details">7.1 বিস্তারিত উল্লেখ করুন <small>Please specify</small></label>
                                <input type="text" id="judicial_mediation_details" name="judicial_mediation_details" class="form-control" placeholder="Mediator, NGO, or other details">
                            </div>
                            <div class="col-md-6 dependent-field" id="judicial_mediation_date_wrap">
                                <label class="field-label" for="judicial_mediation_date">7.1 Referral Date <small>রেফারের তারিখ</small></label>
                                <input type="date" id="judicial_mediation_date" name="judicial_mediation_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryEight" aria-expanded="false" aria-controls="summaryEight">
                        <span class="case-summary-badge">8</span> মামলা নিষ্পত্তি (Case Disposed)
                    </button>
                </h2>
                <div id="summaryEight" class="accordion-collapse collapse" aria-labelledby="summaryHeaderEight" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="summary-field-card h-100">
                                    <label class="field-label" for="case_disposal_method">8.1 How Disposed <small>যেভাবে মামলা নিষ্পত্তি হয়েছে</small></label>
                                    <select id="case_disposal_method" name="case_disposal_method" class="form-select">
                                        <option value="">Select disposal method</option>
                                        <option value="Acquitted">Acquitted</option>
                                        <option value="Sentence Given">Sentence Given</option>
                                    </select>

                                    <div class="dependent-field mt-2" id="acquittal_reason_wrap">
                                        <label class="field-label" for="acquittal_reason">Acquittal Reason <small>খালাসের কারণ</small></label>
                                        <select id="acquittal_reason" name="acquittal_reason" class="form-select">
                                            <option value="">Select reason</option>
                                            <option value="Lack of Evidence">Lack of Evidence</option>
                                            <option value="Complaint Withdrawn">Complaint Withdrawn</option>
                                            <option value="Deceased">Deceased</option>
                                        </select>
                                    </div>

                                    <div class="dependent-field mt-2" id="punishment_details_wrap">
                                        <label class="field-label" for="punishment_details">Details of Punishment <small>শাস্তির বিস্তারিত</small></label>
                                        <textarea id="punishment_details" name="punishment_details" class="form-control" rows="2" placeholder="Enter punishment details"></textarea>
                                    </div>

                                    <div class="dependent-field mt-2" id="case_disposal_date_wrap">
                                        <label class="field-label" for="case_disposal_date">Disposal Date <small>নিষ্পত্তির তারিখ</small></label>
                                        <input type="date" id="case_disposal_date" name="case_disposal_date" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="decision-item h-100">
                                    <div class="decision-head">
                                        <div class="decision-title">
                                            <span class="field-no">8.2</span>
                                            <span class="decision-title-text">
                                                <span>মেডিয়েশনের মাধ্যমে নিষ্পত্তি</span>
                                                <small>Disposed through Mediation</small>
                                            </span>
                                        </div>
                                        <div class="decision-control">
                                            <label class="row-control-label" for="disposed_through_mediation_status">Disposed / নিষ্পত্তি</label>
                                            <input type="hidden" name="disposed_through_mediation" value="No">
                                            <label class="decision-check" for="disposed_through_mediation_status">
                                                <span>Yes / হ্যাঁ</span>
                                                <input id="disposed_through_mediation_status" type="checkbox" name="disposed_through_mediation" value="Yes" data-yes-target="disposed_through_mediation_details">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="dependent-field decision-detail" id="disposed_through_mediation_details">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label class="field-label" for="mediation_disposal_date">Disposal Date <small>নিষ্পত্তির তারিখ</small></label>
                                                <input type="date" id="mediation_disposal_date" name="mediation_disposal_date" class="form-control" data-required-when-active>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderTen">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryTen" aria-expanded="false" aria-controls="summaryTen">
                        <span class="case-summary-badge">10</span> মামলার অগ্রগতি (Case Progress / Follow-up)
                    </button>
                </h2>
                <div id="summaryTen" class="accordion-collapse collapse" aria-labelledby="summaryHeaderTen" data-bs-parent="#caseSummaryAccordion">
                    <div class="accordion-body">
                        <div class="section-note">Previous follow-up records are preserved in chronological order.</div>
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="summary-field-card h-100">
                                    <label class="follow-up-card-title" for="follow_up_action">অগ্রগতি / গৃহীত পদক্ষেপ <small>Progress / Action Taken</small></label>
                                    <label class="field-label" for="follow_up_date">তারিখ <small>Date</small></label>
                                    <input type="date" id="follow_up_date" name="follow_up_date" class="form-control mb-2">
                                    <textarea id="follow_up_action" name="follow_up_action" class="form-control" rows="2" placeholder="Enter the latest progress or action taken"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="summary-field-card h-100">
                                    <label class="follow-up-card-title" for="interventions_to_be_taken">পদক্ষেপ নিতে হবে <small>Interventions to be Taken</small></label>
                                    <label class="field-label" for="intervention_to_be_taken_date">তারিখ <small>Date</small></label>
                                    <input type="date" id="intervention_to_be_taken_date" name="intervention_to_be_taken_date" class="form-control mb-2">
                                    <textarea id="interventions_to_be_taken" name="interventions_to_be_taken" class="form-control" rows="2" placeholder="Enter the intervention or follow-up action to be taken"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item case-summary-card">
                <h2 class="accordion-header" id="summaryHeaderEvidence">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#summaryEvidence" aria-expanded="false" aria-controls="summaryEvidence">
                        <span class="case-summary-badge">11</span> প্রমাণ/সহায়ক নথি (Evidence Attachments)
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
                const item = document.createElement('div');
                item.className = 'act-selected-item';
                const title = document.createElement('span');
                title.textContent = option.title;
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'act-selected-remove';
                button.innerHTML = '<i class="fas fa-trash-can" aria-hidden="true"></i> Delete';
                button.setAttribute('aria-label', 'Remove ' + option.title);
                button.addEventListener('click', () => {
                    option.checkbox.checked = false;
                    option.checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                    document.getElementById('act').focus();
                });
                item.append(title, button);
                chips.append(item);
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

        const compoundablePicker = document.getElementById('compoundable-section-picker');
        const compoundableSearch = document.getElementById('compoundable-section-search');
        const compoundableOptions = Array.from(compoundablePicker.querySelectorAll('.compoundable-section-option')).map(function(label) {
            return {
                label: label,
                checkbox: label.querySelector('input'),
                title: label.querySelector('span').textContent.trim()
            };
        });

        function filterCompoundableSections() {
            const query = compoundableSearch.value.trim().toLocaleLowerCase();
            let visible = 0;

            compoundableOptions.forEach(function(option) {
                option.label.hidden = query !== '' && !option.title.toLocaleLowerCase().includes(query);
                if (!option.label.hidden) {
                    visible++;
                }
            });

            document.getElementById('compoundable-section-results').textContent = visible
                ? visible + ' sections available'
                : 'No matching sections found.';
        }

        function syncSelectedCompoundableSections() {
            const selected = compoundableOptions.filter(function(option) { return option.checkbox.checked; });
            const summary = document.getElementById('compoundable-section-summary');
            const selectedList = document.getElementById('compoundable-section-selected');

            summary.textContent = selected.length
                ? selected.length + ' section' + (selected.length === 1 ? '' : 's') + ' selected'
                : 'Select sections';
            selectedList.replaceChildren();

            selected.forEach(function(option) {
                const item = document.createElement('div');
                item.className = 'act-selected-item';
                const title = document.createElement('span');
                title.textContent = option.title;
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'act-selected-remove';
                button.innerHTML = '<i class="fas fa-trash-can" aria-hidden="true"></i> Delete';
                button.setAttribute('aria-label', 'Remove ' + option.title);
                button.addEventListener('click', function() {
                    option.checkbox.checked = false;
                    syncSelectedCompoundableSections();
                    document.getElementById('compoundable-section-control').focus();
                });
                item.append(title, button);
                selectedList.append(item);
            });
        }

        compoundableSearch.addEventListener('input', filterCompoundableSections);
        compoundableSearch.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
        compoundablePicker.addEventListener('change', syncSelectedCompoundableSections);
        compoundablePicker.addEventListener('toggle', function() {
            if (compoundablePicker.open) {
                compoundableSearch.focus();
            }
        });
        compoundablePicker.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                compoundablePicker.open = false;
                document.getElementById('compoundable-section-control').focus();
            }
        });
        document.addEventListener('click', function(event) {
            if (!compoundablePicker.contains(event.target)) {
                compoundablePicker.open = false;
            }
        });
        document.getElementById('caseSummaryDemoForm').addEventListener('reset', function() {
            setTimeout(function() {
                compoundableSearch.value = '';
                filterCompoundableSections();
                syncSelectedCompoundableSections();
            }, 0);
        });
        filterCompoundableSections();
        syncSelectedCompoundableSections();

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
            const lastHearingInput = document.getElementById('last_hearing_date');
            const yearsOutput = document.getElementById('pending_years_display');
            const monthsOutput = document.getElementById('pending_months_display');
            const daysOutput = document.getElementById('pending_days_display');

            if (!filingInput || !yearsOutput || !monthsOutput || !daysOutput) {
                return;
            }

            if (!filingInput.value || !lastHearingInput?.value) {
                yearsOutput.textContent = '-';
                monthsOutput.textContent = '-';
                daysOutput.textContent = '-';
                return;
            }

            const startDate = new Date(filingInput.value + 'T00:00:00');
            const endDate = new Date(lastHearingInput.value + 'T00:00:00');

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
        document.getElementById('last_hearing_date')?.addEventListener('change', syncPendingDuration);
        syncPendingDuration();

        function syncCaseTitle() {
            const partyNames = function(prefix) {
                return Array.from(document.querySelectorAll('[data-party-prefix="' + prefix + '"] [data-party-field="name"]'))
                    .map(function(input) { return input.value.trim(); })
                    .filter(Boolean);
            };
            const complainantNames = partyNames('complainant');
            const defendantNames = partyNames('defendant');
            const titlePreview = document.getElementById('case_title_preview');

            if (!titlePreview) {
                return;
            }

            if (!complainantNames.length && !defendantNames.length) {
                titlePreview.textContent = 'Case parties will appear here';
                titlePreview.classList.add('is-empty');
                return;
            }

            const complainantTitle = complainantNames.length
                ? complainantNames[0] + (complainantNames.length > 1 ? ' and others' : '')
                : 'Complainant';
            const defendantTitle = defendantNames.length
                ? defendantNames[0] + (defendantNames.length > 1 ? ' and others' : '')
                : 'Defendant';

            titlePreview.textContent = complainantTitle + ' vs ' + defendantTitle;
            titlePreview.classList.remove('is-empty');
        }

        function refreshPartyRowTitles(section) {
            const label = section.dataset.partyLabel;
            section.querySelectorAll('.party-entry').forEach(function(row, index) {
                const title = row.querySelector('.party-entry-title');
                const removeButton = row.querySelector('.party-remove-row');

                if (title) {
                    title.textContent = label + ' ' + (index + 1);
                }

                if (removeButton) {
                    removeButton.hidden = index === 0;
                }
            });
        }

        function preparePartyRow(section, row, index) {
            const prefix = section.dataset.partyPrefix;
            row.dataset.partyIndex = index;

            row.querySelectorAll('[data-party-field]').forEach(function(field) {
                const fieldName = field.dataset.partyField;
                field.id = prefix + '_' + index + '_' + fieldName;
                field.name = prefix + '[' + index + '][' + fieldName + ']';
                field.value = '';

                const label = row.querySelector('label[for$="_' + fieldName + '"]');
                if (label) {
                    label.htmlFor = field.id;
                }
            });

            const representation = row.querySelector('[data-party-field="representation"]');
            const lawyerTarget = prefix + '_lawyer_fields_' + index;
            if (representation) {
                representation.dataset.lawyerTarget = lawyerTarget;
                representation.addEventListener('change', function() {
                    syncLawyerFields(representation);
                });
            }

            row.querySelectorAll('[data-lawyer-field]').forEach(function(field) {
                field.dataset.lawyerField = lawyerTarget;
                field.style.display = 'none';
            });

            row.querySelector('[data-party-field="name"]')?.addEventListener('input', syncCaseTitle);
            row.querySelector('.party-remove-row')?.addEventListener('click', function() {
                row.remove();
                refreshPartyRowTitles(section);
                syncCaseTitle();
            });
        }

        document.querySelectorAll('.party-section').forEach(function(section) {
            const rows = section.querySelector('.party-rows');
            const firstRow = rows?.querySelector('.party-entry');

            if (!rows || !firstRow) {
                return;
            }

            section.dataset.nextIndex = '1';
            firstRow.querySelector('[data-party-field="name"]')?.addEventListener('input', syncCaseTitle);

            section.querySelector('.party-add-row')?.addEventListener('click', function() {
                const index = Number(section.dataset.nextIndex || 1);
                const row = firstRow.cloneNode(true);
                preparePartyRow(section, row, index);
                rows.appendChild(row);
                section.dataset.nextIndex = String(index + 1);
                refreshPartyRowTitles(section);
            });

            refreshPartyRowTitles(section);
        });

        syncCaseTitle();

        function syncMediationReferral() {
            const dateInput = document.getElementById('refer_mediation_date');
            const destinationWrap = document.getElementById('refer_mediation_destination_wrap');
            const destination = document.getElementById('refer_mediation_destination');
            const detailsWrap = document.getElementById('refer_mediation_destination_details_wrap');
            const details = document.getElementById('refer_mediation_destination_details');

            if (!dateInput || !destinationWrap || !destination || !detailsWrap || !details) {
                return;
            }

            const hasDate = Boolean(dateInput.value);
            const requiresDetails = hasDate && ['Court Annex Mediator', 'NGO', 'Other'].includes(destination.value);

            destinationWrap.style.display = hasDate ? 'block' : 'none';
            destination.required = hasDate;
            detailsWrap.style.display = requiresDetails ? 'block' : 'none';
            details.required = requiresDetails;
        }

        document.getElementById('refer_mediation_date')?.addEventListener('change', syncMediationReferral);
        document.getElementById('refer_mediation_destination')?.addEventListener('change', syncMediationReferral);
        syncMediationReferral();

        function syncJudicialMediationReferral() {
            const destination = document.getElementById('judicial_mediation_destination');
            const detailsWrap = document.getElementById('judicial_mediation_details_wrap');
            const details = document.getElementById('judicial_mediation_details');
            const dateWrap = document.getElementById('judicial_mediation_date_wrap');
            const date = document.getElementById('judicial_mediation_date');

            if (!destination || !detailsWrap || !details || !dateWrap || !date) {
                return;
            }

            const hasDestination = Boolean(destination.value);
            const requiresDetails = ['Court Annex Mediator', 'NGO', 'Other'].includes(destination.value);
            detailsWrap.style.display = requiresDetails ? 'block' : 'none';
            details.required = requiresDetails;
            dateWrap.style.display = hasDestination ? 'block' : 'none';
            date.required = hasDestination;
        }

        document.getElementById('judicial_mediation_destination')?.addEventListener('change', syncJudicialMediationReferral);
        syncJudicialMediationReferral();

        function syncCaseDisposal() {
            const method = document.getElementById('case_disposal_method');
            const acquittalWrap = document.getElementById('acquittal_reason_wrap');
            const acquittalReason = document.getElementById('acquittal_reason');
            const punishmentWrap = document.getElementById('punishment_details_wrap');
            const punishmentDetails = document.getElementById('punishment_details');
            const dateWrap = document.getElementById('case_disposal_date_wrap');
            const disposalDate = document.getElementById('case_disposal_date');

            if (!method || !acquittalWrap || !acquittalReason || !punishmentWrap || !punishmentDetails || !dateWrap || !disposalDate) {
                return;
            }

            const isAcquitted = method.value === 'Acquitted';
            const isSentenced = method.value === 'Sentence Given';
            const hasMethod = isAcquitted || isSentenced;

            acquittalWrap.style.display = isAcquitted ? 'block' : 'none';
            acquittalReason.required = isAcquitted;
            punishmentWrap.style.display = isSentenced ? 'block' : 'none';
            punishmentDetails.required = isSentenced;
            dateWrap.style.display = hasMethod ? 'block' : 'none';
            disposalDate.required = hasMethod;
        }

        document.getElementById('case_disposal_method')?.addEventListener('change', syncCaseDisposal);
        syncCaseDisposal();

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
            if (target.id === 'absent_witnesses_details') {
                syncAbsentWitnessFields();
            }
        }

        function syncOtherField(select) {
            const target = document.getElementById(select.dataset.otherTarget);

            if (!target) {
                return;
            }

            const decisionItem = select.closest('.decision-item');
            const specifyValues = (select.dataset.otherValues || 'Other').split('|');
            const hasOther = select.multiple
                ? Array.from(select.selectedOptions).some(function(option) { return specifyValues.includes(option.value); })
                : specifyValues.includes(select.value);
            const isVisible = !select.disabled && hasOther && (!decisionItem || decisionItem.classList.contains('is-active'));

            target.style.display = isVisible ? 'block' : 'none';
            target.querySelectorAll('[data-required-when-visible]').forEach(function(field) {
                field.required = isVisible;
            });
        }

        function syncAbsentWitnessFields() {
            const witnessType = document.getElementById('absent_witnesses_type');
            const governmentType = document.getElementById('government_witness_type');
            const governmentWrap = document.getElementById('government_witness_type_wrap');
            const governmentOtherWrap = document.getElementById('government_witness_other_wrap');
            const generalWrap = document.getElementById('general_witness_details_wrap');

            if (!witnessType || !governmentType || !governmentWrap || !governmentOtherWrap || !generalWrap) {
                return;
            }

            const isActive = witnessType.closest('.decision-item')?.classList.contains('is-active');
            const showGovernment = isActive && witnessType.value === 'Government Witness';
            const showGovernmentOther = showGovernment && governmentType.value === 'Others';
            const showGeneral = isActive && witnessType.value === 'General Witness';

            governmentWrap.style.display = showGovernment ? 'block' : 'none';
            governmentOtherWrap.style.display = showGovernmentOther ? 'block' : 'none';
            generalWrap.style.display = showGeneral ? 'block' : 'none';

            governmentType.required = showGovernment;
            document.getElementById('government_witness_other').required = showGovernmentOther;
            document.getElementById('general_witness_details').required = showGeneral;
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

        document.getElementById('absent_witnesses_type')?.addEventListener('change', syncAbsentWitnessFields);
        document.getElementById('government_witness_type')?.addEventListener('change', syncAbsentWitnessFields);
        syncAbsentWitnessFields();

        document.getElementById('caseSummaryDemoReset')?.addEventListener('click', function() {
            document.getElementById('caseSummaryDemoForm')?.reset();
            document.querySelectorAll('.party-section').forEach(function(section) {
                const rows = Array.from(section.querySelectorAll('.party-entry'));
                rows.slice(1).forEach(function(row) { row.remove(); });
                section.dataset.nextIndex = '1';
                refreshPartyRowTitles(section);
            });
            filterCourtsByDistrict();
            syncPendingDuration();
            syncCaseTitle();
            syncMediationReferral();
            syncJudicialMediationReferral();
            syncCaseDisposal();

            document.querySelectorAll('[data-yes-target]').forEach(syncYesField);
            document.querySelectorAll('[data-other-target]').forEach(syncOtherField);
            document.querySelectorAll('[data-lawyer-target]').forEach(syncLawyerFields);
            syncAbsentWitnessFields();
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
