@extends('dashboard.layouts.admin-layout')

@section('title', 'Paralegal Aid Clinic')

@push('styles')
<style>
    .plc-page { display:grid; width:100%; max-width:100%; gap:14px; min-width:0; padding-bottom:34px; color:#1f2937; overflow:hidden; }
    .plc-page * { box-sizing:border-box; }
    .plc-page form,.plc-page .accordion,.plc-page .accordion-item,.plc-page .accordion-body { min-width:0; max-width:100%; }
    .plc-hero { padding:16px 19px; border:1px solid #e1e5ea; border-left:4px solid #c30f08; border-radius:8px; background:#fff; box-shadow:0 1px 2px rgba(16,24,40,.05); }
    .plc-hero h1 { margin:0 0 4px; color:#111827; font-size:23px; font-weight:800; }
    .plc-hero p { margin:0; color:#6b7280; font-size:13px; font-weight:600; }
    .plc-panel,.plc-section { border:1px solid #e0e5eb; border-radius:8px; background:#fff; box-shadow:0 2px 10px rgba(16,24,40,.05); overflow:hidden; }
    .plc-panel-head { display:flex; align-items:center; gap:10px; padding:10px 15px; border-bottom:1px solid #ebeef2; background:linear-gradient(90deg,#fff7f6 0%,#fff 65%); }
    .plc-panel-icon { display:inline-flex; width:32px; height:32px; align-items:center; justify-content:center; border-radius:7px; background:#c30f08; color:#fff; flex:0 0 auto; }
    .plc-panel-head h2 { margin:0; font-size:15px; font-weight:800; }
    .plc-panel-head p { margin:1px 0 0; color:#6b7280; font-size:11px; font-weight:600; }
    .plc-panel-body { padding:14px 15px 16px; }
    .plc-location { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; margin-bottom:12px; }
    .plc-choice { position:relative; margin:0; cursor:pointer; }
    .plc-choice input { position:absolute; width:1px; height:1px; opacity:0; }
    .plc-choice > span { display:flex; min-height:42px; align-items:center; justify-content:center; gap:8px; padding:7px 11px; border:1px solid #d8dee6; border-radius:7px; background:#f8fafc; color:#4b5563; font-size:13px; font-weight:800; text-align:center; }
    .plc-choice .plc-choice-text { display:grid; gap:1px; }
    .plc-choice .plc-choice-text small { color:#6b7280; font-size:11px; font-weight:600; }
    .plc-choice > span:before { content:""; width:12px; height:12px; border:2px solid #aeb7c2; border-radius:50%; background:#fff; box-shadow:inset 0 0 0 2px #fff; flex:0 0 auto; }
    .plc-choice input:checked + span { border-color:#d89d98; background:#fff7f6; color:#9d0c06; box-shadow:0 0 0 2px rgba(195,15,8,.07); }
    .plc-choice input:checked + span:before { border-color:#c30f08; background:#c30f08; }
    .plc-details-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; }
    .plc-field { min-width:0; }
    .plc-field.span-2 { grid-column:span 2; }
    .plc-label { display:block; margin-bottom:5px; color:#374151; font-size:12px; font-weight:800; }
    .plc-label small { display:block; margin-top:1px; color:#6b7280; font-size:11px; font-weight:600; }
    .plc-page .form-control,.plc-page .form-select { min-height:40px; border:1px solid #cfd6df; border-radius:6px; padding:8px 10px; font-size:13px; }
    .plc-page .form-control:focus,.plc-page .form-select:focus { border-color:#c30f08; box-shadow:0 0 0 3px rgba(195,15,8,.11); }
    .plc-section .accordion-button { gap:10px; min-width:0; padding:13px 16px; background:#fff; color:#1f2937; font-size:14px; font-weight:800; line-height:1.35; white-space:normal; box-shadow:none; }
    .plc-section .accordion-button::after { flex:0 0 auto; }
    .plc-section-title { display:grid; gap:1px; min-width:0; }
    .plc-section-title small { color:#6b7280; font-size:11px; font-weight:600; }
    .plc-section .accordion-button:not(.collapsed) .plc-section-title small { color:#8f4b47; }
    .plc-section .accordion-button:hover,.plc-section .accordion-button:focus { color:#c30f08; background:#fff8f7; box-shadow:none; }
    .plc-section .accordion-button:not(.collapsed) { color:#c30f08; background:#fdf3f2; border-bottom:1px solid #f0d2cf; }
    .plc-section .accordion-collapse.show .accordion-body { background:#fffbfa; box-shadow:inset 3px 0 0 #edc5c2; }
    .plc-section .accordion-body { padding:15px; }
    .plc-badge { display:inline-flex; min-width:36px; padding:3px 8px; justify-content:center; border:1px solid #f0d2cf; border-radius:999px; background:#fff; color:#9d0c06; font-size:11px; font-weight:800; }
    .plc-subhead { display:flex; align-items:center; justify-content:space-between; gap:10px; margin:0 0 10px; color:#263238; font-size:13px; font-weight:800; }
    .plc-subhead > span { display:grid; gap:1px; }
    .plc-subhead small { color:#6b7280; font-weight:600; }
    .plc-table-wrap { width:100%; max-width:100%; overflow-x:auto; overscroll-behavior-inline:contain; -webkit-overflow-scrolling:touch; border:1px solid #e2e7ec; border-radius:7px; background:#fff; scrollbar-width:thin; scrollbar-color:#c8cfd7 #f3f4f6; }
    .plc-table-wrap::-webkit-scrollbar { height:7px; }
    .plc-table-wrap::-webkit-scrollbar-track { background:#f3f4f6; }
    .plc-table-wrap::-webkit-scrollbar-thumb { border-radius:8px; background:#c8cfd7; }
    .plc-table { min-width:760px; margin:0; font-size:12px; }
    .plc-table th { padding:8px; vertical-align:middle; background:#f8fafc; color:#374151; text-align:center; white-space:normal; }
    .plc-table th small { display:block; margin-top:2px; color:#6b7280; font-size:10px; font-weight:600; line-height:1.25; }
    .plc-table td { padding:7px; vertical-align:middle; }
    .plc-table tbody th { position:sticky; left:0; z-index:1; min-width:170px; background:#fff; color:#374151; }
    .plc-table tbody tr:nth-child(even) th { background:#fbfcfd; }
    .plc-table .form-control,.plc-table .form-select { min-height:36px; padding:6px 8px; }
    .plc-block + .plc-block { margin-top:16px; }
    .plc-request-list { display:grid; gap:10px; }
    .plc-request-row { position:relative; width:100%; min-width:0; padding:12px; border:1px solid #e0e6ed; border-radius:8px; background:#fff; }
    .plc-request-head { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px; }
    .plc-request-no { color:#9d0c06; font-size:12px; font-weight:800; }
    .plc-request-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; }
    .plc-checks { display:flex; flex-wrap:wrap; gap:7px 12px; min-height:40px; align-items:center; padding:7px 9px; border:1px solid #d8dee6; border-radius:6px; background:#fafbfc; }
    .plc-checks label { display:inline-flex; align-items:center; gap:5px; margin:0; color:#4b5563; font-size:12px; font-weight:700; }
    .plc-checks input { width:16px; height:16px; accent-color:#c30f08; }
    .plc-prisoner-only,.plc-seeker-only { display:none; }
    .plc-actions { display:flex; justify-content:flex-end; gap:9px; }
    .plc-submit { position:sticky; bottom:0; z-index:3; padding-top:10px; background:linear-gradient(rgba(245,246,248,0),#f5f6f8 38%); }
    .plc-submit .btn { font-weight:700; }
    .plc-empty-note { margin:0 0 10px; color:#6b7280; font-size:12px; font-weight:600; }

    @media (max-width: 992px) {
        .plc-details-grid,.plc-request-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media (max-width: 768px) {
        .plc-hero { padding:15px 16px; }
        .plc-panel-head { padding:10px 13px; }
        .plc-section .accordion-button { padding:12px 13px; }
        .plc-table { min-width:700px; }
        .plc-table th,.plc-table td { padding:6px; }
        .plc-table tbody th { min-width:155px; }
        .plc-request-row { padding:10px; }
    }
    @media (max-width: 576px) {
        .plc-page { gap:11px; }
        .plc-hero { padding:14px; }
        .plc-hero h1 { font-size:20px; }
        .plc-hero p { font-size:12px; }
        .plc-panel-head h2 { font-size:14px; }
        .plc-panel-icon { width:30px; height:30px; }
        .plc-panel-body,.plc-section .accordion-body { padding:12px; }
        .plc-details-grid,.plc-request-grid { grid-template-columns:1fr; gap:9px; }
        .plc-field.span-2 { grid-column:auto; }
        .plc-location { gap:6px; }
        .plc-choice > span { min-height:44px; padding:6px; font-size:12px; line-height:1.2; }
        .plc-page .form-control,.plc-page .form-select { min-height:42px; font-size:16px; }
        .plc-table .form-control,.plc-table .form-select { min-height:38px; min-width:76px; padding:5px 7px; font-size:16px; }
        .plc-table { min-width:670px; }
        .plc-table tbody th { min-width:145px; }
        .plc-subhead { align-items:flex-start; flex-direction:column; gap:2px; }
        .plc-request-head { margin-bottom:8px; }
        .plc-request-head .btn { width:38px; height:38px; padding:0; }
        .plc-checks { min-height:42px; }
        #addPlcRequest { width:100%; min-height:42px; }
        .plc-actions { display:grid; grid-template-columns:1fr 1fr; }
        .plc-submit .btn { width:100%; min-height:42px; }
    }
    @media (max-width: 360px) {
        .plc-location,.plc-actions { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<section class="plc-page">
    <div class="plc-hero">
        <h1>Paralegal Aid Clinic</h1>
        <p>প্যারালিগ্যাল এইড ক্লিনিক</p>
    </div>

    <form id="plcForm" action="javascript:void(0);">
        @php
            $districtNamesBn = [
                'Barishal' => 'বরিশাল',
                'Khulna' => 'খুলনা',
                'Narsingdi' => 'নরসিংদী',
                'Cumilla' => 'কুমিল্লা',
                'Rangpur' => 'রংপুর',
                'Moulvibazar' => 'মৌলভীবাজার',
                'Dhaka' => 'ঢাকা',
            ];
        @endphp
        <div class="plc-panel mb-3">
            <div class="plc-panel-head">
                <span class="plc-panel-icon"><i class="fas fa-scale-balanced"></i></span>
                <div><h2>Clinic Details</h2><p>প্যারালিগ্যাল এইড ক্লিনিকের প্রাথমিক তথ্য</p></div>
            </div>
            <div class="plc-panel-body">
                <div class="plc-location" role="radiogroup" aria-label="Clinic location">
                    <label class="plc-choice"><input type="radio" name="clinic_location" value="Inside Prison" checked><span><span class="plc-choice-text">Inside Prison<small>কারাগারের অভ্যন্তরে</small></span></span></label>
                    <label class="plc-choice"><input type="radio" name="clinic_location" value="Outside Prison"><span><span class="plc-choice-text">Outside Prison<small>কারাগারের বাহিরে</small></span></span></label>
                </div>
                <div class="plc-details-grid">
                    <div class="plc-field"><label class="plc-label" for="clinic_number">Clinic Number <small>প্যারালিগ্যাল এইড ক্লিনিক নম্বর</small></label><input id="clinic_number" class="form-control" value="Auto-generated / স্বয়ংক্রিয়ভাবে তৈরি হবে" readonly></div>
                    <div class="plc-field"><label class="plc-label" for="plc_district">District <small>জেলা</small></label><select id="plc_district" name="district_id" class="form-select"><option value="">Select district / জেলা নির্বাচন করুন</option>@foreach($districts as $district)<option value="{{ $district->id }}">{{ $district->name }}@if(isset($districtNamesBn[$district->name])) / {{ $districtNamesBn[$district->name] }}@endif</option>@endforeach</select></div>
                    <div class="plc-field"><label class="plc-label" for="prison_name">Prison Name <small>কারাগারের নাম</small></label><input id="prison_name" name="prison_name" class="form-control"></div>
                    <div class="plc-field"><label class="plc-label" for="clinic_date">Date <small>তারিখ</small></label><input type="date" id="clinic_date" name="clinic_date" class="form-control" value="{{ now()->format('Y-m-d') }}"></div>
                    <div class="plc-field span-2"><label class="plc-label" for="facilitator_name">Facilitator Name <small>ফ্যাসিলিটেটরের নাম</small></label><input id="facilitator_name" name="facilitator_name" class="form-control"></div>
                    <div class="plc-field span-2"><label class="plc-label" for="activities">Activities <small>কার্যক্রম</small></label><input id="activities" name="activities" class="form-control"></div>
                </div>
            </div>
        </div>

        <div class="accordion" id="plcAccordion">
            <div class="accordion-item plc-section">
                <h2 class="accordion-header" id="plcHeadingOne"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#plcOne" aria-expanded="true"><span class="plc-badge">1</span><span class="plc-section-title">Participants in the Paralegal Aid Clinic<small>প্যারালিগ্যাল এইড ক্লিনিকে অংশগ্রহণকারী</small></span></button></h2>
                <div id="plcOne" class="accordion-collapse collapse show" data-bs-parent="#plcAccordion">
                    <div class="accordion-body">
                        <div class="plc-block" id="insideParticipants">
                            <div class="plc-subhead"><span>Inside Prison <small>কারাগারের অভ্যন্তরে</small></span><small>New and old prisoners / নতুন ও পুরাতন কারাবন্দী</small></div>
                            <div class="plc-table-wrap" tabindex="0" role="region" aria-label="Inside prison participant statistics"><table class="table table-bordered plc-table"><thead><tr><th rowspan="2">Classification of Participants<small>অংশগ্রহণকারীর শ্রেণিবিভাগ</small></th><th colspan="3">Number of New Prisoners<small>নতুন বন্দীর সংখ্যা</small></th><th colspan="3">Number of Old Prisoners<small>পুরাতন বন্দীর সংখ্যা</small></th></tr><tr><th>Male<small>পুরুষ</small></th><th>Female<small>নারী</small></th><th>Transgender Person<small>ট্রান্সজেন্ডার পার্সন</small></th><th>Male<small>পুরুষ</small></th><th>Female<small>নারী</small></th><th>Transgender Person<small>ট্রান্সজেন্ডার পার্সন</small></th></tr></thead><tbody>
                                @foreach(['overall'=>['Overall Total','সর্বমোট'],'under_18'=>['Under 18','১৮ বছরের কম বয়সী'],'disability'=>['Disability','প্রতিবন্ধিতা'],'foreign_national'=>['Foreign Nationals','বিদেশি নাগরিক'],'under_trial'=>['Under Trial Prisoner','বিচারাধীন কারাবন্দী'],'convicted'=>['Convicted Prisoner','সাজাপ্রাপ্ত কারাবন্দী']] as $key=>$label)
                                    <tr><th>{{ $label[0] }}<small>{{ $label[1] }}</small></th>@foreach(['new_male','new_female','new_transgender','old_male','old_female','old_transgender'] as $column)<td><input type="number" min="0" name="inside[{{ $key }}][{{ $column }}]" class="form-control" inputmode="numeric"></td>@endforeach</tr>
                                @endforeach
                            </tbody></table></div>
                        </div>
                        <div class="plc-block" id="outsideParticipants" hidden>
                            <div class="plc-subhead"><span>Outside Prison <small>কারাগারের বাহিরে</small></span><small>Justice seekers and other participants / বিচারপ্রার্থী ও অন্যান্য অংশগ্রহণকারী</small></div>
                            <div class="plc-table-wrap" tabindex="0" role="region" aria-label="Outside prison participant statistics"><table class="table table-bordered plc-table"><thead><tr><th>Classification of Participants<small>অংশগ্রহণকারীর শ্রেণিবিভাগ</small></th><th>Male<small>পুরুষ</small></th><th>Female<small>নারী</small></th><th>Transgender Person<small>ট্রান্সজেন্ডার পার্সন</small></th></tr></thead><tbody>
                                @foreach(['overall'=>['Overall Total','সর্বমোট'],'under_18'=>['Under 18','১৮ বছরের কম বয়সী'],'disability'=>['Disability','প্রতিবন্ধিতা']] as $key=>$label)
                                    <tr><th>{{ $label[0] }}<small>{{ $label[1] }}</small></th>@foreach(['male','female','transgender'] as $column)<td><input type="number" min="0" name="outside[{{ $key }}][{{ $column }}]" class="form-control" inputmode="numeric"></td>@endforeach</tr>
                                @endforeach
                            </tbody></table></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item plc-section" id="plcKnowledgeSection">
                <h2 class="accordion-header" id="plcHeadingTwo"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#plcTwo" aria-expanded="false"><span class="plc-badge">2</span><span class="plc-section-title">Prisoners' Knowledge Regarding Legal Issues<small>আইনি বিষয়সমূহ সম্পর্কে কারাবন্দীদের ধারণা</small></span></button></h2>
                <div id="plcTwo" class="accordion-collapse collapse" data-bs-parent="#plcAccordion">
                    <div class="accordion-body">
                        <div class="plc-table-wrap" tabindex="0" role="region" aria-label="Prisoners legal knowledge assessment"><table class="table table-bordered plc-table"><thead><tr><th>Topics<small>বিষয়সমূহ</small></th><th>Pre-knowledge of Prisoners<small>প্যারালিগ্যাল এইড ক্লিনিকে অংশগ্রহণের পূর্ববর্তী ধারণা</small></th><th>Post-knowledge of Prisoners<small>প্যারালিগ্যাল এইড ক্লিনিকে অংশগ্রহণের পরবর্তী ধারণা</small></th></tr></thead><tbody>
                            @foreach([['Paralegal Aid Services','প্যারালিগ্যাল এইড সার্ভিসেস'],['Bail','জামিন'],['Steps of Criminal Cases','ফৌজদারি মামলার ধাপসমূহ'],['Type of Offences','অপরাধের ধরন'],['Government Legal Aid','সরকারি লিগ্যাল এইড'],['Court Structures','আদালতের কাঠামো'],['Court Etiquette','আদালতের শিষ্টাচার'],['Compounding','আপস-মীমাংসা']] as $index=>$topic)
                                <tr><th>{{ $topic[0] }}<small>{{ $topic[1] }}</small></th><td><input type="number" min="0" name="knowledge[{{ $index }}][before]" class="form-control" inputmode="numeric"></td><td><input type="number" min="0" name="knowledge[{{ $index }}][after]" class="form-control" inputmode="numeric"></td></tr>
                            @endforeach
                        </tbody></table></div>
                    </div>
                </div>
            </div>

            <div class="accordion-item plc-section">
                <h2 class="accordion-header" id="plcHeadingThree"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#plcThree" aria-expanded="false"><span class="plc-badge">3</span><span class="plc-section-title">Legal Assistance Requested<small>আইনগত সহায়তার জন্য অনুরোধ</small></span></button></h2>
                <div id="plcThree" class="accordion-collapse collapse" data-bs-parent="#plcAccordion">
                    <div class="accordion-body">
                        <p class="plc-empty-note">Add one entry for each prisoner or justice seeker requesting legal assistance.<br>আইনগত সহায়তার জন্য অনুরোধকারী প্রত্যেক কারাবন্দী বা বিচারপ্রার্থীর তথ্য আলাদাভাবে যোগ করুন।</p>
                        <div class="plc-request-list" id="plcRequestList"></div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-3" id="addPlcRequest"><i class="fas fa-plus"></i> Add Row / নতুন সারি যোগ করুন</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="plc-actions plc-submit">
            <button type="button" class="btn btn-outline-secondary" id="resetPlc"><i class="fas fa-rotate-left"></i> Reset / রিসেট</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Submit PLC / জমা দিন</button>
        </div>
    </form>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('plcForm');
    const requestList = document.getElementById('plcRequestList');
    let requestIndex = 0;

    function syncLocation() {
        const location = form.querySelector('[name="clinic_location"]:checked')?.value;
        document.getElementById('insideParticipants').hidden = location !== 'Inside Prison';
        document.getElementById('outsideParticipants').hidden = location !== 'Outside Prison';
        document.getElementById('plcKnowledgeSection').hidden = location !== 'Inside Prison';
        requestList.querySelectorAll('.plc-request-row').forEach(row => syncRequesterTypeForLocation(row, location));
    }

    function syncRequesterTypeForLocation(row, location) {
        const select = row.querySelector('.requester-type');
        const isInside = location === 'Inside Prison';
        const value = isInside ? 'Prisoner' : 'Justice Seeker';
        const label = isInside ? 'Prisoner / কারাবন্দী' : 'Justice Seeker / বিচারপ্রার্থী';

        select.innerHTML = '';
        select.add(new Option(label, value, true, true));
        syncRequester(row);
    }

    function syncRequester(row) {
        const type = row.querySelector('.requester-type').value;
        row.querySelectorAll('.plc-prisoner-only').forEach(el => el.style.display = type === 'Prisoner' ? 'block' : 'none');
        row.querySelectorAll('.plc-seeker-only').forEach(el => el.style.display = type === 'Justice Seeker' ? 'block' : 'none');
    }

    function renumberRequests() {
        requestList.querySelectorAll('.plc-request-row').forEach((row, index) => {
            row.querySelector('.plc-request-no').textContent = 'Entry ' + (index + 1) + ' / তথ্য ' + (index + 1);
            row.querySelector('.remove-request').disabled = requestList.children.length === 1;
        });
    }

    function addRequest() {
        const index = requestIndex++;
        const row = document.createElement('div');
        row.className = 'plc-request-row';
        row.innerHTML = `
            <div class="plc-request-head"><span class="plc-request-no"></span><button type="button" class="btn btn-outline-danger btn-sm remove-request" title="Remove row / সারি মুছুন" aria-label="Remove row / সারি মুছুন"><i class="fas fa-trash"></i></button></div>
            <div class="plc-request-grid">
                <div class="plc-field"><label class="plc-label">Requester Type <small>সহায়তা প্রার্থীর ধরন</small></label><select name="requests[${index}][requester_type]" class="form-select requester-type"></select></div>
                <div class="plc-field"><label class="plc-label">Name <small>নাম</small></label><input name="requests[${index}][name]" class="form-control"></div>
                <div class="plc-field plc-prisoner-only"><label class="plc-label">Prison Register Number <small>কারাবন্দীর রেজিস্টার নম্বর</small></label><input name="requests[${index}][prison_register_no]" class="form-control"></div>
                <div class="plc-field plc-seeker-only"><label class="plc-label">Mobile Number <small>মোবাইল নম্বর</small></label><input name="requests[${index}][mobile]" class="form-control" inputmode="tel"></div>
                <div class="plc-field"><label class="plc-label">Sex <small>লিঙ্গ</small></label><select name="requests[${index}][sex]" class="form-select"><option value="">Select / নির্বাচন করুন</option><option value="Male">Male / পুরুষ</option><option value="Female">Female / নারী</option><option value="Transgender Person">Transgender Person / ট্রান্সজেন্ডার পার্সন</option></select></div>
                <div class="plc-field"><label class="plc-label">Other Information <small>অন্যান্য তথ্য</small></label><div class="plc-checks"><label><input type="checkbox" name="requests[${index}][under_18]" value="1"> Under 18 / ১৮ বছরের কম বয়সী</label><label><input type="checkbox" name="requests[${index}][disability]" value="1"> Disability / প্রতিবন্ধিতা</label></div></div>
                <div class="plc-field plc-prisoner-only"><label class="plc-label">Prisoner Status <small>কারাবন্দীর বর্তমান অবস্থা</small></label><select name="requests[${index}][prisoner_status]" class="form-select"><option value="">Select / নির্বাচন করুন</option><option value="Under Trial Prisoner">Under Trial Prisoner / বিচারাধীন কারাবন্দী</option><option value="Convicted Prisoner">Convicted Prisoner / সাজাপ্রাপ্ত কারাবন্দী</option></select></div>
                <div class="plc-field span-2"><label class="plc-label">Type of Legal Assistance Requested <small>আইনগত সহায়তার ধরন</small></label><input name="requests[${index}][assistance_type]" class="form-control"></div>
            </div>`;
        requestList.appendChild(row);
        row.querySelector('.requester-type').addEventListener('change', () => syncRequester(row));
        row.querySelector('.remove-request').addEventListener('click', () => { row.remove(); renumberRequests(); });
        syncRequesterTypeForLocation(row, form.querySelector('[name="clinic_location"]:checked')?.value);
        renumberRequests();
    }

    form.querySelectorAll('[name="clinic_location"]').forEach(input => input.addEventListener('change', syncLocation));
    document.getElementById('addPlcRequest').addEventListener('click', addRequest);
    document.getElementById('resetPlc').addEventListener('click', function () {
        form.reset();
        requestList.innerHTML = '';
        requestIndex = 0;
        addRequest();
        syncLocation();
    });
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (window.Swal) Swal.fire({ icon:'success', title:'Paralegal Aid Clinic / প্যারালিগ্যাল এইড ক্লিনিক', text:'The entered information has been reviewed successfully. / প্রদত্ত তথ্য সফলভাবে পর্যালোচনা করা হয়েছে।', confirmButtonColor:'#2f7d62' });
    });

    addRequest();
    syncLocation();
});
</script>
@endpush
