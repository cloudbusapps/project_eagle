@extends('layouts.app')

@section('content')

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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF PROJECT ASSIGNED PER RESOURCE</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Resource</th>
                                            <th class="text-center">
                                                <div>Project Eagle</div>
                                                <small>(Complex)</small>
                                            </th>
                                            <th class="text-center">
                                                <div>Carmen's Best</div>
                                                <small>(Intermediate)</small>
                                            </th>
                                            <th class="text-center">
                                                <div>Project Eagle</div>
                                                <small>(Complex)</small>
                                            </th>
                                            <th class="text-center">
                                                <div>Carmen's Best</div>
                                                <small>(Intermediate)</small>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Arjay Diangzon</td>
                                            <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                            <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">SUMMARY OF MANHOURS PER RESOURCE</h5>
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
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 font-weight-bold">MONTHLY SUMMARY PER RESOURCE</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">Resource</th>
                                            <th colspan="2" class="text-center">January</th>
                                            <th colspan="2" class="text-center">February</th>
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

@endsection
