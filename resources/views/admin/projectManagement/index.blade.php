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
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array(Session::get('tab'), ['Project', null]) ? 'active' : '' }}" id="project-tab" data-bs-toggle="tab" data-bs-target="#project-tab-content">Project Cost</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ Session::get('tab') == 'Resource Cost' ? 'active' : '' }}" id="resource-cost-tab" data-bs-toggle="tab" data-bs-target="#resource-cost-tab-content">Resource Cost</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-4 px-4">
                        <div class="tab-pane fade {{ in_array(Session::get('tab'), ['Project', null]) ? 'show active' : '' }}" id="project-tab-content" role="tabpanel" aria-labelledby="project-tab">
                            @if (Session::get('tab') == "Project" && Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                                    <i class="bi bi-check-circle me-1"></i> 
                                    <?= Session::get('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
        
                            <table class="table table-striped table-hover" id="tableProject">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Description</th>
                                        <th>Timeline</th>
                                        <th>Budget</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                @if (!empty($projectCost))
                                @foreach ($projectCost as $index => $dt)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div>
                                            <a href="{{ route('pm.edit', ['Id' => $dt['Id']]) }}">
                                                {{ $dt['Name'] }}
                                            </a>
                                        </div>
                                        <small style="font-size: 0.8em;">{{ $dt['FirstName'].' '.$dt['LastName'] }}</small>
                                    </td>
                                    <td>{{ $dt['Description'] }}</td>
                                    <td>{{ date('M Y', strtotime($dt['KickoffDate'])) .' - '. date('M Y', strtotime($dt['ClosedDate'])) }}</td>
                                    <td class="text-end">{{ formatAmount($dt['Budget'], true) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Done</span>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
            
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade {{ Session::get('tab') == "Resource Cost" ? "show active" : "" }}" id="resource-cost-tab-content" role="tabpanel" aria-labelledby="resource-cost-tab">
                            @if (Session::get('tab') == "Resource Cost" && Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                                    <i class="bi bi-check-circle me-1"></i> 
                                    <?= Session::get('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
        
                            <table class="table table-striped table-hover" id="tableResourceCost">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>Level</th>
                                        <th>Basic Salary</th>
                                        <th>Daily Rate</th>
                                        <th>Hourly Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                @if (!empty($resourceCost))
                                @foreach ($resourceCost as $index => $dt)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('pm.resourceCost.edit', ['Id' => $dt['Id']]) }}">
                                            {{ $dt['FirstName'].' '.$dt['LastName'] }}
                                        </a>
                                    </td>
                                    <td>{{ $dt['Level'] }}</td>
                                    <td class="text-end">{{ formatAmount($dt['BasicSalary'], true) }}</td>
                                    <td class="text-end">{{ formatAmount($dt['DailyRate'], true) }}</td>
                                    <td class="text-end">{{ formatAmount($dt['HourlyRate'], true) }}</td>
                                </tr>
                                @endforeach
                                @endif
            
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {

        // ----- DATATABLES -----
        let tableResourceCost = $('#tableResourceCost')
            .css({ "min-width": "99%" })
            .removeAttr("width")
            .DataTable({
                scrollX: true,
                scrollY: '300px',
                sorting: [],
                scrollCollapse: true,
                columnDefs: [
                    { targets: 0,  width: 10  },
                    { targets: 1,  width: 120 },
                    { targets: 2,  width: 80  },
                    { targets: 3,  width: 120 },
                    { targets: 4,  width: 120 },
                    { targets: 5,  width: 120 },
                ],
            });

        let tableProject = $('#tableProject')
            .css({ "min-width": "99%" })
            .removeAttr("width")
            .DataTable({
                scrollX: true,
                scrollY: '300px',
                sorting: [],
                scrollCollapse: true,
                columnDefs: [
                    { targets: 0,  width: 10  },
                    { targets: 1,  width: 120 },
                    { targets: 2,  width: 200  },
                    { targets: 3,  width: 150 },
                    { targets: 4,  width: 120 },
                    { targets: 5,  width: 120 },
                ],
            });
        // ----- END DATATABLES -----


        // ----- ADJUST DATATABLE COLUMN WIDTH -----
        $(document).on('click', '#resource-cost-tab', function() {
            tableResourceCost.columns.adjust().draw();
        })
        $(document).on('click', '#project-tab', function() {
            tableProject.columns.adjust().draw();
        })
        // ----- END ADJUST DATATABLE COLUMN WIDTH -----
    })

</script>

@endsection