@extends('dashboard.layouts.admin-layout')
@section('title', 'Case List')
@push('styles')
<style>
    a::after {
        content: none !important;
    }

    td a {
        display: inline-block;
        margin-right: 5px;
        /* Adjust as needed */
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }

    .case-message-action {
        position: relative;
        color: #2f7d62;
    }

    .case-message-action .message-unread-dot {
        position: absolute;
        top: -7px;
        right: -8px;
        min-width: 15px;
        height: 15px;
        padding: 0 4px;
        border-radius: 999px;
        background: #dc3545;
        color: #fff;
        font-size: 10px;
        line-height: 15px;
        text-align: center;
        font-weight: 800;
    }

    .case-message-list {
        display: grid;
        gap: 10px;
        max-height: calc(100vh - 310px);
        overflow-y: auto;
        padding-right: 4px;
    }

    .case-message-offcanvas {
        width: min(100vw, 520px) !important;
        border-left: 1px solid #dbe7df;
    }

    .case-message-offcanvas .offcanvas-header {
        align-items: flex-start;
        gap: 12px;
        background: #f8fcfa;
        border-bottom: 1px solid #dbe7df;
    }

    .case-message-offcanvas-title {
        margin: 0;
        color: #111827;
        font-size: 18px;
        font-weight: 800;
    }

    .case-message-offcanvas .offcanvas-body {
        display: flex;
        flex-direction: column;
        padding: 14px;
    }

    .case-message-compose {
        margin-top: auto;
        background: #fff;
    }

    .case-message-bubble {
        max-width: 88%;
        padding: 9px 11px;
        border: 1px solid #dbe7df;
        border-radius: 8px;
        background: #f8fcfa;
    }

    .case-message-bubble.mine {
        margin-left: auto;
        background: #eef7f1;
        border-color: #c9ddcf;
    }

    .case-message-meta {
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .case-message-text {
        color: #111827;
        font-size: 13px;
        line-height: 1.45;
        white-space: pre-wrap;
    }

    @media (max-width: 576px) {
        .case-message-offcanvas {
            width: 100vw !important;
        }

        .case-message-list {
            max-height: calc(100vh - 360px);
        }

        .case-message-bubble {
            max-width: 96%;
        }
    }
</style>
@endpush

@section('content')



<section class="contents mt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline">
                    <div class="card-header text-bg-dark d-flex justify-content-between align-items-center">
                        <h6 class="card-title">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            Case List
                        </h6>
                        <button type="button" class="btn btn-success btn-sm" id="printButton">
                            <i class="fas fa-print mr-1"></i> Print Report
                        </button>

                    </div>
                    <div class="card-body table-responsive">
                        <div class="alert alert-danger" id="errorAlert" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>
                        <div id="reportDiv">
                            <table class="table table-bordered table-striped table-hover table-sm" id="class-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Central ID</th>
                                        <th>Institute</th>
                                        {{--
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Date of Interview</th>
                                        --}}
                                        <th>Creator</th>
                                        <th>District</th>
                                        <th>PNGO</th>
                                        <th>Status</th>
                                        <th class="no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="result-table-body">
                                    @php $serial = 1; @endphp
                                    @foreach ($cases as $caseData)
                                    <tr>
                                        <td>{{ $serial++ }}</td>
                                        <td>{{ $caseData->central_id ?? 'N/A' }}</td>
                                        <td>{{ $caseData->institute ?? 'N/A' }}</td>
                                        {{--
                                        <td>{{ $caseData->full_name ?? 'N/A' }}</td>
                                        <td>{{ $caseData->phone_number ?? 'N/A' }}</td>
                                        <td>
                                            @if ($caseData->legal_representation_date)
                                            {{ \Carbon\Carbon::parse($caseData->legal_representation_date)->translatedFormat('d F, Y') }}
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        --}}
                                        <td>{{ $caseData->creator->full_name ?? $caseData->creator->name ?? 'N/A' }}</td>
                                        <td>{{ $caseData->district->name ?? 'N/A' }}</td>
                                        <td>{{ $caseData->pngo->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($caseData->status == 1)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-danger">Pending</span>
                                                @can('Verified by DPO')
                                                <div class="form-check form-switch no-print">
                                                    <input class="form-check-input verify-toggle" type="checkbox" data-id="{{ $caseData->id }}">
                                                </div>
                                                @endcan
                                            </div>
                                            @elseif ($caseData->status == 2)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-success">Verified by DPO</span>
                                                @can('Verified by MNEO')
                                                <div class="form-check form-switch no-print">
                                                    <input class="form-check-input verify-toggle-dpo" type="checkbox" data-ids="{{ $caseData->id }}">
                                                </div>
                                                @endcan
                                            </div>
                                            @elseif ($caseData->status == 3)
                                            <span class="badge bg-primary">Verified by MNEO</span>
                                            @else
                                            <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>

                                        <td class="no-print">
                                            <a href="javascript:void(0);" class="pngo-link" data-pngo-id="{{ $caseData->id }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="edit-link" data-edit-id="{{ $caseData->id }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if((int) ($caseData->file_uploads_count ?? 0) > 0)
                                            <a href="javascript:void(0);" class="file-link" data-file-id="{{ $caseData->id }}" title="View attachment">
                                                <i class="fa fa-paperclip"></i>
                                            </a>
                                            @endif
                                            @can('View Case Messages')
                                            <a href="javascript:void(0);" class="case-message-link case-message-action" data-case-id="{{ $caseData->id }}" title="Case messages">
                                                <i class="fa fa-comment-dots"></i>
                                                @if((int) ($caseData->unread_case_messages_count ?? 0) > 0)
                                                    <span class="message-unread-dot">{{ $caseData->unread_case_messages_count }}</span>
                                                @endif
                                            </a>
                                            @endcan
                                            @can('Delete Formal Case')
                                            <a href="javascript:void(0);" class="delete-case-link text-danger" data-case-id="{{ $caseData->id }}" title="Delete case">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </div>
</section>

<!-- PNGO Details Modal -->
<div class="modal fade" id="pngoModal" tabindex="-1" role="dialog" aria-labelledby="pngoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pngoModalLabel">PNGO Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="pngoDetails">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-end case-message-offcanvas" tabindex="-1" id="caseMessageOffcanvas" aria-labelledby="caseMessageOffcanvasLabel">
    <div class="offcanvas-header">
        <div>
            <h5 class="case-message-offcanvas-title" id="caseMessageOffcanvasLabel">Case Messages</h5>
            <small class="text-muted" id="caseMessageSubTitle">Loading...</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            @can('Resolve Case Message')
            <button type="button" class="btn btn-outline-success btn-sm d-none" id="resolveCaseMessageThread">
                <i class="fas fa-check"></i> Resolve
            </button>
            @endcan
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div id="caseMessageAlert" class="alert alert-danger d-none"></div>
        <div class="case-message-list mb-3" id="caseMessageList">
            <div class="text-muted text-center py-3">Loading messages...</div>
        </div>

        @canany(['Send Case Message', 'Reply Case Message'])
        <form id="caseMessageForm" class="case-message-compose border-top pt-3">
            <input type="hidden" id="caseMessageCaseId">
            <div class="row g-2">
                <div class="col-12">
                    <label class="form-label">Send To</label>
                    <select id="caseMessageReceiver" class="form-control form-control-sm" required></select>
                </div>
                <div class="col-12">
                    <label class="form-label">Message</label>
                    <textarea id="caseMessageText" class="form-control form-control-sm" rows="3" maxlength="2000" required></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </div>
        </form>
        @endcanany
    </div>
</div>







@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    $('#printButton').on('click', function(event) {
        event.preventDefault();
        var data = $('#reportDiv').clone(); // Clone to keep original structure
        data.find('.no-print').remove(); // Remove unwanted elements

        $('#loader-overlay').show(); // Show loader

        $.ajax({
            url: '{{ route('generate-pdf', [], false) }}',
            type: 'POST',
            method: 'POST',
            data: {
                pdf_data: data.html(), // Send modified HTML
                title: 'Case List',
                orientation: 'L',
                fname: 'Case List.pdf',
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.pdf_url && isValidUrl(response.pdf_url)) {
                    $('#pdfModal').remove(); // Remove existing modal before adding a new one

                    var modalContent = `
                    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel">Generated Report</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="pdfLoaderOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center;">
                                        <img src="/path/to/loader.gif" alt="Loading...">
                                    </div>
                                    <iframe id="pdfIframe" src="${response.pdf_url}" style="width: 100%; height: 80vh; display: none;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $('body').append(modalContent); // Append modal
                    $('#pdfModal').modal('show'); // Show modal

                    $('#pdfIframe').on('load', function() {
                        $('#pdfLoaderOverlay').hide(); // Hide loader when PDF loads
                        $('#pdfIframe').show();
                    });

                } else {
                    alert('Error generating PDF. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error generating PDF. Please try again.');
            },
            complete: function() {
                $('#loader-overlay').hide(); // Hide main loader
            }
        });
    });

    // URL Validation Function
    function isValidUrl(url) {
        return /^https?:\/\/.+/.test(url);
    }






    $(document).on('click', '.pngo-link', function() {
        var pngoId = $(this).data('pngo-id'); // Get PNGO ID from data attribute

        // Show the loader overlay
        $('#loader-overlay').show();

        $.ajax({
            url: '/mne/generate-form', // Update with the correct URL to fetch the PDF URL
            method: 'POST',
            data: {
                title: 'Case List',
                orientation: 'P',
                fname: 'Case List.pdf',
                id: pngoId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.pdf_url && isValidUrl(response.pdf_url)) {
                    $('#pdfModal').remove();
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');

                    var pdfUrl = response.pdf_url + (response.pdf_url.indexOf('?') === -1 ? '?' : '&') + 'v=' + Date.now();

                    // Create the modal content dynamically with the response PDF URL
                    var modalContent =
                        '<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">';
                    modalContent +=
                        '<div class="modal-dialog modal-dialog-centered modal-fullscreen">'; // Changed to fullscreen modal
                    modalContent += '<div class="modal-content">';
                    modalContent += '<div class="modal-header">';
                    modalContent += '<h5 class="modal-title" id="pdfModalLabel">CentralID Form</h5>';
                    modalContent +=
                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    modalContent += '</div>';
                    modalContent += '<div class="modal-body">';
                    modalContent +=
                        '<div id="pdfLoaderOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center;">';
                    modalContent += '<img src="/path/to/loader.gif" alt="Loader">';
                    modalContent += '</div>';
                    modalContent += '<iframe id="pdfIframe" src="' + pdfUrl +
                        '" style="width: 100%; height: 80vh; display: none;"></iframe>';
                    modalContent += '</div>';
                    modalContent += '</div>';
                    modalContent += '</div>';
                    modalContent += '</div>';

                    // Append modal to the body and show it
                    $('body').append(modalContent);
                    $('#pdfModal').modal('show');

                    // Hide the loader overlay when the PDF is loaded
                    $('#pdfIframe').on('load', function() {
                        $('#pdfLoaderOverlay').hide();
                        $('#pdfIframe').show();
                    });

                    $('#pdfModal').on('hidden.bs.modal', function() {
                        $('#pdfIframe').attr('src', 'about:blank');
                        $(this).remove();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open').css('padding-right', '');
                    });

                    console.log('PDF URL received successfully');
                } else {
                    console.error('Invalid PDF response:', response);
                    alert('Error fetching PNGO report. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
                alert('Error fetching PNGO report. Please try again.');
            },
            complete: function() {
                // Hide the loader overlay when the request is complete
                $('#loader-overlay').hide();
            }
        });
    });

    function isValidUrl(url) {
        // Check if the URL is valid based on your requirements
        return /^https?:\/\/.+/.test(url);
    }
</script>

<script>
    $(document).on("click", ".edit-link", function(event) {
        event.preventDefault();

        var editId = $(this).data("edit-id"); // Get the edit ID

        $.ajax({
            url: "/mne/edit-case",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                edit_id: editId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url; // Redirect to edit form
                }
            },
            error: function(xhr) {
                console.error("AJAX request failed", xhr);
            }
        });
    });

    $(document).on("click", ".file-link", function(event) {
        event.preventDefault();

        var fileId = $(this).data("file-id"); // Get the edit ID

        $.ajax({
            url: "/mne/edit-file",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                file_id: fileId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url; // Redirect to edit form
                    return;
                }

                Swal.fire('No attachment', response.message || 'No available attachment was found for this case.', 'info');
            },
            error: function(xhr) {
                console.error("AJAX request failed", xhr);
                Swal.fire('No attachment', xhr.responseJSON?.message || 'No available attachment was found for this case.', 'info');
            }
        });
    });

    $(document).on("click", ".delete-case-link", function(event) {
        event.preventDefault();

        const caseId = $(this).data("case-id");

        Swal.fire({
            title: 'Delete this case?',
            text: 'The case will be hidden from active lists and reports, but can be restored by an authorized user.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: "{{ url('/mne/formal-cases') }}/" + caseId,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: response.message || 'Case deleted successfully.',
                        timer: 1800,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Could not delete this case.', 'error');
                }
            });
        });
    });
</script>
<script>
    let activeCaseMessageThreadId = null;
    const caseMessageOffcanvasElement = document.getElementById('caseMessageOffcanvas');
    const caseMessageOffcanvas = caseMessageOffcanvasElement ? new bootstrap.Offcanvas(caseMessageOffcanvasElement) : null;

    function escapeCaseMessageHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function(char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function renderCaseMessages(response) {
        activeCaseMessageThreadId = response.thread.id;
        $('#caseMessageCaseId').val(response.case.id);
        $('#caseMessageSubTitle').text((response.case.central_id || 'N/A') + ' - ' + (response.case.full_name || 'N/A'));
        $('#caseMessageAlert').addClass('d-none').text('');

        const receiverSelect = $('#caseMessageReceiver');
        receiverSelect.empty();

        if (response.receivers && response.receivers.length) {
            response.receivers.forEach(function(receiver) {
                receiverSelect.append(`<option value="${receiver.id}">${escapeCaseMessageHtml(receiver.name)}${receiver.role ? ' (' + escapeCaseMessageHtml(receiver.role) + ')' : ''}</option>`);
            });
            $('#caseMessageForm').removeClass('d-none');
        } else {
            receiverSelect.append('<option value="">No eligible receiver found</option>');
            $('#caseMessageForm').addClass('d-none');
        }

        const list = $('#caseMessageList');
        list.empty();

        if (response.messages && response.messages.length) {
            response.messages.forEach(function(message) {
                list.append(`
                    <div class="case-message-bubble ${message.is_mine ? 'mine' : ''}">
                        <div class="case-message-meta">
                            ${escapeCaseMessageHtml(message.sender)} to ${escapeCaseMessageHtml(message.receiver)} - ${escapeCaseMessageHtml(message.created_at)}
                        </div>
                        <div class="case-message-text">${escapeCaseMessageHtml(message.message)}</div>
                    </div>
                `);
            });
        } else {
            list.html('<div class="text-muted text-center py-3">No messages yet for this case.</div>');
        }

        if (response.thread.status === 'resolved' || !activeCaseMessageThreadId) {
            $('#resolveCaseMessageThread').addClass('d-none');
        } else {
            $('#resolveCaseMessageThread').removeClass('d-none');
        }
    }

    function loadCaseMessages(caseId) {
        if (!caseMessageOffcanvas) {
            return;
        }

        $('#caseMessageList').html('<div class="text-muted text-center py-3">Loading messages...</div>');
        $('#caseMessageText').val('');
        caseMessageOffcanvas.show();

        $.ajax({
            url: "{{ url('/mne/case-messages/cases') }}/" + caseId,
            method: 'GET',
            success: function(response) {
                renderCaseMessages(response);
                $('.case-message-link[data-case-id="' + caseId + '"] .message-unread-dot').remove();
            },
            error: function() {
                $('#caseMessageAlert').removeClass('d-none').text('Could not load case messages.');
            }
        });
    }

    $(document).on('click', '.case-message-link', function(event) {
        event.preventDefault();
        loadCaseMessages($(this).data('case-id'));
    });

    $('#caseMessageForm').on('submit', function(event) {
        event.preventDefault();
        const caseId = $('#caseMessageCaseId').val();

        $.ajax({
            url: "{{ url('/mne/case-messages/cases') }}/" + caseId,
            method: 'POST',
            data: {
                receiver_id: $('#caseMessageReceiver').val(),
                message: $('#caseMessageText').val()
            },
            success: function() {
                $('#caseMessageText').val('');
                loadCaseMessages(caseId);
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Could not send message.';
                $('#caseMessageAlert').removeClass('d-none').text(message);
            }
        });
    });

    $('#resolveCaseMessageThread').on('click', function() {
        if (!activeCaseMessageThreadId) {
            return;
        }

        $.ajax({
            url: "{{ url('/mne/case-messages/threads') }}/" + activeCaseMessageThreadId + "/resolve",
            method: 'PATCH',
            success: function() {
                $('#resolveCaseMessageThread').addClass('d-none');
            },
            error: function() {
                $('#caseMessageAlert').removeClass('d-none').text('Could not resolve this message thread.');
            }
        });
    });
</script>
<script>
    $(document).on('change', '.verify-toggle', function() {
        const id = $(this).data('id');
        const checkbox = $(this); // Store reference to toggle

        if (this.checked) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Please see the form carefully before verifying.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('formal-case.verify') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Verified!',
                                    text: 'Case has been verified successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Replace toggle with badge
                                const td = checkbox.closest('td');
                                td.html(
                                    '<span class="badge bg-success">Verified by DPO</span>'
                                );
                            } else {
                                Swal.fire('Error!', 'Verification failed.', 'error');
                                checkbox.prop('checked', false);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred during verification.',
                                'error');
                            checkbox.prop('checked', false);
                        }
                    });
                } else {
                    checkbox.prop('checked', false); // Uncheck if canceled
                }
            });
        }
    });


    $(document).on('change', '.verify-toggle-dpo', function() {
        const id = $(this).data('ids');
        const checkbox = $(this); // Store reference to toggle

        if (this.checked) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Please see the form carefully before verifying.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('formal-case.verifymneo') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Verified!',
                                    text: 'Case has been verified successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Replace toggle with badge
                                const td = checkbox.closest('td');
                                td.html(
                                    '<span class="badge bg-primary">Verified by MNEO</span>'
                                );
                            } else {
                                Swal.fire('Error!', 'Verification failed.', 'error');
                                checkbox.prop('checked', false);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred during verification.',
                                'error');
                            checkbox.prop('checked', false);
                        }
                    });
                } else {
                    checkbox.prop('checked', false); // Uncheck if canceled
                }
            });
        }
    });
</script>
@endpush
