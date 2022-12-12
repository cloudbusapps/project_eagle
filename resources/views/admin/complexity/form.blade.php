@extends('layouts.app')

@section('content')

<?php
    $Title = $todo = null;
    $Status = 1;
    $details = $details ?? [];

    if (isset($data) && !empty($data)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('complexity.update', ['Id' => $data['Id']]);
        $button = '<a href="/admin/setup/complexity/delete/'.$data['Id'].'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $Title  = (!empty($data)) ? ($data['Title'] ?? '') : '';
        $Status = (!empty($data)) ? ($data['Status'] ?? '') : '';
    } else {
        $todo   = "insert";
        $method = "POST";
        $action = route('complexity.save');
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
                        <li class="breadcrumb-item"><a href="{{ route('complexity') }}">Complexity</a></li>
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
                        id="formComplexity"
                        todo="{{ $todo }}">
                        @csrf
                        @method($method)

                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="row my-3">
                                    <label for="Title" class="col-sm-2">Complexity <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Title" name="Title" placeholder="Complexity Title"
                                            value="{{ old('Title') ?? $Title }}" required>
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
                                            <h5 class="mb-0">DETAILS</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped table-hover" id="tableComplexityDetails">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 30px;"></th>
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
                                            <button class="btn btn-outline-primary btnAddRow" type="button">
                                                <i class="fas fa-plus"></i> Add Row
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('complexity') }}" class="btn btn-secondary">Cancel</a>
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
        $(document).on('submit', '#formComplexity', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new complexity?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this complexity?</b>
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
                                $('#formComplexity').attr('validated', 'true').submit();
        
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
                    <b class="mt-4">Are you sure you want to delete this complexity?</b>
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


        // ----- BUTTON ADD ROW -----
        $(document).on('click', '.btnAddRow', function() {
            let html = `
            <tr>
                <td class="text-center">
                    <button class="btn btn-outline-danger btnDeleteRow" type="button">
                        <i class="fas fa-trash-alt"></i>
                    </button>
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

            $('#tableComplexityDetails tbody').append(html);
            initSelect2();
        })
        // ----- END BUTTON ADD ROW -----


        // ----- BUTTON DELETE ROW -----
        $(document).on('click', '.btnDeleteRow', function() {
            let $parent = $(this).closest('tr');
            $parent.fadeOut(500, function() {
                $parent.remove();
            })
        })
        // ----- END BUTTON DELETE ROW -----

    })

</script>

@endsection