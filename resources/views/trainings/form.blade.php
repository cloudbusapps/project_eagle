@extends('layouts.app')

@section('content')

<?php
    if (isset($data) && !empty($data)) {
        // $todo   = "update";
        // $method = "PUT";
        // $action = route('training.update', ['Id' => $data['Id']]);
        // $button = '<a href="'. route('training.delete', ['Id' => $data['Id']]) .'" class="btn btn-danger btnDeleteForm">Delete</a>
        // <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
    } else {
        $todo   = "insert";
        $method = "POST";
        $action = route('training.save');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    }

    // READ
    if ($todo == "read") { 
        $required = '';
        $method = '';
        $action = '';
        $button =  '
        <a href="'. route('training.delete', ['Id' => $data['Id']]) .'" class="btn btn-danger btnDeleteForm">Delete</a>
        <a href="'. route('training.edit', ['Id' => $data['Id']]) .'" class="btn btn-warning btnUpdateForm">Edit</a>';

        $UserId      = $data['UserId'];
        $Status      = $data['Status'];
        $Name        = $data['Name'];
        $FirstName   = $data['FirstName'];
        $LastName    = $data['LastName'];
        $Type        = $data['Type'];
        $StartDate   = $data['StartDate'];
        $EndDate     = $data['EndDate'];
        $Facilitator = $data['Facilitator'];
        $Purpose     = $data['Purpose'];
        $Attachments = $data['Attachments'];
    } 
    // EDIT
    else if ($todo == 'update') {
        $required = '<code>*</code>';
        $method = 'PUT';
        $action = route('training.update', ['Id' => $data['Id']]);
        $button =  '
        <a href="'. route('training.delete', ['Id' => $data['Id']]) .'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';

        $UserId      = $data['UserId'];
        $Status      = $data['Status'];
        $Name        = $data['Name'];
        $FirstName   = $data['FirstName'];
        $LastName    = $data['LastName'];
        $Type        = $data['Type'];
        $StartDate   = $data['StartDate'];
        $EndDate     = $data['EndDate'];
        $Facilitator = $data['Facilitator'];
        $Purpose     = $data['Purpose'];
        $Attachments = $data['Attachments'];
    } 
    // CREATE
    else {
        $required = '<code>*</code>';

        $UserId      = '';
        $Status      = '';
        $Name        = '';
        $FirstName   = '';
        $LastName    = '';
        $Type        = '';
        $StartDate   = null;
        $EndDate     = null;
        $Facilitator = '';
        $Purpose     = '';
        $Attachments = '';
    }
?>

