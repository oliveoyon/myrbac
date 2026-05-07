<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class FormalCaseImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new FormalCaseImportDataTemplateSheet(),
            new FormalCaseImportFieldGuideSheet(),
        ];
    }
}

class FormalCaseImportDataTemplateSheet implements FromArray, WithHeadings, WithTitle, WithEvents, ShouldAutoSize
{
    public function title(): string
    {
        return 'Upload Data';
    }

    public function headings(): array
    {
        return array_column(FormalCaseImportTemplateFields::fields(), 'key');
    }

    public function array(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->freezePane('A2');
                $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true);
                $sheet->getStyle("A1:{$lastColumn}1")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE8F5EE');
                $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setWrapText(true);
            },
        ];
    }
}

class FormalCaseImportFieldGuideSheet implements FromArray, WithHeadings, WithTitle, WithEvents, ShouldAutoSize
{
    public function title(): string
    {
        return 'Field Guide';
    }

    public function headings(): array
    {
        return ['Upload header', 'Form no.', 'Field label', 'Required', 'Notes / sample values'];
    }

    public function array(): array
    {
        return array_map(function (array $field) {
            return [
                $field['key'],
                $field['no'] ?? '',
                $field['label'],
                $field['required'] ?? '',
                $field['note'] ?? '',
            ];
        }, FormalCaseImportTemplateFields::fields());
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                $sheet->getStyle('A1:E1')->getFont()->setBold(true);
                $sheet->getStyle('A1:E1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE8F5EE');
                $sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
            },
        ];
    }
}

