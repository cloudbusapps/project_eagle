@extends('layouts.app')

@section('content')

<?php
    $Title = $Required = $todo = null;
    $Percentage = 0;
    $Status = 1;

    $details   = $details ?? [];
    $resources = $resources ?? [];

    if (isset($data) && !empty($data)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('projectPhase.update', ['Id' => $data['Id']]);
        $button = '<a href="/admin/setup/projectPhase/delete/'.$data['Id'].'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $Title      = (!empty($data)) ? ($data['Title'] ?? '') : '';
        $Percentage = (!empty($data)) ? ($data['Percentage'] ?? '') : '';
        $Required   = (!empty($data)) ? ($data['Required'] ?? '') : '';
        $Status     = (!empty($data)) ? ($data['Status'] ?? '') : '';
    } else {
        $todo   = "insert";
        $method = "POST";
        $action = route('projectPhase.save');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    }
?>

<main id="main" class="main" designations="{{ $designations }}">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Setup</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projectPhase') }}">Project Phases</a></li>
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

                    <form action="{{ $action }}" 
                        method="POST" 
                        id="formProjectPhase"
                        todo="{{ $todo }}">
                        @csrf
                        @method($method)

                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="row my-3">
                                    <label for="Title" class="col-sm-2">Project Phase <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Title" name="Title" placeholder="Project Phase"
                                            value="{{ old('Title') ?? $Title }}" required>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Percentage" class="col-sm-2">Percentage <code>*</code></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="number" step=".01" class="form-control text-end" id="Percentage" name="Percentage" placeholder="Percentage"
                                                value="{{ old('Percentage') ?? $Percentage }}" required>
                                            <div class="input-group-append">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-3 py-2">
                                    <label for="Required" class="col-sm-2">Required</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="Required" {{ $Required == 1 ? 'checked' : '' }}>
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
                                <div class="row my-3">
                                    <div class="card">
                                        <div class="card-header p-3">
                                            <h5 class="mb-0">RESOURCES</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped table-hover" id="tableProjectPhaseResources">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 30px;"></th>
                                                        <th>Designation</th>
                                                        <th>Percentage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                @forelse ($resources as $dt)
                                                    <tr>
                                                        <td class="text-center">
                                                            <button class="btn btn-outline-danger btnDeleteRow" type="button">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <select name="SubResources[]" class="form-control" select2 required>
                                                                <option value="" selected disabled>Select Designation</option>

                                                                @foreach ($designations as $dt2)
                                                                    <option value="{{ $dt2['Id'] }}" {{ $dt['DesignationId'] == $dt2['Id'] ? 'selected' : '' }}>{{ $dt2['Name'] }}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="number" step=".01" class="form-control text-end" name="SubPercentage[]" placeholder="Percentage"
                                                                    value="{{ $dt['Percentage'] ?? 0 }}" required>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">%</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-center">
                                                            <button class="btn btn-outline-danger btnDeleteRow" type="button">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <select name="SubResources[]" class="form-control" select2 required>
                                                                <option value="" selected disabled>Select Designation</option>

                                                                @foreach ($designations as $dt)
                                                                    <option value="{{ $dt['Id'] }}">{{ $dt['Name'] }}</option>
                                                                @endforeach

                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="number" step=".01" class="form-control text-end" name="SubPercentage[]" placeholder="Percentage"
                                                                    value="0" required>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">%</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                
                                                </tbody>
                                            </table>
                                            <button class="btn btn-outline-primary btnAddRowResources" type="button">
                                                <i class="fas fa-plus"></i> Add Row
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="card">
                                        <div class="card-header p-3">
                                            <h5 class="mb-0">DETAILS</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped table-hover" id="tableProjectPhaseDetails">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;"></th>
                                                        <th style="width: 50px;">Required</th>
                                                        <th>Title</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                @forelse ($details as $dt)
                                                    <tr>
                                                        <td class="text-center">
                                                            <button class="btn btn-outline-danger btnDeleteRow" type="button">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" name="SubRequired[]" {{ $dt['Required'] == 1 ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" name="SubDetail[]" placeholder="Title" value="{{ $dt['Title'] }}" required>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="SubStatus[]" select2>
                                                                <option value="1" {{ $dt['Status'] == 1 ? 'selected' : '' }}>Active</option>
                                                                <option value="0" {{ $dt['Status'] == 0 ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-center">
                                                            <button class="btn btn-outline-danger btnDeleteRow" type="button">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" name="SubRequired[]">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" name="SubDetail[]" placeholder="Title" required>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="SubStatus[]" select2>
                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                
                                                </tbody>
                                            </table>
                                            <button class="btn btn-outline-primary btnAddRowDetails" type="button">
                                                <i class="fas fa-plus"></i> Add Row
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('projectPhase') }}" class="btn btn-secondary">Cancel</a>
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

        // ----- GLOBAL VARIABLES -----
        let designationList = JSON.parse($('main').attr('designations'));
        // ----- END GLOBAL VARIABLES -----


        // ----- SUBMIT FORM -----
        $(document).on('submit', '#formProjectPhase', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new project phase?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this project phase?</b>
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
                                $('#formProjectPhase').attr('validated', 'true').submit();
        
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
                    <b class="mt-4">Are you sure you want to delete this project phase?</b>
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


        // ----- SELECT DESIGNATION -----
        $(document).on('change', `[name="SubResources[]"]`, function(e) {
            let designationId = $(this).val();
            let desingationText = $('option:selected', this).text()?.trim();

            let designations = [];
            $(`[name="SubResources[]"]`).each(function() {
                designations.push($(this).val());
            });
            
            if (designations.filter(dt => dt == designationId).length >= 2) {
                showToast('danger', `${desingationText} is already selected`);
                $(this).val('').trigger('change');
            }
        })
        // ----- END SELECT DESIGNATION -----


        // ----- BUTTON ADD ROW RESOURCES -----
        $(document).on('click', '.btnAddRowResources', function() {
            let designationOption = '';
            if (designationList && designationList.length) {
                designationList.map(dt => {
                    designationOption += `<option value="${dt.Id}">${dt.Name}</option>`;
                })
            }

            let html = `
            <tr>
                <td class="text-center">
                    <button class="btn btn-outline-danger btnDeleteRow" type="button">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
                <td>
                    <select name="SubResources[]" class="form-control" select2 required>
                        <option value="" selected disabled>Select Designation</option>
                        ${designationOption}
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" step=".01" class="form-control text-end" name="SubPercentage[]" placeholder="Percentage"
                            value="0" required>
                        <div class="input-group-append">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                </td>
            </tr>`;

            $('#tableProjectPhaseResources tbody').append(html);
            initSelect2();
        })
        // ----- END BUTTON ADD ROW RESOURCES -----


        // ----- BUTTON ADD ROW DETAILS -----
        $(document).on('click', '.btnAddRowDetails', function() {
            let html = `
            <tr>
                <td class="text-center">
                    <button class="btn btn-outline-danger btnDeleteRow" type="button">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
                <td class="text-center">
                    <input type="checkbox" name="SubRequired[]">
                </td>
                <td>
                    <input class="form-control" name="SubDetail[]" placeholder="Title" required>
                </td>
                <td>
                    <select class="form-control" name="SubStatus[]" select2>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </td>
            </tr>`;

            $('#tableProjectPhaseDetails tbody').append(html);
            initSelect2();
        })
        // ----- END BUTTON ADD ROW DETAILS -----


        // ----- BUTTON DELETE ROW -----
        $(document).on('click', '.btnDeleteRow', function() {
            let $parentTable = $(this).closest('table');
            let $parent = $(this).closest('tr');

            if ($parentTable.attr('id') == 'tableProjectPhaseResources' && $('#tableProjectPhaseResources tbody tr').length == 1) {
                showToast('danger', 'Resources must have at least one or more assigned designation.');
                return;
            }

            $parent.fadeOut(500, function() {
                $parent.remove();
            })
        })
        // ----- END BUTTON DELETE ROW -----

    })

</script>

@endsection