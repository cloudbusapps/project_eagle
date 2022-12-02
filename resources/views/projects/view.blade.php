@extends('layouts.app')

@section('content')
    <!-- datagrid -->
    <main id="main" class="main">

        <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h4 class="mb-0">{{ $title }}</h4>
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a class="text-secondary" href="#">Project</a></li>
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
                            @if (Auth::user()->IsAdmin)
                            <div class="text-end">

                                <a href="{{ route('projects.addProject') }}" type="button" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i> New Project
                                </a>
                            </div>
                            @endif
                          


                        </div>
                        <!-- Table with stripped rows -->
                        <table id="projectTable" class="table table-striped" style="width:100%">
                            <thead>

                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Project Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Kickoff Date</th>
                                    <th scope="col">Closed Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projectData as $index => $project)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>

                                        <td>
                                            <a href="{{ route('projects.projectDetails', ['Id' => $project->Id]) }}">
                                                {{ $project->Name }}</a>
                                        </td>

                                        <td>{{ $project->Description }}</td>
                                        <td>{{ date('F d, Y', strtotime($project->KickoffDate)) }}</td>
                                        <td>{{ date('F d, Y', strtotime($project->ClosedDate)) }}</td>
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
            let dataTable = $('#projectTable').DataTable({
                scrollX: true,
                scrollY: '500px',
                sorting: [],
                scrollCollapse: true,

            });
            // ----- END DATATABLES -----




        })
    </script>
@endsection
