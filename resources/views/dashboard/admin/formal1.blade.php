@extends('dashboard.layouts.admin-layout')

@section('title', 'Court Prison Data')

@push('styles')
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }

        .accordion-item {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .accordion-button {
            /* background-color: #4CAF50; */
            background-color: #545b62;
            color: white;
            font-weight: bold;
            padding: 15px;
            font-size: 16px;
            border: none;
            text-align: left;
            border-radius: 5px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .accordion-button:hover,
        .accordion-button:focus {
            background-color: #45a049;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .accordion-button:not(.collapsed) {
            background-color: #45a049;
        }

        .accordion-collapse {
            padding: 20px;
            border-top: 1px solid #ddd;
        }

        /* Form Field Styles */
        .form-label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.3);
        }

        .select2-container .form-select {
            padding: 10px;
            border-radius: 5px;
        }

        /* Apply focus effect to form-select dropdowns */
        .form-select:focus {
            border-color: #4CAF50 !important;
            /* Green border like input fields */
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.3) !important;
            outline: none;
        }

        /* Improve dropdown appearance */
        .form-select {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            width: 100%;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
        }

        /* Optional: Custom dropdown arrow */
        .form-select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="gray"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 30px;
            /* Space for dropdown arrow */
        }

        /* Column Spacing and Alignment */
        .row.g-3 {
            margin-bottom: 20px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 767px) {
            .accordion-button {
                font-size: 14px;
                padding: 10px;
            }

            .form-control,
            .form-select {
                font-size: 14px;
            }

            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Accordion Header Styles */
        .accordion-header {
            padding: 10px;
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }

        .accordion-body {
            background-color: #fafafa;
            padding: 20px;
        }

        /* Transitions and Animations */
        .accordion-collapse {
            transition: height 0.3s ease-out;
        }
    </style>
@endpush

@section('content')


    <section>
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="post" action="{{ route('formaction') }}" enctype="multipart/form-data">
                @csrf
                <h2>Court-Police-Prison Form</h2>
                <div class="accordion" id="caseFormAccordion">
                    <!-- Profile Information (Section 1) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                1. Profile Information
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="profile_no" class="form-label">Profile No./Central ID No</label>
                                        <input type="text" class="form-control" id="profile_no" name="profile_no">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" id="full_name" name="full_name"
                                            class="form-control @error('full_name') is-invalid @enderror"
                                            value="{{ old('full_name') }}">
                                        @error('full_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="nick_name" class="form-label">Nick Name</label>
                                        <input type="text" class="form-control" id="nick_name" name="nick_name"
                                            value="{{ old('nick_name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="father_name" class="form-label">Father's Name</label>
                                        <input type="text" class="form-control" id="father_name" name="father_name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mother_name" class="form-label">Mother's Name</label>
                                        <input type="text" class="form-control" id="mother_name" name="mother_name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="sex" class="form-label">Sex</label>
                                        <select class="form-select" id="sex" name="sex">
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Transgender">Transgender</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="age" class="form-label">Age</label>
                                        <input type="number" class="form-control" id="age" name="age">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="disability" class="form-label">Disability</label>
                                        <select class="form-select" id="disability" name="disability">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="nationality" class="form-label">Nationality</label>
                                        <input type="text" class="form-control" id="nationality" name="nationality">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="nid_passport" class="form-label">National ID/Passport No/Birth
                                            Certificate
                                            No</label>
                                        <input type="text" class="form-control" id="nid_passport"
                                            name="nid_passport">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number"
                                            name="phone_number">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="address" class="form-label">Contact Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 2: Session Information -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                2. Session Information
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="interview_date" class="form-label">Date of Interview</label>
                                        <input type="date" class="form-control" id="interview_date"
                                            name="interview_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="interview_time" class="form-label">Time of Interview</label>
                                        <input type="time" class="form-control" id="interview_time"
                                            name="interview_time">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="interview_place" class="form-label">Place of Interview</label>
                                        <input type="text"
                                            class="form-control @error('interview_place') is-invalid @enderror"
                                            id="interview_place" name="interview_place">
                                        @error('interview_place')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Personal Information -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                3. Personal Information
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="marital_status" class="form-label">Marital Status</label>
                                        <select class="form-select" id="marital_status" name="marital_status">
                                            <option value="">Select</option>
                                            <option value="Married">Married</option>
                                            <option value="Single">Single</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4" id="spouse_field" style="display: none;">
                                        <label for="spouse_name" class="form-label">Spouse Name (If Married)</label>
                                        <input type="text" class="form-control" id="spouse_name" name="spouse_name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="education_level" class="form-label">Education Level</label>
                                        <select class="form-select" id="education_level" name="education_level">
                                            <option value="">Select</option>
                                            <option value="Illiterate">Illiterate</option>
                                            <option value="Can Sign">Can Sign</option>
                                            <option value="Primary">Primary</option>
                                            <option value="Secondary">Secondary</option>
                                            <option value="Higher Secondary">Higher Secondary</option>
                                            <option value="Graduate">Graduate</option>
                                            <option value="Postgraduate">Postgraduate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="occupation" class="form-label">Occupation (At Time of Arrest)</label>
                                        <input type="text" class="form-control" id="occupation" name="occupation">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="monthly_income" class="form-label">Last Monthly Income</label>
                                        <input type="number" class="form-control" id="monthly_income"
                                            name="monthly_income">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="family_informed" class="form-label">Have family/relatives been
                                            informed?</label>
                                        <select class="form-select" id="family_informed" name="family_informed">
                                            <option value="">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- For Female Prisoners Only -->
                                <div class="mt-4">
                                    <h5>For Female Prisoners Only</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="children_with_prisoner" class="form-label">Are there children
                                                accompanying the female prisoner?</label>
                                            <select class="form-select" id="children_with_prisoner"
                                                name="children_with_prisoner">
                                                <option value="">Select</option>
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3" id="child_sex" style="display: none;">
                                            <label for="child_sex" class="form-label">Child's Sex</label>
                                            <select class="form-select" id="child_sex" name="child_sex">
                                                <option value="">Select</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Transgender">Transgender</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3" id="child_age" style="display: none;">
                                            <label for="child_age" class="form-label">Child's Age</label>
                                            <input type="number" class="form-control" id="child_age" name="child_age">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Guardian Information -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                4. Guardian Information
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="has_guardian" class="form-label">Does the Justice Seeker/Prisoner have
                                            a
                                            local guardian?</label>
                                        <select class="form-select" id="has_guardian" name="has_guardian">
                                            <option value="">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="guardian_name" class="form-label">Guardian’s Name</label>
                                        <input type="text" class="form-control" id="guardian_name"
                                            name="guardian_name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="guardian_phone" class="form-label">Guardian’s Phone Number</label>
                                        <input type="text" class="form-control" id="guardian_phone"
                                            name="guardian_phone">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="guardian_address" class="form-label">Guardian’s Address</label>
                                        <textarea class="form-control" id="guardian_address" name="guardian_address" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="guardian_relation" class="form-label">Relation with Guardian</label>
                                        <select class="form-select" id="guardian_relation" name="guardian_relation">
                                            <option value="">Select</option>
                                            <option value="Family Member">Family Member</option>
                                            <option value="Relative">Relative</option>
                                            <option value="Neighbor">Neighbor</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="guardian_surety" class="form-label">Will the Guardian act as
                                            surety?</label>
                                        <select class="form-select" id="guardian_surety" name="guardian_surety">
                                            <option value="">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Legal Representation -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                5. Legal Representation
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="has_lawyer" class="form-label">Does the Justice Seeker/Prisoner
                                            already
                                            have a lawyer?</label>
                                        <select class="form-select" id="has_lawyer" name="has_lawyer">
                                            <option value="">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lawyer_type" class="form-label">Type of Lawyer</label>
                                        <select class="form-select" id="lawyer_type" name="lawyer_type">
                                            <option value="">Select</option>
                                            <option value="Personal">Personal</option>
                                            <option value="NGO">NGO</option>
                                            <option value="District Legal Aid">District Legal Aid</option>
                                            <option value="State Defense">State Defense</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lawyer_name" class="form-label">Lawyer’s Name</label>
                                        <input type="text" class="form-control" id="lawyer_name" name="lawyer_name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lawyer_membership" class="form-label">Lawyer’s Membership
                                            Number</label>
                                        <input type="text" class="form-control" id="lawyer_membership"
                                            name="lawyer_membership">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lawyer_phone" class="form-label">Lawyer’s Phone Number</label>
                                        <input type="text" class="form-control" id="lawyer_phone"
                                            name="lawyer_phone">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 6: Details of Incident -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                6. Details of Incident
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="incident_details" class="form-label">Brief of Incident</label>
                                        <textarea class="form-control" id="incident_details" name="incident_details" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Case Information -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                7. Case Information
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="custody_status" class="form-label">Is the interviewee in police/court
                                            custody?</label>
                                        <select class="form-select" id="custody_status" name="custody_status">
                                            <option value="">Select One</option>
                                            <option value="Police Custody">Police Custody</option>
                                            <option value="Court Custody">Court Custody</option>
                                            <option value="Not Applicable">Not Applicable</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="charges_details" class="form-label">Charges (Details)</label>
                                        <input type="text" class="form-control" id="charges_details"
                                            name="charges_details">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="arrest_date" class="form-label">Date of Arrest</label>
                                        <input type="date" class="form-control" id="arrest_date" name="arrest_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="case_no" class="form-label">Case No</label>
                                        <input type="text" class="form-control" id="case_no" name="case_no">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 8: Nature of Assistance -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                8. Nature of Assistance
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="family_communication_date" class="form-label">Communicate with
                                            families/relatives</label>
                                        <input type="date" class="form-control" id="family_communication_date"
                                            name="family_communication_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="legal_representation" class="form-label">Referred for legal
                                            representation</label>
                                        <select class="form-select" id="legal_representation"
                                            name="legal_representation">
                                            <option value="">Select</option>
                                            <option value="District Legal Aid Office">District Legal Aid Office</option>
                                            <option value="District Project Officer">District Project Officer</option>
                                            <option value="NGO Panel Lawyer">NGO Panel Lawyer</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="legal_representation_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="legal_representation_date"
                                            name="legal_representation_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="collected_vokalatnama_date" class="form-label">Collected Vokalatnama
                                            (Power of Attorney)</label>
                                        <input type="date" class="form-control" id="collected_vokalatnama_date"
                                            name="collected_vokalatnama_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="collected_case_doc" class="form-label">Collected case document (FIR,
                                            Charge Sheet, etc.)</label>
                                        <input type="date" class="form-control" id="collected_case_doc"
                                            name="collected_case_doc">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="identify_sureties" class="form-label">Identify sureties (With National
                                            ID
                                            and Phone No)</label>
                                        <input type="text" class="form-control" id="identify_sureties"
                                            name="identify_sureties">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="witness_communication_date" class="form-label">Communicate with
                                            Witness</label>
                                        <input type="date" class="form-control" id="witness_communication_date"
                                            name="witness_communication_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="medical_report_date" class="form-label">Assist in collecting medical
                                            report</label>
                                        <input type="date" class="form-control" id="medical_report_date"
                                            name="medical_report_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="legal_assistance_date" class="form-label">Legal assistance to
                                            vulnerable
                                            persons</label>
                                        <input type="date" class="form-control" id="legal_assistance_date"
                                            name="legal_assistance_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="assistance_under_custody_date" class="form-label">Assistance under
                                            police
                                            custody</label>
                                        <input type="date" class="form-control" id="assistance_under_custody_date"
                                            name="assistance_under_custody_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="referral_service" class="form-label">Referral for other
                                            services</label>
                                        <select class="form-select" id="referral_service" name="referral_service">
                                            <option value="">Select</option>
                                            <option value="District Legal Aid Office">District Legal Aid Office</option>
                                            <option value="NGOs/RJ/Mediation">NGOs/RJ/Mediation</option>
                                            <option value="Village Court">Village Court</option>
                                            <option value="Safe Home">Safe Home</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="referral_service_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="referral_service_date"
                                            name="referral_service_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 9: Result -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingNine">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                9. Result
                            </button>
                        </h2>
                        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="resolved_dispute_date" class="form-label">Resolved Dispute</label>
                                        <input type="date" class="form-control" id="resolved_dispute_date"
                                            name="resolved_dispute_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="appoint_lawyer_date" class="form-label">Appoint Lawyer</label>
                                        <input type="date" class="form-control" id="appoint_lawyer_date"
                                            name="appoint_lawyer_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="release_status" class="form-label">Released on</label>
                                        <select class="form-select" id="release_status" name="release_status">
                                            <option value="">Select</option>
                                            <option value="Bail">Bail</option>
                                            <option value="Guilty Plea">Guilty Plea</option>
                                            <option value="With Fine">With Fine</option>
                                        </select>
                                    </div>

                                    <!-- Fine Amount Field (Initially Hidden) -->
                                    <div class="col-md-4" id="fine_field" style="display: none;">
                                        <label for="fine_amount" class="form-label">Fine Amount</label>
                                        <input type="text" class="form-control" id="fine_amount" name="fine_amount">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="release_status_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="release_status_date"
                                            name="release_status_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 10: District Legal Aid Office Information -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                10. District Legal Aid Office Information
                            </button>
                        </h2>
                        <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="application_mode" class="form-label">Mode of application</label>
                                        <select class="form-select" id="application_mode" name="application_mode">
                                            <option value="">Select</option>
                                            <option value="Online">Online</option>
                                            <option value="Office Application">Office Application</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="application_mode_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="application_mode_date"
                                            name="application_mode_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="received_application" class="form-label">Application Received?</label>
                                        <select class="form-select" id="received_application"
                                            name="received_application">
                                            <option value="">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="reference_no" style="display: none;">
                                        <label for="reference_no" class="form-label">Reference No</label>
                                        <input type="number" class="form-control" id="reference_no"
                                            name="reference_no">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="type_of_service" class="form-label">Type of Service</label>
                                        <select class="form-select" id="type_of_service" name="type_of_service">
                                            <option value="">Select</option>
                                            <option value="Legal Advice">Legal Advice</option>
                                            <option value="Alternate Dispute Resolution">Alternate Dispute Resolution
                                            </option>
                                            <option value="Filing New Lawsuit">Filing New Lawsuit</option>
                                            <option value="Legal Aid in Existing Case">Legal Aid in Existing Case</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="type_of_service_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="type_of_service_date"
                                            name="type_of_service_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 11: Description of Service Provided -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEleven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                11. Description of Service Provided
                            </button>
                        </h2>
                        <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="service_description" class="form-label">Description of Service</label>
                                        <textarea class="form-control" id="service_description" name="service_description" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 12: Basic Case Information -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwelve">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                12. Basic Case Information
                            </button>
                        </h2>
                        <div id="collapseTwelve" class="accordion-collapse collapse" aria-labelledby="headingTwelve"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="source_of_interview" class="form-label">Source of Interview</label>
                                        <select class="form-select" id="source_of_interview" name="source_of_interview">
                                            <option value="">Select</option>
                                            <option value="Prison Staff">Prison Staff</option>
                                            <option value="Case File">Case File</option>
                                            <option value="Prison Register">Prison Register</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="prison_reg_no" class="form-label">Prison Registration Number</label>
                                        <input type="text" class="form-control" id="prison_reg_no"
                                            name="prison_reg_no">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="case_no" class="form-label">Case Number(s)</label>
                                        <input type="text" class="form-control" id="case_no" name="case_no">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="section_no" class="form-label">Section Number</label>
                                        <input type="text" class="form-control" id="section_no" name="section_no">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="present_court" class="form-label">Present Court’s Name</label>
                                        <input type="text" class="form-control" id="present_court"
                                            name="present_court">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lockup_no" class="form-label">Lock-Up Number (If applicable)</label>
                                        <input type="text" class="form-control" id="lockup_no" name="lockup_no">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="entry_date" class="form-label">Date of Entry in Prison</label>
                                        <input type="date" class="form-control" id="entry_date" name="entry_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="case_transferred" class="form-label">Has the case transferred?</label>
                                        <select class="form-select" id="case_transferred" name="case_transferred">
                                            <option value="">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="current_court_name" style="display: none;">
                                        <label for="current_court" class="form-label">If Yes, Current Court’s Name</label>
                                        <input type="text" class="form-control" id="current_court"
                                            name="current_court">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="case_status" class="form-label">Present Status of Case</label>
                                        <input type="text" class="form-control" id="case_status" name="case_status">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="co_offenders" class="form-label">Number of Co-Offenders (If
                                            any)</label>
                                        <input type="number" class="form-control" id="co_offenders"
                                            name="co_offenders">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="next_court_date" class="form-label">Next Court Date</label>
                                        <input type="date" class="form-control" id="next_court_date"
                                            name="next_court_date">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="facts_of_case" class="form-label">Facts of the Case</label>
                                        <textarea class="form-control" id="facts_of_case" name="facts_of_case" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 13: Imprisonment Information -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThirteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThirteen" aria-expanded="false"
                                aria-controls="collapseThirteen">
                                13. Imprisonment Information
                            </button>
                        </h2>
                        <div id="collapseThirteen" class="accordion-collapse collapse" aria-labelledby="headingThirteen"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="imprisonment_condition" class="form-label">Basic Condition</label>
                                        <select class="form-select" id="imprisonment_condition"
                                            name="imprisonment_condition">
                                            <option value="">Select One</option>
                                            <option value="Regular">Regular</option>
                                            <option value="On Call">On Call</option>
                                            <option value="Safe Custody">Safe Custody</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="imprisonment_status" class="form-label">Status of Imprisonment</label>
                                        <select class="form-select" id="imprisonment_status" name="imprisonment_status">
                                            <option value="">Select One</option>
                                            <option value="Under Trial">Under Trial</option>
                                            <option value="Convicted">Convicted</option>
                                            <option value="Released">Released</option>
                                            <option value="Sentenced but under trial for another offense">Sentenced but
                                                under trial for another offense
                                            </option>
                                            <option value="foreigner">Foreigner</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="special_condition" class="form-label">Special Condition</label>
                                        <select class="form-select" id="special_condition" name="special_condition">
                                            <option value="">Select</option>
                                            <option value="Critical Ill">Critical Ill</option>
                                            <option value="Disabled">Disabled</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="arrest_date" class="form-label">Date of Arrest</label>
                                        <input type="date" class="form-control" id="arrest_date" name="arrest_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="surrender_date" class="form-label">Date of Surrender</label>
                                        <input type="date" class="form-control" id="surrender_date"
                                            name="surrender_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 14: Nature of Assistance in Prison -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFourteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFourteen" aria-expanded="false"
                                aria-controls="collapseFourteen">
                                14. Nature of Assistance in Prison
                            </button>
                        </h2>
                        <div id="collapseFourteen" class="accordion-collapse collapse" aria-labelledby="headingFourteen"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="prison_family_communication" class="form-label">Communicate with
                                            families/relatives</label>
                                        <input type="date" class="form-control" id="prison_family_communication"
                                            name="prison_family_communication">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="prison_legal_representation" class="form-label">Referred for legal
                                            representation</label>
                                        <select class="form-select" id="prison_legal_representation"
                                            name="prison_legal_representation">
                                            <option value="">Select</option>
                                            <option value="District Legal Aid Offic">District Legal Aid Office</option>
                                            <option value="District Project Officer">District Project Officer</option>
                                            <option value="NGO Panel Lawyer">NGO Panel Lawyer</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="prison_legal_representation_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="prison_legal_representation_date"
                                            name="prison_legal_representation_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="next_court_collection_date" class="form-label">Collect next court date
                                            (Collection Date)</label>
                                        <input type="date" class="form-control" id="next_court_collection_date"
                                            name="next_court_collection_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="next_court_date" class="form-label">Next Court Date</label>
                                        <input type="date" class="form-control" id="next_court_date"
                                            name="next_court_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="collected_case_doc_prison" class="form-label">Collected case document
                                            (FIR, Charge Sheet, etc.)</label>
                                        <input type="date" class="form-control" id="collected_case_doc_prison"
                                            name="collected_case_doc_prison">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="identify_sureties_prison_nid" class="form-label">Identify Sureties
                                            (With
                                            National ID)</label>
                                        <input type="text" class="form-control" id="identify_sureties_prison_nid"
                                            name="identify_sureties_prison_nid">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="identify_sureties_prison_phone" class="form-label">Identify Sureties
                                            (With
                                            Phone Number)</label>
                                        <input type="text" class="form-control" id="identify_sureties_prison_phone"
                                            name="identify_sureties_prison_phone">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="witness_communication_prison" class="form-label">Communicate with
                                            Witness</label>
                                        <input type="date" class="form-control" id="witness_communication_prison"
                                            name="witness_communication_prison">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="bail_bond_submission" class="form-label">Communicate for submission of
                                            bail bond</label>
                                        <input type="date" class="form-control" id="bail_bond_submission"
                                            name="bail_bond_submission">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="court_order_communication" class="form-label">Communicate with
                                            relevant
                                            institutions for Court Orders</label>
                                        <input type="date" class="form-control" id="court_order_communication"
                                            name="court_order_communication">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="application_certified_copies" class="form-label">Application filed for
                                            certified copies</label>
                                        <input type="date" class="form-control" id="application_certified_copies"
                                            name="application_certified_copies">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="appeal_assistance" class="form-label">Assistance in appeal</label>
                                        <input type="date" class="form-control" id="appeal_assistance"
                                            name="appeal_assistance">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="ministerial_communication" class="form-label">Communicate with
                                            Ministries,
                                            Embassy, etc.</label>
                                        <input type="date" class="form-control" id="ministerial_communication"
                                            name="ministerial_communication">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="other_legal_assistance" class="form-label">Other legal
                                            assistance</label>
                                        <select class="form-select" id="other_legal_assistance"
                                            name="other_legal_assistance">
                                            <option value="">Select</option>
                                            <option value="fine_assistance">Assist in depositing fine</option>
                                            <option value="court_communication">Communicate with court</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="other_legal_assistance_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="other_legal_assistance_date"
                                            name="other_legal_assistance_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 15: Result -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFifteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFifteen" aria-expanded="false"
                                aria-controls="collapseFifteen">
                                15. Result
                            </button>
                        </h2>
                        <div id="collapseFifteen" class="accordion-collapse collapse" aria-labelledby="headingFifteen"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="released_on" class="form-label">Released on</label>
                                        <select class="form-select" id="released_on" name="released_on">
                                            <option value="">Select</option>
                                            <option value="Bail">Bail</option>
                                            <option value="Discharged">Discharged</option>
                                            <option value="Acquitted">Acquitted</option>
                                            <option value="Guilty Plea">Guilty Plea</option>
                                            <option value="Released from Safe Custody">Released from Safe Custody</option>
                                            <option value="Foreign Prisoner">Foreign Prisoner</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="released_on_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="released_on_date"
                                            name="released_on_date">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="send_to" class="form-label">Send to</label>
                                        <select class="form-select" id="send_to" name="send_to">
                                            <option value="">Select</option>
                                            <option value="Safe Home">Safe Home</option>
                                            <option value="Legal Guardian">Legal Guardian</option>
                                            <option value="Own Custody">Own Custody</option>
                                            <option value="NGO Shelter">NGO Shelter</option>
                                            <option value="Child Development Centre">Child Development Centre</option>
                                            <option value="Victim Support Centre">Victim Support Centre</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="send_to_date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="send_to_date"
                                            name="send_to_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="convicted_length" class="form-label">Convicted-Length of
                                            Sentence</label>
                                        <input type="date" class="form-control" id="convicted_length"
                                            name="convicted_length">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="convicted_sentence_expire" class="form-label">Convicted-Sentence
                                            Expire
                                            on</label>
                                        <input type="date" class="form-control" id="convicted_sentence_expire"
                                            name="convicted_sentence_expire">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="result_of_appeal" class="form-label">Result of Appeal</label>
                                        <select class="form-select" id="result_of_appeal" name="result_of_appeal">
                                            <option value="">Select</option>
                                            <option value="Sentence Upheld">Sentence Upheld</option>
                                            <option value="Acquitted">Acquitted</option>
                                            <option value="Sentence Reduced">Sentence Reduced</option>
                                            <option value="Sentence Enhanced">Sentence Enhanced</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="date_of_reliefe" class="form-label">Date of Released from
                                            Prison</label>
                                        <input type="date" class="form-control" id="date_of_reliefe"
                                            name="date_of_reliefe">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section 16: Description of Service Provided -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSixteen">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSixteen" aria-expanded="false"
                                aria-controls="collapseSixteen">
                                16. Description of Service Provided
                            </button>
                        </h2>
                        <div id="collapseSixteen" class="accordion-collapse collapse" aria-labelledby="headingSixteen"
                            data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="service_description" class="form-label">Description of
                                            Service</label>
                                        <textarea class="form-control" id="service_description" name="service_description" rows="6"></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="file_closure_date" class="form-label">File Closed Date</label>
                                        <input type="date" class="form-control" id="file_closure_date"
                                            name="file_closure_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeventeen">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSeventeen" aria-expanded="true"
                                aria-controls="collapseSeventeen">
                                17. Upload Additional Documents
                            </button>
                        </h2>
                        <div id="collapseSeventeen" class="accordion-collapse collapse show"
                            aria-labelledby="headingSeventeen" data-bs-parent="#caseFormAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="fileUpload" class="form-label">Upload Files (Multiple)</label>
                                        <input type="file" class="form-control" id="fileUpload"
                                            name="fileUpload[]" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btnCustom btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Reusable function to toggle the visibility of dependent fields
            function toggleDependentFields(triggerElementId, targetElementIds, showValue, isParentColumn = false) {
                const triggerElement = document.getElementById(triggerElementId);

                // Function to toggle visibility
                function toggleFields() {
                    // Convert `showValue` to an array (to handle both single & multiple values)
                    const showValues = Array.isArray(showValue) ? showValue : [showValue];

                    // Check if the selected value exists in `showValues`
                    const showFields = showValues.includes(triggerElement.value);

                    targetElementIds.forEach(function(targetId) {
                        const targetElement = document.getElementById(targetId);

                        if (isParentColumn) {
                            const parentElement = targetElement.closest(
                                '.col-md-4, .col-md-6, .col-md-3'); // Support for different column sizes
                            if (parentElement) {
                                parentElement.style.display = showFields ? "block" :
                                    "none"; // Show/Hide entire column
                            }
                        } else {
                            targetElement.style.display = showFields ? "block" :
                                "none"; // Show/Hide input/select field
                        }
                    });
                }

                // Add event listener to trigger element
                triggerElement.addEventListener("change", toggleFields);

                // Run the function on page load to set initial visibility
                toggleFields();
            }

            // List of fields to toggle visibility for
            const fieldToggles = [{
                    id: "marital_status",
                    targets: ["spouse_field"],
                    showValue: "Married"
                },
                {
                    id: "children_with_prisoner",
                    targets: ["child_sex", "child_age"],
                    showValue: "Yes"
                },
                {
                    id: "has_guardian",
                    targets: ["guardian_name", "guardian_phone", "guardian_address", "guardian_relation",
                        "guardian_surety"
                    ],
                    showValue: "Yes",
                    isParentColumn: true
                },
                {
                    id: "has_lawyer",
                    targets: ["lawyer_type", "lawyer_name", "lawyer_membership", "lawyer_phone"],
                    showValue: "Yes",
                    isParentColumn: true
                },
                {
                    id: "custody_status",
                    targets: ["charges_details", "arrest_date", "case_no"],
                    showValue: ["Police Custody", "Court Custody", "Not Applicable"],
                    isParentColumn: true
                },
                {
                    id: "release_status",
                    targets: ["fine_field"],
                    showValue: "fine"
                },
                {
                    id: "received_application",
                    targets: ["reference_no"],
                    showValue: "Yes"
                },
                {
                    id: "case_transferred",
                    targets: ["current_court_name"],
                    showValue: "Yes"
                },
            ];

            // Apply toggle function for all cases
            fieldToggles.forEach(function(toggle) {
                toggleDependentFields(toggle.id, toggle.targets, toggle.showValue, toggle.isParentColumn);
            });
        });
    </script>
@endpush
