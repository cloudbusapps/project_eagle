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
                    
                    @if (Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                            <i class="bi bi-check-circle me-1"></i> 
                            <?= Session::get('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table table-striped table-hover" id="tableManhour">
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
                            
                        @if (!empty($data))
                        @foreach ($data as $index => $dt)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('resourceCost.edit', ['Id' => $dt['Id']]) }}">
                                    {{ $dt['FirstName'].' '.$dt['LastName'] }}
                                </a>
                            </td>
                            <td>{{ $dt['Level'] }}</td>
                            <td class="text-end">{{ formatAmount($dt['BasicSalary']) }}</td>
                            <td class="text-end">{{ formatAmount($dt['DailyRate']) }}</td>
                            <td class="text-end">{{ formatAmount($dt['HourlyRate']) }}</td>
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
        let tableManhour = $('#tableManhour')
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
    })

</script>

@endsection