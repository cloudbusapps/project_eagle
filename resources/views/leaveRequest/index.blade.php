@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}">
<script src="{{ asset('assets/js/fullcalendar.js') }}"></script>

<main id="main" class="main" calendarData="{{ json_encode($calendar) }}">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="{{ route('dashboard') }}">Dashboard</a></li>
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

                <div class="card-body pt-3">
                    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTabJustified" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array(Session::get('tab'), ['My Forms', null]) ? 'active' : '' }}" id="my-forms-tab" data-bs-toggle="tab" data-bs-target="#my-forms" type="button" role="tab">My Forms</button>
                        </li>
                        @if ($forApproval && count($forApproval))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array(Session::get('tab'), ['For Approval']) ? 'active' : '' }}" id="for-approval-tab" data-bs-toggle="tab" data-bs-target="#for-approval" type="button" role="tab">For Approval</button>
                        </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array(Session::get('tab'), ['Calendar']) ? 'active' : '' }}" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar-content" type="button" role="tab">Calendar</button>
                        </li>

                        
                    </ul>
                    <div class="tab-content pt-4 px-4">
                        <div class="tab-pane fade {{ in_array(Session::get('tab'), ['My Forms', null]) ? 'show active' : '' }}" id="my-forms" role="tabpanel">
                            <div class="w-100 text-end mb-3">
                                <a href="{{ route('leaveRequest.add') }}" class="btn btn-outline-primary px-2 py-1">
                                    <i class="bi bi-plus-lg"></i> New
                                </a>
                            </div>
        
                            <table class="table table-striped table-hover" id="tableLeaveRequestMyForms">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document No.</th>
                                        <th>Employee Name</th>
                                        <th>Leave Type</th>
                                        <th>Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
        
                                @if (!empty($data))
                                @foreach ($data as $index => $dt)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('leaveRequest.view', ['Id' => $dt->Id]) }}">
                                                {{ $dt->DocumentNumber }}
                                            </a>
                                        </td>
                                        <td>{{ $dt->FirstName.' '.$dt->LastName }}</td>
                                        <td>{{ $dt->Name }}</td>
                                        <td>
                                            {{ $dt->StartDate == $dt->EndDate ? 
                                            (date('F d, Y', strtotime($dt->StartDate))) :
                                            (date('M d', strtotime($dt->StartDate)).' - '.date('M d, Y', strtotime($dt->EndDate)))
                                            }}
                                        </td>
                                        <td>{{ $dt->Reason }}</td>
                                        <td><?= getStatusDisplay($dt->Status) ?></td>
                                    </tr>
                                @endforeach
                                @endif
        
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade {{ in_array(Session::get('tab'), ['For Approval']) ? 'show active' : '' }}" id="for-approval" role="tabpanel">
                            <table class="table table-striped table-hover" id="tableLeaveRequestForApproval">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document No.</th>
                                        <th>Employee Name</th>
                                        <th>Leave Type</th>
                                        <th>Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
        
                                @if (!empty($forApproval))
                                @foreach ($forApproval as $index => $dt)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('leaveRequest.view', ['Id' => $dt->Id]) }}">
                                                {{ $dt->DocumentNumber }}
                                            </a>
                                        </td>
                                        <td>{{ $dt->FirstName.' '.$dt->LastName }}</td>
                                        <td>{{ $dt->LeaveType }}</td>
                                        <td>
                                            {{ $dt->StartDate == $dt->EndDate ? 
                                            (date('F d, Y', strtotime($dt->StartDate))) :
                                            (date('M d', strtotime($dt->StartDate)).' - '.date('M d, Y', strtotime($dt->EndDate)))
                                            }}
                                        </td>
                                        <td>{{ $dt->Reason }}</td>
                                        <td><?= getStatusDisplay($dt->Status) ?></td>
                                    </tr>
                                @endforeach
                                @endif
        
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade {{ in_array(Session::get('tab'), ['Calendar']) ? 'show active' : '' }}" id="calendar-content" role="tabpanel">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>

                                        @foreach ($leaveTypes as $dt)
                                        <?php 
                                            $className = $dt['Name'] == "Vacation Leave" ? 'text-success' : ($dt['Name'] == "Sick Leave" ? 'text-danger' : 'text-info'); 
                                        ?>
                                        <label class="{{ $className }} mx-2"><input type="checkbox" class="leaveTypes" leaveType="{{ $dt['Name'] }}" checked> {{ $dt['Name'] }}</label>
                                        @endforeach

                                    </div>
                                    <div>
                                        <label><input type="checkbox" checked name="viewAll"> View All</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                  <div id="myCalendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {
        var calendarEl = document.getElementById('myCalendar');

        let eventData = JSON.parse($('#main').attr('calendarData'));
        let currentEventData = [...eventData];

        let calendar = function(data = []) { 
            return new FullCalendar.Calendar(calendarEl, {
                height: 650,
                contentHeight: 600,
                initialView: 'dayGridMonth',
                headerToolbar: {
                    start: 'prev,next today',
                    center: 'title',
                    end: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today:    'Today',
                    month:    'Month',
                    week:     'Week',
                    day:      'Day',
                },
                events: data
            })
        };

        
        // ----- INIT CALENDAR -----
        function initCalendar(data = []) {
            calendar().destroy();
            calendar(data).render();
        }
        
        let calendarInit = false;
        $('#calendar-tab').on('shown.bs.tab', function (e) {
            if ($(this).data('bs-target') == '#calendar-content' && !calendarInit) {
                initCalendar(currentEventData);
                calendarInit = true;
            }
        });
        // ----- END INIT CALENDAR -----
        


        // ----- CHANGE CALENDAR VIEW -----
        $(document).on('change', `.leaveTypes`, function() {
            let leaveTypeArr = [];
            $('[type="checkbox"].leaveTypes').each(function() {
                this.checked && leaveTypeArr.push($(this).attr('leaveType'));
            })

            currentEventData = eventData.filter(dt => leaveTypeArr.includes(dt.leaveType));
            initCalendar(currentEventData);
        })
        // ----- END CHANGE CALENDAR VIEW -----


        // ----- CHANGE VIEW ALL -----
        $(document).on('change', `[name="viewAll"]`, function() {
            $(`[type="checkbox"].leaveTypes`).prop('checked', this.checked).trigger('change');
        })
        // ----- END CHANGE VIEW ALL -----

    })


    $(document).ready(function() {
        
        // ----- DATATABLES -----
        let tableLeaveRequestMyForms = $('#tableLeaveRequestMyForms')
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
                    { targets: 3,  width: 120 },
                    { targets: 4,  width: 120 },
                    { targets: 5,  width: 200 }, 
                    { targets: 6,  width: 80  }, 
                ],
            });

        let tableLeaveRequestForApproval = $('#tableLeaveRequestForApproval')
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
                    { targets: 3,  width: 120 },
                    { targets: 4,  width: 120 },
                    { targets: 5,  width: 200 }, 
                    { targets: 6,  width: 80  }, 
                ],
            });

        $(document).on('click', '#my-forms-tab', function() {
            tableLeaveRequestMyForms.columns.adjust().draw();
        })

        $(document).on('click', '#for-approval-tab', function() {
            tableLeaveRequestForApproval.columns.adjust().draw();
        })
        // ----- END DATATABLES -----

    })

</script>

@endsection