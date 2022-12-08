@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">User</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.viewProfile') }}">Profile</a></li>
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
                    <form action="{{ route('user.updatePersonalInformation', ['Id' => $userData['Id']]) }}" method="POST" id="fomrPersonalInformation" validated="false">
                        @csrf
                        @method('PUT')
    
                        <div class="card">
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
    
                            <div class="card-body pt-3">
                                <div class="row mb-3">
                                    <label for="About" class="col-sm-2">About <code>*</code></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="About" id="About" rows="3" 
                                            placeholder="About" style="resize: none;" required>{{ old('About') ?? $userData['About'] }}</textarea>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <label for="EmployeeNumber" class="col-sm-2">Employee # <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="EmployeeNumber" name="EmployeeNumber" placeholder="Employee Number" required
                                            value="{{ old('EmployeeNumber') ?? $userData['EmployeeNumber'] }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="FirstName" class="col-sm-2">First Name <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" required
                                            value="{{ old('FirstName') ?? $userData['FirstName'] }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="MiddleName" class="col-sm-2">Middle Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="MiddleName" name="MiddleName" placeholder="Middle Name" 
                                            value="{{ old('MiddleName') ?? $userData['MiddleName'] }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="LastName" class="col-sm-2">Last Name <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last Name" required
                                            value="{{ old('LastName') ?? $userData['LastName'] }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Gender" class="col-sm-2">Gender <code>*</code></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="Gender" name="Gender" required select2>
                                            <option value="" selected disabled>Select Gender</option>
                                            <option value="Male" {{ (old('Gender') ?? $userData['Gender']) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ (old('Gender') ?? $userData['Gender']) == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Others" {{ (old('Gender') ?? $userData['Gender']) == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-2">Email Address <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required
                                            value="{{ old('email') ?? $userData['email'] }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="ContactNumber" class="col-sm-2">Contact No. <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="ContactNumber" name="ContactNumber" placeholder="Contact Number" 
                                            value="{{ old('ContactNumber') ?? $userData['ContactNumber'] }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="DepartmentId" class="col-sm-2">Department <code>*</code></label>
                                    <div class="col-sm-10">
                                        <select name="DepartmentId" id="DepartmentId" class="form-control" data="{{ $departments }}" select2 required>
                                            <option value="" selected disabled>Select Department</option>
                                            
                                            @foreach ($departments as $dt)
                                            <option value="{{ $dt['Id'] }}" {{ $dt['Id'] == $userData['DepartmentId'] ? 'selected' : '' }}>{{ $dt['Name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Title" class="col-sm-2">Designation <code>*</code></label>
                                    <div class="col-sm-10">
                                        {{-- <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" required
                                            value="{{ old('Title') ?? $userData['Title'] }}"> --}}

                                        <select name="DesignationId" id="DesignationId" class="form-control" select2 required data="{{ $designations }}" DesignationId="{{ $userData['DesignationId'] }}">
                                            <option value="" selected disabled>Select Designation</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Address" class="col-sm-2">Address</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="Address" id="Address" rows="3" 
                                            placeholder="Address" style="resize: none;">{{ old('Address') ?? $userData['Address'] }}</textarea>
                                    </div>
                                </div>
                            </div>
    
                            <div class="card-footer text-end">
                                <a href="{{ route('user.viewProfile') }}" class="btn btn-secondary">Cancel</a>
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
        
        let departmentList = JSON.parse($(`[name="DepartmentId"]`).attr('data'));
        let designationList = JSON.parse($(`[name="DesignationId"]`).attr('data'));
        $(`[name="DepartmentId"]`).trigger('change');

        // ----- DESIGNATION OPTION -----
        function designationOption(departmentId = null, designationId = null) {
            let designationOptions = designationList.filter(dt => dt.DepartmentId == departmentId);
            let html = `<option value="" selected disabled>Select Designation</option>`;
            designationOptions.map(dt => {
                let { Id, Name } = dt;
                html += `<option value="${Id}" ${designationId == Id ? 'selected' : ''}>${Name}</option>`;
            })
            $(`[name="DesignationId"]`).html(html);
            initSelect2();
        }

        let saveDepartmentId  = $(`[name="DepartmentId"]`).val();
        let saveDesignationId = $(`[name="DesignationId"]`).attr('DesignationId');
        designationOption(saveDepartmentId, saveDesignationId);
        // ----- END DESIGNATION OPTION -----


        // ----- SELECT DEPARTMENT -----
        $(document).on('change', `[name="DepartmentId"]`, function() {
            let departmentId = $(this).val();
            designationOption(departmentId);
        })
        // ----- END SELECT DEPARTMENT -----


        // ----- SUBMIT FORM -----
        $(document).on('submit', '#fomrPersonalInformation', function(e) {
            let isValidated = $(this).attr('validated') == 'true';

            if (!isValidated) {
                e.preventDefault();
    
                let confirmation = $.confirm({
                    title: false,
                    content: `
                    <div class="d-flex justify-content-center align-items-center flex-column text-center">
                        <img src="/assets/img/modal/update.svg" height="150" width="150">
                        <b class="mt-4">Are you sure you want to update personal information?</b>
                    </div>`,
                    buttons: {
                        no: {
                            btnClass: 'btn-default',
                        },
                        yes: {
                            btnClass: 'btn-blue',
                            keys: ['enter'],
                            action: function() {
                                $('#fomrPersonalInformation').attr('validated', 'true').submit();
    
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

    })

</script>

@endsection