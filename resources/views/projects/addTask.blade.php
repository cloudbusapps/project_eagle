@extends('layouts.app')

@section('content')
    <?php
    $Title = $Description = $StartDate = $EndDate = $ActualStartDate = $ActualEndDate = $todo = '';
    $Manhour = $ActualTaskDuration = '';
    $isEditable = '';
    if ($type === 'insert') {
        $todo = 'insert';
        $method = 'POST';
        $action = route('projects.saveTask', ['Id' => $UserStoryId]);
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    
        $UserId = !empty($taskData) ? $taskData['UserId'] ?? '' : '';
        $status = !empty($taskData) ? $taskData['Status'] ?? '' : '';
    } elseif ($type === 'edit') {
        $todo = 'update';
        $method = 'PUT';
        $action = route('projects.updateTask', ['Id' => $Id]);
        $button =
            '<a href="/projects/delete/task/' .
            $Id .
            '" class="btn btn-danger btnDeleteForm">Delete</a>
                                  <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        $Title = !empty($taskData) ? $taskData['Title'] ?? '' : '';
        $Description = !empty($taskData) ? $taskData['Description'] ?? '' : '';
        $StartDate = !empty($taskData) ? $taskData['StartDate'] ?? '' : '';
        $EndDate = !empty($taskData) ? $taskData['EndDate'] ?? '' : '';
        $ActualStartDate = !empty($taskData) ? $taskData['ActualStartDate'] ?? '' : '';
        $ActualEndDate = !empty($taskData) ? $taskData['ActualEndDate'] ?? '' : '';
        $Manhour = !empty($taskData) ? (!empty($taskData['Manhour']) ?$taskData['Manhour']: '') : '';
        $ActualTaskDuration = !empty($taskData) ? (!empty($taskData['TimeCompleted']) ? (int) $taskData['TimeCompleted'] / 60 / 60 : '') : '';
        $UserId = !empty($taskData) ? $taskData['UserId'] ?? '' : '';
        $status = !empty($taskData) ? $taskData['Status'] ?? '' : '';
        $isEditable = $UserId == (Auth::id()|| Auth::user()->IsAdmin) ? '' : 'readonly';
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
                            <li class="breadcrumb-item"><a href="{{ route('projects') }}">List of Project</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('projects.userStoryDetails', ['Id' => $UserStoryId]) }}">User Story
                                    Details</a>
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
                        <form validated="false" id="formTask" todo="{{ $todo }}" method="POST"
                            action="{{ $action }}">
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
                                    <label for="inputText" class="col-sm-2 label">Name<code>*</code></label>
                                    <div class="col-sm-10">
                                        <input {{ $isEditable }} value="{{ old('TaskName') ?? $Title }}" required
                                            id="TaskName" name="TaskName" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 label">Description<code>*</code></label>
                                    <div class="col-sm-10">
                                        <textarea {{ $isEditable }} value="" required id="TaskDescription" name="TaskDescription" type="text"
                                            class="form-control"style="height: 82px;">{{ old('TaskDescription') ?? $Description }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-2 label">Start Date<code>*</code></label>
                                    <div class="col-sm-10">
                                        <input {{ $isEditable }} value="{{ old('TaskStartDate') ?? $StartDate }}"
                                            required name="TaskStartDate" id="TaskStartDate" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-2 label">End Date<code>*</code></label>
                                    <div class="col-sm-10">
                                        <input {{ $isEditable }} value="{{ old('TaskEndDate') ?? $EndDate }}" required
                                            name="TaskEndDate" id="TaskEndDate" type="date" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputTime" class="col-sm-2 label">Budgeted Hours</label>
                                    <div class="col-sm-10">
                                        <input {{ $isEditable }} step=".01"
                                            value="{{ old('Manhour') ?? $Manhour }}" name="Manhour"
                                            id="Manhour" type="number" min="0" class="form-control"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-2 label">Actual Start Date</label>
                                    <div class="col-sm-10">
                                        <input {{ $isEditable }}
                                            value="{{ old('ActualTaskStartDate') ?? $ActualStartDate }}"
                                            name="ActualTaskStartDate" id="ActualTaskStartDate" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-2 label">Actual End Date</label>
                                    <div class="col-sm-10">
                                        <input {{ $isEditable }}
                                            value="{{ old('ActualTaskEndDate') ?? $ActualEndDate }}"
                                            name="ActualTaskEndDate" id="ActualTaskEndDate" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputTime" class="col-sm-2 label">Actual Hours</label>
                                    <div class="col-sm-10">
                                        <input {{ $isEditable }} step=".01"
                                            value="{{ old('ActualTaskDuration') ?? $ActualTaskDuration }}"
                                            name="ActualTaskDuration" id="ActualTaskDuration" type="number"
                                            min="0" class="form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 label">User Assigned</label>
                                    <div class="col-sm-10">
                                        <select select2 {{ $isEditable }} class="form-select" name="TaskUserAssigned"
                                            id="TaskUserAssigned" aria-label="State">
                                            <option value="" selected disabled>Select User</option>
                                            @foreach ($userList as $user)
                                                <option {{ $UserId == $user->Id ? 'selected' : '' }}
                                                    value="{{ $user->Id }}">
                                                    {{ $user->FirstName . ' ' . $user->LastName }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 label">Status<code>*</code></label>
                                    <div class="col-sm-10">
                                        <select {{ $isEditable }} required class="form-select" name="TaskStatus"
                                            id="TaskStatus" aria-label="status">
                                            <option value="" selected disabled>Select Status</option>
                                            <option {{ $status == 'Pending' ? 'selected' : '' }} value="Pending">Pending
                                            </option>
                                            <option {{ $status == 'On Progress' ? 'selected' : '' }} value="On Progress">
                                                On Progress</option>
                                            <option {{ $status == 'Done' ? 'selected' : '' }} value="Done">Done
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-footer text-end">
                                <a href="{{ route('projects.userStoryDetails', ['Id' => $UserStoryId]) }}"
                                    class="btn btn-secondary">Cancel</a>
                                @if ($UserId == Auth::id() || $UserId === '' || Auth::user()->IsAdmin)
                                    <?= $button ?>
                                @endif
                            </div>

                            {{-- Modal start --}}
                            <div class="modal fade" id="modal" tabindex="-1" style="display: none;"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                        </div>
                                        <div class="modal-footer">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Modal end --}}
                        </form>
                    </div>
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

            // ----- Task Date Validation
            const firstdate = document.getElementById("TaskStartDate");
            firstdate.addEventListener('change', updateDate);

            function updateDate() {
                const minDate = firstdate.value;
                document.getElementById("TaskEndDate").value = "";
                document.getElementById("TaskEndDate").setAttribute("min", minDate);
            }


            // ----- Actual Task Date Validation
            const actualFirstDate = document.getElementById("ActualTaskStartDate");
            actualFirstDate.addEventListener('change', updateActualDate);

            function updateActualDate() {
                const actualMinDate = actualFirstDate.value;
                document.getElementById("ActualTaskEndDate").value = "";
                document.getElementById("ActualTaskEndDate").setAttribute("min", actualMinDate);
            }


            // ----- SUBMIT FORM -----
            $(document).on('submit', '#formTask', function(e) {
                let isValidated = $(this).attr('validated') == "true";
                let todo = $(this).attr('todo');

                if (!isValidated) {
                    e.preventDefault();

                    let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new task?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this task?</b>
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
                                    $('#formTask').attr('validated', 'true').submit();

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
                    <b class="mt-4">Are you sure you want to delete this task?</b>
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

        })
    </script>
@endsection
