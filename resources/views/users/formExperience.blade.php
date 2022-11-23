@extends('layouts.app')

@section('content')

<?php
    $JobTitle = $Company = $Description = $StartDate = $EndDate = $todo = '';

    if (isset($UserId) && !empty($UserId)) {
        $todo   = "insert";
        $method = "POST";
        $action = route('user.saveExperience', ['Id' => $UserId]);
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } else if (isset($Id) && !empty($Id)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('user.updateExperience', ['Id' => $Id]);
        $button = '<a href="/user/profile/delete/experience/'.$Id.'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $JobTitle    = (!empty($data)) ? ($data['JobTitle'] ?? '') : '';
        $Company     = (!empty($data)) ? ($data['Company'] ?? '') : '';
        $Description = (!empty($data)) ? ($data['Description'] ?? '') : '';
        $StartDate   = (!empty($data)) ? ($data['StartDate'] ?? '') : '';
        $EndDate     = (!empty($data)) ? ($data['EndDate'] ?? '') : '';

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
    
                    <form action="{{ $action }}" method="POST" id="formExperience" validated="false" todo="{{ $todo }}">
                        @csrf
                        @method($method)
    
                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="row my-3">
                                    <label for="JobTitle" class="col-sm-2">Job Title <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="JobTitle" name="JobTitle" placeholder="Job Title" required
                                            value="{{ old('JobTitle') ?? $JobTitle }}">
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <label for="Company" class="col-sm-2">Company <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="Company" name="Company" placeholder="Company" required
                                            value="{{ old('Company') ?? $Company }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Description" class="col-sm-2">Description <code>*</code></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" placeholder="Description" id="Description" name="Description" style="height: 100px; resize: none;" required>{{ old('Description') ?? $Description }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="StartDate" class="col-sm-2">Start Date <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="StartDate" name="StartDate" placeholder="Start Date" required
                                            value="{{ old('StartDate') ?? $StartDate }}" max="{{ date('Y-m-d') }}"
                                            onchange="let dateEnd = document.getElementById('EndDate');
                                            dateEnd.setAttribute('min', this.value);
                                            dateEnd.value = this.value">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="EndDate" class="col-sm-2">End Date <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="EndDate" name="EndDate" placeholder="End Date" required
                                            value="{{ old('EndDate') ?? $EndDate }}" max="{{ date('Y-m-d') }}">
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
        $(document).on('submit', '#formExperience', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new experience?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this experience?</b>
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
                                $('#formExperience').attr('validated', 'true').submit();
        
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
                    <b class="mt-4">Are you sure you want to delete this experience?</b>
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