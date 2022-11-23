<?php
    $FullName = $userData->FirstName .' '. $userData->LastName;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Technical Profile</title>

    <link rel="stylesheet" href="{{ asset('assets/cssbundle/daterangepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/luno-style.css') }}">
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        #mainContent {
            font-size: 12px !important;
        }

        @media print {
            body {
                background: #ffffff;
                margin: 0;
            }

            button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <main id="main" class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex jutify-content-between align-items-center">
                        <img src="{{ asset('uploads/profile/' . $userData->Profile ?? 'default.png') }}" alt="Profile"
                            class="rounded-circle" height="120" width="120">
                        <div class="px-2">
                            <h5 class="fw-bold mb-0">{{ $FullName }}</h3>
                            <h6 class="mb-1">{{ $userData->Title ?? '' }}</h6>
                            <small>{{ $userData->About ?? '' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div id="mainContent" class="row mt-3">
                        <div class="col-3">
                            <section>
                                <h6 class="card-title fw-bold border-bottom pb-2"><i class="bi bi-info-circle-fill"></i> Personal Info</h6>
                                <div class="py-2">
                                    <div class="mb-2">
                                        <label for="">Address</label>
                                        <div>{{ $userData->Address ?? '' }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Contact Number</label>
                                        <div>{{ $userData->ContactNumber ?? '' }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="">Email Address</label>
                                        <div>{{ $userData->email ?? '' }}</div>
                                    </div>
                            </section>

                            @if (count($skills))
                            <section>
                                <h6 class="card-title fw-bold border-bottom pb-2"><i class="bi bi-puzzle-fill"></i> Skills</h6>
                                <div class="py-2">
                                    @foreach ($skills as $dt)
                                    <div>{{ $dt['Title'] }}</div>
                                    @endforeach
                                </div>
                            </section>
                            @endif

                        </div>
                        <div class="col-9">

                            @if (count($experiences))
                            <section>
                                <h6 class="card-title fw-bold border-bottom pb-2"><i class="bi bi-briefcase-fill"></i> Experience</h6>
                                <div class="py-2">
                                    @foreach ($experiences as $dt)
                                    <div class="row mb-2">
                                        <div class="col-3">{{ date('M Y', strtotime($dt['StartDate'])).' - '.date('M Y', strtotime($dt['EndDate'])) }}</div>
                                        <div class="col-9">
                                            <b>{{ $dt['Company'] }}</b><br>
                                            <b>{{ $dt['JobTitle'] }}</b>
                                            <div>{{ $dt['Description'] }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </section>
                            @endif

                            @if (count($educations))
                            <section>
                                <h6 class="card-title fw-bold border-bottom pb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-mortarboard-fill" viewBox="0 0 16 16">
                                        <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z"/>
                                        <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z"/>
                                    </svg> 
                                    Education
                                </h6>
                                <div class="py-2">
                                    @foreach ($educations as $dt)
                                    <div class="row mb-2">
                                        <div class="col-3">{{ date('M Y', strtotime($dt['StartDate'])).' - '.date('M Y', strtotime($dt['EndDate'])) }}</div>
                                        <div class="col-9">
                                            <b>{{ $dt['School'] }}</b><br>
                                            <b>{{ $dt['DegreeTitle'] }}</b>
                                            <div>{{ $dt['Achievement'] }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </section>
                            @endif

                            @if (count($awards))
                            <section>
                                <h6 class="card-title fw-bold border-bottom pb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-award-fill" viewBox="0 0 16 16">
                                        <path d="m8 0 1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864 8 0z"/>
                                        <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
                                    </svg>
                                    Awards
                                </h6>
                                <div class="py-2">
                                    @foreach ($awards as $dt)
                                    <div class="row mb-2">
                                        <div class="col-3">{{ date('M Y', strtotime($dt['Date'])) }}</div>
                                        <div class="col-9">
                                            <b>{{ $dt['Title'] }}</b>
                                            <div>{{ $dt['Description'] }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </section>
                            @endif

                            @if (count($certifications))
                            <section>
                                <h6 class="card-title fw-bold border-bottom pb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-book-fill" viewBox="0 0 16 16">
                                        <path d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                    </svg>
                                    Certification
                                </h6>
                                <div class="py-2">
                                    @foreach ($certifications as $dt)
                                    @if ($dt['DateTaken'] && $dt['Status'] == 'Acquired')
                                    <div class="row mb-2">
                                        <div class="col-3">{{ date('M Y', strtotime($dt['DateTaken'])) }}</div>
                                        <div class="col-9">
                                            <b>{{ $dt['Code'] }}</b>
                                            <div>{{ $dt['Description'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </section>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/bundle/apexcharts.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/bundle/dataTables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/bundle/apexcharts.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if ($action == "pdf")
    <script>
        $(document).ready(function() {
            $(window).on('load', function() {
                let element = document.querySelector('main');
                let opt = {
                    margin:       0.5,
                    filename:     '<?= $FullName ?>.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2 },
                    jsPDF:        { unit: 'cm', format: 'A4', orientation: 'portrait' }
                };

                html2pdf().set(opt).from(element).save();
            })
        })
    </script>
    @endif

    @if ($action == "print")
    <script>
        $(document).ready(function() {
            $(window).on('load', function() {
                setTimeout(() => {
                    window.print();
                }, 1000);
            })
        })
    </script>
    @endif

</body>
</html>