@extends('layouts.app')

@section('content')

<?php
    $ParentId = $Title = $WithApproval = $RouteName = $Prefix = $todo = null;
    $SortOrder = 1;
    $Icon      = "default.png"; 
    $Status    = "Active";

    if (isset($data) && !empty($data)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('modules.update', ['id' => $data['id']]);
        $button = '<a href="/admin/modules/delete/'.$data['id'].'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $ParentId  = (!empty($data)) ? ($data['ParentId'] ?? '') : '';
        $Title     = (!empty($data)) ? ($data['Title'] ?? '') : '';
        $WithApproval = (!empty($data)) ? ($data['WithApproval'] ?? '') : '';
        $SortOrder = (!empty($data)) ? ($data['SortOrder'] ?? '') : '';
        $Icon      = (!empty($data)) ? ($data['Icon'] ?? '') : '';
        $Status    = (!empty($data)) ? ($data['Status'] ?? '') : '';
        $RouteName = (!empty($data)) ? ($data['RouteName'] ?? '') : '';
        $Prefix    = (!empty($data)) ? ($data['Prefix'] ?? '') : '';
    } else {
        $todo   = "insert";
        $method = "POST";
        $action = route('modules.save');
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
                        <li class="breadcrumb-item"><a class="text-secondary" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('modules') }}">Modules</a></li>
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

                    <form action="{{ $action }}" method="POST" id="formModule" validated="false" todo="{{ $todo }}" enctype="multipart/form-data">
                        @csrf
                        @method($method)
    
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
                                    <label for="ParentModule" class="col-sm-2">Parent Module</label>
                                    <div class="col-sm-10">
                                        <select name="ParentId" id="ParentId" class="form-select" select2>
                                            <option value="" {{ (old('ParentId') ?? $ParentId) == "" ? "selected" : '' }}>None</option>
                                            
                                            @foreach ($modules as $dt)
                                            <option value="{{ $dt->id }}" {{ (old('ParentId') ?? $ParentId) == $dt->id ? "selected" : '' }}>{{ $dt->Title }}</option>    
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Title" class="col-sm-2">Title <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Title" name="Title" placeholder="Title"
                                            value="{{ old('Title') ?? $Title }}" required>
                                    </div>
                                </div>
                                <div class="row my-3 py-2">
                                    <label for="WithApproval" class="col-sm-2">With Approval?</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="WithApproval" id="WithApproval"  
                                            {{ (old('WithApproval') ?? $WithApproval) ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="RouteName" class="col-sm-2">Route Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="RouteName" name="RouteName" placeholder="Route Name"
                                            value="{{ old('RouteName') ?? $RouteName }}">
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Prefix" class="col-sm-2">Prefix <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Prefix" name="Prefix" placeholder="Prefix"
                                            value="{{ old('Prefix') ?? $Prefix }}" required>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="SortOrder" class="col-sm-2">Sort Order <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="SortOrder" name="SortOrder" placeholder="Sort Order"
                                            value="{{ old('SortOrder') ?? $SortOrder }}" required>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Icon" class="col-sm-2">Icon</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="Icon" name="Icon" placeholder="Icon">
                                        <input type="hidden" class="form-control" id="IconStore" name="IconStore" placeholder="IconStore" value="{{ old('IconStore') ?? $Icon }}">
                                        <img class="preview-image mt-2" src="{{ asset('uploads/icons/'. (old('IconStore') ?? $Icon)) }}" alt="Icon" height="100" width="100">
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Status" class="col-sm-2">Status</label>
                                    <div class="col-sm-10">
                                        <select name="Status" id="Status" class="form-select" select2>
                                            <option value="Active" {{ $Status == "Active" ? "selected" : "" }}>Active</option>
                                            <option value="Inactive" {{ $Status == "Inactive" ? "selected" : "" }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('module') }}" class="btn btn-secondary">Cancel</a>
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
        $(document).on('submit', '#formModule', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new module?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this module?</b>
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
                                $('#formModule').attr('validated', 'true').submit();
        
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

    })

</script>

@endsection