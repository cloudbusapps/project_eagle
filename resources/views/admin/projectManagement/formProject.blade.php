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
                        <li class="breadcrumb-item"><a href="{{ route('projectManagement') }}">Project Management</a></li>
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
                    
                    <form action="{{ route('pm.update', ['Id' => $data->Id, 'ProjectCostId' => $data->ProjectCostId]) }}" method="POST" id="formProject" validated="false">
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
                                    <label for="title" class="col-sm-2">Project Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Project Name"
                                            value="{{ $data['Name'] }}" readonly disabled>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="fullname" class="col-sm-2">Project Manager</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name"
                                            value="{{ $data['FirstName'].' '.$data['LastName'] }}" readonly disabled>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Description" class="col-sm-2">Description</label>
                                    <div class="col-sm-10">
                                        <textarea name="Description" class="form-control" id="Description" rows="3" style="resize: none;"
                                            readonly disabled>{{ $data['Description'] }}</textarea>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Timeline" class="col-sm-2">Timeline</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Timeline" name="Timeline" placeholder="Timeline"
                                            value="{{ date('F Y', strtotime($data['KickoffDate'])).' - '.date('F Y', strtotime($data['ClosedDate'])) }}" readonly disabled>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Budget" class="col-sm-2">Budget <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" class="form-control text-end" id="Budget" name="Budget" placeholder="Budget"
                                            value="{{ $data['Budget'] ?? 0 }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('projectManagement') }}" class="btn btn-secondary">Cancel</a>
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
        $(document).on('submit', '#formProject', function(e) {
            let isValidated = $(this).attr('validated') == "true";

            if (!isValidated) {
                e.preventDefault();

                let content = `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this project cost?</b>
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
                                $('#formProject').attr('validated', 'true').submit();
        
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