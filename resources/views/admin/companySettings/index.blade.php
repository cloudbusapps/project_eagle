@extends('layouts.app')

@section('content')


<?php
    $HoursPerDay = $HoursPerWeek = $PTO =$PaidHoliday=$AnnualWorkingHours= null;
    $Status = 1;
    if (isset($data) && !empty($data)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('companySetting.update', ['Id' => $data['Id']]);
        $button = '<button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $HoursPerDay        = (!empty($data)) ? ($data['HoursPerDay'] ?? '') : '';
        $HoursPerWeek       = (!empty($data)) ? ($data['HoursPerWeek'] ?? '') : '';
        $PTO                = (!empty($data)) ? ($data['PTO'] ?? '') : '';
        $PaidHoliday        = (!empty($data)) ? ($data['PaidHoliday'] ?? '') : '';
        $AnnualWorkingHours = (!empty($data)) ? ($data['AnnualWorkingHours'] ?? '') : '';
    } else {
        $todo   = "insert";
        $method = "POST";
        $action = route('companySetting.save');
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
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>  

    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
        <div class="container-fluid">

            @if (Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                    <i class="bi bi-check-circle me-1"></i> 
                    <?= Session::get('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Session::get('fail'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    <?= Session::get('danger') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ $action }}" method="POST" id="formCompanySetting" validated="false" todo="{{ $todo }}">
                @csrf
                @method($method)

                <div class="card">
                    <div class="card-header">
                        <h3>Annual Working Hours</h3>
                    </div>
                    <div class="card-body">
                        <div class="row my-3">
                            <label for="HoursPerDay" class="col-sm-2">Working Hours Per Day <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="HoursPerDay" name="HoursPerDay" placeholder="Daily Hours Here"
                                   value="{{ old('HoursPerDay') ?? $HoursPerDay }}" required>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label for="HoursPerWeek" class="col-sm-2">Working Hours Per Week <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="HoursPerWeek" name="HoursPerWeek" placeholder="Hours Per Week"
                                value="{{ old('HoursPerWeek') ?? $HoursPerWeek }}" required>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label for="PTO" class="col-sm-2">Number of Days PTO <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="PTO" name="PTO" placeholder="PTO"
                                value="{{ old('PTO') ?? $PTO }}" required>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label for="PaidHoliday" class="col-sm-2">Number of Paid Holidays <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="PaidHoliday" name="PaidHoliday" placeholder="Holidays"
                                value="{{ old('PaidHoliday') ?? $PaidHoliday }}" required>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label for="AnnualWorkingHours" class="col-sm-2">Annual Working Hours</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="AnnualWorkingHours" name="AnnualWorkingHours" placeholder="Holidays"
                                value="{{ old('AnnualWorkingHours') ?? $AnnualWorkingHours }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <?=$button?>
                    </div>
                </div>
            </form>

            
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {
        
        // ----- SUBMIT -----
        $(document).on('submit', '#formCompanySetting', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="${ASSET_URL}assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to save new company settings?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="${ASSET_URL}assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this company settings?</b>
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
                                $('#formCompanySetting').attr('validated', 'true').submit();
        
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
        // ----- END SUBMIT -----

    })

</script>

@endsection