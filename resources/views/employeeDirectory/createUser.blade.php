@extends('layouts.app')

@section('content')

<main  id="main" class="main">
    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>  
    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
        <div class="container-fluid">
            <div class="card">
                <form id="formAddUser" validated="false" action="{{ route('employeeDirectory.save') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="card-body pt-3">
                        <div class="card-title">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <div>
                                            <i class="bi bi-exclamation-octagon me-1"></i>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @if (Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= Session::get('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @if (Session::get('fail'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                    <?= Session::get('danger') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                        <div class="row my-3">
                            <label for="EmployeeNumber" class="col-sm-2">Employee # <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="EmployeeNumber" name="EmployeeNumber" placeholder="Employee Number" required
                                    value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="FirstName" class="col-sm-2">First Name <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" required
                                    value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="MiddleName" class="col-sm-2">Middle Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="MiddleName" name="MiddleName" placeholder="Middle Name" 
                                    value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="LastName" class="col-sm-2">Last Name <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last Name" required
                                    value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Gender" class="col-sm-2">Gender <code>*</code></label>
                            <div class="col-sm-10">
                                <select class="form-control" id="Gender" name="Gender" required select2>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male" >Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-2">Email Address <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required
                                    value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ContactNumber" class="col-sm-2">Contact No. <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ContactNumber" name="ContactNumber" placeholder="Contact Number" 
                                    value="" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="DepartmentId" class="col-sm-2">Department <code>*</code></label>
                            <div class="col-sm-10">
                                <select name="DepartmentId" id="DepartmentId" class="form-control" data="{{ $departments }}" select2 required>
                                    <option value="" selected disabled>Select Department</option>
                                    
                                    @foreach ($departments as $dt)
                                    <option value="{{ $dt['Id'] }}">{{ $dt['Name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Title" class="col-sm-2">Designation <code>*</code></label>
                            <div class="col-sm-10">
                                {{-- <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" required
                                    value="{{ old('Title') ?? $userData['Title'] }}"> --}}
    
                                    <select name="DesignationId" id="DesignationId" class="form-control" select2 required data="{{ $designations }}">
                                        <option value="" selected disabled>Select Designation</option>
                                    </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="Address" class="col-sm-2">Address</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="Address" id="Address" rows="3" 
                                    placeholder="Address" style="resize: none;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a class="btn btn-secondary" href="{{ route('employeeDirectory') }}">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btnAddUser">
                            Save
                        </button>
                    </div>
                </form>
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
                html += `<option value="${Id}">${Name}</option>`;
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
        $(document).on('submit', '#formAddUser', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');
            if(!isValidated){
                e.preventDefault();
                let content = `
                            <div class="d-flex justify-content-center align-items-center flex-column text-center">
                                <img src="${ASSET_URL}assets/img/modal/new.svg" class="py-3" height="150" width="150">
                                <b class="mt-4">Are you sure you want to add new user?</b>
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
                                $('#formAddUser').attr('validated', 'true').submit();
                
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