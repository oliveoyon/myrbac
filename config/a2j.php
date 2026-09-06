<?php

return [
    'case_entry' => [
        'interview_date_cutoff_day' => (int) env('CASE_ENTRY_INTERVIEW_DATE_CUTOFF_DAY', 5),
    ],
    'formal_case_upload' => [
        'max_files' => (int) env('FORMAL_CASE_UPLOAD_MAX_FILES', 20),
        'max_size_mb' => (int) env('FORMAL_CASE_UPLOAD_MAX_SIZE_MB', 2),
        'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
    ],
    'reporting' => [
        'header_title' => env('REPORT_HEADER_TITLE', 'Access to Justice for Women'),
        'header_subtitle' => env('REPORT_HEADER_SUBTITLE', '(A Project Implemented jointly by Law and Justice Division, Ministry of Law, Justice and Parliamentary Affairs and GIZ Bangladesh)'),
    ],
];
