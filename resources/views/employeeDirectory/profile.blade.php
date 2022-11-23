@extends('layouts.app')

@section('content')

<style>

    .skills .badge {
        border: 1px solid green;
        border-radius: 20px;
        margin: 1px;
    }

</style>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employeeDirectory') }}">Employee Directory</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ asset('assets/img/userpics/erick-profile.jpeg') }}" alt="Profile"
                            class="rounded-circle">
                        <h2>{{ $userData['FirstName'] .' '. $userData['LastName'] }}</h2>
                        <h3>{{ $userData['Title'] }}</h3>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <a href="#" class="btn btn-primary float-right"><span class="bi bi-printer"
                                    aria-hidden="true"></span> Print</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title py-0">Skills</h5>
                    </div>
                    <div class="card-body">
                        <div class="skills py-2" style="font-size: 1.1rem">

                        @if (count($skills))
                        @foreach ($skills as $skill)
                            <span class="badge text-success"><i class="bi bi-check me-1"></i>{{ $skill['Title'] }}</span>
                        @endforeach
                        @else
                            <div class="text-center py-2">
                                <img src="{{ asset('assets/img/modal/database-search.png') }}" height="80" width="80"  alt="No data found">
                                <h6 class="mt-2">No data found.</h6>
                            </div>
                        @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100 {{ Session::get('tab') != 'Certification' ? 'active' : '' }}" id="personal-information-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-personal-information" type="button" role="tab" aria-controls="personal-information" aria-selected="true">Personal Information</button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100 {{ Session::get('tab') == 'Certification' ? 'active' : '' }}" id="certification-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-certification" type="button" role="tab" aria-controls="certification" aria-selected="false">Certification</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-4 px-4" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade {{ Session::get('tab') != 'Certification' ? 'show active' : '' }}" id="bordered-justified-personal-information" role="tabpanel" aria-labelledby="personal-information-tab">

                                <div class="profile-overview">
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-5 label ">Employee #:</div>
                                        <div class="col-lg-8 col-md-7">
                                            {{ $userData['EmployeeNumber'] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-5 label ">Last Name:</div>
                                        <div class="col-lg-8 col-md-7">
                                            {{ $userData['LastName'] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-5 label ">First Name:</div>
                                        <div class="col-lg-8 col-md-7">
                                            {{ $userData['FirstName'] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-5 label ">Middle Name:</div>
                                        <div class="col-lg-8 col-md-7">
                                            {{ $userData['MiddleName'] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-5 label ">Title:</div>
                                        <div class="col-lg-8 col-md-7">
                                            {{ $userData['Title'] ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-5 label ">Email:</div>
                                        <div class="col-lg-8 col-md-7">
                                            {{ $userData['email'] ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ Session::get('tab') == 'Certification' ? 'show active' : '' }}" id="bordered-justified-certification" role="tabpanel" aria-labelledby="certification-tab">

                                @if (count($certifications))
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="tableCertification">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Code</th>
                                                    <th>Description</th>
                                                    <th>Date Taken</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            @foreach ($certifications as $index => $cert)

                                            <?php 
                                                switch ($cert['Status']) {
                                                    case 'Acquired':
                                                        $statusDisplay = '<span class="badge rounded-pill bg-success">Acquired</span>';
                                                        break;
                                                    case 'For Review':
                                                        $statusDisplay = '<span class="badge rounded-pill bg-warning">For Review</span>';
                                                        break;
                                                    default:
                                                        $statusDisplay = '<span class="badge rounded-pill bg-secondary">To Take</span>';        
                                                        break;
                                                }
                                                
                                            ?>

                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $cert['Code'] }}</td>
                                                <td>{{ $cert['Description'] }}</td>
                                                <td>{{ $cert['DateTaken'] ? date('F d, Y', strtotime($cert['DateTaken'])) : '-' }}</td>
                                                <td><?= $statusDisplay ?></td>
                                            </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <img src="{{ asset('assets/img/modal/database-search.png') }}" height="80" width="80"  alt="No data found">
                                        <h6 class="mt-2">No data found.</h6>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<script>

    $(document).ready(function() {
        
        // ----- DATATABLES -----
        let dataTable = $('#tableCertification').DataTable({
            scrollX: true,
            scrollY: '300px',
            sorting: [],
            scrollCollapse: true,
            columnDefs: [
                { targets: 0,  width: 10  },
                { targets: 1,  width: 100 },
                { targets: 2,  width: "100%" },
                { targets: 3,  width: 100 },
                { targets: 4,  width: 80  },
            ],
        });
        // ----- END DATATABLES -----


        // ----- BUTTON TAB CERTIFICATION -----
        $(document).on('click', '#certification-tab', function() {
            dataTable.columns.adjust().draw();
        })
        // ----- END BUTTON TAB CERTIFICATION -----

    })

</script>

@endsection