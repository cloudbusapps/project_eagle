@extends('layouts.app')

@section('content')

<?php
    $disabledField = $event == 'view' ? 'disabled' : '';
    $requiredLabel = $event == 'view' ? '' : '<code>*</code>';

    $DocumentNumber = $LeaveType = $StartDate = $EndDate = $LeaveDuration = $LeaveBalance = $Reason = $Status = null;
    $method = $action = $button = $approverButton = $todo = '';

    $currentApprover = $currentApprover ?? null;
    $pending = $pending ?? false;

    if (isset($data) && !empty($data)) {
        $UserId         = (!empty($data)) ? ($data['UserId'] ?? '') : '';
        $DocumentNumber = (!empty($data)) ? ($data['DocumentNumber'] ?? '') : '';
        $LeaveType      = (!empty($data)) ? ($data['LeaveType'] ?? '') : '';
        $StartDate      = (!empty($data)) ? ($data['StartDate'] ?? '') : '';
        $EndDate        = (!empty($data)) ? ($data['EndDate'] ?? '') : '';
        $LeaveDuration  = (!empty($data)) ? ($data['LeaveDuration'] ?? '') : '';
        $LeaveBalance   = (!empty($data)) ? ($data['LeaveBalance'] ?? '') : '';
        $Reason         = (!empty($data)) ? ($data['Reason'] ?? '') : '';
        $Status         = (!empty($data)) ? ($data['Status'] ?? '') : '';
    } 

    $UserId         = old('UserId') ?? $UserId;
    $DocumentNumber = old('DocumentNumber') ?? $DocumentNumber;
    $LeaveType      = old('LeaveType') ?? $LeaveType;
    $StartDate      = old('StartDate') ?? $StartDate;
    $EndDate        = old('EndDate') ?? $EndDate;
    $LeaveDuration  = old('LeaveDuration') ?? $LeaveDuration;
    $LeaveBalance   = old('LeaveBalance') ?? $LeaveBalance;
    $Reason         = old('Reason') ?? $Reason;

    if ($event == 'edit') {
        $todo   = "update";
        $method = "PUT";
        $action = route('leaveRequest.update', ['Id' => $data['Id']]);
        $button = '
        <a href="/forms/leaveRequest" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
    } else if ($event == 'add') {
        $UserId = Auth::id();
        $todo   = "insert";
        $method = "POST";
        $action = route('leaveRequest.save');
        $button = '
        <a href="/forms/leaveRequest" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } else if (($pending || $Status == 2) && $UserId == Auth::id()) {
        $todo   = "revise";
        $method = "GET";
        $action = route('leaveRequest.revise', ['Id' => $data['Id']]) ;
        $button = '
        <form action="'. route('leaveRequest.revise', ['Id' => $data['Id']]) .'" method="GET">
            '. csrf_field() .'
            <button type="submit" class="btn btn-warning btnReviseForm">Revise</button>    
        </form>';
    }

    if ($currentApprover == Auth::id()) {
        $button = '
        <div class="d-flex justify-content-end" style="gap: 5px;">
            <form action="'. route('leaveRequest.approve', ['Id' => $data->Id, 'UserId' => Auth::id()]) .'" method="POST">
                '. csrf_field() .'
                <input type="hidden" name="Remarks" id="approveRemarks">
                <button type="submit" class="btn btn-success btnApprove">Approve</button>    
            </form>
            <form action="'. route('leaveRequest.reject', ['Id' => $data->Id, 'UserId' => Auth::id()]) .'" method="POST">
                '. csrf_field() .'
                <input type="hidden" name="Remarks" id="rejectRemarks">
                <button type="submit" class="btn btn-danger btnReject">Reject</button>    
            </form>
        </div>';
    }
?>

