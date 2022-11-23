@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('resourceCost') }}">Manhour</a></li>
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
                    
                    <form action="{{ route('resourceCost.update', ['Id' => $data->Id, 'ManhourId' => $data->ManhourId]) }}" method="POST" id="formManhour" validated="false">
                        @csrf
                        @method("PUT")
    
                        <div class="card">
                            <div class="card-body pt-3">
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

                                <div class="row my-3">
                                    <label for="fullname" class="col-sm-2">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name"
                                            value="{{ $data['FirstName'].' '.$data['LastName'] }}" readonly disabled>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Level" class="col-sm-2">Level <code>*</code></label>
                                    <div class="col-sm-10">
                                        <select name="Level" id="Level" class="form-select" required>
                                            <option value="" selected disabled>Select Level</option>
                                            <option value="Entry" {{ (old('Level') ?? $data['Level']) == "Entry" ? "selected" : '' }}>Entry</option>
                                            <option value="Intermediate" {{ (old('Level') ?? $data['Level']) == "Intermediate" ? "selected" : '' }}>Intermediate</option>
                                            <option value="Senior" {{ (old('Level') ?? $data['Level']) == "Senior" ? "selected" : '' }}>Senior</option>
                                            <option value="Managerial" {{ (old('Level') ?? $data['Level']) == "Managerial" ? "selected" : '' }}>Managerial</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="BasicSalary" class="col-sm-2">Basic Salary <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" class="form-control text-end" id="BasicSalary" name="BasicSalary" placeholder="Basic Salary" required
                                            value="{{ old('BasicSalary') ?? $data['BasicSalary'] }}">
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="DailyRate" class="col-sm-2">Daily Rate</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control text-end" id="DailyRate" name="DailyRate" placeholder="Daily Rate" required
                                            value="{{ old('DailyRate') ?? $data['DailyRate'] }}" readonly disabled>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="HourlyRate" class="col-sm-2">Hourly Rate</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control text-end" id="HourlyRate" name="HourlyRate" placeholder="Hourly Rate" required
                                            value="{{ old('HourlyRate') ?? $data['HourlyRate'] }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('resourceCost') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>
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
        $(document).on('submit', '#formManhour', function(e) {
            let isValidated = $(this).attr('validated') == "true";

            if (!isValidated) {
                e.preventDefault();

                let content = `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this resource cost?</b>
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
                                $('#formManhour').attr('validated', 'true').submit();
        
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


        // ----- CHANGE BASIC SALARY -----
        $(document).on('keyup', `[name="BasicSalary"]`, function() {
            let basicSalary = this.value;
            let dailyRate = 0, hourlyRate = 0;

            if (!isNaN(basicSalary)) {
                basicSalary = Number(basicSalary);
                dailyRate = basicSalary / 22;
                hourlyRate = dailyRate / 8;
            } 

            $('#DailyRate').val(dailyRate.toFixed(2));            
            $('#HourlyRate').val(hourlyRate.toFixed(2));            
        })
        // ----- END CHANGE BASIC SALARY -----

    })

</script>

@endsection