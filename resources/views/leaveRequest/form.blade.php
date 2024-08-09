@extends('layouts.app')

@section('content')

<?php

    $DocumentNumber = $LeaveTypeId = $StartDate = $EndDate = $StartTime = $EndTime = $Reason = $Status = null;
    $IsWholeDay    = 1;
    $LeaveDuration = 1;
    $LeaveBalance  = 0;
    $UserId        = Auth::id();

    $requiredLabel = "<code>*</code>";
    $disabledField = '';

    $pending         = $pending ?? false;
    $currentApprover = null;

    // ADD
    $action = route('leaveRequest.save');
    $method = 'POST';
    $button = '
    <a href="'. route("leaveRequest") .'" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';

    if (in_array($event, ['view', 'edit']))  // VIEW & EDIT
    {
        if ($event == 'view') {
            $noApprover = !$data['ApproverId'] && $data['Status'] == 1;
            if (($noApprover || $pending) && $data['UserId'] == Auth::id()) { // PENDING OR REJECTED
                $action = route('leaveRequest.revise', ['Id' => $data['Id']]) ;
                $method = "GET";
                $button = '<button type="submit" class="btn btn-warning btnReviseForm">Revise</button>';
            } else {
                $button = '';
            }
    
            $requiredLabel = "";
            $disabledField = "disabled";
        } else {
            $action = route('leaveRequest.update', ['Id' => $data['Id']]);
            $method = "PUT";
            $button = '
            <a href="'. route("leaveRequest") .'" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        }

        $DocumentNumber  = $data['DocumentNumber'];
        $UserId          = $data['UserId'];
        $LeaveTypeId     = $data['LeaveTypeId'];
        $StartDate       = $data['StartDate'];
        $EndDate         = $data['EndDate'];
        $IsWholeDay      = $data['IsWholeDay'];
        $StartTime       = $data['StartTime'];
        $EndTime         = $data['EndTime'];
        $Reason          = $data['Reason'];
        $Status          = $data['Status'];
        $LeaveDuration   = $data['LeaveDuration'];
        $LeaveBalance    = $data['LeaveBalance'];
        $currentApprover = $data['ApproverId'];
    } 

    if ($currentApprover == Auth::id() && isEditAllowed($MODULE_ID)) {
        $button = '
        <div class="d-flex justify-content-end" style="gap: 5px;">
            <form action="'. route('leaveRequest.reject', ['Id' => $data->Id, 'UserId' => Auth::id()]) .'" method="POST">
                '. csrf_field() .'
                <input type="hidden" name="Remarks" id="rejectRemarks">
                <button type="submit" class="btn btn-danger btnReject">Reject</button>    
            </form>
            <form action="'. route('leaveRequest.approve', ['Id' => $data->Id, 'UserId' => Auth::id()]) .'" method="POST">
                '. csrf_field() .'
                <input type="hidden" name="Remarks" id="approveRemarks">
                <button type="submit" class="btn btn-success btnApprove">Approve</button>    
            </form>
        </div>';
    }

?>

