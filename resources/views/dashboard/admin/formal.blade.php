@extends('dashboard.layouts.admin-layout')

@section('title', 'Court Prison Data')

@push('styles')
    <!-- Add your custom CSS here for collapsible cards or any additional styling -->
    <style>
        .required:after {
            content: " *";
            color: red;
        }
    
        .card-title {
            font-size: 20px;
            color: white;
            font-family: 'Lucida Sans', 'SolaimanLipi';
        }
    
        .collapsible-card .card-header {
            cursor: pointer;
            display: flex;
            /* Flexbox for alignment */
            justify-content: space-between;
            /* Space between title and icon */
            align-items: center;
            /* Center align vertically */
        }
    
        /* Add margin between cards */
        .card {
            margin-bottom: 15px;
        }
    
        /* Adjust card padding */
        .card-body {
            padding: 15px;
        }
    
        /* Space between form elements inside cards */
        .form-group {
            margin-bottom: 20px;
        }
    
        /* Card header padding */
        .card-header {
            padding: 10px 15px;
            background-color: #343a40;
            color: white;
        }
    
        /* Control visibility and padding of collapsing cards */
        .card.collapsing .card-body {
            padding: 0 !important;
        }
    
        /* Ensure margin for collapsible cards */
        .collapsible-card {
            margin-bottom: 20px;
        }
    
        /* Improve card border and shadow */
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    
        .custom-card {
            border-width: 2px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    
        .custom-card:hover {
            border-color: #28a745;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
    
        /* Add the toggle icon dynamically using CSS */
        .card-header::after {
            content: "+";
            font-size: 20px;
            font-weight: bold;
            margin-left: auto;
            /* Push icon to the right */
            transition: transform 0.2s ease;
        }
    
        /* Change the icon to "-" when the collapsible section is expanded */
        .card-header[aria-expanded="true"]::after {
            content: "-";
        }
    
        /* Animation for collapse */
        .collapse {
            transition: max-height 0.35s ease-out;
            max-height: 0;
            overflow: hidden;
        }
    
        .collapse.show {
            max-height: 1000px; /* Adjust this to the maximum height of the content */
        }
    
        /* Style for disabled input and form elements */
        .form-control:disabled {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>
    
@endpush

@section('content')


    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Court Prison Form Section -->
                            <div class="collapsible-card custom-card">
                                <div class="card-header bg-dark" id="institutionCardHeader" role="button" data-bs-toggle="collapse" data-bs-target="#institutionCard" aria-expanded="true">
                                    <h3 class="card-title">Institution Information</h3>
                                </div>
                                <div id="institutionCard" class="collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="institution_name">Institution Name</label>
                                                    <select class="form-control" id="institution_name" name="institution_name" required>
                                                        <option value="">--Select an Option--</option>
                                                        <option value="prison">Prison</option>
                                                        <option value="court">Court</option>
                                                        <option value="police_station">Police Station</option>
                                                    </select>
                                                </div>
                                            </div>
                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="central_id">Central ID</label>
                                                    <input type="text" class="form-control" id="central_id" name="central_id" value="RAN-LA-01" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="district">District</label>
                                                    <input type="text" class="form-control" id="district" name="district" value="Rangpur" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Profile and Session Information Section -->
                            <div class="collapsible-card custom-card">
                                <div class="card-header bg-secondary" data-bs-toggle="collapse" data-bs-target="#profileCard" aria-expanded="false">
                                    <h3 class="card-title">Profile / Session Information</h3>
                                </div>
                                <div id="profileCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date_of_interview">Date of Interview</label>
                                                    <input type="date" class="form-control" id="date_of_interview" name="date_of_interview" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name_of_prison_court_police_station">Name of Prison/Court/Police Station</label>
                                                    <input type="text" class="form-control" id="name_of_prison_court_police_station" name="name_of_prison_court_police_station" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="national_id">National ID</label>
                                                    <input type="text" class="form-control" id="national_id" name="national_id" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="phone_number">Phone Number</label>
                                                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="fathers_mothers_spouses_name">Father's/Mother's/Spouse's Name</label>
                                                    <input type="text" class="form-control" id="fathers_mothers_spouses_name" name="fathers_mothers_spouses_name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="sex">Sex</label>
                                                    <select class="form-control" id="sex" name="sex" required>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Transgender Person">Transgender Person</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="age">Age</label>
                                                    <input type="number" class="form-control" id="age" name="age" required>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="disability">Disability</label>
                                                    <select class="form-control" id="disability" name="disability" required>
                                                        <option value="No">No</option>
                                                        <option value="Yes">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="last_monthly_income">Last Monthly Income</label>
                                                    <input type="number" step="0.01" class="form-control" id="last_monthly_income" name="last_monthly_income" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Beneficiary Analysis Section -->
                            <div class="collapsible-card custom-card">
                                <div class="card-header bg-dark" data-bs-toggle="collapse" data-bs-target="#beneficiaryCard" aria-expanded="false">
                                    <h3 class="card-title">Beneficiary Analysis</h3>
                                </div>
                                <div id="beneficiaryCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="satisfaction_accessibility_of_justice_services">Satisfaction - Accessibility of Justice Services</label>
                                                    <select class="form-control" id="satisfaction_accessibility_of_justice_services" name="satisfaction_accessibility_of_justice_services" required>
                                                        <option value="">--Select an Option--</option>
                                                        <option value="1">Strongly Dissatisfied</option>
                                                        <option value="2">Moderately Dissatisfied</option>
                                                        <option value="3">Neither Satisfied nor Dissatisfied</option>
                                                        <option value="4">Moderately Satisfied</option>
                                                        <option value="5">Strongly Satisfied</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="satisfaction_speed_of_process">Satisfaction - Speed of Process</label>
                                                    <select class="form-control" id="satisfaction_speed_of_process" name="satisfaction_speed_of_process" required>
                                                        <option value="">--Select an Option--</option>
                                                        <option value="1">Strongly Dissatisfied</option>
                                                        <option value="2">Moderately Dissatisfied</option>
                                                        <option value="3">Neither Satisfied nor Dissatisfied</option>
                                                        <option value="4">Moderately Satisfied</option>
                                                        <option value="5">Strongly Satisfied</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="satisfaction_cost_of_accessing_justice">Satisfaction - Cost of Accessing Justice</label>
                                                    <select class="form-control" id="satisfaction_cost_of_accessing_justice" name="satisfaction_cost_of_accessing_justice" required>
                                                        <option value="">--Select an Option--</option>
                                                        <option value="1">Strongly Dissatisfied</option>
                                                        <option value="2">Moderately Dissatisfied</option>
                                                        <option value="3">Neither Satisfied nor Dissatisfied</option>
                                                        <option value="4">Moderately Satisfied</option>
                                                        <option value="5">Strongly Satisfied</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="trust_in_justice_system_experience">Trust in Justice System (Experience)</label>
                                                    <select class="form-control" id="trust_in_justice_system_experience" name="trust_in_justice_system_experience" required>
                                                        <option value="">--Select an Option--</option>
                                                        <option value="1">Strongly Dissatisfied</option>
                                                        <option value="2">Moderately Dissatisfied</option>
                                                        <option value="3">Neither Satisfied nor Dissatisfied</option>
                                                        <option value="4">Moderately Satisfied</option>
                                                        <option value="5">Strongly Satisfied</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Support in Court and Police Station Section -->
                            <div class="collapsible-card custom-card">
                                <div class="card-header bg-secondary" data-bs-toggle="collapse" data-bs-target="#courtSupportCard" aria-expanded="false">
                                    <h3 class="card-title">Support in Court and Police Station - Result</h3>
                                </div>
                                <div id="courtSupportCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="latest_intervention_release_date">Latest Intervention Release Date</label>
                                                    <input type="date" class="form-control" id="latest_intervention_release_date" name="latest_intervention_release_date">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="released_on">Released On</label>
                                                    <select class="form-control" id="released_on" name="released_on">
                                                        <option value="">--Select an Option--</option>
                                                        <option value="Bail">Bail</option>
                                                        <option value="Guilty Plea">Guilty Plea</option>
                                                        <option value="With Fine">With Fine</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date_of_released_on">Date of Released On</label>
                                                    <input type="date" class="form-control" id="date_of_released_on" name="date_of_released_on">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="sent_to_prison_date">Sent to Prison Date</label>
                                                    <input type="date" class="form-control" id="sent_to_prison_date" name="sent_to_prison_date">
                                                </div>
                                            </div>
                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="referred_to">Referred To</label>
                                                    <select class="form-control" id="referred_to" name="referred_to">
                                                        <option value="">--Select an Option--</option>
                                                        <option value="village_court">Village Court</option>
                                                        <option value="arbitration_council">Arbitration Council</option>
                                                        <option value="union_upazila_parishad">Union or Upazila Parishad or Pourashava</option>
                                                        <option value="ngos_rj_mediation">NGOs for RJ or Mediation</option>
                                                        <option value="community_mediation">Community Mediation</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="other_referred_to">Other Referred To</label>
                                                    <input type="text" class="form-control" id="other_referred_to" name="other_referred_to">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date_of_referred_to">Date of Referred To</label>
                                                    <input type="date" class="form-control" id="date_of_referred_to" name="date_of_referred_to">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="sent_to">Sent To</label>
                                                    <select class="form-control" id="sent_to" name="sent_to">
                                                        <option value="">--Select an Option--</option>
                                                        <option value="safe_home">Safe Home</option>
                                                        <option value="legal_guardian">Legal Guardian</option>
                                                        <option value="own_custody">Own Custody</option>
                                                        <option value="juvenile_development_centre">Juvenile Development Centre</option>
                                                        <option value="victim_support_center">Victim Support Center</option>
                                                        <option value="ngo_shelter">NGO Shelter</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="other_sent_to">Other Sent to</label>
                                                    <input type="text" class="form-control" id="other_sent_to" name="other_sent_to">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date_of_sent_to">Date of Sent to</label>
                                                    <input type="date" class="form-control" id="date_of_sent_to" name="date_of_sent_to">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Support in Court and Police Station - Intervention Section -->
                            <div class="collapsible-card custom-card" data-bs-toggle="collapse" data-bs-target="#courtInterventionCard" aria-expanded="false">
                                <div class="card-header bg-dark">
                                    <h3 class="card-title">Support in Court and Police Station - Intervention</h3>
                                </div>
                                <div id="courtInterventionCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Provided General Information Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="provided_general_information_date">Provided General
                                                        Information Date</label>
                                                    <input type="date" class="form-control"
                                                        id="provided_general_information_date"
                                                        name="provided_general_information_date">
                                                </div>
                                            </div>
                                            <!-- Provided Required Legal Information Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="provided_required_legal_information_date">Provided Required
                                                        Legal Information Date</label>
                                                    <input type="date" class="form-control"
                                                        id="provided_required_legal_information_date"
                                                        name="provided_required_legal_information_date">
                                                </div>
                                            </div>
                                            <!-- Collected Case Related Information Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_case_related_information_date">Collected Case
                                                        Related Information Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_case_related_information_date"
                                                        name="collected_case_related_information_date">
                                                </div>
                                            </div>
                                            <!-- Collected Case Documents Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_case_documents_date">Collected Case Documents
                                                        Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_case_documents_date"
                                                        name="collected_case_documents_date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Collected Vokalatnama Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_vokalatnama_date">Collected Vokalatnama
                                                        Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_vokalatnama_date" name="collected_vokalatnama_date">
                                                </div>
                                            </div>
                                            <!-- Contacted with Families/Relatives Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contacted_with_families_relatives_date">Contacted with
                                                        Families/Relatives Date</label>
                                                    <input type="date" class="form-control"
                                                        id="contacted_with_families_relatives_date"
                                                        name="contacted_with_families_relatives_date">
                                                </div>
                                            </div>
                                            <!-- Followed up with Lawyers Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="followed_up_with_lawyers_date">Followed up with Lawyers
                                                        Date</label>
                                                    <input type="date" class="form-control"
                                                        id="followed_up_with_lawyers_date"
                                                        name="followed_up_with_lawyers_date">
                                                </div>
                                            </div>
                                            <!-- Contacted with Witnesses Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contacted_with_witnesses_date">Contacted with Witnesses
                                                        Date</label>
                                                    <input type="date" class="form-control"
                                                        id="contacted_with_witnesses_date"
                                                        name="contacted_with_witnesses_date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Referred for Legal Representation DLAC Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="referred_for_legal_representation_dlac_date">Referred for
                                                        Legal Representation DLAC Date</label>
                                                    <input type="date" class="form-control"
                                                        id="referred_for_legal_representation_dlac_date"
                                                        name="referred_for_legal_representation_dlac_date">
                                                </div>
                                            </div>
                                            <!-- Find Out Sureties/Local Guardian Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="find_out_sureties_local_guardian_date">Find Out
                                                        Sureties/Local Guardian Date</label>
                                                    <input type="date" class="form-control"
                                                        id="find_out_sureties_local_guardian_date"
                                                        name="find_out_sureties_local_guardian_date">
                                                </div>
                                            </div>
                                            <!-- Assisted in Collecting Medical Report Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="assisted_in_collecting_medical_report_date">Assisted in
                                                        Collecting Medical Report Date</label>
                                                    <input type="date" class="form-control"
                                                        id="assisted_in_collecting_medical_report_date"
                                                        name="assisted_in_collecting_medical_report_date">
                                                </div>
                                            </div>
                                            <!-- Assisted in Filing Case Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="assisted_in_filing_case_date">Assisted in Filing Case
                                                        Date</label>
                                                    <input type="date" class="form-control"
                                                        id="assisted_in_filing_case_date"
                                                        name="assisted_in_filing_case_date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Assisted in Filing General Diary Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="assisted_in_filing_general_diary_date">Assisted in Filing
                                                        General Diary Date</label>
                                                    <input type="date" class="form-control"
                                                        id="assisted_in_filing_general_diary_date"
                                                        name="assisted_in_filing_general_diary_date">
                                                </div>
                                            </div>
                                            <!-- Counseling Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="counseling_date">Counseling Date</label>
                                                    <input type="date" class="form-control" id="counseling_date"
                                                        name="counseling_date">
                                                </div>
                                            </div>
                                            <!-- Total Intervention -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="total_intervention">Total Intervention</label>
                                                    <input type="number" class="form-control" id="total_intervention"
                                                        name="total_intervention">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Support in Prison Section -->
                            <div class="collapsible-card custom-card" data-bs-toggle="collapse" data-bs-target="#prisonSupportCard" aria-expanded="false">
                                <div class="card-header bg-secondary">
                                    <h3 class="card-title">Support in Prison - Information</h3>
                                </div>
                                <div id="prisonSupportCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Source of Interview -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="source_of_interview">Source of Interview</label>
                                                    <select class="form-control" id="source_of_interview"
                                                        name="source_of_interview">
                                                        <option value="">-- Select an Option --</option>
                                                        <option value="Prison Register">Prison Register</option>
                                                        <option value="Prison Staff">Prison Staff</option>
                                                        <option value="Case File">Case File</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Other Source of Interview -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="other_source_of_interview">Other Source of
                                                        Interview</label>
                                                    <input type="text" class="form-control"
                                                        id="other_source_of_interview" name="other_source_of_interview">
                                                </div>
                                            </div>
                                            <!-- Nature of Imprisonment -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="nature_of_imprisonment">Nature of Imprisonment</label>
                                                    <select class="form-control" id="nature_of_imprisonment"
                                                        name="nature_of_imprisonment">
                                                        <option value="">-- Select an Option --</option>
                                                        <option value="Under trial">Under trial</option>
                                                        <option value="Convicted">Convicted</option>
                                                        <option value="Sentenced but undertrial for another offence">
                                                            Sentenced but undertrial for another offence</option>
                                                        <option value="Safe Custody">Safe Custody</option>
                                                        <option value="Juvenile">Juvenile</option>
                                                        <option value="Released Prisoners">Released Prisoners</option>
                                                        <option value="Foreigner">Foreigner</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Date of Entry into Prison -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date_of_entry_into_prison">Date of Entry into
                                                        Prison</label>
                                                    <input type="date" class="form-control"
                                                        id="date_of_entry_into_prison" name="date_of_entry_into_prison">
                                                </div>
                                            </div>

                                            <!-- Prison Registry Number -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="prison_registry_number">Prison Registry Number</label>
                                                    <input type="text" class="form-control"
                                                        id="prison_registry_number" name="prison_registry_number">
                                                </div>
                                            </div>
                                            <!-- Latest Intervention Release Date (Prison) -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="latest_intervention_release_date_prison">Latest
                                                        Intervention Release Date (Prison)</label>
                                                    <input type="date" class="form-control"
                                                        id="latest_intervention_release_date_prison"
                                                        name="latest_intervention_release_date_prison">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <!-- Support in Prison - Result Section -->
                            <div class="collapsible-card custom-card" data-bs-toggle="collapse" data-bs-target="#prisonSupportResultCard" aria-expanded="false">
                                <div class="card-header bg-dark">
                                    <h3 class="card-title">Support in Prison - Result</h3>
                                </div>
                                <div id="prisonSupportResultCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Released on Bail -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="released_on_bail">Released on Bail</label>
                                                    <input type="text" class="form-control" id="released_on_bail"
                                                        name="released_on_bail">
                                                </div>
                                            </div>
                                            <!-- Date of Release on Bail -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date_of_release_on_bail">Date of Release on Bail</label>
                                                    <input type="date" class="form-control"
                                                        id="date_of_release_on_bail" name="date_of_release_on_bail">
                                                </div>
                                            </div>
                                            <!-- Released on Other -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="released_on_other">Released on Other</label>
                                                    <input type="text" class="form-control" id="released_on_other"
                                                        name="released_on_other">
                                                </div>
                                            </div>
                                            <!-- Date of Release on Others -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date_of_release_on_others">Date of Release on
                                                        Others</label>
                                                    <input type="date" class="form-control"
                                                        id="date_of_release_on_others" name="date_of_release_on_others">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Convicted through Paralegal Intervention Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="convicted_through_paralegal_intervention_date">Convicted
                                                        Through Paralegal Intervention Date</label>
                                                    <input type="date" class="form-control"
                                                        id="convicted_through_paralegal_intervention_date"
                                                        name="convicted_through_paralegal_intervention_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Support in Prison - Pre-Release Intervention Section -->
                            <div class="collapsible-card custom-card" data-bs-toggle="collapse" data-bs-target="#prisonPreReleaseInterventionCard" aria-expanded="false">
                                <div class="card-header bg-secondary">
                                    <h3 class="card-title">Support in Prison - Pre-Release Intervention</h3>
                                </div>
                                <div id="prisonPreReleaseInterventionCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Legal Assistance by Panel Lawyers Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="legal_assistance_by_panel_lawyers_date">Legal Assistance by
                                                        Panel Lawyers Date</label>
                                                    <input type="date" class="form-control"
                                                        id="legal_assistance_by_panel_lawyers_date"
                                                        name="legal_assistance_by_panel_lawyers_date">
                                                </div>
                                            </div>
                                            <!-- Referred to DLAC/NGOs for Legal Aid Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="referred_to_dlac_ngos_for_legal_aid_date">Referred to
                                                        DLAC/NGOs for Legal Aid Date</label>
                                                    <input type="date" class="form-control"
                                                        id="referred_to_dlac_ngos_for_legal_aid_date"
                                                        name="referred_to_dlac_ngos_for_legal_aid_date">
                                                </div>
                                            </div>
                                            <!-- Collected Case Related Information (Prison) Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_case_related_information_prison_date">Collected
                                                        Case Related Information (Prison) Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_case_related_information_prison_date"
                                                        name="collected_case_related_information_prison_date">
                                                </div>
                                            </div>
                                            <!-- Collected Case Documents (Prison) Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_case_documents_prison_date">Collected Case
                                                        Documents (Prison) Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_case_documents_prison_date"
                                                        name="collected_case_documents_prison_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Collected Vokalatnama (Prison) Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_vokalatnama_prison_date">Collected Vokalatnama
                                                        (Prison) Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_vokalatnama_prison_date"
                                                        name="collected_vokalatnama_prison_date">
                                                </div>
                                            </div>
                                            <!-- Application Filed for Time Petition Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="application_filed_for_time_petition_date">Application Filed
                                                        for Time Petition Date</label>
                                                    <input type="date" class="form-control"
                                                        id="application_filed_for_time_petition_date"
                                                        name="application_filed_for_time_petition_date">
                                                </div>
                                            </div>
                                            <!-- Application Filed for Certified Copies Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="application_filed_for_certified_copies_date">Application
                                                        Filed for Certified Copies Date</label>
                                                    <input type="date" class="form-control"
                                                        id="application_filed_for_certified_copies_date"
                                                        name="application_filed_for_certified_copies_date">
                                                </div>
                                            </div>
                                            <!-- Application Filed for Hazira Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="application_filed_for_hazira_date">Application Filed for
                                                        Hazira Date</label>
                                                    <input type="date" class="form-control"
                                                        id="application_filed_for_hazira_date"
                                                        name="application_filed_for_hazira_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Application Filed for Others Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="application_filed_for_others_date">Application Filed for
                                                        Others Date</label>
                                                    <input type="date" class="form-control"
                                                        id="application_filed_for_others_date"
                                                        name="application_filed_for_others_date">
                                                </div>
                                            </div>
                                            <!-- Collected Information on Call Prisoners Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_information_on_call_prisoners_date">Collected
                                                        Information on Call Prisoners Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_information_on_call_prisoners_date"
                                                        name="collected_information_on_call_prisoners_date">
                                                </div>
                                            </div>
                                            <!-- Collected Next Court Date on Call Prisoners Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="collected_next_court_date_on_call_prisoners_date">Collected
                                                        Next Court Date on Call Prisoners Date</label>
                                                    <input type="date" class="form-control"
                                                        id="collected_next_court_date_on_call_prisoners_date"
                                                        name="collected_next_court_date_on_call_prisoners_date">
                                                </div>
                                            </div>
                                            <!-- Court Update Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="court_update_date">Court Update Date</label>
                                                    <input type="date" class="form-control" id="court_update_date"
                                                        name="court_update_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Urge Court to Send Conviction Warrant to Prison Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="urge_court_to_send_conviction_warrant_to_prison_date">Urge
                                                        Court to Send Conviction Warrant to Prison Date</label>
                                                    <input type="date" class="form-control"
                                                        id="urge_court_to_send_conviction_warrant_to_prison_date"
                                                        name="urge_court_to_send_conviction_warrant_to_prison_date">
                                                </div>
                                            </div>
                                            <!-- Communicate to Prison for Bail Bond Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="communicate_to_prison_for_bail_bond_date">Communicate to
                                                        Prison for Bail Bond Date</label>
                                                    <input type="date" class="form-control"
                                                        id="communicate_to_prison_for_bail_bond_date"
                                                        name="communicate_to_prison_for_bail_bond_date">
                                                </div>
                                            </div>
                                            <!-- Communicate Other Court Orders to Institutions Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label
                                                        for="communicate_other_court_orders_to_institutions_date">Communicate
                                                        Other Court Orders to Institutions Date</label>
                                                    <input type="date" class="form-control"
                                                        id="communicate_other_court_orders_to_institutions_date"
                                                        name="communicate_other_court_orders_to_institutions_date">
                                                </div>
                                            </div>
                                            <!-- Identify Sureties Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="identify_sureties_date">Identify Sureties Date</label>
                                                    <input type="date" class="form-control"
                                                        id="identify_sureties_date" name="identify_sureties_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Contacted with Families/Relatives (Prison) Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contacted_with_families_relatives_prison_date">Contacted
                                                        with Families/Relatives (Prison) Date</label>
                                                    <input type="date" class="form-control"
                                                        id="contacted_with_families_relatives_prison_date"
                                                        name="contacted_with_families_relatives_prison_date">
                                                </div>
                                            </div>
                                            <!-- Followed Up with Lawyers (Prison) Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="followed_up_with_lawyers_prison_date">Followed Up with
                                                        Lawyers (Prison) Date</label>
                                                    <input type="date" class="form-control"
                                                        id="followed_up_with_lawyers_prison_date"
                                                        name="followed_up_with_lawyers_prison_date">
                                                </div>
                                            </div>
                                            <!-- Communicate with Institutions for Witness Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="communicate_with_institutions_for_witness_date">Communicate
                                                        with Institutions for Witness Date</label>
                                                    <input type="date" class="form-control"
                                                        id="communicate_with_institutions_for_witness_date"
                                                        name="communicate_with_institutions_for_witness_date">
                                                </div>
                                            </div>
                                            <!-- Assist in Depositing Fine for Convicted Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="assist_in_depositing_fine_for_convicted_date">Assist in
                                                        Depositing Fine for Convicted Date</label>
                                                    <input type="date" class="form-control"
                                                        id="assist_in_depositing_fine_for_convicted_date"
                                                        name="assist_in_depositing_fine_for_convicted_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Contacted with Social Welfare Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contacted_with_social_welfare_date">Contacted with Social
                                                        Welfare Date</label>
                                                    <input type="date" class="form-control"
                                                        id="contacted_with_social_welfare_date"
                                                        name="contacted_with_social_welfare_date">
                                                </div>
                                            </div>
                                            <!-- Pre-Release Feedback Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pre_release_feedback_date">Pre-Release Feedback
                                                        Date</label>
                                                    <input type="date" class="form-control"
                                                        id="pre_release_feedback_date" name="pre_release_feedback_date">
                                                </div>
                                            </div>
                                            <!-- Contacted with Embassy/Foreign Ministry Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contacted_with_embassy_foreign_ministry_date">Contacted
                                                        with Embassy/Foreign Ministry Date</label>
                                                    <input type="date" class="form-control"
                                                        id="contacted_with_embassy_foreign_ministry_date"
                                                        name="contacted_with_embassy_foreign_ministry_date">
                                                </div>
                                            </div>
                                            <!-- Total Intervention Prison -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="total_intervention_prison">Total Intervention
                                                        Prison</label>
                                                    <input type="number" class="form-control"
                                                        id="total_intervention_prison" name="total_intervention_prison">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Post-Release Counseling Section -->
                            <div class="collapsible-card custom-card" data-bs-toggle="collapse" data-bs-target="#postReleaseCard" aria-expanded="false">
                                <div class="card-header bg-dark">
                                    <h3 class="card-title">Post-Release Counseling</h3>
                                </div>
                                <div id="postReleaseCard" class="collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Post Release Counseling Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="post_release_counseling_date">Post Release Counseling
                                                        Date</label>
                                                    <input type="date" class="form-control"
                                                        id="post_release_counseling_date"
                                                        name="post_release_counseling_date">
                                                </div>
                                            </div>

                                            <!-- Follow Up Cases Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="follow_up_cases_date">Follow Up Cases Date</label>
                                                    <input type="date" class="form-control" id="follow_up_cases_date"
                                                        name="follow_up_cases_date">
                                                </div>
                                            </div>

                                            <!-- Contacted with Families Post Release Date -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contacted_with_families_post_release_date">Contacted with
                                                        Families Post Release Date</label>
                                                    <input type="date" class="form-control"
                                                        id="contacted_with_families_post_release_date"
                                                        name="contacted_with_families_post_release_date">
                                                </div>
                                            </div>

                                            <!-- Total Intervention Post Release -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="total_intervention_post_release">Total Intervention Post
                                                        Release</label>
                                                    <input type="number" class="form-control"
                                                        id="total_intervention_post_release"
                                                        name="total_intervention_post_release">
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <!-- Remarks Field (Single Column) -->
                            <div class="card">

                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Enter remarks here..."></textarea>
                                    </div>
                                </div>
                            </div>



                            <!-- Submit Button -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Generic function to toggle "disabled" state based on dropdown value
            function setupDropdownToggle(dropdownId, dependentFieldIds = [], triggerValues = [],
                specialCaseCallback = null) {
                const dropdown = document.getElementById(dropdownId);

                if (!dropdown) {
                    console.warn(`Dropdown not found for ID: ${dropdownId}`);
                    return;
                }

                // Disable all dependent fields initially
                dependentFieldIds.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.disabled = true;
                        field.closest('.col-md-3').style.display = "none"; // Hide the field's column
                    }
                });

                // Add an event listener to the dropdown
                dropdown.addEventListener("change", function() {
                    dependentFieldIds.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field) {
                            const parentCol = field.closest(
                                '.col-md-3'); // Get the column that wraps the field
                            if (triggerValues.includes(dropdown.value)) {
                                field.disabled = false; // Enable the field
                                parentCol.style.display = "block"; // Show the field's column
                            } else {
                                field.disabled = true; // Disable the field
                                parentCol.style.display = "none"; // Hide the field's column
                            }
                        }
                    });

                    // Call the special case function if provided (for dropdowns like "referred_to")
                    if (specialCaseCallback) {
                        specialCaseCallback(dropdown);
                    }
                });
            }

            // Special handling for the "Referred To" dropdown
            function handleReferredTo(dropdown) {
                const otherReferredToField = document.getElementById("other_referred_to");
                const dateOfReferredToField = document.getElementById("date_of_referred_to");

                // If "Other" is selected, show both "Other Referred To" and "Date of Referred To"
                if (dropdown.value === "other") {
                    // Show "Other Referred To" and "Date of Referred To"
                    otherReferredToField.disabled = false;
                    otherReferredToField.closest('.col-md-3').style.display = "block";

                    dateOfReferredToField.disabled = false;
                    dateOfReferredToField.closest('.col-md-3').style.display = "block";
                } else if (dropdown.value === "") {
                    // Hide both "Other Referred To" and "Date of Referred To" for empty value
                    otherReferredToField.disabled = true;
                    otherReferredToField.closest('.col-md-3').style.display = "none";

                    dateOfReferredToField.disabled = true;
                    dateOfReferredToField.closest('.col-md-3').style.display = "none";
                } else {
                    // Hide "Other Referred To" but show "Date of Referred To" for other values
                    otherReferredToField.disabled = true;
                    otherReferredToField.closest('.col-md-3').style.display = "none";

                    dateOfReferredToField.disabled = false;
                    dateOfReferredToField.closest('.col-md-3').style.display = "block";
                }

            }

            function handleSentTo(dropdown) {
                const otherSentToField = document.getElementById("other_sent_to");
                const dateOfSentToField = document.getElementById("date_of_sent_to");

                if (dropdown.value === "other") {
                    // Show both "Other Sent To" and "Date of Sent To"
                    otherSentToField.disabled = false;
                    otherSentToField.closest('.col-md-3').style.display = "block";

                    dateOfSentToField.disabled = false;
                    dateOfSentToField.closest('.col-md-3').style.display = "block";
                } else if (dropdown.value === "") {
                    // Hide both "Other Sent To" and "Date of Sent To" for empty value
                    otherSentToField.disabled = true;
                    otherSentToField.closest('.col-md-3').style.display = "none";

                    dateOfSentToField.disabled = true;
                    dateOfSentToField.closest('.col-md-3').style.display = "none";
                } else {
                    // Hide "Other Sent To" but show "Date of Sent To" for other values
                    otherSentToField.disabled = true;
                    otherSentToField.closest('.col-md-3').style.display = "none";

                    dateOfSentToField.disabled = false;
                    dateOfSentToField.closest('.col-md-3').style.display = "block";
                }

            }


            // Call the function for the "Released On" dropdown
            setupDropdownToggle("released_on", ["date_of_released_on"], ["Bail", "Guilty Plea", "With Fine"]);
            setupDropdownToggle("source_of_interview", ["other_source_of_interview"], ["Other"]);

            // Call the function for the "Referred To" dropdown with special case handler
            setupDropdownToggle("referred_to", ["other_referred_to", "date_of_referred_to"], ["other"],
                handleReferredTo);
            setupDropdownToggle("sent_to", ["other_sent_to", "date_of_sent_to"], ["other"], handleSentTo);


        });
    </script>
@endpush
