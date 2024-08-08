@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>  

    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
        <div class="container-fluid">

            @if (Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                    <i class="bi bi-check-circle me-1"></i> 
                    <?= Session::get('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Session::get('fail'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    <?= Session::get('danger') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if (isCreateAllowed($MODULE_ID))
                        <div class="w-100 text-end mb-3">
                            <a href="{{ route('training.add') }}" class="btn btn-outline-primary px-2 py-1">
                                <i class="bi bi-plus-lg"></i> New
                            </a>
                        </div> 
                    @endif

                    <table class="table table-striped table-hover" id="tableTraining">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Training</th>
                                <th>Date</th>
                                <th>Facilitator</th>
                                <th>Purpose</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        @if (isset($data) && count($data))
                            @foreach ($data as $index => $dt)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('training.view', ['Id' => $dt->Id]) }}">
                                            {{ $dt->FirstName . ' ' . $dt->LastName }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>{{ $dt->Name }}</div>
                                        <small>{{ $dt->Type }}</small>
                                    </td>
                                    <td>
                                        {{ date('F d, Y', strtotime($dt->StartDate)) . ' - ' . date('F d, Y', strtotime($dt->EndDate)) }}
                                    </td>
                                    <td>{{ $dt->Facilitator }}</td> 
                                    <td>{{ $dt->Purpose }}</td>
                                    <td>{{ $dt->Status }}</td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {
        
        // ----- DATATABLES -----
        let tableTraining = $('#tableTraining')
            .css({ "min-width": "99%" })
            .removeAttr("width")
            .DataTable({
                scrollX: true,
                scrollY: '300px',
                sorting: [],
                scrollCollapse: true,
                columnDefs: [
                    { targets: 0,  width: 10  },
                    { targets: 1,  width: 150 },
                    { targets: 2,  width: 150 },
                    { targets: 3,  width: 150 },
                    { targets: 4,  width: 150 },
                    { targets: 5,  width: 150 },
                    { targets: 6,  width: 80  },
                ],
            });
        // ----- END DATATABLES -----

    })

</script>

@endsection