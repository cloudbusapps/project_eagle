@extends('layouts.app')

@section('content')
    <?php


    $Name = $Description = $KickoffDate = $ClosedDate = $todo = '';
    
    if ($type === 'insert') {
        $todo = 'insert';
        $method = 'POST';
        $action = route('projects.add');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } elseif ($type === 'edit') {
        $todo = 'update';
        $method = 'PUT';
        $action = route('projects.update', ['Id' => $Id]);
        $button =
            '<a href="/projects/delete/' .
            $Id .
            '" class="btn btn-danger btnDeleteForm">Delete</a>
    <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $Name = !empty($projectData) ? $projectData['Name'] ?? '' : '';
        $Description = !empty($projectData) ? $projectData['Description'] ?? '' : '';
        $KickoffDate = !empty($projectData) ? $projectData['KickoffDate'] ?? '' : '';
        $ClosedDate = !empty($projectData) ? $projectData['ClosedDate'] ?? '' : '';
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
                            <li class="breadcrumb-item"><a class="text-secondary" href="#">Project</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('projects') }}">List of Project</a></li>
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

                        <form validated="false" id="formProject" class="row g-3" action="{{ $action }}"
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
                                    <label for="inputText" class="col-sm-2 label">Project Name <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('ProjectName') ?? $Name }}" required type="text"
                                            class="form-control" name="ProjectName" id="ProjectName" placeholder="Name">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label label" for="floatingTextarea">Project Description
                                        <code>*</code></label>
                                    <div class="col-sm-10">
                                        <textarea required class="form-control" name="ProjectDescription" placeholder="Description" id="ProjectDescription"
                                            style="height: 82px;">{{ old('ProjectDescription') ?? $Description }}</textarea>
                                    </div>
                                </div>
                                @if ($type === 'edit')
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label label" for="floatingTextarea">Project Manager
                                            <code>*</code></label>
                                        <div class="col-sm-10">
                                            <select select2 name="ProjectManagerId" id="ProjectManagerId" class="form-select"
                                                id="floatingSelect" aria-label="State">
                                                <option value="" selected disabled>Select Project Manager</option>
                                                @foreach ($userList as $user)
                                                    <option
                                                        {{ $projectData->ProjectManagerId == $user->Id ? 'selected' : '' }}
                                                        value="{{ $user->Id }}">
                                                        {{ $user->FirstName . ' ' . $user->LastName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div id="title" class="d-block">

                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-3">
                                    <label class="col-sm-2 label" for="floatingTextarea">Kickoff Date <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('ProjectKickoffDate') ?? $KickoffDate }}" required
                                            placeholder="Kickoff Date" name="ProjectKickoffDate" id="ProjectKickoffDate"
                                            type="date" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label label" for="floatingTextarea">Closed
                                        Date <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input value="{{ old('ProjectClosedDate') ?? $ClosedDate }}" required
                                            placeholder="Closed Date" name="ProjectClosedDate" id="ProjectClosedDate"
                                            type="date" class="form-control">

                                    </div>
                                </div>
                            </div>
                            <div class="button-footer text-end">
                                <a href="{{ route('projects') }}" class="btn btn-secondary">Cancel</a>

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
            
             // ----- Date Validation
             const firstdate = document.getElementById("ProjectKickoffDate");
            firstdate.addEventListener('change', updateDate);

            function updateDate() {
                const minDate = firstdate.value;
                document.getElementById("ProjectClosedDate").value = "";
                document.getElementById("ProjectClosedDate").setAttribute("min", minDate);
            }

            // Fix if already have PM then display title
            // if (userId !== "") {
            //     const title = userArray.find(x => x.Id === userId).Title;
            //     $('#title').addClass('d-block');
            //     $('#title').text(`Title: ${title?title:'none'}`);
            // }

            // ----- DISPLAY TITLE BELOW SELECT -----
            $(document).on('change', 'select', function(e) {
                const userArray = @json($userList);
                const userId = $('#ProjectManagerId').find(":selected").val();
                const title = userArray.find(x => x.Id === userId).Title;

                console.log(title)
                $('#title').addClass('d-block');
                $('#title').text(`Title: ${title?title:'----'}`);
            })

            // ----- SUBMIT FORM -----
            $(document).on('submit', '#formProject', function(e) {
                let isValidated = $(this).attr('validated') == "true";
                let todo = $(this).attr('todo');

                if (!isValidated) {
                    e.preventDefault();

                    let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="${ASSET_URL}assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new project?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="${ASSET_URL}assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this project?</b>
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
                                    $('#formProject').attr('validated', 'true').submit();

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
                    <b class="mt-4">Are you sure you want to delete this project?</b>
                </div>`,
                    buttons: {

                        no: {
                            btnClass: 'btn-default',
                        },
                        yes: {
                            btnClass: 'btn-blue',
                            keys: ['enter'],
                            action: function() {

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

        })
    </script>
@endsection
