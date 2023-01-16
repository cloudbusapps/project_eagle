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
                    {{-- SUMMARY OF PROJECT ASSIGNED PER RESOURCE --}}
                    {{-- MAKE IT BY TITLE, FC,TC,BA... --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF PROJECT ASSIGNED PER RESOURCE</h5>
                            </div>
                            <div class="card-body scrollableX">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Resource</th>
                                            @foreach ($projects as $project)
                                                <th class="text-center">
                                                    <div>{{ $project->Name}}</div>
                                                    <small>(Complex)</small>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projectResources as $index=> $projectResource)
                                        <tr>
                                            
                                                <td>{{ $index+1}}</td>
                                                <td>{{ $projectResource['FullName']}}</td>
                                                @foreach ($projects as $project)
                                                        @if (in_array($project->Id,$projectResource['ProjectsId']))
                                                            <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                                        @else
                                                            <td class="text-center"></td>
                                                        @endif
                                                @endforeach
                                               
                                                
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- MAKE IT A PER PROJECT BASIS, DISPLAY USED AND BUDGETED HOURS PER PROJECT, MAY ANOTHER TABLE FOR COMPLEXITY COUNTS --}}
                    {{-- SUMMARY OF MAN HOURS PER RESOURCE --}}
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF MAN HOURS PER RESOURCE</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Resource</th>
                                            <th>Complex</th>
                                            <th>Intermediate</th>
                                            <th>Easy</th>
                                            <th>Total Projects</th>
                                            <th>Budgeted Hours</th>
                                            <th>Used Hours</th>
                                            <th>Remaining Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Arjay Diangzon</td>
                                            <td class="text-center">5</td>
                                            <td class="text-center">3</td>
                                            <td class="text-center">2</td>
                                            <td class="text-center">10</td>
                                            <td class="text-center">10</td>
                                            <td class="text-center">4</td>
                                            <td class="text-center">6</td>
                                        </tr>
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
                                    <button value="DAILY" name="btnUtilization" type="submit" class="btn btn-success text-white btnFilterForm">Daily</button>
                                    <button value="WEEKLY" name="btnUtilization" type="submit" class="btn btn-secondary text-white btnFilterForm">Weekly</button>
                                    <button value="MONTHLY" name="btnUtilization" type="submit" class="btn btn-secondary btnFilterForm">Monthly</button>
                                    <button value="YEARLY" name="btnUtilization" type="submit" class="btn btn-secondary btnFilterForm">Yearly</button>
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

            // ------ FILTER BUTTON FOR UTILIZATION ------
            $(document).on('click', 'button[name="btnUtilization"]', function() {
                $('button[name="btnUtilization"]').removeClass('btn-success');
                $('button[name="btnUtilization"]').addClass('btn-secondary');
                $(this).toggleClass('btn-success'); 
                let filterType = $(this).val()
                let title = $('#summaryUtilization .card .card-header h5').text(filterType+' SUMMARY OF UTILIZATION')
            })
            // ------ END FILTER BUTTON FOR UTILIZATION -----

            // FILTER TABLE
            $(document).on('click', '.btnFilterForm', function(e) {
                e.preventDefault();
                let type = $(this).val();
                let tableContainer = $(this).closest('.card-header').next()
                tableContainer.html(PRELOADER)

                    setTimeout(() => {
                        var method = 'GET';
                        $.ajax({
                            type: method,
                            url: `utilizationDashboard/filter/${type}`,
                            async: false,
                            success: function(html) {
                                tableContainer.html(html)
                            }
                        })
                    }, 100);
                });
            // END FILTER TABLE
        })
       
    </script>
@endsection
