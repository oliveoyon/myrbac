<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Capturing Form - Court Police Station Prison</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        html {
            background: #d9dde3;
        }

        body {
            font-family: Arial, 'Noto Sans Bengali', 'SolaimanLipi', sans-serif;
            margin: 0;
            background: #d9dde3;
            color: #111;
            font-size: 12px;
            line-height: 1.3;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .preview-toolbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            max-width: 210mm;
            margin: 0 auto;
            padding: 10px 12px;
            background: #ffffff;
            border-bottom: 1px solid #d7dde5;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .08);
            color: #243142;
        }

        .preview-toolbar strong {
            display: block;
            font-size: 14px;
            line-height: 1.2;
        }

        .preview-toolbar span {
            display: block;
            margin-top: 2px;
            color: #667085;
            font-size: 12px;
        }

        .preview-toolbar button {
            border: 0;
            border-radius: 6px;
            background: #17643a;
            color: #fff;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .form-document {
            width: 210mm;
            margin: 14px auto;
            background: #fff;
            padding: 10mm;
            box-shadow: 0 8px 28px rgba(15, 23, 42, .16);
            position: relative;
        }

        .logos {
            margin-bottom: 7px;
            text-align: center;
        }

        .report-header-image {
            display: block;
            width: 100%;
            max-height: 92px;
            object-fit: contain;
            margin: 0 auto 5px;
        }

        .logo-fallback {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 6px;
        }

        .logo-box {
            min-height: 42px;
            border: 1px solid #d7dde5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            font-size: 10px;
            text-align: center;
            color: #555;
        }

        h1,
        h2,
        h3 {
            margin: 4px 0;
            text-align: center;
        }

        h1 {
            font-size: 15px;
        }

        h2 {
            font-size: 17px;
            text-decoration: underline;
        }

        h3 {
            font-size: 14px;
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .support {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 3px 0 8px;
            table-layout: fixed;
            page-break-inside: auto;
            break-inside: auto;
        }

        td,
        th {
            border: 1px solid #222;
            padding: 4px 5px;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        th {
            background: #f3f3f3;
            text-align: center;
            font-weight: bold;
        }

        tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .no {
            width: 34px;
            text-align: center;
            white-space: nowrap;
        }

        .label {
            width: 38%;
        }

        .input {
            min-height: 20px;
        }

        .checkbox {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 1px solid #111;
            margin: 0 3px -2px 8px;
        }

        .line {
            border-bottom: 1px dotted #333;
            min-height: 18px;
            display: inline-block;
            min-width: 140px;
        }

        .textarea {
            height: 105px;
            border: 1px solid #222;
            padding: 5px;
        }

        .dots {
            line-height: 1.9;
            letter-spacing: 1px;
        }

        .section-title {
            font-weight: bold;
            text-align: center;
            font-size: 15px;
            margin: 9px 0 5px;
            text-decoration: underline;
            page-break-after: avoid;
            break-after: avoid;
        }

        .sub-title {
            font-weight: bold;
            margin: 6px 0 3px;
            page-break-after: avoid;
            break-after: avoid;
        }

        .grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .address-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 35px;
        }

        .small {
            font-size: 11px;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .date-col {
            width: 23%;
        }

        @media print {
            html,
            body {
                background: #fff;
            }

            .preview-toolbar {
                display: none;
            }

            .form-document {
                margin: 0;
                box-shadow: none;
                width: auto;
                padding: 0;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        @media screen and (max-width: 860px) {
            .preview-toolbar,
            .form-document {
                width: 100%;
                max-width: 100%;
            }

            .form-document {
                margin: 0;
                padding: 14px;
                box-shadow: none;
            }

            body {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    <div class="preview-toolbar">
        <div>
            <strong>New PDF Form Demo</strong>
            <span>Static preview only. No production PDF logic is connected yet.</span>
        </div>
        <button type="button" onclick="window.print()">Print Preview</button>
    </div>

    <main class="form-document">
        <div class="logos">
            @if (file_exists(public_path('reportHeader.png')))
                <img class="report-header-image" src="{{ asset('reportHeader.png') }}" alt="Report Header">
            @else
                <div class="logo-fallback">
                    <div class="logo-box">EU Logo</div>
                    <div class="logo-box">German Cooperation / GIZ</div>
                    <div class="logo-box">Govt. Logo</div>
                </div>
            @endif
        </div>
        <h1>Access to Justice for Women</h1>
        <div class="center small">(A Project Implemented jointly by Law and Justice Division, Ministry of Law, Justice
            and Parliamentary Affairs and GIZ Bangladesh)</div>
        <div class="support">SUPPORT IN <span class="checkbox"></span> COURT <span class="checkbox"></span> POLICE
            STATION <span class="checkbox"></span> PRISON</div>
        <div class="section-title">A. প্রোফাইল তথ্য (PROFILE INFORMATION)</div>
        <table>
            <tr>
                <td class="label"><b>প্রোফাইল নম্বর /সেন্ট্রাল আইডি নং (PROFILE NO/CENTRAL ID NO)</b></td>
                <td></td>
            </tr>
            <tr>
                <td><b>জেলা (District)</b></td>
                <td></td>
            </tr>
        </table>
        <div class="sub-title">1. প্রাথমিক তথ্য (General Information)</div>
        <table>
            <tr>
                <td class="no">1.1</td>
                <td class="label">নাম (Name)</td>
                <td>সম্পূর্ণ নাম (Full Name)</td>
                <td>ডাক নাম (Nick Name)</td>
            </tr>
            <tr>
                <td>1.2</td>
                <td>পিতার নাম (Father’s Name)</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>1.3</td>
                <td>মাতার নাম (Mother’s Name)</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>1.4</td>
                <td>লিঙ্গ (Sex)</td>
                <td colspan="2"><span class="checkbox"></span> পুরুষ (Male) <span class="checkbox"></span> নারী
                    (Female) <span class="checkbox"></span> ট্রান্সজেন্ডার পার্সন (Transgender Person)</td>
            </tr>
            <tr>
                <td>1.5</td>
                <td>বয়স (Age)</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>1.6</td>
                <td>প্রতিবন্ধিতা (Disability)</td>
                <td colspan="2"><span class="checkbox"></span> হ্যাঁ (Yes) <span class="checkbox"></span> না (No)
                </td>
            </tr>
            <tr>
                <td>1.7</td>
                <td>জাতীয়তা (Nationality)</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>1.8</td>
                <td>জাতীয় পরিচয়পত্র/ পাসপোর্ট নং/ জন্ম নিবন্ধন নং<br>[National ID/ Passport No/ Birth Certificate No.]
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>1.9</td>
                <td>যোগাযোগের ঠিকানা (Contact Address)</td>
                <td colspan="2">
                    <div class="address-grid">
                        <div>বাসা/ গ্রাম (House/ Village) :</div>
                        <div>থানা (Police Station) :</div>
                        <div>রাস্তা/ ডাকঘর (Road/ Post Office) :</div>
                        <div>উপজেলা (Upazilla) :</div>
                        <div>জিপ কোড (Zip Code) :</div>
                        <div>জেলা (District) :</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>1.10</td>
                <td>ফোন নাম্বার (Phone Number)</td>
                <td colspan="2">(১)<br>(২)</td>
            </tr>
        </table>
        <div class="section-title">B. সেশন তথ্য (SESSION INFORMATION)</div>
        <div class="sub-title">2. সাক্ষাৎকারের তথ্য (Information of Interview)</div>
        <table>
            <tr>
                <td colspan="3"><b>Process No:</b></td>
            </tr>
            <tr>
                <td class="no">2.1</td>
                <td class="label">সাক্ষাৎকার গ্রহণের তারিখ (Date of Interview)</td>
                <td></td>
            </tr>
            <tr>
                <td>2.2</td>
                <td>সাক্ষাৎকার গ্রহণের সময় (Time of Interview)</td>
                <td></td>
            </tr>
            <tr>
                <td>2.3</td>
                <td>সাক্ষাৎকার গ্রহণের স্থান, থানা/ আদালত/ কারাগারের নাম<br>(Place of Interview, Name of police
                    station/court/ prison)</td>
                <td></td>
            </tr>
        </table>
        <div class="section-title">C. ব্যক্তিগত তথ্য (PERSONAL INFORMATION)</div>
        <div class="sub-title">3. পরিবার, শিক্ষা ও আয় সম্পর্কিত তথ্য (Family Detail, Education, and Income Information)
        </div>
        <table>
            <tr>
                <td class="no">3.1</td>
                <td class="label">বৈবাহিক অবস্থা (Marital Status)</td>
                <td><span class="checkbox"></span> বিবাহিত (Married) <span class="checkbox"></span> তালাকপ্রাপ্ত
                    (Divorced)<br><span class="checkbox"></span> অবিবাহিত (Single) <span class="checkbox"></span> বিধবা/
                    বিপত্নীক (Widow)</td>
            </tr>
            <tr>
                <td>3.2</td>
                <td>যদি বিবাহিত হয় তবে, স্বামী/ স্ত্রীর নাম (If married then, Spouse Name)</td>
                <td></td>
            </tr>
            <tr>
                <td>3.3</td>
                <td>শিক্ষাগত যোগ্যতা (Level of education)</td>
                <td><span class="checkbox"></span> নিরক্ষর (Illiterate) <span class="checkbox"></span> উচ্চ মাধ্যমিক
                    (Higher Secondary)<br><span class="checkbox"></span> স্বাক্ষরজ্ঞান সম্পন্ন (Can sign) <span
                        class="checkbox"></span> স্নাতক (Graduate)<br><span class="checkbox"></span> প্রাথমিক (Primary)
                    <span class="checkbox"></span> স্নাতকত্তোর (Postgraduate)<br><span class="checkbox"></span> মাধ্যমিক
                    (Secondary)</td>
            </tr>
            <tr>
                <td>3.4</td>
                <td>পেশা (Occupation) [কারাবন্দীর ক্ষেত্রে গ্রেফতারের সময় পেশা]</td>
                <td></td>
            </tr>
            <tr>
                <td>3.5</td>
                <td>সর্বশেষ মাসিক আয় (Last monthly income) [কারাবন্দীর ক্ষেত্রে গ্রেফতারের সময়]</td>
                <td></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="no">3.6</td>
                <td class="label">পরিবার/আত্মীয়রা কেউ তার বিরোধ/মামলা/গ্রেফতারের বিষয়ে জানে কি না?<br>(Have
                    family/relative been informed about his/her dispute/case/arrest?)</td>
                <td><span class="checkbox"></span> হ্যাঁ (Yes) <span class="checkbox"></span> না (No)</td>
            </tr>
        </table>
        <div class="sub-title">৩.৭ এবং ৩.৮ অংশটি শুধুমাত্র নারী বন্দীদের জন্য প্রযোজ্য (3.7 and 3.8 part is applicable
            only for Female Prisoners)</div>
        <table>
            <tr>
                <td class="no">3.7</td>
                <td>নারী বন্দীর সাথে কি শিশু (এক বা একাধিক) কারাগারে অবস্থান করছে?<br>[Are there children accompanying
                    with the female prisoner?]</td>
                <td><span class="checkbox"></span> হ্যাঁ (Yes) <span class="checkbox"></span> না (No)</td>
            </tr>
            <tr>
                <td>3.8</td>
                <td>যদি হ্যাঁ হয়, তবে শিশু বা শিশুদের তথ্য দিন<br>(If yes, then provide information on accompanied
                    children)</td>
                <td>Sex <span class="checkbox"></span> Male <span class="checkbox"></span> Female <span
                        class="checkbox"></span> Transgender Person &nbsp; Age <span class="line"></span><br>Sex <span
                        class="checkbox"></span> Male <span class="checkbox"></span> Female <span
                        class="checkbox"></span> Transgender Person &nbsp; Age <span class="line"></span></td>
            </tr>
        </table>
        <div class="sub-title">৪ এবং ৫ অংশটি শুধুমাত্র মামলা সংশ্লিষ্ট/ গ্রেফতারকৃত ব্যক্তি/কারাবন্দীর জন্য প্রযোজ্য
        </div>
        <div class="section-title">4. অভিভাবকের তথ্য (Information of Guardian)</div>
        <table>
            <tr>
                <td class="no">4.1</td>
                <td>বিচারপ্রার্থী/ কারাবন্দির স্থানীয় অভিভাবক আছে কিনা?<br>(Does the Justice seeker/prisoner have local
                    guardian?)</td>
                <td><span class="checkbox"></span> হ্যাঁ (Yes) <span class="checkbox"></span> না (No)</td>
            </tr>
            <tr>
                <td colspan="3">যদি ৪.১ “হ্যাঁ” হয়, তবে পরবর্তী ৪.২ থেকে ৪.৬ অংশটি পূরণ করুন</td>
            </tr>
            <tr>
                <td>4.2</td>
                <td>অভিভাবকের নাম (Guardian’s Name)</td>
                <td></td>
            </tr>
            <tr>
                <td>4.3</td>
                <td>অভিভাবকের ঠিকানা (Guardian’s Address)</td>
                <td>
                    <div class="address-grid">
                        <div>বাসা/ গ্রাম :</div>
                        <div>থানা :</div>
                        <div>রাস্তা/ ডাকঘর :</div>
                        <div>উপজেলা :</div>
                        <div>জিপ কোড :</div>
                        <div>জেলা :</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>4.4</td>
                <td>ফোন নাম্বার (Phone Number)</td>
                <td></td>
            </tr>
            <tr>
                <td>4.5</td>
                <td>অভিভাবকের সাথে সম্পর্ক (Relation with Guardian)</td>
                <td><span class="checkbox"></span> পরিবারের সদস্য (Family Member) <span class="line"></span><br><span
                        class="checkbox"></span> আত্মীয় (Relative) <span class="line"></span><br><span
                        class="checkbox"></span> প্রতিবেশী (Neighbor) <span class="checkbox"></span> অন্যান্য (Other)
                    <span class="line"></span></td>
            </tr>
            <tr>
                <td>4.6</td>
                <td>অভিভাবক কি জামিনদার হবেন? (Will the Guardian act as surety?)</td>
                <td><span class="checkbox"></span> হ্যাঁ (Yes) <span class="checkbox"></span> না (No)</td>
            </tr>
        </table>
        <div class="section-title">5. আইনগত প্রতিনিধিত্ব (Legal Representation)</div>
        <table>
            <tr>
                <td class="no">5.1</td>
                <td>বিচারপ্রার্থী/ কারাবন্দির আইনজীবী আছে কিনা?</td>
                <td><span class="checkbox"></span> হ্যাঁ (Yes) <span class="checkbox"></span> না (No)</td>
            </tr>
            <tr>
                <td colspan="3">যদি ৫.১ “হ্যাঁ” হয়, তবে পরবর্তী ৫.২ থেকে ৫.৫ অংশটি পূরণ করুন</td>
            </tr>
            <tr>
                <td>5.2</td>
                <td>আইনজীবীর ধরণ (Type of Lawyer)</td>
                <td><span class="checkbox"></span> ব্যক্তিগত (Personal) <span class="checkbox"></span> এন.জি.ও
                    (NGO)<br><span class="checkbox"></span> জেলা লিগ্যাল এইড (District Legal Aid) <span
                        class="checkbox"></span> অন্যান্য (Other) <span class="line"></span><br><span
                        class="checkbox"></span> রাষ্ট্র নিযুক্ত আইনজীবী (State Defense)</td>
            </tr>
            <tr>
                <td>5.3</td>
                <td>আইনজীবীর নাম (Lawyer’s Name)</td>
                <td></td>
            </tr>
            <tr>
                <td>5.4</td>
                <td>আইনজীবীর সদস্য নাম্বার (Membership number of Lawyer)</td>
                <td></td>
            </tr>
            <tr>
                <td>5.5</td>
                <td>ফোন নাম্বার (Phone Number)</td>
                <td></td>
            </tr>
        </table>

        <div class="section-title">6. ঘটনার বিবরণ (Details of Incident)</div>
        <table>
            <tr>
                <td><b>6.1 ঘটনার সংক্ষিপ্ত বিবরণ (Brief of Incident)</b>
                    <div class="textarea dots">
                        ……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………
                    </div>
                </td>
            </tr>
        </table>
        <div class="center"><b>এই অংশটি শুধুমাত্র আদালত এবং থানায় সহায়তার জন্য প্রযোজ্য</b><br>(Part D is applicable
            Only for Support in Court and Police Station)</div>
        <div class="section-title">D. আদালত এবং থানায় সহায়তা (SUPPORT IN COURT AND POLICE STATION)</div>
        <div class="small">[ টিক (✓) চিহ্ন দিয়ে তার পাশে ফলোআপ শিট অনুযায়ী তারিখ লিখুন]</div>
        <div class="section-title">7. মামলা বা গ্রেফতার সম্পর্কিত তথ্য (Case Information)</div>
        <table>
            <tr>
                <td class="no">7.1</td>
                <td>সাক্ষাৎকার প্রদানকারী কি পুলিশ/ আদালত হেফাজতে?<br>(Is the interviewee in police/court custody)</td>
                <td><span class="checkbox"></span> পুলিশ হেফাজত (Police custody)<br><span class="checkbox"></span>
                    আদালত হেফাজত (Court custody)<br><span class="checkbox"></span> প্রযোজ্য নয় (Not applicable)</td>
            </tr>
            <tr>
                <td colspan="3">যদি ৭.১ “হ্যাঁ” হয়, তবে পরবর্তী অংশ ৭.২ এবং ৭.৩ পূরণ করুন</td>
            </tr>
            <tr>
                <td>7.2</td>
                <td colspan="2">অভিযোগসমূহ কি কি? (What are the charges?)<div class="textarea dots">
                        ……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………
                    </div>
                </td>
            </tr>
            <tr>
                <td>7.3</td>
                <td>গ্রেফতারের তারিখ (Date of arrest)</td>
                <td>মামলা নং (Case Number)</td>
            </tr>
        </table>
        <div class="section-title">8. সহায়তার ধরণ (Nature of Assistance)</div>
        <table>
            <tr>
                <th class="no">Sl. No.</th>
                <th>সহায়তার বিবরণ (Details of Assistance)</th>
                <th style="width:23%">তারিখ (Date)</th>
            </tr>
            <tr>
                <td>8.1</td>
                <td><span class="checkbox"></span> পরিবারের সাথে যোগাযোগ (Communicate with families/ relatives)</td>
                <td></td>
            </tr>
            <tr>
                <td>8.2</td>
                <td><span class="checkbox"></span> আইনগত প্রতিনিধিত্বের জন্য প্রেরণ (Referred for legal
                    representation)-<br><span class="checkbox"></span> জেলা লিগ্যাল এইড অফিস &nbsp; <span
                        class="checkbox"></span> জেলা প্রকল্প কর্মকর্তা<br><span class="checkbox"></span> এনজিওর
                    প্যানেল আইনজীবী, উল্লেখ করুন <span class="line"></span><br><span class="checkbox"></span>
                    অন্যান্য, উল্লেখ করুন <span class="line"></span></td>
                <td></td>
            </tr>
        </table>

        <table>
            <tr>
                <th class="no">Sl. No.</th>
                <th>সহায়তার বিবরণ (Details of Assistance)</th>
                <th style="width:23%">তারিখ (Date)</th>
            </tr>
            <tr>
                <td>8.3</td>
                <td><span class="checkbox"></span> ওকালতনামা সংগ্রহ [Collected Vokalatnama (power of attorney)]</td>
                <td></td>
            </tr>
            <tr>
                <td>8.4</td>
                <td><span class="checkbox"></span> আদালত হতে মামলার নথি সংগ্রহ [Collected case document (FIR, CS,
                    Police forwarding, judgment etc.) from Courts and other places]</td>
                <td></td>
            </tr>
            <tr>
                <td>8.5</td>
                <td><span class="checkbox"></span> জামিনদার খুঁজে দেয়া, জাতীয় পরিচয়পত্র ও ফোন নং সহ বিস্তারিত লিখুন
                    [Identify sureties]<br><br>a)<br><br>b)</td>
                <td></td>
            </tr>
            <tr>
                <td>8.6</td>
                <td><span class="checkbox"></span> সাক্ষীর সাথে যোগাযোগ (Communicate with witness)</td>
                <td></td>
            </tr>
            <tr>
                <td>8.7</td>
                <td><span class="checkbox"></span> মেডিকেল রিপোর্ট সংগ্রহে সহায়তা প্রদান (Assist in collecting medical
                    report)</td>
                <td></td>
            </tr>
            <tr>
                <td>8.8</td>
                <td><span class="checkbox"></span> থানায় আটক নারী/শিশু/কিশোর/ অসহায় ব্যক্তিকে আইনগত সহায়তা</td>
                <td></td>
            </tr>
            <tr>
                <td>8.9</td>
                <td><span class="checkbox"></span> থানা হেফাজতে অবস্থানকৃত নারী/শিশু/কিশোর/ অসহায় ব্যক্তিকে
                    পরিবারে/আইনগত অভিভাবকের নিকট ফিরে যেতে সহায়তা</td>
                <td></td>
            </tr>
            <tr>
                <td>8.10</td>
                <td>অন্যান্য সেবার জন্য রেফারেল (Referral for other services)<br><span class="checkbox"></span> জেলা
                    লিগ্যাল এইড অফিস<br><span class="checkbox"></span> এনজিওতে আরজে/সালিশ, উল্লেখ করুন <span
                        class="line"></span><br><span class="checkbox"></span> গ্রাম আদালত (Village Court)<br><span
                        class="checkbox"></span> নিরাপদ আবাসন (Safe Home)</td>
                <td class="dots">……………………<br>……………………<br>……………………<br>……………………</td>
            </tr>
        </table>
        <div class="section-title">9. ফলাফল (Result)</div>
        <table>
            <tr>
                <th class="no">Sl. No.</th>
                <th>বিবরণ (Details)</th>
                <th style="width:23%">তারিখ (Date)</th>
            </tr>
            <tr>
                <td>9.1</td>
                <td><span class="checkbox"></span> বিরোধ মিমাংসা হয়েছে (Resolved dispute)</td>
                <td></td>
            </tr>
            <tr>
                <td>9.2</td>
                <td><span class="checkbox"></span> আইনজীবী নিয়োগ করা হয়েছে (Appoint lawyer)</td>
                <td></td>
            </tr>
            <tr>
                <td>9.3</td>
                <td>মুক্ত হওয়া (Released on)<br><span class="checkbox"></span> জামিন (Bail) <span
                        class="line"></span><br><span class="checkbox"></span> দোষ স্বীকার (Guilty Plea) <span
                        class="line"></span><br><span class="checkbox"></span> জরিমানাসহ (With fine) <span
                        class="line"></span></td>
                <td class="dots">………………<br>………………<br>………………</td>
            </tr>
            <tr>
                <td>9.4</td>
                <td><span class="checkbox"></span> অন্যান্য ফলাফল, উল্লেখ করুন (Other result, please specify) <span
                        class="line"></span></td>
                <td></td>
            </tr>
        </table>
        <div class="section-title">10. জেলা লিগ্যাল এইড অফিস তথ্য (District Legal Aid Office Information)</div>
        <table>
            <tr>
                <th class="no">Sl. No.</th>
                <th>বিবরণ (Details)</th>
                <th style="width:23%">তারিখ (Date)</th>
            </tr>
            <tr>
                <td>10.1</td>
                <td>জেলা লিগ্যাল এইডের সেবার জন্য আবেদনের ধরণ (Mode of application for District Legal Aid Service)</td>
                <td></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="no">10.1</td>
                <td>অনলাইন আবেদন (Online Application) <span class="line"></span><br>সরাসরি অফিসে আবেদন (Office
                    Application) <span class="line"></span></td>
                <td></td>
            </tr>
            <tr>
                <td>10.2</td>
                <td>জেলা লিগ্যাল এইড অফিসে রেফারকৃত আবেদন যদি গৃহীত হয়, তাহলে আবেদন গৃহীতের রেফারেন্স নাম্বার উল্লেখ
                    করুন</td>
                <td></td>
            </tr>
            <tr>
                <td>10.3</td>
                <td>জেলা লিগ্যাল এইড অফিসে প্রাপ্ত সেবার ধরণ (Type of service received at District Legal Aid
                    Office)<br>আইনগত পরামর্শ (Legal advice) <span class="line"></span><br>বিকল্প বিরোধ নিষ্পত্তি
                    (Alternative Dispute Resolution) <span class="line"></span><br>নতুন মামলা দায়ের (Filing new
                    lawsuit) <span class="line"></span><br>বিদ্যমান মামলায় আইনগত সহায়তা <span class="line"></span>
                </td>
                <td></td>
            </tr>
        </table>
        <div class="section-title">11. প্রদত্ত সেবার বিবরণ (Description of Service Provided)</div>
        <table>
            <tr>
                <td><b>11.1 সেবার বিবরণ (Description of Service)</b>
                    <div class="textarea dots">
                        ……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………
                    </div>
                </td>
            </tr>
        </table>
        <div class="center"><b>এই অংশটি শুধুমাত্র কারাগারে সহায়তার জন্য প্রযোজ্য</b><br>(Part E is applicable Only for
            Support in Prison)</div>
        <div class="section-title">E. কারাগারে সহায়তা (SUPPORT IN PRISON)</div>
        <div class="section-title">12. মামলার মৌলিক তথ্য (Basic Case Information)</div>
        <table>
            <tr>
                <td class="no">12.1</td>
                <td>সাক্ষাৎকারের উৎস (Source of Interview)</td>
                <td><span class="checkbox"></span> Prison Staff <span class="checkbox"></span> Case File <span
                        class="checkbox"></span> Prison Register <span class="checkbox"></span> Other <span
                        class="line"></span></td>
            </tr>
            <tr>
                <td>12.2</td>
                <td>রেজিস্ট্রেশন নং (Prison registration no.)</td>
                <td></td>
            </tr>
            <tr>
                <td>12.3</td>
                <td>মামলার নাম্বার [Case No(s)]</td>
                <td></td>
            </tr>
            <tr>
                <td>12.4</td>
                <td>ধারার নাম্বার (Section No)</td>
                <td></td>
            </tr>
            <tr>
                <td>12.5</td>
                <td>বর্তমান আদালতের নাম (Present Court’s name)</td>
                <td></td>
            </tr>
            <tr>
                <td>12.6</td>
                <td>লকআপ নাম্বার, প্রযোজ্য হলে [Lock Up number]</td>
                <td></td>
            </tr>
            <tr>
                <td>12.7</td>
                <td>কারাগারে আগমনের তারিখ (Date of entry in prison)</td>
                <td></td>
            </tr>
            <tr>
                <td>12.8</td>
                <td>মামলাটি বদলি হয়েছে কিনা? (Has the case transferred?)</td>
                <td><span class="checkbox"></span> হ্যাঁ (Yes) <span class="checkbox"></span> না (No)</td>
            </tr>
            <tr>
                <td>12.9</td>
                <td>যদি হ্যাঁ হয়, সর্বশেষ আদালতের নাম (If yes, current Court’s name)</td>
                <td></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="no">12.10</td>
                <td>মামলার বর্তমান অবস্থা (Present status of case)</td>
                <td></td>
            </tr>
            <tr>
                <td>12.11</td>
                <td>সহ-আসামীর সংখ্যা, যদি থাকে [Number of co-offenders (if any)]</td>
                <td></td>
            </tr>
            <tr>
                <td>12.12</td>
                <td>পরবর্তী হাজিরার তারিখ (Next Court date)</td>
                <td></td>
            </tr>
            <tr>
                <td>12.13</td>
                <td colspan="2">মামলার বিবরণ (Facts of the case)<div class="textarea dots">
                        ……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………
                    </div>
                </td>
            </tr>
        </table>
        <div class="section-title">13. কারাবাসের ধরণ (Imprisonment Information)</div>
        <table>
            <tr>
                <td class="no">13.1</td>
                <td>মৌলিক অবস্থা (Basic condition)</td>
                <td><span class="checkbox"></span> সাধারণ (Regular) <span class="checkbox"></span> তলবমতে (On Call)
                    <span class="checkbox"></span> নিরাপদ হেফাজত (Safe Custody)</td>
            </tr>
            <tr>
                <td>13.2</td>
                <td>কারাবাসের অবস্থা (Status of imprisonment)</td>
                <td><span class="checkbox"></span> বিচারাধীন (Under trial)<br><span class="checkbox"></span>
                    সাজাপ্রাপ্ত (Convicted)<br><span class="checkbox"></span> সাজার মেয়াদ উত্তীর্ণ (Released
                    Prisoner)<br><span class="checkbox"></span> সাজাপ্রাপ্ত কিন্তু অন্য অপরাধে বিচারাধীন<br><span
                        class="checkbox"></span> বিদেশী বন্দী (Foreigner)</td>
            </tr>
            <tr>
                <td>13.3</td>
                <td>বিশেষ অবস্থা (Special condition)</td>
                <td><span class="checkbox"></span> গুরুতর অসুস্থ (Critical Ill) <span class="checkbox"></span> অক্ষম
                    (Disable)<br><span class="checkbox"></span> অন্য অবস্থা (Other condition) <span
                        class="line"></span></td>
            </tr>
            <tr>
                <td>13.4</td>
                <td>গ্রেফতারের তারিখ (Date of arrest)</td>
                <td></td>
            </tr>
            <tr>
                <td>13.5</td>
                <td>আত্মসমর্পণের তারিখ (Date of surrender)</td>
                <td></td>
            </tr>
        </table>
        <div class="section-title">14. সহায়তার ধরণ (Nature of Assistance)</div>
        <table>
            <tr>
                <th class="no">Sl. No.</th>
                <th>সহায়তার বিবরণ (Details of Assistance)</th>
                <th style="width:23%">তারিখ (Date)</th>
            </tr>
            <tr>
                <td>14.1</td>
                <td><span class="checkbox"></span> পরিবারের সাথে যোগাযোগ (Communicate with families/ relatives)</td>
                <td></td>
            </tr>
            <tr>
                <td>14.2</td>
                <td><span class="checkbox"></span> আইনগত প্রতিনিধিত্বের জন্য প্রেরণ - জেলা লিগ্যাল এইড অফিস / জেলা
                    প্রকল্প কর্মকর্তা / NGO Panel Lawyer / Other</td>
                <td class="dots">………………<br>………………</td>
            </tr>
        </table>

        <table>
            <tr>
                <th class="no">Sl. No.</th>
                <th>সহায়তার বিবরণ (Details of Assistance)</th>
                <th style="width:23%">তারিখ (Date)</th>
            </tr>
            <tr>
                <td>14.3</td>
                <td><span class="checkbox"></span> ‘তলবমতে’ বন্দীর আদালত থেকে পরবর্তী তারিখ সংগ্রহ<br>i. সংগ্রহের তারিখ
                    <span class="line"></span><br>ii. বন্দীর আদালতে উপস্থিতির পরবর্তী তারিখ <span
                        class="line"></span></td>
                <td></td>
            </tr>
            <tr>
                <td>14.4</td>
                <td><span class="checkbox"></span> আদালত হতে মামলার নথি সংগ্রহ [FIR, CS, Police forwarding, judgment
                    etc.]</td>
                <td></td>
            </tr>
            <tr>
                <td>14.5</td>
                <td><span class="checkbox"></span> জামিনদার খুঁজে দেয়া, জাতীয় পরিচয়পত্র ও ফোন নং সহ বিস্তারিত
                    লিখুন<br><br>a)<br><br>b)</td>
                <td></td>
            </tr>
            <tr>
                <td>14.6</td>
                <td><span class="checkbox"></span> সাক্ষীর সাথে যোগাযোগ (Communicate with Witness)</td>
                <td></td>
            </tr>
            <tr>
                <td>14.7</td>
                <td><span class="checkbox"></span> জামিননামা কারাগারে প্রেরণের জন্য যোগাযোগ</td>
                <td></td>
            </tr>
            <tr>
                <td>14.8</td>
                <td><span class="checkbox"></span> আদালতের আদেশনামা সংশ্লিষ্ট প্রতিষ্ঠান/কারাগারে প্রেরণের জন্য যোগাযোগ
                </td>
                <td></td>
            </tr>
            <tr>
                <td>14.9</td>
                <td><span class="checkbox"></span> সই-মোহরের নকল সংগ্রহের জন্য আবেদন</td>
                <td></td>
            </tr>
            <tr>
                <td>14.10</td>
                <td><span class="checkbox"></span> আপিলে সহায়তা (Assistance in appeal)</td>
                <td></td>
            </tr>
            <tr>
                <td>14.11</td>
                <td><span class="checkbox"></span> সংশ্লিষ্ট মন্ত্রণালয়, দূতাবাস, হাইকমিশন, আন্তর্জাতিক ও অন্যান্য
                    সংস্থার সাথে যোগাযোগ (with contact details)<br><span class="line"></span></td>
                <td></td>
            </tr>
            <tr>
                <td>14.12</td>
                <td><span class="checkbox"></span> অন্যান্য আইনগত সহায়তা<br>সাজাপ্রাপ্ত বন্দীর জরিমানার টাকা জমাদানে
                    সহায়তা <span class="line"></span><br>সাজার পরোয়ানা কারাগারে প্রেরণের জন্য আদালতের সাথে যোগাযোগ
                    <span class="line"></span><br>অন্যান্য, উল্লেখ করুন <span class="line"></span></td>
                <td class="dots">………………<br>………………<br>………………</td>
            </tr>
        </table>

        <div class="section-title">15. ফলাফল (Result)</div>
        <table>
            <tr>
                <th class="no">Sl. No.</th>
                <th>বিবরণ (Details)</th>
                <th style="width:23%">তারিখ (Date)</th>
            </tr>
            <tr>
                <td>15.1</td>
                <td>মুক্ত হওয়া (Released on)<br><span class="checkbox"></span> জামিন মঞ্জুর (Bail granted)<br><span
                        class="checkbox"></span> অব্যাহতি (Discharged/FRT)<br><span class="checkbox"></span> খালাস
                    (Acquitted)<br><span class="checkbox"></span> দোষ স্বীকারের পর মুক্তি<br><span
                        class="checkbox"></span> নিরাপত্তা হেফাজত থেকে মুক্তি<br><span class="checkbox"></span>
                    প্রত্যাবর্তনের তারিখ (বিদেশি কারাবন্দীর জন্য)</td>
                <td></td>
            </tr>
            <tr>
                <td>15.2</td>
                <td>প্রেরণ (Send to)-<br><span class="checkbox"></span> নিরাপত্তা হেফাজত (Safe Home) <span
                        class="checkbox"></span> আইনগত অভিভাবক (Legal Guardian)<br><span class="checkbox"></span> নিজ
                    জিম্মায় (Own Custody) <span class="checkbox"></span> এনজিও আশ্রয় কেন্দ্র<br><span
                        class="checkbox"></span> কিশোর/কিশোরী উন্নয়ন কেন্দ্র <span class="checkbox"></span>
                    ক্ষতিগ্রস্থদের সহায়তা কেন্দ্র<br><span class="checkbox"></span> অন্যান্য, উল্লেখ করুন</td>
                <td></td>
            </tr>
            <tr>
                <td>15.3</td>
                <td>সাজাপ্রাপ্ত (Convicted)<br>সাজার সময়কাল (Length of sentence) <span class="line"></span><br>সাজা
                    সমাপ্তির তারিখ (Sentence expires on) <span class="line"></span></td>
                <td></td>
            </tr>
            <tr>
                <td>15.4</td>
                <td>আপিলের ফলাফল (Result of the Appeal)<br><span class="checkbox"></span> সাজা বহাল (Sentence upheld)
                    <span class="checkbox"></span> খালাস (Acquitted)<br><span class="checkbox"></span> সাজা হ্রাস
                    (Sentence reduced) <span class="checkbox"></span> সাজা বৃদ্ধি (Sentence enhanced)</td>
                <td></td>
            </tr>
            <tr>
                <td>15.5</td>
                <td>কারামুক্তির তারিখ (Date of released from Prison)</td>
                <td></td>
            </tr>
        </table>
        <div class="section-title">16. প্রদত্ত সেবার বিবরণ (Description of Service Provided)</div>
        <table>
            <tr>
                <td><b>16.1 সেবার বিবরণ (Description of Service)</b>
                    <div class="textarea dots">
                        ……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………<br>……………………………………………………………………………………………………………………………
                    </div>
                </td>
            </tr>
        </table>
        <br><b>ফাইল বন্ধ (File Closed)</b> তারিখ (Date): <span class="line"></span>
        <br><br><br>
        <table>
            <tr>
                <td class="center">প্যারালিগ্যালের স্বাক্ষর ও তারিখ<br>(Signature and date of Paralegal)</td>
                <td class="center">জেলা প্রকল্প কর্মকর্তার স্বাক্ষর ও তারিখ<br>(Signature and date of District Project
                    Officer)</td>
            </tr>
        </table>

        <div class="section-title">ফলোআপ শিট (Follow up Sheet)</div>
        <table>
            <tr>
                <th rowspan="2" class="no">Sl. No.</th>
                <th colspan="2">পদক্ষেপ নেয়া হয়েছে (Interventions taken)</th>
                <th colspan="2">পদক্ষেপ নিতে হবে (Interventions to be taken)</th>
            </tr>
            <tr>
                <th>তারিখ (Date)</th>
                <th>পদক্ষেপসমূহ (Interventions)</th>
                <th>পদক্ষেপসমূহ (Interventions)</th>
                <th>তারিখ (Date)</th>
            </tr>
            <tr>
                <td>1</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>2</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>3</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>4</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>5</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>6</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>7</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>8</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>9</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>10</td>
                <td style="height:42px"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

    </main>

</body>

</html>
