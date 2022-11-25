@extends('layouts.app')

@section('content')
    <?php
    $Name = $Description = $KickoffDate = $ClosedDate = $todo = '';
    
    if ($type === 'insert') {
        $todo = 'insert';
        $method = 'POST';
        $action = '/projects/add/resource/' . $projectId . '/save';
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } elseif ($type === 'edit') {
        $todo = 'update';
        $method = 'PUT';
        $action = '/projects/update/resource/' . $projectId;
        $button = '<button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
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
                            <li class="breadcrumb-item"><a class="text-secondary" href="#">Projects</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('projects.view') }}">List of Project</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('projects.projectDetails', ['Id' => $projectId]) }}">Project Details</a>
                            </li>
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

                        <form validated="false" id="formUserResource" class="row g-3" action="{{ $action }}"
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

                            <div class="profile-overview h-25 overflow-y">
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 label">User Name</label>
                                    <div class="col-sm-9">
                                        <select select2 name="UsersId" id="UsersIdSelect" class="form-select" id="floatingSelect">
                                            <option value="" selected disabled>Select User</option>
                                            @foreach ($userList as $users)
                                                <option value="{{ $users->Id }}">
                                                    {{ $users->FirstName . ' ' . $users->LastName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <button id="btnAddUser" type="button" class="btn btn-outline-success text-end"><i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="userDiv" class="profile-overview container">
                                <label for="inputText" class="col label mb-2">Selected:</label>

                            </div>
                            <div class="button-footer text-end">
                                <a href="{{ route('projects.view') }}" class="btn btn-secondary">Cancel</a>
                                {{-- <button id="test">test</button> --}}

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
            let users = [];
            const savedUser = @json($savedUser);
            if (savedUser.length > 0) {
                savedUser.forEach(e => {
                    users.push(e.Id)
                    let body = `
                <div id="userContainer" class="row mb-3">
                                <div class="col-11 text-left mb-2">
                                    ${e.FirstName} ${e.LastName} 
                                </div>
                                <div class="col-1">
                                    <a id="btnDeleteUser" type="button" class="btn btn-outline-danger text-end"><i
                                            class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                  `;
                    $('#userDiv').append(body);
                });
            }



            // ------ ADD  USER---------
            $("#btnAddUser").click(function() {
                const selectedVal = $('#UsersIdSelect :selected');
                if (selectedVal.val() === "") {
                    return ""
                }

                if (users.indexOf(selectedVal.val()) != -1) {
                    $('#UsersIdSelect').addClass('is-invalid');
                    $('#UsersIdSelect').closest('.col-sm-9').find('.invalid-feedback').text(
                        `User already picked`);
                    return ""
                }
                $('#UsersIdSelect').removeClass('is-invalid');
                $('#UsersIdSelect').closest('.col-sm-9').find('.invalid-feedback').text(``);

                users.push(selectedVal.val())


                let body = `
                <div id="userContainer" class="row mb-3">
                                <div class="col-11 text-left mb-2">
                                    ${selectedVal.text()}
                                </div>
                                <div class="col-1">
                                    <a id="btnDeleteUser" type="button" class="btn btn-outline-danger text-end"><i
                                            class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                  `;
                $('#userDiv').append(body);
                $('#UsersIdSelect').val('');
            });





            // ----- DELETE USER -----
            $(document).on('click', '#btnDeleteUser', function() {
                let parent = $(this).closest('#userContainer');
                const index = parent.index() - 1
                users.splice(index, 1)
                parent.fadeOut(500, function() {
                    parent.remove();
                })
            });

            // ----- SUBMIT FORM -----
            $(document).on('submit', '#formUserResource', function(e) {
                let isValidated = $(this).attr('validated') == "true";
                let todo = $(this).attr('todo');

                // IF ONLY ONE USER IS ADDED MAKE IT THIS USER,

                if (!isValidated) {
                    e.preventDefault();

                    let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add these users to the project?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update the users in this project?</b>
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
                                    $.ajax({
                                        method: "{{ $method }}",
                                        url: '{{ $action }}',
                                        dataType: 'json',
                                        data: {
                                            usersId: users,
                                        },
                                        success: function(response) {
                                            window.location=response.url;
                                        }
                                    });
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
