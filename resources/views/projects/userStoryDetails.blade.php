@extends('layouts.app')

@section('content')
    <?php
    $canModify = Auth::user()->IsAdmin ? true : ($userStoryData->ProjectManagerId == Auth::id() ? true : false);
    $percentage = 0;
    $progressColor = 'bg-info';
    if ($userStoryData->PercentComplete != null) {
        $percentage = (float) $userStoryData->PercentComplete;
    }
    if ($percentage > 30) {
        $progressColor = '';
    }
    if ($percentage > 80) {
        $progressColor = 'bg-success';
    }
    
    function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%ad : %hhrs : %imins');
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
                                    href="{{ route('projects.projectDetails', ['Id' => $userStoryData->ProjectId]) }}">Project
                                    Details</a></li>
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


                        <div class="card-title">
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
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                        aria-selected="true">Details</button>
                                </li>
                            </ul>
                        </div>
                        <!-- Horizontal Form -->

                        <div class="profile-overview">
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Name</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ $userStoryData->Title }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Description</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ $userStoryData->Description }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Start Date</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ date('F d, Y', strtotime($userStoryData->StartDate)) }}
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">End Date</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ date('F d, Y', strtotime($userStoryData->EndDate)) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Created By</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ $userStoryData->FirstName . ' ' . $userStoryData->LastName }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Created At</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ date('F d, Y', strtotime($userStoryData->created_at)) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Percentage Completed</div>
                                <div class="col-lg-8 col-md-7">
                                    <div class="progress mt-3">
                                        <div class="progress-bar <?= $progressColor ?>" role="progressbar"
                                            style="width: {{ $percentage }}%" aria-valuenow="" aria-valuemin="0"
                                            aria-valuemax="100"><?= $percentage ?>%</div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="form-footer text-end">


                            <a href="{{ url('projects/projectDetails', ['Id' => $userStoryData->ProjectId]) }}"
                                class="btn btn-secondary">
                                Cancel
                            </a>
                            @if ($canModify)
                                <a href="{{ route('projects.editUserStory', ['Id' => $userStoryData->Id]) }}"
                                    id="btnUpdate" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Update User Story
                                </a>
                            @endif

                        </div>

                    </div>
                </div>

                <div class="card mt-3">

                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between card-title">
                            <h4 class="font-weight-bold">
                                Tasks
                            </h4>
                            @if ($canModify)
                                <div class="text-end">
                                    <a href="{{ route('projects.addTask', ['Id' => $userStoryData->Id]) }}" id="btnAddTask"
                                        type="button" class="btn btn-outline-primary">
                                        <i class="bi bi-plus-lg"></i> New Tasks
                                    </a>
                                </div>
                            @endif




                        </div>
                        <!-- Table with stripped rows -->
                        <table id="taskTable" class="table table-striped" style="width:100%">
                            <thead>

                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">User Assigned</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Budgeted Hours</th>
                                    <th scope="col">Actual Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taskData as $index => $tasks)
                                    <?php
                                    
                                    $fullname = $tasks->FirstName . ' ' . $tasks->LastName;
                                    $timeCompleted = '----';
                                    $durationAssgined = '----';
                                    
                                    if (!empty($tasks->TimeCompleted)) {
                                        $timeCompleted = secondsToTime($tasks->TimeCompleted);
                                    }
                                    if (!empty($tasks->Duration)) {
                                        $durationAssgined = secondsToTime($tasks->Duration);
                                    }
                                    
                                    switch ($tasks->Status) {
                                        case 'Done':
                                            $statusDisplay = '<span class="badge rounded-pill bg-success">Done</span>';
                                            break;
                                        case 'On Progress':
                                            $statusDisplay = '<span class="badge rounded-pill bg-warning">On Progress</span>';
                                            break;
                                        default:
                                            $statusDisplay = '<span class="badge rounded-pill bg-secondary">Pending</span>';
                                            break;
                                    }
                                    
                                    ?>
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <a href="{{ route('projects.editTask', ['Id' => $tasks->Id]) }}">
                                                {{ $tasks->Title }}</a>
                                        </td>
                                        <td>{{ $tasks->Description }}</td>
                                        <td>{{ date('d-M-Y', strtotime($tasks->StartDate)) }}</td>
                                        <td>{{ date('d-M-Y', strtotime($tasks->EndDate)) }}</td>
                                        <td>{{ $tasks->UserId ? $fullname : '----' }}</td>
                                        <td><?= $statusDisplay ?></td>

                                        <td><?= $durationAssgined ?></td>
                                        <td><?= $timeCompleted ?></td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

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


            // ----- DATATABLES -----
            let dataTable = $('#taskTable').DataTable({
                scrollX: true,
                scrollY: '500px',
                sorting: [],
                autoWidth: true,
                scrollCollapse: true,
                columnDefs: [{
                        targets: 0,
                        width: 0
                    },
                    {
                        targets: 1,
                        width: '100%'
                    },
                    {
                        targets: 2,
                        width: '100%'
                    },
                    {
                        targets: 3,
                        width: 100
                    },
                    {
                        targets: 4,
                        width: 90
                    },
                    {
                        targets: 5,
                        width: 150
                    },
                    {
                        targets: 6,
                        width: 60
                    },
                ],
            });
            // ----- END DATATABLES -----
            let useDataTable = $('#userStoryTable').DataTable({
                scrollX: true,
                scrollY: '500px',
                sorting: [],
                scrollCollapse: true,
            });
            // ----- END DATATABLES -----

        })
    </script>
@endsection
