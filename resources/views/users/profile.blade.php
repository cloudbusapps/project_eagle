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

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">Profile</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="{{ route('employeeDirectory') }}">Directory</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>  

    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0 mb-3">
        <div class="container-fluid">

            @if ($errors->any())
            @foreach ($errors->all() as $err)
            <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                <i class="bi bi-exclamation-octagon me-1"></i>
                <?= $err ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endforeach
            @endif

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

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        @if ($requestId == Auth::id())
                        <a href="#" class="text-secondary btnEditImage" id="{{ Auth::id() }}"
                            style="position: absolute; right: 0; margin: 10px;">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @endif

                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <img src="{{ asset('uploads/profile/' . $userData->Profile ?? 'default.png') }}" alt="Profile"
                                class="rounded-circle" height="150" width="150">
                            <h4 class="mb-0">{{ $userData->FirstName .' '. $userData->LastName }}</h4>
                            <small>{{ $userData->designation }}</small>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <a href="{{ route('user.generate', ['UserId' => $requestId, 'action' => 'print']) }}" 
                                    target="_blank"
                                    class="btn btn-primary">
                                    <span class="bi bi-printer"></span> Print
                                </a>
                                <a href="{{ route('user.generate', ['UserId' => $requestId, 'action' => 'pdf']) }}" 
                                    target="_blank"
                                    class="btn btn-secondary">
                                    <i class="bi bi-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title py-0">Skills</h5>
                            @if ($requestId == Auth::id())
                            <a href="#" class="text-secondary btnEditSkill" id="{{ Auth::id() }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
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
                                    <button class="nav-link w-100 {{ in_array(Session::get('tab'), ['Overview', null]) ? 'active' : '' }}" id="personal-information-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-personal-information" type="button" role="tab" aria-controls="personal-information" aria-selected="true">Overview</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 {{ Session::get('tab') == 'Education' ? 'active' : '' }}" id="education-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-education" type="button" role="tab" aria-controls="education" aria-selected="false">Education</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 {{ Session::get('tab') == 'Certification' ? 'active' : '' }}" id="certification-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-certification" type="button" role="tab" aria-controls="certification" aria-selected="false">Certification</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 {{ Session::get('tab') == 'Award' ? 'active' : '' }}" id="award-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-award" type="button" role="tab" aria-controls="award" aria-selected="false">Award</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 {{ Session::get('tab') == 'Experience' ? 'active' : '' }}" id="experience-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-experience" type="button" role="tab" aria-controls="experience" aria-selected="false">Experience</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-4 px-4" id="borderedTabJustifiedContent">
                                <div class="tab-pane fade {{ in_array(Session::get('tab'), ['Overview', null]) ? 'show active' : '' }}" id="bordered-justified-personal-information" role="tabpanel" aria-labelledby="personal-information-tab">
                                    <div class="profile-overview">
                                        <h5 class="card-title">About</h5>
                                        <p>{{ $userData->About }}</p>

                                        <h5 class="card-title">Personal Info</h5>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Employee #:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->EmployeeNumber ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Last Name:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->LastName ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">First Name:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->FirstName ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Middle Name:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->MiddleName ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Gender:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->Gender ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Email:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->email ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Contact Number:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->ContactNumber ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Department:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->department ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Designation:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->designation ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-4 col-sm-5 label ">Address:</div>
                                            <div class="col-lg-8 col-sm-7">
                                                {{ $userData->Address ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    @if ($requestId == Auth::id())
                                    <div class="w-100 mt-4">
                                        <a href="{{ route('user.editPersonalInformation', ['Id' => Auth::id()]) }}"
                                            class="btn btn-outline-secondary px-2 py-1 btnEditProfile">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ Session::get('tab') == 'Education' ? 'show active' : '' }}" id="bordered-justified-education" role="tabpanel" aria-labelledby="education-tab">
                                    @if ($requestId == Auth::id())
                                    <div class="w-100 text-end mb-3">
                                        <a href="{{ route('user.addEducation', ['Id' => $userData->Id]) }}" class="btn btn-outline-primary px-2 py-1">
                                            <i class="bi bi-plus-lg"></i> New
                                        </a>
                                    </div>
                                    @endif

                                    <table class="table table-striped table-hover" style="white-space: normal" id="tableEducation">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Degree Title</th>
                                                <th>School Name</th>
                                                <th>Year Attended</th>
                                                <th>Achievements</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($educations as $index => $dt)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                @if ($requestId == Auth::id())
                                                <a href="{{ route('user.editEducation', ['Id' => $dt['Id']]) }}">
                                                    {{ $dt['DegreeTitle'] }}
                                                </a>
                                                @else
                                                {{ $dt['DegreeTitle'] }}
                                                @endif
                                            </td>
                                            <td>{{ $dt['School'] }}</td>
                                            <td>{{ date('M Y', strtotime($dt['StartDate'])).' - '.date('M Y', strtotime($dt['EndDate'])) }}</td>
                                            <td>{{ $dt['Achievement'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade {{ Session::get('tab') == 'Certification' ? 'show active' : '' }}" id="bordered-justified-certification" role="tabpanel" aria-labelledby="certification-tab">
                                    @if (Session::get('tab') == 'Certification')
                                        
                                    @endif

                                    @if ($requestId == Auth::id())
                                    <div class="w-100 text-end mb-3">
                                        <a href="{{ route('user.addCertification', ['Id' => $userData->Id]) }}" class="btn btn-outline-primary px-2 py-1">
                                            <i class="bi bi-plus-lg"></i> New
                                        </a>
                                    </div>
                                    @endif

                                    <table class="table table-striped table-hover" style="white-space: normal" id="tableCertification">
                                        <thead>
                                            <tr>
                                                <th>#</th>
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
                                            <td>
                                                @if ($requestId == Auth::id())
                                                <a href="{{ route('user.editCertification', ['Id' => $cert['Id']]) }}">
                                                    {{ $cert['Code'] }}
                                                </a>
                                                @else
                                                {{ $cert['Code'] }}
                                                @endif
                                            </td>
                                            <td>{{ $cert['Description'] }}</td>
                                            <td>{{ $cert['DateTaken'] ? date('F d, Y', strtotime($cert['DateTaken'])) : '-' }}</td>
                                            <td><?= $statusDisplay ?></td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade {{ Session::get('tab') == 'Award' ? 'show active' : '' }}" id="bordered-justified-award" role="tabpanel" aria-labelledby="award-tab">
                                    @if (Session::get('tab') == 'Award')
                                        
                                    @endif

                                    @if ($requestId == Auth::id())
                                    <div class="w-100 text-end mb-3">
                                        <a href="{{ route('user.addAward', ['Id' => $userData->Id]) }}" class="btn btn-outline-primary px-2 py-1">
                                            <i class="bi bi-plus-lg"></i> New
                                        </a>
                                    </div>
                                    @endif

                                    <table class="table table-striped table-hover" style="white-space: normal" id="tableAward">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($awards as $index => $award)

                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                @if ($requestId == Auth::id())
                                                <a href="{{ route('user.editAward', ['Id' => $award['Id']]) }}">
                                                    {{ $award['Title'] }}
                                                </a>
                                                @else
                                                {{ $award['Title'] }}
                                                @endif
                                            </td>
                                            <td>{{ $award['Description'] }}</td>
                                            <td>{{ $award['Date'] ? date('F d, Y', strtotime($award['Date'])) : '-' }}</td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade {{ Session::get('tab') == 'Experience' ? 'show active' : '' }}" id="bordered-justified-experience" role="tabpanel" aria-labelledby="experience-tab">
                                    @if (Session::get('tab') == 'Experience')
                                        
                                    @endif

                                    @if ($requestId == Auth::id())
                                    <div class="w-100 text-end mb-3">
                                        <a href="{{ route('user.addExperience', ['Id' => $userData->Id]) }}" class="btn btn-outline-primary px-2 py-1">
                                            <i class="bi bi-plus-lg"></i> New
                                        </a>
                                    </div>
                                    @endif

                                    <table class="table table-striped table-hover" style="white-space: normal" id="tableExperience">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Job Title</th>
                                                <th>Company</th>
                                                <th>Description</th>
                                                <th>Period Employed</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($experiences as $index => $experience)

                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                @if ($requestId == Auth::id())
                                                <a href="{{ route('user.editExperience', ['Id' => $experience['Id']]) }}">
                                                    {{ $experience['JobTitle'] }}
                                                </a>
                                                @else
                                                {{ $experience['JobTitle'] }}
                                                @endif
                                            </td>
                                            <td>{{ $experience['Company'] }}</td>
                                            <td>{{ $experience['Description'] }}</td>
                                            <td>{{ $experience['StartDate'] ? (date('M Y', strtotime($experience['StartDate'])). ' - ' .date('M Y', strtotime($experience['EndDate']))) : '-' }}</td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title py-0">Projects</h5>
                        </div>
                        <div class="card-body py-3">
                            @if (count($projects))
                            <table class="table table-striped table-hover" id="tableProject">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $index => $project)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $project->Name }}</td>
                                        <td>{{ $project->KickoffDate ? date('Y', strtotime($project->KickoffDate)) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

</main>

{{-- <iframe src="{{ route('user.generatePdf', [
    'userData' => $userData,
    'certifications' => $certifications,
    'skills' => $skills,
    'awards' => $awards,
    'experiences' => $experiences,
    'educations' => $educations,
]) }}" frameborder="1" height="100px"></iframe> --}}



