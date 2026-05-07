(function () {
    function makeEntry(no, bn, en) {
        return { no: no, bn: bn, en: en };
    }

    const labels = {
        institute: makeEntry('0', 'সহায়তার স্থান', 'Support in'),
        full_name: makeEntry('1.1', 'সম্পূর্ণ নাম', 'Full Name'),
        nick_name: makeEntry('1.1', 'ডাক নাম', 'Nick Name'),
        father_name: makeEntry('1.2', 'পিতার নাম', "Father's Name"),
        mother_name: makeEntry('1.3', 'মাতার নাম', "Mother's Name"),
        sex: makeEntry('1.4', 'লিঙ্গ', 'Sex'),
        age: makeEntry('1.5', 'বয়স', 'Age'),
        disability: makeEntry('1.6', 'প্রতিবন্ধিতা', 'Disability'),
        nationality: makeEntry('1.7', 'জাতীয়তা', 'Nationality'),
        nid_passport: makeEntry('1.8', 'জাতীয় পরিচয়পত্র / পাসপোর্ট / জন্ম নিবন্ধন', 'National ID / Passport / Birth Certificate No.'),
        address: makeEntry('1.9', 'যোগাযোগের ঠিকানা', 'Contact Address'),
        phone_number: makeEntry('1.10', 'ফোন নাম্বার', 'Phone Number'),
        interview_date: makeEntry('2.1', 'সাক্ষাৎকার গ্রহণের তারিখ', 'Date of Interview'),
        interview_time: makeEntry('2.2', 'সাক্ষাৎকার গ্রহণের সময়', 'Time of Interview'),
        interview_place: makeEntry('2.3', 'সাক্ষাৎকার গ্রহণের স্থান', 'Place of Interview'),
        marital_status: makeEntry('3.1', 'বৈবাহিক অবস্থা', 'Marital Status'),
        spouse_name: makeEntry('3.2', 'স্বামী / স্ত্রীর নাম', 'Spouse Name'),
        education_level: makeEntry('3.3', 'শিক্ষাগত যোগ্যতা', 'Level of Education'),
        occupation: makeEntry('3.4', 'পেশা', 'Occupation'),
        monthly_income: makeEntry('3.5', 'সর্বশেষ মাসিক আয়', 'Last Monthly Income'),
        family_informed: makeEntry('3.6', 'পরিবার / আত্মীয় অবহিত কিনা', 'Family / Relative Informed'),
        children_with_prisoner: makeEntry('3.7', 'নারী বন্দীর সাথে শিশু আছে কিনা', 'Children Accompanying Female Prisoner'),
        child_sex: makeEntry('3.8', 'শিশুর লিঙ্গ', 'Child Sex'),
        child_age: makeEntry('3.8', 'শিশুর বয়স', 'Child Age'),
        child_2_sex: makeEntry('3.8', 'দ্বিতীয় শিশুর লিঙ্গ', 'Second Child Sex'),
        child_2_age: makeEntry('3.8', 'দ্বিতীয় শিশুর বয়স', 'Second Child Age'),
        has_guardian: makeEntry('4.1', 'স্থানীয় অভিভাবক আছে কিনা', 'Has Local Guardian'),
        guardian_name: makeEntry('4.2', 'অভিভাবকের নাম', "Guardian's Name"),
        guardian_address: makeEntry('4.3', 'অভিভাবকের ঠিকানা', "Guardian's Address"),
        guardian_phone: makeEntry('4.4', 'অভিভাবকের ফোন নাম্বার', 'Guardian Phone Number'),
        guardian_relation: makeEntry('4.5', 'অভিভাবকের সাথে সম্পর্ক', 'Relation with Guardian'),
        guardian_relation_details: makeEntry('4.5', 'সম্পর্কের বিস্তারিত', 'Relation Details'),
        guardian_surety: makeEntry('4.6', 'অভিভাবক জামিনদার হবেন কিনা', 'Will Guardian Act as Surety'),
        has_lawyer: makeEntry('5.1', 'আইনজীবী আছে কিনা', 'Already Has a Lawyer'),
        lawyer_type: makeEntry('5.2', 'আইনজীবীর ধরণ', 'Type of Lawyer'),
        lawyer_type_details: makeEntry('5.2', 'আইনজীবীর ধরণের বিস্তারিত', 'Lawyer Type Details'),
        lawyer_name: makeEntry('5.3', 'আইনজীবীর নাম', "Lawyer's Name"),
        lawyer_membership: makeEntry('5.4', 'আইনজীবীর সদস্য নাম্বার', 'Lawyer Membership Number'),
        lawyer_phone: makeEntry('5.5', 'আইনজীবীর ফোন নাম্বার', 'Lawyer Phone Number'),
        incident_details: makeEntry('6.1', 'ঘটনার সংক্ষিপ্ত বিবরণ', 'Brief of Incident'),
        custody_status: makeEntry('7.1', 'পুলিশ / আদালত হেফাজতে কিনা', 'Police / Court Custody Status'),
        charges_details: makeEntry('7.2', 'অভিযোগসমূহ', 'Charges'),
        family_communication_date: makeEntry('8.1', 'পরিবারের সাথে যোগাযোগ', 'Communicate with Families / Relatives'),
        legal_representation: makeEntry('8.2', 'আইনগত প্রতিনিধিত্বের জন্য প্রেরণ', 'Referred for Legal Representation'),
        legal_representation_details: makeEntry('8.2', 'আইনগত প্রতিনিধিত্বের বিস্তারিত', 'Legal Representation Details'),
        legal_representation_date: makeEntry('8.2', 'তারিখ', 'Legal Representation Date'),
        collected_vokalatnama_date: makeEntry('8.3', 'ওকালতনামা সংগ্রহ', 'Collected Vokalatnama'),
        collected_case_doc: makeEntry('8.4', 'মামলার নথি সংগ্রহ', 'Collected Case Document'),
        identify_sureties: makeEntry('8.5', 'জামিনদার খুঁজে দেয়া', 'Identify Sureties'),
        identify_sureties_date: makeEntry('8.5', 'তারিখ', 'Identify Sureties Date'),
        witness_communication_date: makeEntry('8.6', 'সাক্ষীর সাথে যোগাযোগ', 'Communicate with Witness'),
        medical_report_date: makeEntry('8.7', 'মেডিকেল রিপোর্ট সংগ্রহে সহায়তা', 'Assist in Collecting Medical Report'),
        legal_assistance_date: makeEntry('8.8', 'থানায় আটক ব্যক্তিকে আইনগত সহায়তা', 'Legal Assistance in Police Station'),
        assistance_under_custody_date: makeEntry('8.9', 'থানা হেফাজত থেকে পরিবার / অভিভাবকের নিকট ফিরতে সহায়তা', 'Assistance Under Police Custody'),
        referral_service: makeEntry('8.10', 'অন্যান্য সেবার জন্য রেফারেল', 'Referral for Other Services'),
        referral_service_details: makeEntry('8.10', 'রেফারেলের বিস্তারিত', 'Referral Service Details'),
        referral_service_date: makeEntry('8.10', 'তারিখ', 'Referral Date'),
        resolved_dispute_date: makeEntry('9.1', 'বিরোধ মিমাংসা হয়েছে', 'Resolved Dispute'),
        case_resolved_date: makeEntry('9.2', 'মামলা নিষ্পত্তি হয়েছে', 'Case Resolved'),
        appoint_lawyer_date: makeEntry('9.3', 'আইনজীবী নিয়োগ করা হয়েছে', 'Appoint Lawyer'),
        release_status: makeEntry('9.4', 'মুক্ত হওয়া', 'Released On'),
        fine_amount: makeEntry('9.4', 'জরিমানার পরিমাণ', 'Fine Amount'),
        release_status_date: makeEntry('9.4', 'তারিখ', 'Release Date'),
        other_result_details: makeEntry('9.5', 'অন্যান্য ফলাফল, উল্লেখ করুন', 'Other result, please specify'),
        other_result_date: makeEntry('9.5', 'তারিখ', 'Other Result Date'),
        source_of_interview: makeEntry('10.1', 'সাক্ষাৎকারের উৎস', 'Source of Interview'),
        prison_reg_no: makeEntry('10.2', 'রেজিস্ট্রেশন নং', 'Prison Registration No.'),
        prison_case_no: makeEntry('10.3', 'মামলার নাম্বার', 'Case No(s)'),
        section_no: makeEntry('10.4', 'ধারার নাম্বার', 'Section No.'),
        present_court: makeEntry('10.5', 'বর্তমান আদালতের নাম', "Present Court's Name"),
        lockup_no: makeEntry('10.6', 'লকআপ নাম্বার', 'Lock Up Number'),
        entry_date: makeEntry('10.7', 'কারাগারে আগমনের তারিখ', 'Date of Entry in Prison'),
        case_transferred: makeEntry('10.8', 'মামলাটি বদলি হয়েছে কিনা', 'Has the Case Transferred'),
        current_court: makeEntry('10.9', 'সর্বশেষ আদালতের নাম', "Current Court's Name"),
        case_status: makeEntry('10.10', 'মামলার বর্তমান অবস্থা', 'Present Status of Case'),
        co_offenders: makeEntry('10.11', 'সহ-আসামীর সংখ্যা', 'Number of Co-offenders'),
        facts_of_case: makeEntry('10.13', 'মামলার বিবরণ', 'Facts of the Case'),
        imprisonment_condition: makeEntry('11.1', 'মৌলিক অবস্থা', 'Basic Condition'),
        imprisonment_status: makeEntry('11.2', 'কারাবাসের অবস্থা', 'Status of Imprisonment'),
        special_condition: makeEntry('11.3', 'বিশেষ অবস্থা', 'Special Condition'),
        surrender_date: makeEntry('11.5', 'আত্মসমর্পণের তারিখ', 'Date of Surrender'),
        prison_family_communication: makeEntry('12.1', 'পরিবারের সাথে যোগাযোগ', 'Communicate with Families / Relatives'),
        prison_legal_representation: makeEntry('12.2', 'আইনগত প্রতিনিধিত্বের জন্য প্রেরণ', 'Referred for Legal Representation'),
        prison_legal_representation_date: makeEntry('12.2', 'তারিখ', 'Legal Representation Date'),
        next_court_collection_date: makeEntry('12.3', 'পরবর্তী তারিখ সংগ্রহের তারিখ', 'Next Court Date Collection Date'),
        prison_next_court_date: makeEntry('12.3', 'বন্দীর আদালতে উপস্থিতির পরবর্তী তারিখ', 'Prisoner Next Court Date'),
        collected_case_doc_prison: makeEntry('12.4', 'আদালত হতে মামলার নথি সংগ্রহ', 'Collected Case Document'),
        identify_sureties_prison_nid: makeEntry('12.5', 'জামিনদারের জাতীয় পরিচয়পত্র', 'Surety National ID Details'),
        identify_sureties_prison_phone: makeEntry('12.5', 'জামিনদারের ফোন নাম্বার', 'Surety Phone Details'),
        identify_sureties_prison_date: makeEntry('12.5', 'তারিখ', 'Surety Identification Date'),
        witness_communication_prison: makeEntry('12.6', 'সাক্ষীর সাথে যোগাযোগ', 'Communicate with Witness'),
        bail_bond_submission: makeEntry('12.7', 'জামিননামা কারাগারে প্রেরণের জন্য যোগাযোগ', 'Submission of Bail Bond'),
        court_order_communication: makeEntry('12.8', 'আদালতের আদেশনামা প্রেরণের জন্য যোগাযোগ', 'Conveying Court Orders'),
        application_certified_copies: makeEntry('12.9', 'সই-মোহরের নকল সংগ্রহের আবেদন', 'Application for Certified Copies'),
        appeal_assistance: makeEntry('12.10', 'আপিলে সহায়তা', 'Assistance in Appeal'),
        ministerial_communication: makeEntry('12.11', 'মন্ত্রণালয় / দূতাবাস / সংস্থার সাথে যোগাযোগ', 'Communicate with Ministries / Embassy / Organizations'),
        ministerial_communication_details: makeEntry('12.11', 'বিস্তারিত', 'Details'),
        other_legal_assistance: makeEntry('12.12', 'অন্যান্য আইনগত সহায়তা', 'Other Legal Assistance'),
        other_legal_assistance_date: makeEntry('12.12', 'তারিখ', 'Other Legal Assistance Date'),
        released_on: makeEntry('13.1', 'মুক্ত হওয়া', 'Released On'),
        released_on_date: makeEntry('13.1', 'তারিখ', 'Release Date'),
        send_to: makeEntry('13.2', 'প্রেরণ', 'Send To'),
        send_to_date: makeEntry('13.2', 'তারিখ', 'Send Date'),
        convicted_length: makeEntry('13.3', 'সাজার সময়কাল', 'Length of Sentence'),
        convicted_length_details: makeEntry('13.3', 'সাজার সময়কাল, উল্লেখ করুন', 'Length of Sentence Details'),
        convicted_sentence_expire: makeEntry('13.3', 'সাজা সমাপ্তির তারিখ', 'Sentence Expires On'),
        convicted_sentence_expire_details: makeEntry('13.3', 'সাজা সমাপ্তির বিস্তারিত', 'Sentence Expiry Details'),
        result_of_appeal: makeEntry('13.4', 'আপিলের ফলাফল', 'Result of the Appeal'),
        result_of_appeal_date: makeEntry('13.4', 'আপিলের ফলাফলের তারিখ', 'Appeal Result Date'),
        prison_case_resolved_date: makeEntry('13.5', 'মামলা নিষ্পত্তি হয়েছে', 'Case Resolved'),
        date_of_reliefe: makeEntry('13.6', 'কারামুক্তির তারিখ', 'Date Released from Prison'),
        application_mode: makeEntry('14.1', 'জেলা লিগ্যাল এইড সেবার আবেদনের ধরণ', 'Mode of Application'),
        application_mode_date: makeEntry('14.1', 'তারিখ', 'Application Date'),
        received_application: makeEntry('14.2', 'আবেদন গৃহীত হয়েছে কিনা', 'Application Received'),
        reference_no: makeEntry('14.2', 'রেফারেন্স নাম্বার', 'Reference No.'),
        type_of_service: makeEntry('14.3', 'প্রাপ্ত সেবার ধরণ', 'Type of Service Received'),
        type_of_service_date: makeEntry('14.3', 'তারিখ', 'Service Date'),
        result_description: makeEntry('15.1', 'সেবার বিবরণ', 'Description of Service'),
        file_closure_date: makeEntry('16.2', 'ফাইল বন্ধের তারিখ', 'File Closed Date'),
        source_of_interview_details: makeEntry('10.1', 'সাক্ষাৎকারের উৎসের বিস্তারিত', 'Source of Interview Details'),
        special_condition_details: makeEntry('11.3', 'বিশেষ অবস্থার বিস্তারিত', 'Special Condition Details'),
        prison_legal_representation_details: makeEntry('12.2', 'আইনগত প্রতিনিধিত্বের বিস্তারিত', 'Prison Legal Representation Details'),
        other_legal_assistance_details: makeEntry('12.12', 'অন্যান্য আইনগত সহায়তার বিস্তারিত', 'Other Legal Assistance Details'),
        send_to_details: makeEntry('13.2', 'প্রেরণের বিস্তারিত', 'Send To Details'),
        intervention_taken: makeEntry('17.1', 'পদক্ষেপ নেয়া হয়েছে', 'Interventions Taken'),
        intervention_taken_date: makeEntry('17.1', 'তারিখ', 'Intervention Taken Date'),
        intervention_to_be_taken: makeEntry('17.2', 'পদক্ষেপ নিতে হবে', 'Interventions To Be Taken'),
        to_be_taken_date: makeEntry('17.2', 'তারিখ', 'To Be Taken Date'),
        fileUpload: makeEntry('18.1', 'সংযুক্তি আপলোড', 'Upload Additional Documents')
    };
    const requiredFields = new Set(['full_name', 'sex', 'age', 'family_informed']);

    const sections = {
        collapseOne: makeEntry('1', 'প্রোফাইল / প্রাথমিক তথ্য', 'Profile / General Information'),
        collapseTwo: makeEntry('2', 'সেশন / সাক্ষাৎকারের তথ্য', 'Session / Interview Information'),
        collapseThree: makeEntry('3', 'ব্যক্তিগত তথ্য', 'Personal Information'),
        collapseFour: makeEntry('4', 'অভিভাবকের তথ্য', 'Information of Guardian'),
        collapseFive: makeEntry('5', 'আইনগত প্রতিনিধিত্ব', 'Legal Representation'),
        collapseSix: makeEntry('6', 'ঘটনার বিবরণ', 'Details of Incident'),
        collapseSeven: makeEntry('7', 'মামলা বা গ্রেফতার সম্পর্কিত তথ্য', 'Case Information'),
        collapseEight: makeEntry('8', 'সহায়তার ধরণ', 'Nature of Assistance'),
        collapseNine: makeEntry('9', 'ফলাফল', 'Result'),
        collapseTwelve: makeEntry('10', 'মামলার মৌলিক তথ্য', 'Basic Case Information'),
        collapseThirteen: makeEntry('11', 'কারাবাসের ধরণ', 'Imprisonment Information'),
        collapseFourteen: makeEntry('12', 'কারাগারে সহায়তার ধরণ', 'Nature of Assistance in Prison'),
        collapseFifteen: makeEntry('13', 'কারাগারে সহায়তার ফলাফল', 'Result of Prison Assistance'),
        collapseTen: makeEntry('14', 'জেলা লিগ্যাল এইড অফিস তথ্য', 'District Legal Aid Office Information'),
        collapseSixteen: makeEntry('15', 'প্রদত্ত সেবার বিবরণ', 'Description of Service Provided'),
        collapseFileClosure: makeEntry('16', 'ফাইল বন্ধ', 'File Closure'),
        collapseSeventeen: makeEntry('17', 'ফলোআপ শিট', 'Follow-up Sheet'),
        collapseEighteen: makeEntry('18', 'সংযুক্তি', 'Upload Additional Documents')
    };

    const selectPlaceholder = { '': 'নির্বাচন করুন (Select)' };
    const selectOnePlaceholder = { '': 'নির্বাচন করুন (Select One)' };
    const yesNo = {
        '': 'নির্বাচন করুন (Select)',
        Yes: 'হ্যাঁ (Yes)',
        No: 'না (No)'
    };
    const sexOptions = {
        '': 'নির্বাচন করুন (Select)',
        Male: 'পুরুষ (Male)',
        Female: 'নারী (Female)',
        Transgender: 'ট্রান্সজেন্ডার পার্সন (Transgender Person)'
    };
    const legalRepresentationOptions = {
        '': 'নির্বাচন করুন (Select)',
        'District Legal Aid Office': 'জেলা লিগ্যাল এইড অফিস (District Legal Aid Office)',
        'District Legal Aid Offic': 'জেলা লিগ্যাল এইড অফিস (District Legal Aid Office)',
        'District Project Officer': 'জেলা প্রকল্প কর্মকর্তা (District Project Officer)',
        'NGO Panel Lawyer': 'এনজিওর প্যানেল আইনজীবী (NGO Panel Lawyer)',
        Other: 'অন্যান্য (Other)'
    };

    const optionLabels = {
        institute: Object.assign({}, selectPlaceholder, {
            Court: 'আদালত (Court)',
            Prison: 'কারাগার (Prison)',
            'Police Station': 'থানা (Police Station)'
        }),
        sex: sexOptions,
        child_sex: sexOptions,
        child_2_sex: sexOptions,
        disability: {
            Yes: 'হ্যাঁ (Yes)',
            No: 'না (No)'
        },
        marital_status: Object.assign({}, selectPlaceholder, {
            Married: 'বিবাহিত (Married)',
            Single: 'অবিবাহিত (Single)',
            Divorced: 'তালাকপ্রাপ্ত (Divorced)',
            Widowed: 'বিধবা / বিপত্নীক (Widow)'
        }),
        education_level: Object.assign({}, selectPlaceholder, {
            Illiterate: 'নিরক্ষর (Illiterate)',
            'Can Sign': 'স্বাক্ষরজ্ঞান সম্পন্ন (Can Sign)',
            Primary: 'প্রাথমিক (Primary)',
            Secondary: 'মাধ্যমিক (Secondary)',
            'Higher Secondary': 'উচ্চ মাধ্যমিক (Higher Secondary)',
            Graduate: 'স্নাতক (Graduate)',
            Postgraduate: 'স্নাতকোত্তর (Postgraduate)'
        }),
        family_informed: yesNo,
        children_with_prisoner: yesNo,
        has_guardian: yesNo,
        guardian_relation: Object.assign({}, selectPlaceholder, {
            'Family Member': 'পরিবারের সদস্য (Family Member)',
            Relative: 'আত্মীয় (Relative)',
            Neighbor: 'প্রতিবেশী (Neighbor)',
            Other: 'অন্যান্য (Other)'
        }),
        guardian_surety: yesNo,
        has_lawyer: yesNo,
        lawyer_type: Object.assign({}, selectPlaceholder, {
            Personal: 'ব্যক্তিগত (Personal)',
            NGO: 'এনজিও (NGO)',
            'District Legal Aid': 'জেলা লিগ্যাল এইড (District Legal Aid)',
            'State Defense': 'রাষ্ট্র নিযুক্ত আইনজীবী (State Defense)',
            Other: 'অন্যান্য (Other)'
        }),
        custody_status: Object.assign({}, selectOnePlaceholder, {
            'Police Custody': 'পুলিশ হেফাজত (Police Custody)',
            'Court Custody': 'আদালত হেফাজত (Court Custody)',
            'Not Applicable': 'প্রযোজ্য নয় (Not Applicable)'
        }),
        legal_representation: legalRepresentationOptions,
        referral_service: Object.assign({}, selectPlaceholder, {
            'District Legal Aid Office': 'জেলা লিগ্যাল এইড অফিস (District Legal Aid Office)',
            'NGOs/RJ/Mediation': 'এনজিওতে আরজে / সালিশ (NGOs RJ / Mediation)',
            'Village Court': 'গ্রাম আদালত (Village Court)',
            'Safe Home': 'নিরাপদ আবাসন (Safe Home)',
            Other: 'অন্যান্য (Other)'
        }),
        release_status: Object.assign({}, selectPlaceholder, {
            Bail: 'জামিন (Bail)',
            'Guilty Plea': 'দোষ স্বীকার (Guilty Plea)',
            'With Fine': 'জরিমানাসহ (With Fine)'
        }),
        source_of_interview: Object.assign({}, selectPlaceholder, {
            'Prison Staff': 'প্রিজন স্টাফ (Prison Staff)',
            'Case File': 'কেস ফাইল (Case File)',
            'Prison Register': 'প্রিজন রেজিস্টার (Prison Register)',
            Other: 'অন্যান্য (Other)'
        }),
        case_transferred: yesNo,
        imprisonment_condition: Object.assign({}, selectOnePlaceholder, {
            Regular: 'সাধারণ (Regular)',
            'On Call': 'তলবমতে (On Call)',
            'Safe Custody': 'নিরাপদ হেফাজত (Safe Custody)'
        }),
        imprisonment_status: Object.assign({}, selectOnePlaceholder, {
            'Under Trial': 'বিচারাধীন (Under Trial)',
            Convicted: 'সাজাপ্রাপ্ত (Convicted)',
            Released: 'সাজার মেয়াদ উত্তীর্ণ (Released Prisoner)',
            'Sentenced but under trial for another offense': 'সাজাপ্রাপ্ত কিন্তু অন্য অপরাধে বিচারাধীন (Sentenced but under trial for another offense)',
            Foreigner: 'বিদেশী বন্দী (Foreigner)',
            foreigner: 'বিদেশী বন্দী (Foreigner)'
        }),
        special_condition: Object.assign({}, selectPlaceholder, {
            'Critical Ill': 'গুরুতর অসুস্থ (Critical Ill)',
            Disabled: 'অক্ষম (Disable)',
            Other: 'অন্য অবস্থা (Other Condition)'
        }),
        prison_legal_representation: legalRepresentationOptions,
        other_legal_assistance: Object.assign({}, selectPlaceholder, {
            fine_assistance: 'সাজাপ্রাপ্ত বন্দীর জরিমানার টাকা জমাদানে সহায়তা (Assist in depositing fine)',
            court_communication: 'সাজার পরোয়ানা কারাগারে প্রেরণের জন্য আদালতের সাথে যোগাযোগ (Communicate with court)',
            other: 'অন্যান্য (Other)'
        }),
        released_on: Object.assign({}, selectPlaceholder, {
            Bail: 'জামিন মঞ্জুর (Bail Granted)',
            Discharged: 'অব্যাহতি (Discharged / FRT)',
            Acquitted: 'খালাস (Acquitted)',
            'Guilty Plea': 'দোষ স্বীকারের পর মুক্তি (Released after Guilty Plea)',
            'Released from Safe Custody': 'নিরাপত্তা হেফাজত থেকে মুক্তি (Released from Safe Custody)',
            'Foreign Prisoner': 'প্রত্যাবর্তন / বিদেশি কারাবন্দী (Foreign Prisoner)'
        }),
        send_to: Object.assign({}, selectPlaceholder, {
            'Safe Home': 'নিরাপদ আবাসন (Safe Home)',
            'Legal Guardian': 'আইনগত অভিভাবক (Legal Guardian)',
            'Own Custody': 'নিজ জিম্মায় (Own Custody)',
            'NGO Shelter': 'এনজিও আশ্রয় কেন্দ্র (NGO Shelter Home)',
            'Child Development Centre': 'কিশোর / কিশোরী উন্নয়ন কেন্দ্র (Child Development Centre)',
            'Victim Support Centre': 'ক্ষতিগ্রস্থদের সহায়তা কেন্দ্র (Victim Support Centre)',
            Other: 'অন্যান্য (Other)'
        }),
        result_of_appeal: Object.assign({}, selectPlaceholder, {
            'Sentence Upheld': 'সাজা বহাল (Sentence Upheld)',
            Acquitted: 'খালাস (Acquitted)',
            'Sentence Reduced': 'সাজা হ্রাস (Sentence Reduced)',
            'Sentence Enhanced': 'সাজা বৃদ্ধি (Sentence Enhanced)'
        }),
        application_mode: Object.assign({}, selectPlaceholder, {
            Online: 'অনলাইন আবেদন (Online Application)',
            'Office Application': 'সরাসরি অফিসে আবেদন (Office Application)'
        }),
        received_application: yesNo,
        type_of_service: Object.assign({}, selectPlaceholder, {
            'Legal Advice': 'আইনগত পরামর্শ (Legal Advice)',
            'Alternate Dispute Resolution': 'বিকল্প বিরোধ নিষ্পত্তি (Alternative Dispute Resolution)',
            'Filing New Lawsuit': 'নতুন মামলা দায়ের (Filing New Lawsuit)',
            'Legal Aid in Existing Case': 'বিদ্যমান মামলায় আইনগত সহায়তা (Legal Aid in Existing Case)'
        })
    };

    function findControl(label) {
        const fieldWrap = label.closest('.col-md-12, .col-md-8, .col-md-6, .col-md-4, .col-md-3');
        return fieldWrap ? fieldWrap.querySelector('input, select, textarea') : null;
    }

    function normalizedName(control) {
        if (!control) {
            return '';
        }

        return (control.getAttribute('name') || control.id || '').replace(/\[\]$/, '');
    }

    function sectionId(label) {
        const section = label.closest('.accordion-collapse');
        return section ? section.id : '';
    }

    function resolveEntry(name, section) {
        if (name === 'arrest_date') {
            return section === 'collapseThirteen' ? makeEntry('11.4', 'গ্রেফতারের তারিখ', 'Date of Arrest') : makeEntry('7.3', 'গ্রেফতারের তারিখ', 'Date of Arrest');
        }

        if (name === 'case_no') {
            return section === 'collapseTwelve' ? makeEntry('10.3', 'মামলার নাম্বার', 'Case No(s)') : makeEntry('7.4', 'মামলা নং', 'Case Number');
        }

        if (name === 'next_court_date') {
            if (section === 'collapseFourteen') {
                return makeEntry('12.3', 'বন্দীর আদালতে উপস্থিতির পরবর্তী তারিখ', 'Prisoner Next Court Date');
            }

            return makeEntry('10.12', 'পরবর্তী হাজিরার তারিখ', 'Next Court Date');
        }

        return labels[name];
    }

    function applyLabel(label, entry, required) {
        label.classList.add('manual-label');
        label.textContent = '';

        const no = document.createElement('span');
        no.className = 'manual-label-no';
        no.textContent = entry.no;

        const text = document.createElement('span');
        text.className = 'manual-label-text';

        const bn = document.createElement('span');
        bn.className = 'manual-label-bn';
        bn.textContent = entry.bn;

        if (required) {
            const star = document.createElement('span');
            star.className = 'manual-label-required';
            star.textContent = ' *';
            bn.appendChild(star);
        }

        const en = document.createElement('span');
        en.className = 'manual-label-en';
        en.textContent = entry.en;

        text.appendChild(bn);
        text.appendChild(en);
        label.appendChild(no);
        label.appendChild(text);
    }

    function applySectionTitle(button, entry) {
        if (button.querySelector('.accordion-title')) {
            return;
        }

        button.textContent = '';

        const title = document.createElement('span');
        title.className = 'accordion-title';

        const no = document.createElement('span');
        no.className = 'accordion-title-no';
        no.textContent = entry.no;

        const text = document.createElement('span');
        text.className = 'accordion-title-text';

        const bn = document.createElement('span');
        bn.className = 'accordion-title-bn';
        bn.textContent = entry.bn;

        const en = document.createElement('span');
        en.className = 'accordion-title-en';
        en.textContent = entry.en;

        text.appendChild(bn);
        text.appendChild(en);
        title.appendChild(no);
        title.appendChild(text);
        button.appendChild(title);
    }

    function applyOptionLabels() {
        Object.keys(optionLabels).forEach(function (fieldName) {
            document.querySelectorAll('select[name="' + fieldName + '"]').forEach(function (select) {
                if (select.dataset.bilingualOptionsApplied === 'true') {
                    return;
                }

                select.querySelectorAll('option').forEach(function (option) {
                    const label = optionLabels[fieldName][option.value];

                    if (label) {
                        option.textContent = label;
                    }
                });

                select.dataset.bilingualOptionsApplied = 'true';
            });
        });
    }

    function fieldLabel(control) {
        const fieldWrap = control.closest('.col-md-12, .col-md-8, .col-md-6, .col-md-4, .col-md-3');
        const bn = fieldWrap ? fieldWrap.querySelector('.manual-label-bn') : null;
        const en = fieldWrap ? fieldWrap.querySelector('.manual-label-en') : null;
        const no = fieldWrap ? fieldWrap.querySelector('.manual-label-no') : null;
        const prefix = no ? no.textContent.trim() + ' ' : '';
        const bnText = bn ? bn.textContent.replace('*', '').trim() : '';
        const enText = en ? en.textContent.trim() : '';

        if (bnText && enText) {
            return prefix + bnText + ' (' + enText + ')';
        }

        return prefix + (control.name || control.id || 'Required field');
    }

    function showValidationMessage(firstInvalid, missingLabels) {
        const message = 'Please complete the required field: ' + missingLabels[0];

        if (window.Swal) {
            window.Swal.fire({
                icon: 'warning',
                title: 'Required field missing',
                html: '<div style="text-align:left"><strong>' + missingLabels[0] + '</strong><br><small>The related accordion section has been opened for you.</small></div>',
                confirmButtonColor: '#c30f08'
            }).then(function () {
                firstInvalid.focus({ preventScroll: true });
            });
        } else {
            alert(message);
            firstInvalid.focus({ preventScroll: true });
        }
    }

    function openSectionFor(control) {
        const section = control.closest('.accordion-collapse');

        if (!section || !window.bootstrap || !window.bootstrap.Collapse) {
            control.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        window.bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();

        window.setTimeout(function () {
            control.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 250);
    }

    function setupRequiredSubmitNotice() {
        document.querySelectorAll('.case-entry-page form, .case-edit-page form').forEach(function (form) {
            if (form.dataset.requiredNoticeApplied === 'true') {
                return;
            }

            form.noValidate = true;

            form.addEventListener('submit', function (event) {
                const requiredControls = Array.from(form.querySelectorAll('[required]'));
                const invalidControls = requiredControls.filter(function (control) {
                    return !control.checkValidity();
                });

                if (!invalidControls.length) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                const firstInvalid = invalidControls[0];
                const missingLabels = invalidControls.slice(0, 4).map(fieldLabel);

                openSectionFor(firstInvalid);
                showValidationMessage(firstInvalid, missingLabels);
            });

            form.dataset.requiredNoticeApplied = 'true';
        });
    }

    function setupDistrictLegalAidSectionVisibility() {
        const section = document.getElementById('collapseTen');
        const header = document.getElementById('headingTen');

        if (!section || !header) {
            return;
        }

        const item = section.closest('.accordion-item') || header.closest('.accordion-item');
        const triggerIds = ['legal_representation', 'referral_service', 'prison_legal_representation'];
        const dlaoValues = ['District Legal Aid Office', 'District Legal Aid Offic'];

        function hasDistrictLegalAidSelected() {
            return triggerIds.some(function (id) {
                const field = document.getElementById(id);
                return field && dlaoValues.includes(field.value);
            });
        }

        function syncVisibility() {
            const show = hasDistrictLegalAidSelected();
            const target = item || section;

            target.style.display = show ? '' : 'none';

            if (!show && window.bootstrap && window.bootstrap.Collapse) {
                window.bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).hide();
            }
        }

        triggerIds.forEach(function (id) {
            const field = document.getElementById(id);

            if (field && field.dataset.dlaoVisibilityApplied !== 'true') {
                field.addEventListener('change', syncVisibility);
                field.dataset.dlaoVisibilityApplied = 'true';
            }
        });

        syncVisibility();
    }

    window.applyCourtPolicePrisonManualLabels = function () {
        applyOptionLabels();

        document.querySelectorAll('.case-entry-page .accordion-collapse, .case-edit-page .accordion-collapse').forEach(function (section) {
            const entry = sections[section.id];
            const header = document.querySelector('[aria-controls="' + section.id + '"]');

            if (entry && header) {
                applySectionTitle(header, entry);
            }
        });

        document.querySelectorAll('.case-entry-page .form-label, .case-edit-page .form-label').forEach(function (label) {
            if (label.classList.contains('manual-label')) {
                return;
            }

            const control = findControl(label);
            const name = normalizedName(control);
            const entry = resolveEntry(name, sectionId(label));

            if (entry) {
                if (requiredFields.has(name) && control) {
                    control.required = true;
                }

                applyLabel(label, entry, requiredFields.has(name));
            }
        });

        setupRequiredSubmitNotice();
        setupDistrictLegalAidSectionVisibility();
    };
})();
