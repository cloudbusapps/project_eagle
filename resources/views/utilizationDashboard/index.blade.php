@extends('layouts.app')

@section('content')
<?php
// $projectResources  = !empty($data) ? $data['projectResources'] ?? '' : '';
?>
<style>
    .scrollableX{
        overflow-x: auto;
        max-width: 100%;
    }
    .group{
        background-color: #ddd !important;
    }
    .group td{
        font-weight: bold !important;
    }
</style>

    <main id="main" class="main">
        <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h4 class="mb-0">Utilization Dashboard</h4>
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a class="text-secondary" href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Utilization Dashboard</li>
                        </ol>
                    </div>
                    <div class="col text-end">
                        <h6 class="mb-0 fw-bold" id="realTime">{{ date('h:i:s A') }}</h6>
                        <small>{{ date('F d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
            <div class="container-fluid">
                <div class="row">

                    {{-- FORECASTED VS ACTUAL --}}
                    @if (isAdminOrHead())
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 font-weight-bold text-uppercase">FORECASTED VS ACTUAL HOURS THIS YEAR</h5>
                                        <input type="text"
                                        name="forecastVSActualHours"
                                        style="width:200px"
                                        autocomplete="off">
                                </div>
                                
                                <div class="card-body scrollableX">
                                    <table id="forecastedVSActualTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                {{-- <th>#</th> --}}
                                                <th>Resource</th>
                                                <th>Designation</th>
                                                <th class="text-center">Forecasted work hours</th>
                                                <th class="text-center">Resource Utilization</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($WorkinghoursData as $index=> $WorkinghourData)
                                            <tr>
                                                <td>{{ $index+1 }}</td>
                                                <td>{{ $WorkinghourData['FullName']}}</td>
                                                <td>{{ $WorkinghourData['DesignationName']}}</td>
                                                <td class="text-center">{{ $WorkinghourData['forecastedAnnualHours'] }}</td>
                                                <td class="text-center">{{ $WorkinghourData['TotalSumHours']?  $WorkinghourData['TotalSumHours']:0}}</td>
                                            </tr>
                                            @endforeach --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    


                    {{-- SUMMARY OF PROJECT ASSIGNED PER RESOURCE --}}
                    {{-- MAKE IT BY TITLE, FC,TC,BA... --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF PROJECT ASSIGNED PER RESOURCE</h5>
                            </div>
                            <div class="card-body scrollableX">
                                <table id="projectPerResourceTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Resource</th>
                                            <th>Designation</th>
                                            @foreach ($projects as $project)
                                                <th class="text-center">
                                                    <div>{{ $project->Name}}</div>
                                                    <small>(Complex)</small>
                                                </th>
                                            @endforeach
                                            <th>Complex</th>
                                            <th>Intermediate</th>
                                            <th>Easy</th>
                                            <th>Total Projects</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projectResources as $index=> $projectResource)
                                        <tr>
                                            
                                            <td>{{ $index+1}}</td>
                                            <td>{{ $projectResource['FullName']}}</td>
                                            <td>{{ $projectResource['DesignationName']}}</td>
                                            @foreach ($projects as $project)
                                                    @if (in_array($project->Id,$projectResource['ProjectsId']))
                                                        <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                                    @else
                                                        <td class="text-center"></td>
                                                    @endif
                                            @endforeach
                                            <td class="text-center">5</td>
                                            <td class="text-center">3</td>
                                            <td class="text-center">2</td>
                                            <td class="text-center">10</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- MAKE IT A PER PROJECT BASIS, DISPLAY USED AND BUDGETED HOURS PER PROJECT, MAY NEED ANOTHER TABLE FOR COMPLEXITY COUNTS --}}
                    {{-- SUMMARY OF MAN HOURS PER RESOURCE --}}
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF MAN HOURS PER RESOURCE</h5>
                            </div>
                            <div class="card-body">
                                <table id="manhoursPerResourceTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Resource</th>
                                            <th>Budgeted Hours</th>
                                            <th>Used Hours</th>
                                            <th>Remaining Hours</th>
                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($timekeepingDatas as $index=> $timekeepingData)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $timekeepingData->FirstName.' '.$timekeepingData->LastName }}</td>
                                            <td class="text-center">{{ $timekeepingData->TotalBudgetedHours }}</td>
                                            <td class="text-center">{{ $timekeepingData->TotalSumHours }}</td>
                                            <td class="text-center">{{ $timekeepingData->TotalBudgetedHours - $timekeepingData->TotalSumHours }}</td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- MONTHLY SUMMARY OF UTILIZATION --}}
                    <div id="summaryUtilization" class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">DAILY SUMMARY OF UTILIZATION</h5>
                                <div class="text-end">
                                   </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2" style="vertical-align : middle;text-align:center;">Resource</th>
                                            <th colspan="2" class="text-center">MONDAY</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Used Hours</th>
                                            <th class="text-center">Utilization</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Arjay Diangzon</td>
                                            <td class="text-center">160</td>
                                            <td class="text-center">100%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                     {{-- SUMMARY OF UTILIZATION PER PROJECT --}}
                     <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF UTILIZATION PER PROJECT</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">Resource</th>
                                            <th colspan="2" class="text-center">Project Eagle</th>
                                            <th colspan="2" class="text-center">Carmen's Best</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Assessed</th>
                                            <th class="text-center">Used Hours</th>
                                            <th class="text-center">Assessed</th>
                                            <th class="text-center">Used Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Arjay Diangzon</td>
                                            <td class="text-center">160</td>
                                            <td class="text-center">2.4</td>
                                            <td class="text-center">160</td>
                                            <td class="text-center">5</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- SUMMARY OF UTILIZATION FOR ADMIN TASK --}}
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF UTILIZATION FOR ADMIN TASK</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">Resource</th>
                                            <th colspan="2" class="text-center">January 2022</th>
                                            <th colspan="2" class="text-center">February 2022</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Used Hours</th>
                                            <th class="text-center">Utilization</th>
                                            <th class="text-center">Used Hours</th>
                                            <th class="text-center">Utilization</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Arjay Diangzon</td>
                                            <td class="text-center">160</td>
                                            <td class="text-center">100%</td>
                                            <td class="text-center">160</td>
                                            <td class="text-center">100%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- SUMMARY OF UTILIZATION FOR TRAILHEAD AND TRAINING --}}
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF UTILIZATION FOR TRAILHEAD AND TRAINING</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">Resource</th>
                                            <th colspan="2" class="text-center">January 2022</th>
                                            <th colspan="2" class="text-center">February 2022</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Used Hours</th>
                                            <th class="text-center">Utilization</th>
                                            <th class="text-center">Used Hours</th>
                                            <th class="text-center">Utilization</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Arjay Diangzon</td>
                                            <td class="text-center">160</td>
                                            <td class="text-center">100%</td>
                                            <td class="text-center">160</td>
                                            <td class="text-center">100%</td>
                                        </tr>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // ------ START PROJECT TABLE ------
            var groupColumn = 2;
            let projectPerResourceTable = $('#projectPerResourceTable').DataTable({
                columnDefs: [{ visible: false, targets: groupColumn }],
                scrollX: true,
                sorting: [],
                scrollCollapse: true,
                drawCallback: function (settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var last = null;
        
                    api
                        .column(groupColumn, { page: 'current' })
                        .data()
                        .each(function (group, i) {
                            if (last !== group) {
                                $(rows)
                                    .eq(i)
                                    .before('<tr class="group"><td colspan="100%">' + group + '</td></tr>');
        
                                last = group;
                            }
                        });
                },
            });
            // ------ END PROJECT TABLE ------

            // ------ START RESOURCE TABLE ------
            let manhoursPerResourceTable = $('#manhoursPerResourceTable').DataTable({
                scrollX: true,
                sorting: [],
                scrollCollapse: true,
            });
            // ------ END RESOURCE TABLE ------

            // ------ START ACTUAL VS FORECAST TABLE ------

             // DEFAULT VALUE IS CURRENT YEAR
            var startOfYear = moment().startOf('year').format('MM/DD/YYYY');
            var endOfYear = moment().endOf('year').format('MM/DD/YYYY');


            $('#forecastedVSActualTable').DataTable({
                columnDefs: [{ visible: false, targets: 1 }],
                scrollX: true,
                sorting: [],
                scrollCollapse: true,
                drawCallback: function (settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var last = null;
        
                    api
                        .column(1, { page: 'current' })
                        .data()
                        .each(function (group, i) {
                            if (last !== group) {
                                $(rows)
                                    .eq(i)
                                    .before('<tr class="group"><td colspan="100%">' + group + '</td></tr>');
        
                                last = group;
                            }
                        });
                },

                processing: true,

                // SET TO TRUE IF DATA PROCESSED IS GREATER THAN 50,000 ROWS
                serverSide: false,
                
                ajax: {
                    url:"{{ route('utilizationDashboard.filterDataByDate') }}",
                    type:'GET',
                    data:{
                        startDate:moment().startOf('year').format('YYYY-MM-DD'),
                        endDate:moment().endOf('year').format('YYYY-MM-DD')
                    },
                    dataSrc:'',
                    // dataSrc:function(res){
                    //     console.log(res)
                    // },
                    error: function (xhr, error, thrown) {
                        console.log(error);
                    }
                },
                columns:[
                    {data:'FullName'},
                    {data:'DesignationName'},
                    {data:'forecastedAnnualHours',className: "text-center"},
                    {data:'TotalSumHours',className: "text-center"},
                ]
            });
            // ------ END ACTUAL VS FORECAST TABLE ------

        $(`input[name="forecastVSActualHours"]`).daterangepicker({
            startDate: startOfYear,
            endDate: endOfYear,
            ranges: {
            'Today': [moment(), moment()],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [startOfYear,endOfYear]
            }
        },function(start, end, label){
            // CHANGE TITLE BASED ON LABEL SELECTED
            let newTitle = `FORECASTED VS ACTUAL HOURS ${label}`;
            $('input[name="forecastVSActualHours"]').closest('.card-header').find('h5').text(newTitle);

            const date={
                startDate:start.format('YYYY-MM-DD'),
                endDate:end.format('YYYY-MM-DD')
            }

            $('#forecastedVSActualTable').DataTable().destroy();

            $('#forecastedVSActualTable').DataTable({
                columnDefs: [{ visible: false, targets: 1 }],
                scrollX: true,
                sorting: [],
                scrollCollapse: true,
                drawCallback: function (settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var last = null;
        
                    api
                        .column(1, { page: 'current' })
                        .data()
                        .each(function (group, i) {
                            if (last !== group) {
                                $(rows)
                                    .eq(i)
                                    .before('<tr class="group"><td colspan="100%">' + group + '</td></tr>');
        
                                last = group;
                            }
                        });
                },

                processing: true,
                // SET TO TRUE IF DATA PROCESSED IS GREATER THAN 50,000 ROWS
                serverSide: false,
                
                ajax: {
                    url:"{{ route('utilizationDashboard.filterDataByDate') }}",
                    type:'GET',
                    data:date,
                    dataSrc:'',
                    error: function (xhr, error, thrown) {
                        console.log(error);
                    }
                },
                columns:[
                    {data:'FullName'},
                    {data:'DesignationName'},
                    {data:'forecastedAnnualHours',className: "text-center"},
                    {data:'TotalSumHours',className: "text-center"},
                ]
            }).draw();
        });

        })
        // END OF DOCUMENT
    </script>
@endsection