<script src="{{ asset('assets/js/webcam.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    $(document).ready(function() {

        $(document).on('click', '#btnPrint', function() {
           
        })

        // ----- SETUP CAMERA -----
        Webcam.set({
            // live preview size
            width: 320,
            height: 240,
            
            // device capture size
            dest_width: 560,
            dest_height: 400,
            
            // final cropped size
            crop_width: 480,
            crop_height: 400,
            
            // format and quality
            image_format: 'jpeg',
            jpeg_quality: 90,
            
            // flip horizontal (mirror mode)
            flip_horiz: true
        });
        
        let shutter = new Audio();
		shutter.autoplay = false;
		shutter.src = '/assets/audio/' + (navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3');
        // shutter.play();
        // ----- END SETUP CAMERA -----


        // ----- DATATABLES -----
        let tableEducation = $('#tableEducation')
            .css({ "min-width": "99%" })
            .removeAttr("width")
            .DataTable({
                autoWidth: false,
                scrollX: true,
                scrollY: '300px',
                sorting: [],
                scrollCollapse: true,
                columnDefs: [
                    { targets: 0,  width: 10  },
                    { targets: 1,  width: 150 },
                    { targets: 2,  width: 150 },
                    { targets: 3,  width: 150 },
                    { targets: 4,  width: 200 },
                ],
            });

        let tableCertification = $('#tableCertification')
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
                    { targets: 2,  width: 180 },
                    { targets: 3,  width: 130 },
                    { targets: 4,  width: 80  },
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
                    { targets: 2,  width: 130 },
                ],
            });

        let tableAward = $('#tableAward')
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
                    { targets: 2,  width: 130 },
                    { targets: 3,  width: 100 },
                ],
            });

        let tableExperience = $('#tableExperience')
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
                    { targets: 2,  width: 120 },
                    { targets: 3,  width: 350 },
                    { targets: 4,  width: 220 },
                ],
            });
        // ----- END DATATABLES -----


        // ----- ADJUST DATATABLES -----
        $(document).on('click', '#education-tab', function() {
            tableEducation.columns.adjust().draw();
        })

        $(document).on('click', '#certification-tab', function() {
            tableCertification.columns.adjust().draw();
        })

        $(document).on('click', '#award-tab', function() {
            tableAward.columns.adjust().draw();
        })

        $(document).on('click', '#experience-tab', function() {
            tableExperience.columns.adjust().draw();
        })
        // ----- END ADJUST DATATABLES -----


        // ----- BUTTON EDIT SKILL -----
        $(document).on('click', '.btnEditSkill', function() {
            let Id = $(this).attr('id');
            $('#custom-modal .modal-dialog').removeClass('modal-lg').addClass('modal-md');
            $('#custom-modal .modal-title').text('Edit Skills');
            $.ajax({
                method: 'GET',
                url: `/user/profile/getFormSkill/${Id}/`,
                async: false,
                dataType: 'html',
                encode: true,
                success: function(result) {
                    $('#custom-modal .modal-body').html(result);
                    $('#custom-modal').modal('show');
                }
            })
        })
        // ----- END BUTTON EDIT SKILL -----


        // ----- BUTTON SAVE SKILL -----
        function saveSkill() {
            let title = $(`[name="Title"]`).val()?.trim();
            if (title.length) {
                let skills = [];
                $('#custom-modal .skill-title').each(function() {
                    let skill = $(this).text()?.trim().toLowerCase();
                    skills.push(skill);
                })

                if (skills.indexOf(title.toLowerCase()) != -1) {
                    $(`[name="Title"]`).addClass('is-invalid');
                    $(`[name="Title"]`).closest('.form-group').find('.invalid-feedback').text(`${title} already exists`);
                } else {
                    $(`[name="Title"]`).removeClass('is-invalid');
                    $(`[name="Title"]`).closest('.form-group').find('.invalid-feedback').text(``);

                    let trHTML = `
                    <tr>
                        <td class="d-flex justify-content-between align-items-center">
                            <div class="skill-title">${title}</div>
                            <div>
                                <a href="#" class="text-secondary btnEditSpecificSkill mr-1"><i class="bi bi-pencil"></i></a>
                                <span class="ml-1"></span>
                                <a href="#" class="text-secondary btnDeleteSpecificSkill ml-1"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>`;
                    $('#tableSkill tbody').append(trHTML);
                    $(`[name="Title"]`).val('').focus();
                }

            }
        }

        $(document).on('click', '.btnSaveSkill', function() {
            saveSkill();
        })

        $(document).on('keyup', `[name="Title"]`, function(e) {
            if (e.which == 13) saveSkill();
        })
        // ----- END BUTTON SAVE SKILL -----


        // ----- BUTTON EDIT SKILL -----
        $(document).on('click', '.btnEditSpecificSkill', function() {
            let $parent = $(this).closest('tr');
            let title = $parent.find('.skill-title').text()?.trim();
            $('[name="Title"]').val(title).focus();
            $parent.fadeOut(500, function() {
                $parent.remove();
            })
        })
        // ----- END BUTTON EDIT SKILL -----


        // ----- BUTTON DELETE SKILL -----
        $(document).on('click', '.btnDeleteSpecificSkill', function() {
            let $parent = $(this).closest('tr');
            $parent.fadeOut(500, function() {
                $parent.remove();
            })
        })
        // ----- END BUTTON DELETE SKILL -----


        // ----- BUTTON UPDATE SKILL -----
        $(document).on('click', '.btnUpdateSkill', function() {
            let Id = $(this).attr('id');
            let skills = [];

            $('#custom-modal .skill-title').each(function() {
                let skill = $(this).text()?.trim();
                skills.push(skill);
            })

            $.ajax({
                method: 'POST',
                url: `/user/profile/saveSkill/${Id}`,
                async: false,
                data: { skills },
                dataType: 'json',
                success: function(result) {
                    window.location.reload();
                }
            })
        })
        // ----- END BUTTON UPDATE SKILL -----


        // ----- BUTTON EDIT IMAGE -----
        $(document).on('click', '.btnEditImage', function() {
            let Id = $(this).attr('id');
            $('#custom-modal .modal-dialog').removeClass('modal-lg').addClass('modal-md');
            $('#custom-modal .modal-title').text('Edit Profile Image');
            $.ajax({
                method: 'GET',
                url: `/user/profile/edit/image/${Id}`,
                async: false,
                dataType: 'html',
                encode: true,
                success: function(result) {
                    $('#custom-modal .modal-body').html(result);
                    $('#custom-modal').modal('show');
                }
            })
        })
        // ----- END BUTTON EDIT IMAGE -----


        // ----- SELECT PROFILE -----
        $(document).on('change', `[name="Profile"]`, function() {
            let [file] = this.files;
            if (file) {
                $(`img.preview-image`).attr('src', URL.createObjectURL(file));
            }
        })
        // ----- END SELECT PROFILE -----

        
        // ----- BUTTON CAMERA -----
        $(document).on('click', '.btnCamera', function() {
            $('.display-image').hide();
            Webcam.on('error', function() {
                alert('Please give permission to access your webcam');
                $('#custom-modal').modal('hide');
            });
            Webcam.attach('#myCamera');
            $('.btnCancelCapture').show();
            $('.btnCapture').show();
            $('#myCamera').show();
        })
        // ----- END BUTTON CAMERA -----


        // ----- BUTTON CAPTURE -----
        $(document).on('click', '.btnCapture', function() {
            // play sound effect
			try { shutter.currentTime = 0; } catch(e) {;} // fails in IE
			shutter.play();
			
			// freeze camera so user can preview current frame
			Webcam.freeze();
			
			// swap button sets
			$('.btnRetake').show();
			$('.btnSaveCapture').show();
            $(this).hide();
            $('.btnCancelCapture').hide();
        })
        // ----- END BUTTON CAPTURE -----


        // ----- BUTTON RETAKE -----
        $(document).on('click', '.btnRetake', function() {
			Webcam.unfreeze();
			
			// swap button sets
			$('.btnCapture').show();
            $('.btnCancelCapture').show();
            $(this).hide();
            $('.btnSaveCapture').hide();
        })
        // ----- END BUTTON RETAKE -----


        // ----- BUTTON SAVE CAPTURE -----
        $(document).on('click', '.btnSaveCapture', function() {
            Webcam.snap( function(data_uri) {
                $('.preview-image').attr('src', data_uri);
                $('.display-image').show();
                Webcam.reset();
                $('.btnCancelCapture, .btnCapture, .btnRetake, .btnSaveCapture').hide();
                $('#myCamera').hide();
                var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
                // console.log(raw_image_data);
                $(`[name="ProfileStore"]`).val(raw_image_data);
            } );
        })
        // ----- END BUTTON SAVE CAPTURE -----


        // ----- BUTTON CANCEL CAPTURE -----
        $(document).on('click', '.btnCancelCapture', function() {
            Webcam.reset();
            $('.display-image').show();
            $('.btnCancelCapture, .btnCapture, .btnRetake, .btnSaveCapture').hide();
            $('#myCamera').hide();
        })
        // ----- END BUTTON CANCEL CAPTURE -----

    })

</script>

@endsection