<main id="main" class="main"
    leaveTypeId="{{ $LeaveTypeId }}"
    sickLeaveId="{{ config('constant.ID.LEAVE_TYPES.SICK_LEAVE') }}"
    vacationLeaveId="{{ config('constant.ID.LEAVE_TYPES.VACATION_LEAVE') }}"
    event="{{ $event }}">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leaveRequest') }}">Leave</a></li>
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

                    @if (!$currentApprover == Auth::id() || $UserId == Auth::id())
                    <form action="{{ $action }}" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        todo="{{ $event }}" 
                        pending="{{ $pending }}"
                        id="formLeaveRequest">
                    @endif

                        @csrf
                        @method($method)

                        <input type="hidden" name="UserId" value="{{ $UserId }}">
                        <input type="hidden" name="LeaveBalance" value="{{ $LeaveBalance }}">
                        <input type="hidden" name="StartDate" value="{{ $StartDate ?? date('Y-m-d') }}">
                        <input type="hidden" name="EndDate" value="{{ $EndDate ?? date('Y-m-d') }}">
                        <input type="hidden" name="LeaveDuration" value="{{ $LeaveDuration }}">

                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="row my-3">
                                    <label for="DocumentNumber" class="col-sm-2">Document No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" 
                                            class="form-control" 
                                            id="DocumentNumber" 
                                            name="DocumentNumber" 
                                            placeholder="Document Number"
                                            value="{{ $DocumentNumber ?? '-' }}" 
                                            disabled 
                                            readonly>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="EmployeeName" class="col-sm-2">Employee Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" 
                                            class="form-control" 
                                            id="EmployeeName" 
                                            name="EmployeeName" 
                                            placeholder="Employee Name"
                                            value="{{ $data->FirstName.' '.$data->LastName }}" 
                                            disabled 
                                            readonly>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="LeaveType" class="col-sm-2">Leave Type <?= $requiredLabel ?></label>
                                    <div class="col-sm-10">
                                        <select name="LeaveTypeId" 
                                            id="LeaveTypeId" 
                                            class="form-select" 
                                            select2 
                                            required
                                            {{ $disabledField }}>
                                            <option value="" selected disabled>Select Leave Type</option>

                                            @foreach ($leaveTypes as $dt)
                                            <option value="{{ $dt['Id'] }}" 
                                                balance="{{ $dt['Balance'] ?? 0 }}"
                                                {{ $LeaveTypeId == $dt['Id'] ? 'selected' : '' }}>{{ $dt['Name'] }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Date" class="col-sm-2">Date <?= $requiredLabel ?></label>
                                    <div class="col-sm-10">
                                        <div>
                                            <input type="text" 
                                                class="form-control" 
                                                name="Date" 
                                                cdaterangepicker
                                                required
                                                {{ $disabledField }}
                                                value="{{ date('F d, Y', strtotime($StartDate ?? now())).' - '.date('F d, Y', strtotime($EndDate ?? now())) }}">
                                        </div>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <label class="align-items-start">
                                                <input type="checkbox" name="IsWholeDay" id="IsWholeDay" {{ $IsWholeDay == 1 ? 'checked' : '' }}
                                                    onchange="
                                                    if (this.checked) {
                                                        document.querySelector('[name=StartTime]').setAttribute('disabled', true);
                                                        document.querySelector('[name=EndTime]').setAttribute('disabled', true);
                                                        document.querySelector('[name=StartTime]').removeAttribute('required');
                                                        document.querySelector('[name=EndTime]').removeAttribute('required');
                                                        document.querySelector('[name=StartTime]').value = '';
                                                        document.querySelector('[name=EndTime]').value = '';
                                                    } else {
                                                        document.querySelector('[name=StartTime]').removeAttribute('disabled');
                                                        document.querySelector('[name=EndTime]').removeAttribute('disabled');
                                                        document.querySelector('[name=StartTime]').setAttribute('required', true);
                                                        document.querySelector('[name=EndTime]').setAttribute('required', true);
                                                    }"
                                                    {{ $LeaveDuration > 1 ? 'disabled' : $disabledField }}>
                                                All day
                                            </label>
                                            <div class="d-flex justify-content-around gap-1" style="width: 80%;">
                                                <input type="time" name="StartTime" id="StartTime" value="{{ $StartTime }}" class="form-control" {{ !$disabledField ? ($IsWholeDay == 1 ? 'disabled' : '') : ' disabled' }}>
                                                <input type="time" name="EndTime" id="EndTime" value="{{ $EndTime }}" class="form-control" {{ !$disabledField ? ($IsWholeDay == 1 ? 'disabled' : '') : ' disabled' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Reason" class="col-sm-2">Reason <?= $requiredLabel ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="Reason" 
                                            id="Reason" 
                                            rows="3" 
                                            style="resize: none;" 
                                            class="form-control" 
                                            required
                                            {{ $disabledField }}>{{ $Reason ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="File" class="col-sm-2">Attachment</label>
                                    <div class="col-sm-10">
                                        @if (!in_array($event, ['view', 'revise']))
                                            <input type="file" class="form-control" id="File" name="File[]" placeholder="Attachment" multiple>
                                            <div class="row mt-1" id="displayFile"></div>
                                        @else
                                            <div class="row mt-1" id="displayFile">
                                                @if (isset($files) && count($files))
                                                @foreach ($files as $file)
                                                    <div class="col-sm-6 col-md-4 parent" filename="{{ $file['File'] }}">
                                                        <div class="p-2 border border-1 rounded">
                                                            <div class="row">
                                                                <img src="/uploads/icons/{{ getFileIcon($file['File']) }}" class="col-sm-3">
                                                                <div class="col-md-9">
                                                                    <div class="d-flex justify-content-between">
                                                                        <a href="{{ asset('uploads/leaveRequest/' . $file['File']) }}"
                                                                            class="text-black fw-bold text-truncate display-file"
                                                                            target="_blank">{{ $file['File'] }}</a>
                                                                    </div>
                                                                    <span style="font-size:14px" class="text-muted">
                                                                        {{ date('F d, Y', strtotime($file->created_at)) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @else
                                                    <div class="text-muted"><i>No attachments</i></div>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="row pt-3">
                                    @if (isset($approvers) && count($approvers))
                                    @foreach ($approvers as $dt)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="pl-2"> 
                                            <span class="badge bg-dark">Level {{ $dt['Level'] }}</span>
                                            <span class="px-2">{{ $dt['FirstName'].' '.$dt['LastName']  }}</span>
                                        </div>
                                        <?= getStatusDisplay($dt['Status'], $dt['Date'] ? date('F d, Y h:i A', strtotime($dt['Date'])) : null) ?>
                                        <?= $dt['Remarks'] ? "<div><small>".$dt['Remarks']."</small></div>" : '' ?>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>

                            </div>
                            <div class="card-footer text-end">
                                <?= $button ?>
                            </div>
                        </div>

                    @if (!$currentApprover == Auth::id() || $UserId == Auth::id())
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {

        // ----- GLOBAL VARIABLES -----
        let LEAVE_TYPE_ID     = $('#main').attr('leaveTypeId');
        let SICK_LEAVE_ID     = $('#main').attr('sickLeaveId');
        let VACATION_LEAVE_ID = $('#main').attr('vacationLeaveId');
        // ----- END GLOBAL VARIABLES -----


        // ----- SELECT FILES -----
        $(document).on('change', '#File', function() {
            let files = this.files;
            if (files && files.length) {
                if (files.length > 5) {
                    showToast('danger', `Only 5 maximum file upload.`)
                } else {
                    let html = '';
                    for (let i=0; i<files.length; i++) {
                        let { name, size } = files[i];
                        size = size / 1024 / 1024; // MB
                        let url = URL.createObjectURL(files[i])
                        if (size > 5) {
                            showToast('danger', `${name} - file size must be less than 5MB`)
                        } else {
                            html += `
                            <div class="col-sm-6 col-md-4 parent" filename="${name}">
                                <div class="p-2 border border-1 rounded">
                                    <div class="row">
                                        <img src="/uploads/icons/${getFileIcon(name)}" class="col-sm-3">
                                        <div class="col-md-9">
                                            <div class="d-flex justify-content-between display-file">
                                                <a href="${url}"
                                                    class="text-black fw-bold text-truncate"
                                                    target="_blank">${name}</a>
                                            </div>
                                            <span style="font-size:14px" class="text-muted">
                                                ${moment().format('MMMM DD, YYYY')}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        }
                    }
                    $('#displayFile').html(html);
                }
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


        // ----- SUBMIT FORM -----
        $(document).on('submit', '#formLeaveRequest', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');
            let pending     = $(this).attr('pending');

            if (!isValidated) {
                e.preventDefault();

                let leaveTypeId   = $(`[name="LeaveTypeId"]`).val();
                let files         = $(`#File`).val();
                let saveFile      = $('.display-file').length;
                let leaveDuration = $(`[name="LeaveDuration"]`).val();

                if (todo != 'view' && leaveTypeId == SICK_LEAVE_ID && parseFloat(leaveDuration) >= 2 && !saveFile) {
                    showToast('danger', 'Please upload attachment.');
                } else {
                    let content = '';
                    switch (todo) {
                        case 'edit':
                            content = `
                            <div class="d-flex justify-content-center align-items-center flex-column text-center">
                                <img src="${ASSET_URL}assets/img/modal/update.svg" class="py-1" height="150" width="150">
                                <b class="mt-4">Are you sure you want to update this leave request?</b>
                            </div>`;
                            break;
                        default:
                            if (pending) {
                                content = `
                                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                                    <img src="${ASSET_URL}assets/img/modal/revise.svg" class="py-3" height="150" width="150">
                                    <b class="mt-4">Are you sure you want to revise this leave request?</b>
                                </div>`;
                            } else {
                                content = `
                                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                                    <img src="${ASSET_URL}assets/img/modal/new.svg" class="py-3" height="150" width="150">
                                    <b class="mt-4">Are you sure you want to add new leave request?</b>
                                </div>`;
                            }
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
                                    $('main').attr('event', 'saving');
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


        // ----- INIT DATERANGEPICKER -----
        function cInitDateRangePicker(minDate = null, maxDate = null, firstLoad = true) {
            if (firstLoad) {
                if (LEAVE_TYPE_ID == VACATION_LEAVE_ID) {
                    minDate = moment().add(14, 'days');
                } else {
                    maxDate = moment();
                }
            }

            $(`[cdaterangepicker]`).daterangepicker({
                opens: 'left',
                // drops: 'up',
                showDropdowns: true,
                minDate,
                maxDate,
                locale: {
                    format: 'MMMM DD, YYYY'
                }
            }, function(start, end, label) {
                let startDate = start.format('YYYY-MM-DD');
                let endDate   = end.format('YYYY-MM-DD');
                let duration  = moment.duration(moment(endDate).diff(moment(startDate))).asDays() + 1;
                
                if (duration > 1) {
                    $(`[name="IsWholeDay"]`).prop('checked', true);
                    $(`[name="StartTime"], [name="EndTime"]`).val('');
                    $(`[name="IsWholeDay"], [name="StartTime"], [name="EndTime"]`).prop('disabled', true);
                } else {
                    $(`[name="IsWholeDay"]`).removeAttr('disabled');
                }

                $(`[name="StartDate"]`).val(startDate);
                $(`[name="EndDate"]`).val(endDate);
                $(`[name="LeaveDuration"]`).val(duration);
            });
        }
        cInitDateRangePicker();
        // ----- END INIT DATERANGEPICKER -----


        // ----- CHANGE TIME -----
        $(document).on('change', `[type=time]`, function() {
            let date = $(`[name=StartDate]`).val();
            let startTime = $(`[name=StartTime]`).val();
            let endTime = $(`[name=EndTime]`).val();
            let isWholeDay = $(`[name=IsWholeDay]`).prop('checked');
            if (!isWholeDay && startTime && endTime) {
                let startDate = moment(date+' '+startTime).format('YYYY-MM-DD HH:mm:ss');
                let endDate   = moment(date+' '+endTime).format('YYYY-MM-DD HH:mm:ss');
                let duration  = moment.duration(moment(endDate).diff(moment(startDate))).asHours();
                    duration  = duration > 0 ? (duration / 8) : 0;
                $(`[name="LeaveDuration"]`).val(duration);
                console.log(duration);
            }
        })
        // ----- END CHANGE TIME -----


        // ----- CHANGE LEAVE TYPE -----
        $(document).on('change', `[name="LeaveTypeId"]`, function() {
            let leaveTypeId = $(this).val();
            let balance = $('option:selected', this).attr('balance');
            let minDate = moment().format('MMMM DD, YYYY');
            let maxDate = moment().format('MMMM DD, YYYY');

            if (leaveTypeId == VACATION_LEAVE_ID) {
                minDate = moment(minDate).add(14, 'days').format('MMMM DD, YYYY');
                maxDate = null;
            } else if (leaveTypeId == SICK_LEAVE_ID) {
                minDate = null;
            } else {
                minDate = null;
            }

            cInitDateRangePicker(minDate, maxDate, false);
            $(`[name="LeaveBalance"]`).val(balance);
            $(`[name="StartDate"]`).val($('[name=Date]').data('daterangepicker').startDate.format('YYYY-MM-DD'));
            $(`[name="EndDate"]`).val($('[name=Date]').data('daterangepicker').endDate.format('YYYY-MM-DD'));
            $(`[name="LeaveDuration"]`).val(1);

            $(`[name="IsWholeDay"]`).prop('checked', true).removeAttr('disabled');
            $(`[name="StartTime"], [name="EndTime"]`).prop('disabled', true).val('');
        })
        // ----- END CHANGE LEAVE TYPE -----


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
                    title: `<h5>APPROVE LEAVE</h5>`,
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
                    title: `<h5>REJECT LEAVE</h5>`,
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
                                } else{
                                    showToast('danger', `Message is required`)
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