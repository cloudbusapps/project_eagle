@extends('layouts.app')

@section('content')
    <?php
    function getHours($TimeIn, $TimeOut)
    {
        $from_time = strtotime($TimeIn);
        $to_time = strtotime($TimeOut);
        if ($from_time > $to_time) {
            $time_perday = ($to_time + 86400 - $from_time) / 3600;
            return $hoursRendered = (int) $time_perday . ' hours, ' . ($time_perday - (int) $time_perday) * 60 . ' minutes.';
        } else {
            $time_perday = ($from_time - $to_time) / 3600;
            $date1 = new DateTime($TimeIn);
            $date2 = new DateTime($TimeOut);
            $diff = $date1->diff($date2);
            return $hoursRendered = $diff->format('%h hours, %i minutes.');
        }
    }
    
    ?>
    <main id="main" class="main">

        <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h4 class="mb-0">{{ $title }}</h4>
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a class="text-secondary" href="#">Overtime Requests</a></li>
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
                            <div class="text-end">

                                <a href="{{ route('overtimeRequest.add') }}" type="button" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i> New Overtime Request
                                </a>
                            </div>


                        </div>
                        <!-- Table with stripped rows -->
                        <table id="overtimeTable" class="table table-striped" style="width:100%">
                            <thead>

                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Agenda</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time In</th>
                                    <th scope="col">Time Out</th>
                                    <th scope="col">Hours</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $data)
                                    <?php
                                    $hoursRendered = getHours($data->TimeIn, $data->TimeOut);
                                    $status = $data->Status;
                                    switch ($status) {
                                        case 1:
                                            $statusDisplay = '<span class="badge rounded-pill bg-success">Approved</span>';
                                            break;
                                        case 2:
                                            $statusDisplay = '<span class="badge rounded-pill bg-danger">Rejected</span>';
                                            break;
                                        default:
                                            $statusDisplay = '<span class="badge rounded-pill bg-secondary">Pending</span>';
                                            break;
                                    }
                                    
                                    ?>

                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>

                                        <td>
                                            <a href="{{ route('overtimeDetails', ['Id' => $data->Id]) }}">
                                                {{ $data->Agenda }}</a>
                                        </td>

                                        <td>{{ date('F d, Y', strtotime($data->Date)) }}</td>
                                        <td>{{ date('g:i: a', strtotime($data->TimeIn)) }}</td>
                                        <td>{{ date('g:i: a', strtotime($data->TimeOut)) }}</td>
                                        <td>{{ $hoursRendered }}
                                        </td>
                                        <td><?= $statusDisplay ?>
                                        </td>
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
            let dataTable = $('#overtimeTable').DataTable({
                scrollX: true,
                scrollY: '500px',
                sorting: [],
                scrollCollapse: true,

            });
            // ----- END DATATABLES -----




        })
    </script>
@endsection