class FormalCaseImportTemplateFields
{
    public static function fields(): array
    {
        return [
            ['key' => 'institute', 'label' => 'Support in', 'required' => 'Yes', 'note' => 'Court, Police Station, Prison'],
            ['key' => 'central_id', 'label' => 'Profile No. / Central ID No.', 'required' => 'Yes'],
            ['key' => 'district_id', 'label' => 'District ID', 'required' => 'Yes', 'note' => 'Use system district id'],
            ['key' => 'pngo_id', 'label' => 'PNGO ID', 'required' => 'Yes', 'note' => 'Use system PNGO id'],
            ['key' => 'status', 'label' => 'Status', 'required' => 'Yes', 'note' => 'Current counted status is 2'],
            ['key' => 'full_name', 'no' => '1.1', 'label' => 'Full Name', 'required' => 'Yes'],
            ['key' => 'nick_name', 'no' => '1.1', 'label' => 'Nick Name'],
            ['key' => 'father_name', 'no' => '1.2', 'label' => "Father's Name"],
            ['key' => 'mother_name', 'no' => '1.3', 'label' => "Mother's Name"],
            ['key' => 'sex', 'no' => '1.4', 'label' => 'Sex', 'required' => 'Yes', 'note' => 'Male, Female, Transgender'],
            ['key' => 'age', 'no' => '1.5', 'label' => 'Age', 'required' => 'Yes'],
            ['key' => 'disability', 'no' => '1.6', 'label' => 'Disability', 'note' => 'yes/no or disability type'],
            ['key' => 'nationality', 'no' => '1.7', 'label' => 'Nationality'],
            ['key' => 'nid_passport', 'no' => '1.8', 'label' => 'National ID / Passport / Birth Certificate No.'],
            ['key' => 'phone_number', 'no' => '1.10', 'label' => 'Phone Number'],
            ['key' => 'address', 'no' => '1.9', 'label' => 'Contact Address'],
            ['key' => 'interview_date', 'no' => '2.1', 'label' => 'Date of Interview', 'note' => 'Use date format like 15-Jul-24'],
            ['key' => 'interview_time', 'no' => '2.2', 'label' => 'Time of Interview'],
            ['key' => 'interview_place', 'no' => '2.3', 'label' => 'Place of Interview'],
            ['key' => 'marital_status', 'no' => '3.1', 'label' => 'Marital Status'],
            ['key' => 'spouse_name', 'no' => '3.2', 'label' => 'Spouse Name'],
            ['key' => 'education_level', 'no' => '3.3', 'label' => 'Level of Education'],
            ['key' => 'occupation', 'no' => '3.4', 'label' => 'Occupation'],
            ['key' => 'monthly_income', 'no' => '3.5', 'label' => 'Last Monthly Income'],
            ['key' => 'family_informed', 'no' => '3.6', 'label' => 'Family / Relative Informed', 'required' => 'Yes'],
            ['key' => 'children_with_prisoner', 'no' => '3.7', 'label' => 'Children Accompanying Female Prisoner'],
            ['key' => 'child_sex', 'no' => '3.8', 'label' => 'First Child Sex'],
            ['key' => 'child_age', 'no' => '3.8', 'label' => 'First Child Age'],
            ['key' => 'child_2_sex', 'no' => '3.8', 'label' => 'Second Child Sex'],
            ['key' => 'child_2_age', 'no' => '3.8', 'label' => 'Second Child Age'],
            ['key' => 'has_guardian', 'no' => '4.1', 'label' => 'Has Local Guardian'],
            ['key' => 'guardian_name', 'no' => '4.2', 'label' => "Guardian's Name"],
            ['key' => 'guardian_phone', 'no' => '4.4', 'label' => 'Guardian Phone Number'],
            ['key' => 'guardian_address', 'no' => '4.3', 'label' => "Guardian's Address"],
            ['key' => 'guardian_relation', 'no' => '4.5', 'label' => 'Relation with Guardian'],
            ['key' => 'guardian_relation_details', 'no' => '4.5', 'label' => 'Relation Details'],
            ['key' => 'guardian_surety', 'no' => '4.6', 'label' => 'Will Guardian Act as Surety'],
            ['key' => 'has_lawyer', 'no' => '5.1', 'label' => 'Already Has a Lawyer'],
            ['key' => 'lawyer_type', 'no' => '5.2', 'label' => 'Type of Lawyer'],
            ['key' => 'lawyer_type_details', 'no' => '5.2', 'label' => 'Lawyer Type Details'],
            ['key' => 'lawyer_name', 'no' => '5.3', 'label' => "Lawyer's Name"],
            ['key' => 'lawyer_membership', 'no' => '5.4', 'label' => 'Lawyer Membership Number'],
            ['key' => 'lawyer_phone', 'no' => '5.5', 'label' => 'Lawyer Phone Number'],
            ['key' => 'incident_details', 'no' => '6.1', 'label' => 'Brief of Incident'],
            ['key' => 'custody_status', 'no' => '7.1', 'label' => 'Police / Court Custody Status'],
            ['key' => 'charges_details', 'no' => '7.2', 'label' => 'Charges'],
            ['key' => 'arrest_date', 'no' => '7.3', 'label' => 'Date of Arrest', 'note' => 'Use date format like 15-Jul-24'],
            ['key' => 'case_no', 'no' => '7.4 / 10.3', 'label' => 'Case Number(s)'],
            ['key' => 'family_communication_date', 'no' => '8.1', 'label' => 'Communicate with Families / Relatives'],
            ['key' => 'legal_representation', 'no' => '8.2', 'label' => 'Referred for Legal Representation', 'note' => 'District Legal Aid Office, District Project Officer, NGO Panel Lawyer, Other'],
            ['key' => 'legal_representation_details', 'no' => '8.2', 'label' => 'Legal Representation Details'],
            ['key' => 'legal_representation_date', 'no' => '8.2', 'label' => 'Legal Representation Date'],
            ['key' => 'collected_vokalatnama_date', 'no' => '8.3', 'label' => 'Collected Vokalatnama'],
            ['key' => 'collected_case_doc', 'no' => '8.4', 'label' => 'Collected Case Document'],
            ['key' => 'identify_sureties', 'no' => '8.5', 'label' => 'Identify Sureties'],
            ['key' => 'identify_sureties_date', 'no' => '8.5', 'label' => 'Identify Sureties Date'],
            ['key' => 'witness_communication_date', 'no' => '8.6', 'label' => 'Communicate with Witness'],
            ['key' => 'medical_report_date', 'no' => '8.7', 'label' => 'Assist in Collecting Medical Report'],
            ['key' => 'legal_assistance_date', 'no' => '8.8', 'label' => 'Legal Assistance in Police Station'],
            ['key' => 'assistance_under_custody_date', 'no' => '8.9', 'label' => 'Assistance Under Police Custody'],
            ['key' => 'referral_service', 'no' => '8.10', 'label' => 'Referral for Other Services', 'note' => 'District Legal Aid Office, NGOs/RJ/Mediation, Village Court, Safe Home, Other'],
            ['key' => 'referral_service_details', 'no' => '8.10', 'label' => 'Referral Service Details'],
            ['key' => 'referral_service_date', 'no' => '8.10', 'label' => 'Referral Date'],
            ['key' => 'resolved_dispute_date', 'no' => '9.1', 'label' => 'Resolved Dispute'],
            ['key' => 'case_resolved_date', 'no' => '9.2', 'label' => 'Case Resolved'],
            ['key' => 'appoint_lawyer_date', 'no' => '9.3', 'label' => 'Appoint Lawyer'],
            ['key' => 'release_status', 'no' => '9.4', 'label' => 'Released On'],
            ['key' => 'fine_amount', 'no' => '9.4', 'label' => 'Fine Amount'],
            ['key' => 'release_status_date', 'no' => '9.4', 'label' => 'Release Date'],
            ['key' => 'other_result_details', 'no' => '9.5', 'label' => 'Other result, please specify'],
            ['key' => 'other_result_date', 'no' => '9.5', 'label' => 'Other Result Date'],
            ['key' => 'source_of_interview', 'no' => '10.1', 'label' => 'Source of Interview'],
            ['key' => 'source_of_interview_details', 'no' => '10.1', 'label' => 'Source of Interview Details'],
            ['key' => 'prison_reg_no', 'no' => '10.2', 'label' => 'Prison Registration No.'],
            ['key' => 'prison_case_no', 'no' => '10.3', 'label' => 'Prison Case No(s)'],
            ['key' => 'section_no', 'no' => '10.4', 'label' => 'Section No.'],
            ['key' => 'present_court', 'no' => '10.5', 'label' => "Present Court's Name"],
            ['key' => 'lockup_no', 'no' => '10.6', 'label' => 'Lock Up Number'],
            ['key' => 'entry_date', 'no' => '10.7', 'label' => 'Date of Entry in Prison'],
            ['key' => 'case_transferred', 'no' => '10.8', 'label' => 'Has the Case Transferred'],
            ['key' => 'current_court', 'no' => '10.9', 'label' => "Current Court's Name"],
            ['key' => 'case_status', 'no' => '10.10', 'label' => 'Present Status of Case'],
            ['key' => 'co_offenders', 'no' => '10.11', 'label' => 'Number of Co-offenders'],
            ['key' => 'next_court_date', 'no' => '10.12 / 12.3', 'label' => 'Next Court Date'],
            ['key' => 'facts_of_case', 'no' => '10.13', 'label' => 'Facts of the Case'],
            ['key' => 'imprisonment_condition', 'no' => '11.1', 'label' => 'Basic Condition'],
            ['key' => 'imprisonment_status', 'no' => '11.2', 'label' => 'Status of Imprisonment'],
            ['key' => 'special_condition', 'no' => '11.3', 'label' => 'Special Condition'],
            ['key' => 'special_condition_details', 'no' => '11.3', 'label' => 'Special Condition Details'],
            ['key' => 'prison_arrest_date', 'no' => '11.4', 'label' => 'Date of Arrest'],
            ['key' => 'surrender_date', 'no' => '11.5', 'label' => 'Date of Surrender'],
            ['key' => 'prison_family_communication', 'no' => '12.1', 'label' => 'Communicate with Families / Relatives'],
            ['key' => 'prison_legal_representation', 'no' => '12.2', 'label' => 'Referred for Legal Representation'],
            ['key' => 'prison_legal_representation_details', 'no' => '12.2', 'label' => 'Prison Legal Representation Details'],
            ['key' => 'prison_legal_representation_date', 'no' => '12.2', 'label' => 'Legal Representation Date'],
            ['key' => 'next_court_collection_date', 'no' => '12.3', 'label' => 'Next Court Date Collection Date'],
            ['key' => 'prison_next_court_date', 'no' => '12.3', 'label' => 'Prisoner Next Court Date'],
            ['key' => 'collected_case_doc_prison', 'no' => '12.4', 'label' => 'Collected Case Document'],
            ['key' => 'identify_sureties_prison_nid', 'no' => '12.5', 'label' => 'Surety National ID Details'],
            ['key' => 'identify_sureties_prison_phone', 'no' => '12.5', 'label' => 'Surety Phone Details'],
            ['key' => 'identify_sureties_prison_date', 'no' => '12.5', 'label' => 'Surety Identification Date'],
            ['key' => 'witness_communication_prison', 'no' => '12.6', 'label' => 'Communicate with Witness'],
            ['key' => 'bail_bond_submission', 'no' => '12.7', 'label' => 'Submission of Bail Bond'],
            ['key' => 'court_order_communication', 'no' => '12.8', 'label' => 'Conveying Court Orders'],
            ['key' => 'application_certified_copies', 'no' => '12.9', 'label' => 'Application for Certified Copies'],
            ['key' => 'appeal_assistance', 'no' => '12.10', 'label' => 'Assistance in Appeal'],
            ['key' => 'ministerial_communication', 'no' => '12.11', 'label' => 'Communicate with Ministries / Embassy / Organizations'],
            ['key' => 'ministerial_communication_details', 'no' => '12.11', 'label' => 'Details'],
            ['key' => 'other_legal_assistance', 'no' => '12.12', 'label' => 'Other Legal Assistance'],
            ['key' => 'other_legal_assistance_details', 'no' => '12.12', 'label' => 'Other Legal Assistance Details'],
            ['key' => 'other_legal_assistance_date', 'no' => '12.12', 'label' => 'Other Legal Assistance Date'],
            ['key' => 'released_on', 'no' => '13.1', 'label' => 'Released On'],
            ['key' => 'released_on_date', 'no' => '13.1', 'label' => 'Released On Date'],
            ['key' => 'send_to', 'no' => '13.2', 'label' => 'Send To'],
            ['key' => 'send_to_details', 'no' => '13.2', 'label' => 'Send To Details'],
            ['key' => 'send_to_date', 'no' => '13.2', 'label' => 'Send To Date'],
            ['key' => 'convicted_length', 'no' => '13.3', 'label' => 'Length of Sentence'],
            ['key' => 'convicted_length_details', 'no' => '13.3', 'label' => 'Length of Sentence Details'],
            ['key' => 'convicted_sentence_expire', 'no' => '13.3', 'label' => 'Sentence Expires On'],
            ['key' => 'convicted_sentence_expire_details', 'no' => '13.3', 'label' => 'Sentence Expiry Details'],
            ['key' => 'result_of_appeal', 'no' => '13.4', 'label' => 'Result of the Appeal'],
            ['key' => 'result_of_appeal_date', 'no' => '13.4', 'label' => 'Appeal Result Date'],
            ['key' => 'prison_case_resolved_date', 'no' => '13.5', 'label' => 'Case Resolved'],
            ['key' => 'date_of_reliefe', 'no' => '13.6', 'label' => 'Date Released from Prison'],
            ['key' => 'application_mode', 'no' => '14.1', 'label' => 'Mode of Application'],
            ['key' => 'application_mode_date', 'no' => '14.1', 'label' => 'Application Mode Date'],
            ['key' => 'received_application', 'no' => '14.2', 'label' => 'Application Received'],
            ['key' => 'reference_no', 'no' => '14.2', 'label' => 'Reference No'],
            ['key' => 'type_of_service', 'no' => '14.3', 'label' => 'Type of Service', 'note' => 'For multiple values, separate with comma: Legal Advice, Filing New Lawsuit'],
            ['key' => 'type_of_service_date', 'no' => '14.3', 'label' => 'Type of Service Date'],
            ['key' => 'result_description', 'no' => '15.1', 'label' => 'Description of Service'],
            ['key' => 'file_closure_date', 'no' => '16.2', 'label' => 'File Closed Date'],
        ];
    }
}
