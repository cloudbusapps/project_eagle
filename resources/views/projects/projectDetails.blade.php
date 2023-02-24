@extends('layouts.app')

@section('content')
    <?php
    
    $canModify = Auth::user()->IsAdmin ? true : ($projectData->ProjectManagerId == Auth::id() ? true : false);
    
    function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%ad : %hhrs : %imins');
    }
    function availableTime($budgetedTime, $timeComplete)
    {
        $seconds = $budgetedTime - $timeComplete;
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        $sign = $budgetedTime > $timeComplete ? '' : '-';
        return $dtF->diff($dtT)->format('%ad : ' . $sign . '%hhrs : %imins');
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
                                    {{ $projectData->Name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Description</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ $projectData->Description }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Kickoff Date</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ date('F d, Y', strtotime($projectData->KickoffDate)) }}
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Closed Date</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ date('F d, Y', strtotime($projectData->ClosedDate)) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Project Manager</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ $projectData->ProjectManagerId ? $projectData->FirstName . ' ' . $projectData->LastName . ' - ' . $projectData->Title : '----' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Created By</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ $projectData->createdBy->FirstName . ' ' . $projectData->createdBy->LastName }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-5 label ">Created At</div>
                                <div class="col-lg-8 col-md-7">
                                    {{ date('F d, Y', strtotime($projectData->created_at)) }}
                                </div>
                            </div>
                        </div>


                        <div class="form-footer text-end">


                            <a href="{{ url('projects/view') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            @if ($canModify)
                                <a href="{{ route('projects.editProject', ['Id' => $projectData->Id]) }}" id="btnUpdate"
                                    class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Update Project
                                </a>
                            @endif

                        </div>



                    </div>
                </div>
                <div class="card mt-3">

                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between card-title">
                            <h4 class="font-weight-bold">
                                Resources
                            </h4>
                            <div class="text-end">

                                {{-- <a href="{{ route('projects.editResource', ['Id' => $projectData->Id]) }}"
                                    id="btnAddTask" type="button" class="btn btn-outline-primary">
                                    Edit Resource
                                </a> --}}
                                @if ($canModify)
                                    <a href="{{ route('projects.addResource', ['Id' => $projectData->Id]) }}"
                                        id="btnAddTask" type="button" class="btn btn-outline-primary">
                                        <i class="bi bi-plus-lg"></i> Add Resource
                                    </a>
                                @endif

                            </div>



                        </div>

                        <table id="resourcesTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Budgeted Hours</th>
                                    <th scope="col">Actual Hours</th>
                                    <th scope="col">Available Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userList as $index => $user)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <a href="{{ route('user.viewProfile', ['Id' => $user->Id]) }}">

                                                {{ $user->FirstName . ' ' . $user->LastName }}
                                            </a>
                                        </td>
                                        <td>{{ $user->Title ? $user->Title : '----' }}</td>
                                        <td>{{ $user->TotalManhours ? $user->TotalManhours : 0 }}
                                        </td>
                                        <td>{{ $user->TotalTimeCompleted ? $user->TotalTimeCompleted : 0 }}
                                        </td>

                                        <td>{{ $user->TotalTimeCompleted ? availableTime($user->TotalManhours, $user->TotalTimeCompleted) : 0 }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="card mt-3">

                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between card-title">
                            <h4 class="font-weight-bold">
                                User Story
                            </h4>
                            @if ($canModify)
                                <div class="text-end">
                                    <a href="{{ route('projects.addUserStory', ['Id' => $projectData->Id]) }}"
                                        id="btnAddTask" type="button" class="btn btn-outline-primary">
                                        <i class="bi bi-plus-lg"></i> New
                                    </a>
                                </div>
                            @endif




                        </div>

                        <table id="userStoryTable" class="table table-striped" style="width:100%">
                            <thead>

                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Percent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userStoryData as $index => $userStory)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <a href="{{ route('projects.userStoryDetails', ['Id' => $userStory->Id]) }}">
                                                {{ $userStory->Title }}</a>
                                        </td>
                                        <td>{{ $userStory->Description }}</td>
                                        <td>{{ date('F d, Y', strtotime($userStory->StartDate)) }}</td>
                                        <td>{{ date('F d, Y', strtotime($userStory->EndDate)) }}</td>
                                        <td>{{ $userStory->PercentComplete ? $userStory->PercentComplete : '0' }} %</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

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
            let dataTable = $('#resourcesTable').DataTable({
                scrollX: true,
                scrollY: '500px',
                sorting: [],
                scrollCollapse: true,
            });
            // ----- END DATATABLES -----
            let userDataTable = $('#userStoryTable').DataTable({
                scrollX: true,
                scrollY: '500px',
                sorting: [],
                scrollCollapse: true,
            });
            // ----- END DATATABLES -----

        })
    </script>
@endsection
