@extends('layouts.app')

@section('content')
    <?php
    $Title = $Description = $StartDate = $EndDate = $ActualStartDate = $ActualEndDate = $todo = '';
    
    if ($type === 'insert') {
        $todo = 'insert';
        $method = 'POST';
        $action = route('projects.saveUserStory', ['Id' => $projectId]);
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } elseif ($type === 'edit') {
        $todo = 'update';
        $method = 'PUT';
        $action = route('projects.updateUserStory', ['Id' => $Id]);
        $button =
            '<a href="/projects/delete/userStory/' .
            $Id .
            '" class="btn btn-danger btnDeleteForm">Delete</a>
                                                                                            <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $Title = !empty($userStoryData) ? $userStoryData['Title'] ?? '' : '';
        $Description = !empty($userStoryData) ? $userStoryData['Description'] ?? '' : '';
        $StartDate = !empty($userStoryData) ? $userStoryData['StartDate'] ?? '' : '';
        $EndDate = !empty($userStoryData) ? $userStoryData['EndDate'] ?? '' : '';
        $ActualStartDate = !empty($userStoryData) ? $userStoryData['ActualStartDate'] ?? '' : '';
        $ActualEndDate = !empty($userStoryData) ? $userStoryData['ActualEndDate'] ?? '' : '';
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
                            <li class="breadcrumb-item"><a class="text-secondary" href="#">Projects</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('projects.view') }}">List of Project</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('projects.projectDetails', ['Id' => $projectId]) }}">Project Details</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>  
    
        <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
    
                        <form validated="false" id="formUserStory" class="row g-3" action="{{ $action }}"
                            todo="{{ $todo }}" method="POST">
                            @csrf
                            @method($method)
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
    
                            <div class="profile-overview">
                                <div class="row mb-3">
                                    <label class="col-sm-2 label">Title<code>*</code></label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('UserStoryTitle') ?? $Title }}" required type="text"
                                            class="form-control" name="UserStoryTitle" id="UserStoryTitle" placeholder="Name">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 label">Description <code>*</code></label>
                                    <div class="col-sm-10">
                                        <textarea required class="form-control" name="UserStoryDescription" placeholder="Description" id="UserStoryDescription"
                                            style="height: 82px;">{{ old('UserStoryDescription') ?? $Description }}</textarea>
    
                                    </div>
                                </div>
    
    
                                <div class="row mb-3">
                                    <label class="col-sm-2 label">Start Date <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('UserStoryStartDate') ?? $StartDate }}" required
                                            placeholder="Kickoff Date" name="UserStoryStartDate" id="UserStoryStartDate"
                                            type="date" class="form-control">
    
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 label">End Date <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('UserStoryEndDate') ?? $EndDate }}" required
                                            placeholder="Closed Date" name="UserStoryEndDate" id="UserStoryEndDate"
                                            type="date" class="form-control">
    
                                    </div>
                                </div>
                                {{-- <div class="row mb-3">
                                    <label class="col-sm-2 label">Actual Start Date</label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('UserStoryActualStartDate') ?? $ActualStartDate }}"
                                            placeholder="Closed Date" name="UserStoryActualStartDate"
                                            id="UserStoryActualStartDate" type="date" class="form-control">
    
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 label">Actual End Date</label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('UserStoryActualEndDate') ?? $ActualEndDate }}"
                                            placeholder="Closed Date" name="UserStoryActualEndDate" id="UserStoryActualEndDate"
                                            type="date" class="form-control">
    
                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-footer text-end">
                                <a href="{{ route('projects.projectDetails', ['Id' => $projectId]) }}"
                                    class="btn btn-secondary">Cancel</a>
    
                                <?= $button ?>
                            </div>
    
                    </div>
    
                    </form>
    
    
    
                </div>
            </div>
        </div>

    </main>


    <script>
        $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ----- SUBMIT FORM -----
            $(document).on('submit', '#formUserStory', function(e) {
                let isValidated = $(this).attr('validated') == "true";
                let todo = $(this).attr('todo');

                if (!isValidated) {
                    e.preventDefault();

                    let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new user story?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this user story?</b>
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
                                action: function() {
                                    $('#formUserStory').attr('validated', 'true').submit();

                                    confirmation.buttons.yes.setText(
                                        `<span class="spinner-border spinner-border-sm"></span> Please wait...`
                                    );
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
                    <b class="mt-4">Are you sure you want to delete this user story?</b>
                </div>`,
                    buttons: {

                        no: {
                            btnClass: 'btn-default',
                        },
                        yes: {
                            btnClass: 'btn-blue',
                            keys: ['enter'],
                            action: function() {
                                $('#formProject').attr('validated', 'true').submit();
                                confirmation.buttons.yes.setText(
                                    `<span class="spinner-border spinner-border-sm"></span> Please wait...`
                                );
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


            //------ DATE VALIDATION -------

            const firstdate = document.getElementById("UserStoryStartDate");
            firstdate.addEventListener('change', updateDate);

            function updateDate() {
                const minDate = firstdate.value;
                document.getElementById("UserStoryEndDate").value = "";
                document.getElementById("UserStoryEndDate").setAttribute("min", minDate);
            }

        })
    </script>
@endsection