<main id="main" class="main">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('training') }}">Training</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
                <div class="col text-end">
                    <a href="{{ route('training') }}" class="btn btn-secondary">
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

                    <form action="{{ $action }}" method="POST" id="formTraining" validated="false" todo="{{ $todo }}" enctype="multipart/form-data">
                        @csrf
                        @method($method)

                        <input type="hidden" name="StartDate" value="{{ $StartDate }}">
                        <input type="hidden" name="EndDate" value="{{ $EndDate }}">
    
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
                                    <label for="User" class="col-sm-2">User <?= $required ?></label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            <input type="text" class="form-control" name="User" value="{{ $FirstName }} {{ $LastName }}" disabled>                                            
                                        @else
                                            <select class="form-select" name="UserId" select2 required>
                                                <option value="" selected disabled>Select User</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->Id }}" 
                                                        {{ old('UserId') == $user->Id || $UserId == $user->Id ? 'selected' : '' }}>
                                                        {{ $user->FirstName }} {{ $user->LastName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="TrainingStatus" class="col-sm-2">Training Status <?= $required ?></label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            <input type="text" class="form-control" name="TrainingStatus" value="{{ $Status }}" disabled>
                                        @else
                                            <select class="form-select" name="TrainingStatus" select2 required>
                                                <option value="" selected disabled>Select Training Status</option>
                                                <option value="Scheduled" 
                                                    {{ old('TrainingStatus') == 'Scheduled' || $Status == 'Scheduled' ? 'selected' : '' }}>
                                                    Scheduled
                                                </option>
                                                <option value="On-going" 
                                                    {{ old('TrainingStatus') == 'On-going' || $Status == 'On-going' ? 'selected' : '' }}>
                                                    On-going
                                                </option>
                                                <option value="Completed" 
                                                    {{ old('TrainingStatus') == 'Completed' || $Status == 'Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        @endif

                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="TrainingType" class="col-sm-2">Training Type <?= $required ?></label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            <input type="text" class="form-control" name="TrainingType" value="{{ $Type }}" disabled>
                                        @else
                                            <select class="form-select" name="TrainingType" select2 required>
                                                <option value="" selected disabled>Select Training Type</option>
                                                <option value="Sales Cloud" 
                                                    {{ old('TrainingType') == 'Sales Cloud' || $Type == 'Sales Cloud' ? 'selected' : '' }}>
                                                    Sales Cloud
                                                </option>
                                                <option value="Service Cloud" 
                                                    {{ old('TrainingType') == 'Service Cloud' || $Type == 'Service Cloud' ? 'selected' : '' }}>
                                                    Service Cloud
                                                </option>
                                                <option value="Marketing Cloud" 
                                                    {{ old('TrainingType') == 'Marketing Cloud' || $Type == 'Marketing Cloud' ? 'selected' : '' }}>
                                                    Marketing Cloud
                                                </option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="TrainingName" class="col-sm-2">Training Name <?= $required ?></label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            <input type="text" class="form-control" name="TrainingName" value="{{ $Name }}" disabled>
                                        @else
                                            <input type="text" 
                                                class="form-control" 
                                                id="TrainingName" 
                                                name="TrainingName" 
                                                placeholder="Training Name"
                                                value="{{ $Name }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="TrainingDate" class="col-sm-2">Training Date <?= $required ?></label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            <input type="text" class="form-control" name="TrainingDate" value="{{ date('F d, Y', strtotime($StartDate ?? now())).' - '.date('F d, Y', strtotime($EndDate ?? now())) }}" disabled>
                                        @else
                                            <input type="text" 
                                                class="form-control" 
                                                name="TrainingDate" 
                                                cdaterangepicker
                                                required
                                                value="{{ date('F d, Y', strtotime($StartDate ?? now())).' - '.date('F d, Y', strtotime($EndDate ?? now())) }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Facilitator" class="col-sm-2">Facilitator <?= $required ?></label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            <input type="text" class="form-control" name="Facilitator" value="{{ $Facilitator }}" disabled>
                                        @else
                                            <input type="text" 
                                                class="form-control" 
                                                id="Facilitator" 
                                                name="Facilitator" 
                                                placeholder="Facilitator"
                                                required
                                                value="{{ $Facilitator }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Purpose" class="col-sm-2">Purpose of Training <?= $required ?></label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            <textarea class="form-control"
                                                name="Purpose"
                                                rows="3"
                                                style="resize: none;"
                                                disabled>{{ $Purpose }}</textarea>
                                        @else
                                            <textarea class="form-control"
                                                name="Purpose"
                                                rows="3"
                                                style="resize: none;"
                                                required
                                                >{{ $Purpose }}</textarea>
                                        @endif
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Attachments" class="col-sm-2">Attachment</label>
                                    <div class="col-sm-10">
                                        @if ($todo == 'read')
                                            @if ($Attachments)
                                                <a href="/uploads/trainings/{{ $Attachments }}" target="_blank"
                                                    class="py-1 px-3" style="border-radius: 5px; background: #d3ffd3 !important;">
                                                    {{ $Attachments }}
                                                </a>
                                            @endif
                                        @else
                                            <input type="file" 
                                                class="form-control" 
                                                id="Attachments" 
                                                name="Attachments"
                                                accept="application/pdf, image/*, .pdf"
                                                value="">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('training') }}" class="btn btn-secondary">Cancel</a>
                                <?= $button ?>
                            </div>
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {

        // ----- INIT DATERANGEPICKER -----
        function cInitDateRangePicker(minDate = null, maxDate = null) {
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
                
                $(`[name="StartDate"]`).val(startDate);
                $(`[name="EndDate"]`).val(endDate);
            });
        }
        cInitDateRangePicker();
        // ----- END INIT DATERANGEPICKER -----


        // ----- SUBMIT FORM -----
        $(document).on('submit', '#formTraining', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new training?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this training?</b>
                </div>`;
    
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
                                $('#formTraining').attr('validated', 'true').submit();
        
                                confirmation.buttons.yes.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
                                confirmation.buttons.yes.disable();
                                confirmation.buttons.no.hide();
        
                                return false;
                            }
                        },
                    }
                });
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
                    <b class="mt-4">Are you sure you want to delete this training?</b>
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

    })

</script>

@endsection