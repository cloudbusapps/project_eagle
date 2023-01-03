@extends('layouts.app')

@section('content')

<?php
    $DepartmentId = $Name = $Initial = $todo = null;
    $BeginnerRate = $IntermediateRate = $SeniorRate = $ExpertRate = $DefaultRate = 0;
    $Status = 1;

    if (isset($data) && !empty($data)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('designation.update', ['Id' => $data['Id']]);
        $button = '<a href="/admin/setup/designation/delete/'.$data['Id'].'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $DepartmentId = (!empty($data)) ? ($data['DepartmentId'] ?? '') : '';
        $Name         = (!empty($data)) ? ($data['Name'] ?? '') : '';
        $Initial      = (!empty($data)) ? ($data['Initial'] ?? '') : '';
        $Status       = (!empty($data)) ? ($data['Status'] ?? '') : '';
        $BeginnerRate     = (!empty($data)) ? ($data['BeginnerRate'] ?? '') : '';
        $IntermediateRate = (!empty($data)) ? ($data['IntermediateRate'] ?? '') : '';
        $SeniorRate       = (!empty($data)) ? ($data['SeniorRate'] ?? '') : '';
        $ExpertRate       = (!empty($data)) ? ($data['ExpertRate'] ?? '') : '';
        $DefaultRate      = (!empty($data)) ? ($data['DefaultRate'] ?? '') : '';
    } else {
        $todo   = "insert";
        $method = "POST";
        $action = route('designation.save');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    }
?>

<main id="main" class="main">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Setup</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('designation') }}">Designation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>  

    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <form action="{{ $action }}" method="POST" id="formDesignation" validated="false" todo="{{ $todo }}">
                        @csrf
                        @method($method)
    
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
                                    <label for="DepartmentId" class="col-sm-2">Department <code>*</code></label>
                                    <div class="col-sm-10">
                                        <select name="DepartmentId" id="DepartmentId" class="form-select" required select2>
                                            <option value="" selected disabled>Select Department</option>
                                            
                                            @foreach ($departments as $dt)
                                            <option value="{{ $dt->Id }}" {{ (old('DepartmentId') ?? $DepartmentId) == $dt->Id ? "selected" : '' }}>{{ $dt->Name }}</option>    
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Name" class="col-sm-2">Designation Name <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Name" name="Name" placeholder="Designation Name"
                                            value="{{ old('Name') ?? $Name }}" required>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Initial" class="col-sm-2">Initial <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Initial" name="Initial" placeholder="Initial"
                                            value="{{ old('Initial') ?? $Initial }}" required>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Status" class="col-sm-2">Status</label>
                                    <div class="col-sm-10">
                                        <select name="Status" id="Status" class="form-select" select2>
                                            <option value="1" {{ $Status == 1 ? "selected" : "" }}>Active</option>
                                            <option value="0" {{ $Status == 0 ? "selected" : "" }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row my-3 px-3">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;">Default</th>
                                                <th style="width: 50%;">Level</th>
                                                <th style="width: 50%;">Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="radio" id="radioBeginnerRate" name="DefaultRate" value="{{ $BeginnerRate }}" 
                                                        {{ $DefaultRate == 0 || $BeginnerRate == $DefaultRate ? 'checked' : '' }}>
                                                </td>
                                                <td>Entry Level</td>
                                                <td>
                                                    <input type="number" step="0.01" name="BeginnerRate" 
                                                        class="form-control text-end" 
                                                        value="{{ $BeginnerRate }}"
                                                        onkeyup="document.querySelector('#radioBeginnerRate').value = this.value"
                                                        required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="radio" id="radioIntermediateRate" name="DefaultRate" value="{{ $IntermediateRate }}"
                                                        {{ $IntermediateRate == $DefaultRate ? 'checked' : '' }}>
                                                </td>
                                                <td>Intermediate</td>
                                                <td>
                                                    <input type="number" step="0.01" name="IntermediateRate" 
                                                        class="form-control text-end" 
                                                        value="{{ $IntermediateRate }}"
                                                        onkeyup="document.querySelector('#radioIntermediateRate').value = this.value"
                                                        required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="radio" id="radioSeniorRate" name="DefaultRate" value="{{ $SeniorRate }}"
                                                        {{ $SeniorRate == $DefaultRate ? 'checked' : '' }}>
                                                </td>
                                                <td>Senior</td>
                                                <td>
                                                    <input type="number" step="0.01" name="SeniorRate" 
                                                        class="form-control text-end" 
                                                        value="{{ $SeniorRate }}"
                                                        onkeyup="document.querySelector('#radioSeniorRate').value = this.value"
                                                        required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="radio" id="radioExpertRate" name="DefaultRate" value="{{ $ExpertRate }}"
                                                        {{ $ExpertRate == $DefaultRate ? 'checked' : '' }}>
                                                </td>
                                                <td>Expert</td>
                                                <td>
                                                    <input type="number" step="0.01" name="ExpertRate" 
                                                        class="form-control text-end" 
                                                        value="{{ $ExpertRate }}"
                                                        onkeyup="document.querySelector('#radioExpertRate').value = this.value"
                                                        required>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('designation') }}" class="btn btn-secondary">Cancel</a>
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

        // ----- SUBMIT FORM -----
        $(document).on('submit', '#formDesignation', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new designation?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this designation?</b>
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
                                $('#formDesignation').attr('validated', 'true').submit();
        
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
                    <b class="mt-4">Are you sure you want to delete this designation?</b>
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