@extends('layouts.app')

@section('content')

<?php
    $Code = $Description = $DateTaken = $Status = $todo = '';

    if (isset($UserId) && !empty($UserId)) {
        $todo   = "insert";
        $method = "POST";
        $action = route('user.saveCertification', ['Id' => $UserId]);
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } else if (isset($Id) && !empty($Id)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('user.updateCertification', ['Id' => $Id]);
        $button = '<a href="/user/profile/delete/certification/'.$Id.'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $Code        = (!empty($data)) ? ($data['Code'] ?? '') : '';
        $Description = (!empty($data)) ? ($data['Description'] ?? '') : '';
        $DateTaken   = (!empty($data)) ? ($data['DateTaken'] ?? '') : '';
        $Status      = (!empty($data)) ? ($data['Status'] ?? '') : '';

    } else {
        return redirect()->back();
    }
?>

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
    
                    <form action="{{ $action }}" method="POST" id="formCertification" validated="false" todo="{{ $todo }}">
                        @csrf
                        @method($method)
    
                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="row my-3">
                                    <label for="Code" class="col-sm-2">Code <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Code" name="Code" placeholder="Code" required
                                            value="{{ old('Code') ?? $Code }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Description" class="col-sm-2">Description <code>*</code></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" placeholder="Description" id="Description" name="Description" style="height: 100px; resize: none;" required>{{ old('Description') ?? $Description }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="DateTaken" class="col-sm-2">Date Taken</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="DateTaken" name="DateTaken" placeholder="Date Taken"
                                            value="{{ old('DateTaken') ?? $DateTaken }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Status" class="col-sm-2">Status <code>*</code></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="Status" name="Status" required>
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="To Take" {{ (old('Status') ?? $Status) == 'To Take' ? 'selected' : ''}}>To Take</option>
                                        <option value="For Review" {{ (old('Status') ?? $Status) == 'For Review' ? 'selected' : ''}}>For Review</option>
                                        <option value="Acquired" {{ (old('Status') ?? $Status) == 'Acquired' ? 'selected' : ''}}>Acquired</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('user.viewProfile') }}" class="btn btn-secondary">Cancel</a>
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
        $(document).on('submit', '#formCertification', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new certification?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this certification?</b>
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
                                $('#formCertification').attr('validated', 'true').submit();
        
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
                    <b class="mt-4">Are you sure you want to delete this certification?</b>
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