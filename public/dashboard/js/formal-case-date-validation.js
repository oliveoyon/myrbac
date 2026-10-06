(function () {
    'use strict';

    const relatedDateFields = [
        'family_communication_date',
        'legal_representation_date',
        'collected_vokalatnama_date',
        'collected_case_doc',
        'identify_sureties_date',
        'witness_communication_date',
        'medical_report_date',
        'legal_assistance_date',
        'assistance_under_custody_date',
        'referral_service_date',
        'resolved_dispute_date',
        'case_resolved_date',
        'appoint_lawyer_date',
        'release_status_date',
        'other_result_date',
        'prison_family_communication',
        'prison_legal_representation_date',
        'next_court_collection_date',
        'prison_next_court_date',
        'collected_case_doc_prison',
        'identify_sureties_prison_date',
        'witness_communication_prison',
        'bail_bond_submission',
        'court_order_communication',
        'application_certified_copies',
        'appeal_assistance',
        'ministerial_communication',
        'other_legal_assistance_date',
        'released_on_date',
        'send_to_date',
        'convicted_sentence_expire',
        'result_of_appeal_date',
        'prison_case_resolved_date',
        'date_of_reliefe',
        'application_mode_date',
        'intervention_taken_date',
        'to_be_taken_date'
    ];

    document.addEventListener('DOMContentLoaded', function () {
        const interviewDate = document.getElementById('interview_date');
        const form = interviewDate ? interviewDate.closest('form') : null;

        if (!interviewDate || !form) {
            return;
        }

        const dateInputs = relatedDateFields
            .map(function (name) { return form.elements.namedItem(name); })
            .filter(function (input) { return input && input.type === 'date'; });

        function fieldLabel(input) {
            const label = form.querySelector('label[for="' + input.id + '"]');
            return label ? label.textContent.replace(/\s+/g, ' ').trim() : input.name;
        }

        function validateDate(input) {
            input.setCustomValidity('');

            if (interviewDate.value && input.value && input.value < interviewDate.value) {
                input.setCustomValidity(fieldLabel(input) + ' cannot be earlier than the Date of Interview.');
                return false;
            }

            return true;
        }

        function syncMinimumDates() {
            dateInputs.forEach(function (input) {
                if (interviewDate.value) {
                    input.min = interviewDate.value;
                } else {
                    input.removeAttribute('min');
                }

                validateDate(input);
            });
        }

        interviewDate.addEventListener('change', syncMinimumDates);
        dateInputs.forEach(function (input) {
            input.addEventListener('change', function () { validateDate(input); });
        });

        form.addEventListener('submit', function (event) {
            const invalidInput = dateInputs.find(function (input) { return !validateDate(input); });

            if (!invalidInput) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            const accordion = invalidInput.closest('.accordion-collapse');
            if (accordion && window.bootstrap) {
                bootstrap.Collapse.getOrCreateInstance(accordion, { toggle: false }).show();
            }

            const message = invalidInput.validationMessage;
            if (window.Swal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please check the dates',
                    text: message,
                    confirmButtonColor: '#2f7d62'
                }).then(function () {
                    invalidInput.focus();
                    invalidInput.reportValidity();
                });
            } else {
                invalidInput.reportValidity();
            }
        }, true);

        syncMinimumDates();
    });
})();