<main id="main" class="main">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Forms</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leaveRequest') }}">Leave Request</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
                <div class="col text-end">
                    <a href="{{ route('leaveRequest') }}" class="btn btn-secondary">
                        <i class="bi bi-skip-backward-fill"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>  

    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @if (!$currentApprover == Auth::id())
                    <form action="{{ $action }}" method="POST" id="formLeaveRequest" validated="false" todo="{{ $todo }}" enctype="multipart/form-data">
                    @endif

                        @csrf
                        @method($method)
                        <input type="hidden" name="UserId" value="{{ $UserId }}">
                        <input type="hidden" name="LeaveBalance" value="0">
                        <input type="hidden" name="LeaveDuration" value="0">
    
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                            @foreach ($errors->all() as $error)
                                <div>
                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                    {{ $error }}
                                </div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="row my-3">
                                    <label for="DocumentNumber" class="col-sm-2">Document No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="DocumentNumber" name="DocumentNumber" placeholder="Employee Name"
                                            value="{{ $DocumentNumber ? $DocumentNumber : '-' }}" disabled readonly>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="EmployeeName" class="col-sm-2">Employee Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="EmployeeName" name="EmployeeName" placeholder="Employee Name"
                                            value="{{ $data->FirstName.' '.$data->LastName }}" disabled readonly>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="LeaveType" class="col-sm-2">Leave Type <?= $requiredLabel ?></label>
                                    <div class="col-sm-10">
                                        <select name="LeaveType" id="LeaveType" class="form-select" select2 required {{ $disabledField }}>
                                            <option value="" selected disabled>Select Leave Type</option>
                                            <option value="Vacation" {{ $LeaveType == 'Vacation' ? 'selected' : '' }}>Vacation Leave</option>
                                            <option value="Sick" {{ $LeaveType == 'Sick' ? 'selected' : '' }}>Sick Leave</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="StartDate" class="col-sm-2">Start Date <?= $requiredLabel ?></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="StartDate" name="StartDate" placeholder="Start Date"
                                            required
                                            value="{{ $StartDate }}"
                                            min="{{ $LeaveType == 'Vacation' ? date('Y-m-d', strtotime(now().' +14 days')) : null }}"
                                            max="{{ $LeaveType == 'Sick' ? date('Y-m-d') : null }}"
                                            onchange="let endDate = document.getElementById('EndDate');
                                            endDate.setAttribute('min', this.value);
                                            endDate.value = this.value"
                                            {{ $disabledField }}>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="EndDate" class="col-sm-2">End Date <?= $requiredLabel ?></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="EndDate" name="EndDate" placeholder="End Date"
                                            required 
                                            value="{{ $EndDate }}"
                                            min="{{ $LeaveType == 'Sick' ? $StartDate : date('Y-m-d') }}"
                                            {{ $disabledField }}>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Reason" class="col-sm-2">Reason <?= $requiredLabel ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="Reason" id="Reason" rows="3" style="resize: none;" class="form-control" 
                                            required
                                            {{ $disabledField }}>{{ $Reason }}</textarea>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="File" class="col-sm-2">Attachment</label>
                                    <div class="col-sm-10">

                                        @if ($event != 'view')
                                        <input type="file" class="form-control" id="File" name="File[]" placeholder="Attachment" multiple>
                                        @endif

                                        @if (isset($files) && count($files))
                                        <div class="row mt-1" id="displayFile">
                                        @foreach ($files as $file)
                                        <div class="py-2 col-sm-6 col-md-4 parent" filename="{{ $file['File'] }}">
                                            <div class="display-filename">
                                                <a href="{{ asset('uploads/leaveRequest/'.$file['File']) }}" 
                                                    class="text-white"
                                                    target="_blank">{{ $file['File'] }}</a>
                                                {{-- <button type="button" class="btn-close btnRemoveFilename"></button> --}}
                                            </div>
                                        </div>
                                        @endforeach
                                        </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="row pt-3">
                                    @if (isset($approvers) && count($approvers))
                                    @foreach ($approvers as $dt)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="pl-2"> 
                                            <span class="badge bg-warning">Level {{ $dt['Level'] }}</span>
                                            <span class="px-2">{{ $dt['FirstName'].' '.$dt['LastName']  }}</span>
                                        </div>
                                        <?= getStatusDisplay($dt['Status'], $dt['Date'] ? date('F d, Y h:i A', strtotime($dt['Date'])) : null) ?>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>

                            </div>
                            <div class="card-footer text-end">
                                <?= $button ?>
                            </div>
                        </div>
                        
                    @if (!$currentApprover == Auth::id())
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {

        // ----- SELECT FILES -----
        $(document).on('change', '#File', function() {
            let files = this.files;
            if (files && files.length) {
                let html = '';
                for (let i=0; i<files.length; i++) {
                    let { name, size } = files[i];
                    size = size / 1024 / 1024; // MB
                    let url = URL.createObjectURL(files[i])
                    if (size > 5) {
                        showToast('danger', `${name} - file size must be less than 5MB`)
                    } else {
                        html += `
                        <div class="py-2 col-sm-6 col-md-4 parent" filename="${name}">
                            <div class="display-filename">
                                <a href="${url}" 
                                    class="text-white"
                                    target="_blank">${name}</a>
                            </div>
                        </div>`;
                    }
                }
                $('#displayFile').html(html);
            }

            $(this).val();
        })
        // ----- END SELECT FILES -----


        // ----- REMOVE FILE -----
        $(document).on('click', '.btnRemoveFilename', function() {
            let $parent = $(this).closest('.parent');
            let filename = $parent.attr('filename');

            $parent.fadeOut(500, function() {
                $parent.remove();
            })
        })
        // ----- END REMOVE FILE -----


        // ----- CHANGE LEAVE TYPE -----
        $(document).on('change', `[name="LeaveType"]`, function() {
            let leaveType = $(this).val();
            let minDate = moment().format('YYYY-MM-DD');
            let maxDate = moment().format('YYYY-MM-DD');

            if (leaveType == "Vacation") {
                minDate = moment(minDate).add(14, 'days').format('YYYY-MM-DD');
                maxDate = null;
            } else if (leaveType == "Sick") {
                minDate = null;
            }

            $(`[name="StartDate"]`).attr('min', minDate)
                .attr('max', maxDate)
                .val(minDate)
                .trigger('change');
            $(`[name="EndDate"]`).attr('max', maxDate);
        })
        // ----- END CHANGE LEAVE TYPE -----


        // ----- CHANGE END DATE -----
        $(document).on('change', `[name="EndDate"]`, function() {
            let startDate = $(`[name="StartDate"]`).val();
            let endDate   = $(this).val();
            let duration = moment.duration(moment(endDate).diff(moment(startDate))).asDays() + 1;
            $(`[name="LeaveDuration"]`).val(duration);
        })
        // ----- END CHANGE END DATE -----


        // ----- SUBMIT FORM -----
        $(document).on('submit', '#formLeaveRequest', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let leaveType = $(`[name="LeaveType"]`).val();
                let files      = $(`#File`).val();
                let saveFile   = $('.display-filename').length;
                let leaveDuration = $(`[name="LeaveDuration"]`).val()
                if (leaveType == 'Sick' && parseFloat(leaveDuration) >= 2 && (!files || saveFile)) {
                    showToast('danger', 'Please upload attachment.');
                } else {
                    let content = '';
                    switch (todo) {
                        case 'revise':
                            content = `
                            <div class="d-flex justify-content-center align-items-center flex-column text-center">
                                <img src="/assets/img/modal/revise.svg" class="py-3" height="150" width="150">
                                <b class="mt-4">Are you sure you want to revise this leave request?</b>
                            </div>`;
                            break;
                        case 'update':
                            content = `
                            <div class="d-flex justify-content-center align-items-center flex-column text-center">
                                <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                                <b class="mt-4">Are you sure you want to update this leave request?</b>
                            </div>`;
                            break;
                        default:
                            content = `
                            <div class="d-flex justify-content-center align-items-center flex-column text-center">
                                <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                                <b class="mt-4">Are you sure you want to add new leave request?</b>
                            </div>`;
                    }
        
                    let confirmation = $.confirm({
                        title: false,
                        content,
                        buttons: {
                            no: {
                                btnClass: 'btn-default',
                            },
                            yes: {
                                btnClass: 'btn-blue',
                                keys: ['enter'],
                                action: function(){
                                    $('#formLeaveRequest').attr('validated', 'true').submit();
            
                                    confirmation.buttons.yes.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
                                    confirmation.buttons.yes.disable();
                                    confirmation.buttons.no.hide();
            
                                    return false;
                                }
                            },
                        }
                    });
                }

            }
        })
        // ----- END SUBMIT FORM -----


        // ----- BUTTON DELETE FORM -----
        $(document).on('click', '.btnDeleteForm', function(e) {
            e.preventDefault();
            let href = $(this).attr('href');

            let confirmation = $.confirm({
                title: false,
                content: `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/delete.svg" class="py-5" height="200" width="200">
                    <b class="mt-4">Are you sure you want to delete this module?</b>
                </div>`,
                buttons: {
                    no: {
                        btnClass: 'btn-default',
                    },
                    yes: {
                        btnClass: 'btn-blue',
                        keys: ['enter'],
                        action: function() {
    
                            confirmation.buttons.yes.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
                            confirmation.buttons.yes.disable();
                            confirmation.buttons.no.hide();

                            window.location.replace(href);
    
                            return false;
                        }
                    },
                }
            });
        })
        // ----- END BUTTON DELETE FORM -----


        // ----- SELECT ICON -----
        $(document).on('change', `[name="Icon"]`, function() {
            let [file] = this.files;
            if (file) {
                $(`img.preview-image`).attr('src', URL.createObjectURL(file));
            }
        })
        // ----- END SELECT ICON -----


        // ----- BUTTON APPROVE -----
        $(document).on('keyup', `[name="approveRemarks"]`, function() {
            let value = $(this).val()?.trim();
            $('#approveRemarks').val(value);
        })

        $(document).on('click', '.btnApprove', function(e) {
            let $form       = $(this).closest('form');
            let isValidated = $(this).attr('validated') == "true";

            if (!isValidated) {
                e.preventDefault();
    
                let confirmation = $.confirm({
                    title: `<h5>APPROVE LEAVE REQUEST</h5>`,
                    content: `
                    <div class="form-group">
                        <label>Remarks</label>    
                        <textarea class="form-control" name="approveRemarks" rows="3" style="resize: none;"></textarea>
                    </div>`,
                    buttons: {
                        cancel: {
                            btnClass: 'btn-default',
                        },
                        approve: {
                            btnClass: 'btn-success',
                            keys: ['enter'],
                            action: function(){
                                $form.attr('validated', 'true').submit();
    
                                confirmation.buttons.approve.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
                                confirmation.buttons.approve.disable();
                                confirmation.buttons.cancel.hide();
        
                                return false;
                            }
                        },
                    }
                });
            }

        })
        // ----- END BUTTON APPROVE -----


        // ----- BUTTON REJECT -----
        $(document).on('keyup', `[name="rejectRemarks"]`, function() {
            let value = $(this).val()?.trim();
            $('#rejectRemarks').val(value);
        })

        $(document).on('click', '.btnReject', function(e) {
            let $form       = $(this).closest('form');
            let isValidated = $(this).attr('validated') == "true";

            if (!isValidated) {
                e.preventDefault();
    
                let confirmation = $.confirm({
                    title: `<h5>REJECT LEAVE REQUEST</h5>`,
                    content: `
                    <div class="form-group">
                        <label>Remarks <code>*</code></label>    
                        <textarea class="form-control" name="rejectRemarks" rows="3" style="resize: none;"></textarea>
                    </div>`,
                    buttons: {
                        cancel: {
                            btnClass: 'btn-default',
                        },
                        reject: {
                            btnClass: 'btn-danger',
                            keys: ['enter'],
                            action: function(){
                                let rejectRemarks = $(`[name="rejectRemarks"]`).val()?.trim();
                                if (rejectRemarks && rejectRemarks.length) {
                                    $form.attr('validated', 'true').submit();
        
                                    confirmation.buttons.reject.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
                                    confirmation.buttons.reject.disable();
                                    confirmation.buttons.cancel.hide();
                                }
        
                                return false;
                            }
                        },
                    }
                });
            }

        })
        // ----- END BUTTON REJECT -----

    })

</script>

@endsection