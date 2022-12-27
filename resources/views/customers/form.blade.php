@extends('layouts.app')

@section('content')
    <?php
    $currentViewStatus = $currentViewStatus ?? 0;
    
    $PreviewStatus = $BusinessNotes = '';
    $CustomerName  = $DSWStatus = $Status = $ProjectName = $Address = $Industry = $Type = $ContactPerson = $Product = $Notes = $Link = $Complex = '';
    $DisableAttr   = '';
    
    if ($type === 'insert') {
        $Status = 0;
        $todo   = 'insert';
        $method = 'POST';
        $action = route('customers.save');
        $cancelRoute = route('customers');
        $Id = '';
    
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } elseif ($type === 'edit') {
        // INITIALIZATION
        $CustomerName  = !empty($data) ? $data['CustomerName'] ?? '' : '';
        $Address       = !empty($data) ? $data['Address'] ?? '' : '';
        $ProjectName   = !empty($data) ? $data['ProjectName'] ?? '' : '';
        $Industry      = !empty($data) ? $data['Industry'] ?? '' : '';
        $Link          = !empty($data) ? $data['Link'] ?? '' : '';
        $Type          = !empty($data) ? $data['Type'] ?? '' : '';
        $ContactPerson = !empty($data) ? $data['ContactPerson'] ?? '' : '';
        $Product       = !empty($data) ? $data['Product'] ?? '' : '';
        $Notes         = !empty($data) ? $data['Notes'] ?? '' : '';
        $Complex       = !empty($data) ? $data['Complex'] ?? '' : '';
        $Status        = !empty($data) ? $data['Status'] ?? '' : '';
        $DSWStatus     = !empty($data) ? $data['DSWStatus'] ?? '' : '';
        $BusinessNotes = !empty($businessProcessData) ? $businessProcessData['Note'] ?? '' : '';
    
        $button = '<button type="submit" class="btn btn-primary btnUpdateForm">Submit</button>';
        $manhourButton = '<a href="#" class="btn btn-warning btnUpdate">Update</a>';
    
        if ($Status == 7 && Request::get('progress') == 'assessment') {
            if(Auth::id()==getDepartmentHeadId(config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION'))){
                $button = '
                <button type="submit" class="btn btn-primary btnUpdateForm">For Release</button>';
                $manhourButton = '
                <a href="#" class="btn btn-warning btnRevise">Revise</a>';
            } else{
                $button = '
                <button type="submit" class="btn btn-primary btnUpdateForm">Submit</button>';
                $manhourButton = '
                <a href="#" class="btn btn-warning btnUpdate">Update Manhours</a>';
            }
           
        }
    
        $todo        = 'update';
        $method      = 'PUT';
        $action      = route('customers.update', ['Id' => $Id, 'Status' => $Status]);
        $cancelRoute = route('customers', ['Id' => $Id]);
    } else {
        return redirect()->back();
    }
    
    // ----- PERMISSION -----
    $DepTechConId       = config('constant.ID.DEPARTMENTS.TECHNOLOGY_CONSULTING');
    $DepBusinessAppsId  = config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION');
    $UserDepartmentId   = Auth::user()->DepartmentId;
    $BusinessAppsHeadId = getDepartmentHeadId($DepBusinessAppsId);
    
    switch ($title) {
        case 'Information': // TECHCON
        case 'Complexity':
        case 'Deployment Strategy Workshop':
        case 'Business Process':
        case 'Requirements and Solutions':
        case 'Project Phase':
        case 'Proposal':
            $DisableAttr = $UserDepartmentId != $DepTechConId ? 'disabled' : '';
            break;
        case 'Capability': // TECHCON & BUSINESS APPS
        case 'Assessment':
        case 'Success':
            $DisableAttr = !in_array($UserDepartmentId, [$DepTechConId, $DepBusinessAppsId]) ? 'disabled' : '';
            break;
        default:
            break;
    }

    $DisableAttr = ''; // TEMP
    $RequiredLabel = $DisableAttr ? '' : "<code>*</code>";
    // ----- END PERMISSION -----
    ?>

    <style>
        
        :root {
            --backgroundColor: lightgrey;
            --borderSize: 40px;
            --active-color: {{ $DSWStatus == 5 ? 'green' : '#eed202' }};
        }
        

        .divSquare {
            position: relative;
            width: 80%;
            height: 80px;
            background: var(--backgroundColor);
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 5px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .divSquare:before {
            content: '';
            position: absolute;
            left: 100%;
            top: 0;
            border-top: var(--borderSize) solid transparent;
            border-bottom: var(--borderSize) solid transparent;
            border-left: var(--borderSize) solid var(--backgroundColor)
        }

        .divSquare:after {
            content: '';
            position: absolute;
            right: 100%;
            top: 0;
            border-top: var(--borderSize) solid var(--backgroundColor);
            border-bottom: var(--borderSize) solid var(--backgroundColor);
            border-left: var(--borderSize) solid transparent
        }

        .activeStatus {
            background: var(--active-color);
            color: var(--active-color);
            --backgroundColor: var(--active-color);
        }

        .dwsCon {
            margin-right: 20px;
            flex: 1;
        }

        .dwsCon strong {
            font-size: 0.8rem;

            color: white;
        }


        /*The background card*/
        .card {
            z-index: 0;
            border: none;
            border-radius: 0.5rem;
            position: relative;
        }


        /*progressbar*/
        #progressbar {
            margin-bottom: 15px;
            overflow: hidden;
            color: lightgrey;
        }

        #progressbar .active {
            color: #000000;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 10%;
            float: left;
            position: relative;
            text-align: center;
        }

        /*Icons in the ProgressBar*/
        #progressbar #Information:before {
            font-family: FontAwesome;
            content: "\f040";
            /* content: "\f05a"; */
        }

        #progressbar #Capability:before {
            font-family: FontAwesome;
            content: "\f2db";
        }

        #progressbar #Complexity:before {
            font-family: FontAwesome;
            content: "\f12e";
        }

        #progressbar #DSW:before {
            font-family: FontAwesome;
            content: "\f085";
        }

        #progressbar #BP:before {
            font-family: FontAwesome;
            content: "\f0c5";
        }

        #progressbar #RaS:before {
            font-family: FontAwesome;
            content: "\f0ae";
        }

        #progressbar #ProjectInclusion:before {
            font-family: FontAwesome;
            content: "\f542";
        }

        #progressbar #Assessment:before {
            font-family: FontAwesome;
            content: "\f007";
        }

        #progressbar #Proposal:before {
            font-family: FontAwesome;
            content: "\f0f6";
        }

        #progressbar #Success:before {
            font-family: FontAwesome;
            content: "\f00c";
        }

        /*ProgressBar before any progress*/
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px;

        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1;
        }

        /*Color number of the step and the connector before it*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: {{ $Status == 9 ? 'green' : '#eed202' }};
        }

        /* TABLE */
        #tableContainer {
            overflow-x: auto;
            max-width: 100%;
        }

        #tableContainer table thead {
            background-color: #f8f6f2;
        }
    </style>


    <main id="main" class="main">

        <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h4 class="mb-0">{{ $title }}</h4>
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('customers') }}">Customer</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
            <div class="container-fluid">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>
                                <i class="bi bi-exclamation-octagon me-1"></i>
                                {{ $error }}
                            </div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
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
                        <?= Session::get('fail') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">

                    <div class="card-body">

                        <form enctype="multipart/form-data" validated="false" id="customerForm" class="row g-3"
                            action="{{ $action }}" todo="{{ $todo }}" method="POST">
                            @csrf
                            @method($method)

                            <ul id="progressbar">
                                <li id="Information">
                                    @if ($Status >= 0)
                                        <a href="?progress=information"
                                            class="{{ $title == 'Information' ? 'active' : '' }}"><b>Information</b></a>
                                    @else
                                        <strong>Information</strong>
                                    @endif
                                </li>
                                <li id="Complexity">
                                    @if ($Status >= 1)
                                        <a href="?progress=complexity"
                                            class="{{ $title == 'Complexity' ? 'active' : '' }}"><b>Complexity</b></a>
                                    @else
                                        <strong>Complexity</strong>
                                    @endif
                                </li>
                                {{-- IF COMPLEX --}}
                                <li id="DSW">
                                    @if ($Status >= 2)
                                        <a href="?progress=dsw"
                                            class="{{ $title == 'Deployment Strategy Workshop' ? 'active' : '' }}"><b>Deployment
                                                Strategy Workshop</b></a>
                                    @else
                                        <strong>Deployment Strategy Workshop</strong>
                                    @endif
                                </li>
                                {{-- END COMPLEX --}}
                                <li id="BP">
                                    @if ($Status >= 3)
                                        <a href="?progress=businessProcess"
                                            class="{{ $title == 'Business Process' ? 'active' : '' }}"><b>Business
                                                Process</b></a>
                                    @else
                                        <strong>Business Process</strong>
                                    @endif
                                </li>
                                <li id="RaS">
                                    @if ($Status >= 4)
                                        <a href="?progress=requirementSolution"
                                            class="{{ $title == 'Requirements and Solutions' ? 'active' : '' }}"><b>Requirements
                                                and Solutions</b></a>
                                    @else
                                        <strong>Requirements and Solutions</strong>
                                    @endif
                                </li>
                                <li id="Capability">
                                    @if ($Status >= 5)
                                        <a href="?progress=capability"
                                            class="{{ $title == 'Capability' ? 'active' : '' }}"><b>Capability</b></a>
                                    @else
                                        <strong>Capability</strong>
                                    @endif
                                </li>
                                <li id="ProjectInclusion">
                                    @if ($Status >= 6)
                                        <a href="?progress=projectPhase"
                                            class="{{ $title == 'Project Phase' ? 'active' : '' }}"><b>Project
                                                Phase</b></a>
                                    @else
                                        <strong>Project Phase</strong>
                                    @endif
                                </li>
                                <li id="Assessment">
                                    @if ($Status >= 7)
                                        <a href="?progress=assessment"
                                            class="{{ $title == 'Assessment' ? 'active' : '' }}"><b>Assessment</b></a>
                                    @else
                                        <strong>Assessment</strong>
                                    @endif
                                </li>
                                <li id="Proposal">
                                    @if ($Status >= 8)
                                        <a href="?progress=proposal"
                                            class="{{ $title == 'Proposal' ? 'active' : '' }}"><b>Proposal</b></a>
                                    @else
                                        <strong>Proposal</strong>
                                    @endif
                                </li>
                                <li id="Success">
                                    @if ($Status >= 9)
                                        <a href="?progress=success"
                                            class="{{ $title == 'Success' ? 'active' : '' }}"><b>Success</b></a>
                                    @else
                                        <strong>Success</strong>
                                    @endif
                                </li>
                            </ul>

                            <div class="profile-overview">

                                @if ($Status != 0 && Request::get('progress') != 'information')
                                    <div class="row mb-4">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="">Customer Name</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $data['CustomerName'] }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="">Industry</label>
                                                <input type="text" class="form-control" value="{{ $data['Industry'] }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="">Contact Person</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $data['ContactPerson'] }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                @endif


                                <!-- ---------- INFORMATION ---------- -->
                                @if ($Status == 0 || Request::get('progress') == 'information')

                                    <?php $informationDisableField = $DisableAttr ? 'disabled' : ''; ?>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Customer Name
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <input {{ $informationDisableField }}
                                                value="{{ old('CustomerName') ?? $CustomerName }}" required
                                                type="text" class="form-control" name="CustomerName"
                                                id="CustomerName" placeholder="Customer Name">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Industry
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <input {{ $informationDisableField }}
                                                value="{{ old('Industry') ?? $Industry }}" required type="text"
                                                class="form-control" name="Industry" id="Industry"
                                                placeholder="Industry">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Address
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <input {{ $informationDisableField }}
                                                value="{{ old('Address') ?? $Address }}" required type="text"
                                                class="form-control" name="Address" id="Address"
                                                placeholder="Address">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Contact Person
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <input {{ $informationDisableField }}
                                                value="{{ old('ContactPerson') ?? $ContactPerson }}" required
                                                type="text" class="form-control" name="ContactPerson"
                                                id="ContactPerson" placeholder="Contact Person">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Product
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <select required select2 name="Product" id="Product" class="form-select"
                                                {{ $informationDisableField }}>
                                                <option value="" selected disabled>Select Product</option>
                                                <option value="1"
                                                    {{ isset($data['Product']) && $data['Product'] == 1 ? 'selected' : '' }}>
                                                    Sales</option>
                                                <option value="2"
                                                    {{ isset($data['Product']) && $data['Product'] == 2 ? 'selected' : '' }}>
                                                    Service</option>
                                                <option value="3"
                                                    {{ isset($data['Product']) && $data['Product'] == 3 ? 'selected' : '' }}>
                                                    Marketing</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Type <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <select required select2 name="Type" id="Type" class="form-select"
                                                {{ $informationDisableField }}>
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="1"
                                                    {{ isset($data['Type']) && $data['Type'] == 1 ? 'selected' : '' }}>
                                                    Deployment</option>
                                                <option value="2"
                                                    {{ isset($data['Type']) && $data['Type'] == 2 ? 'selected' : '' }}>
                                                    Enhancement</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Notes <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <textarea style="resize: none;" rows="3" required type="text" class="form-control" name="Notes"
                                                id="Notes" placeholder="Notes">{{ old('Notes') ?? $Notes }}</textarea>
                                        </div>
                                    </div>


                                    <!-- ---------- END INFORMATION ---------- -->
                                @elseif ($Status == 1 || Request::get('progress') == 'complexity')
                                    <!-- ---------- COMPLEXITY ---------- -->


                                    <?php $complexityDisableField = $DisableAttr || $data['Status'] > 2 ? 'disabled' : ''; ?>

                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <b><i class="bi bi-info-circle-fill"></i> NOTE: </b>
                                                <small>Determine if the requirements is complex</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <table id="mainTable" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" scope="col">COMPLEXITY</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($complexities as $index => $complexity)
                                                        <input type="hidden"
                                                            name="complexity[{{ $complexity['Id'] }}][Id]"
                                                            value="{{ $complexity['Id'] }}">
                                                        <input type="hidden"
                                                            name="complexity[{{ $complexity['Id'] }}][Title]"
                                                            value="{{ $complexity['Title'] }}">

                                                        <tr>
                                                            <td>{{ $complexity['Title'] }}

                                                                @if (count($complexity['Details']) > 0)
                                                                    <table
                                                                        class="table table-bordered table-hover mt-2 mb-0">
                                                                        @foreach ($complexity['Details'] as $SubDetail)
                                                                            <input type="hidden"
                                                                                name="complexity[{{ $complexity['Id'] }}][Sub][{{ $SubDetail['Id'] }}][Id]"
                                                                                value="{{ $SubDetail['Id'] }}">
                                                                            <input type="hidden"
                                                                                name="complexity[{{ $complexity['Id'] }}][Sub][{{ $SubDetail['Id'] }}][Title]"
                                                                                value="{{ $SubDetail['Title'] }}">

                                                                            <tr>
                                                                                <td>
                                                                                    <li>{{ $SubDetail['Title'] }}</li>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div
                                                                                        class="custom-control custom-checkbox">
                                                                                        <input type="checkbox"
                                                                                            class="custom-control-input subComplexity"
                                                                                            id="subCheck"
                                                                                            name="complexity[{{ $complexity['Id'] }}][Sub][{{ $SubDetail['Id'] }}][Selected]"
                                                                                            value={{ $SubDetail['Id'] }}
                                                                                            {{ $SubDetail['Checked'] == 1 ? 'checked' : '' }}
                                                                                            {{ $complexityDisableField }}>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        class="custom-control-input mainComplexity"
                                                                        value={{ $complexity['Id'] }} id="mainCheck"
                                                                        name="complexity[{{ $complexity['Id'] }}][Selected]"
                                                                        {{ $complexity['Checked'] == 1 ? 'checked' : '' }}
                                                                        {{ $complexityDisableField }}>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- ---------- END COMPLEXITY ---------- -->
                                @elseif ($Status == 2 || Request::get('progress') == 'dsw')
                                    <!-- ---------- DSW ---------- -->
                                    @if ($data['DSWStatus'] > 0)
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 label">Current Progress for DSW
                                                <?= $RequiredLabel ?></label>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-sm-2 dwsCon">
                                                        <div class="divSquare activeStatus">
                                                            <strong>DSW Started</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 dwsCon">
                                                        <div class="divSquare">
                                                            <strong>Ongoing DSW</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 dwsCon">
                                                        <div class="divSquare">
                                                            <strong>Completed DSW</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 dwsCon">
                                                        <div class="divSquare">
                                                            <strong>For Consolidation of Requirements</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 dwsCon">
                                                        <div class="divSquare">
                                                            <strong>Completeted Requirements Consolidation</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <h6 class="text-danger text-center">Deployment Strategy Workshop is not available
                                            for customer requirements</h6>
                                    @endif

                                    <!-- ---------- END DSW ---------- -->
                                @elseif ($Status == 3 || Request::get('progress') == 'businessProcess')
                                    <!-- ---------- BUSINESS PROCESS ---------- -->

                                    <?php $businessProcessDisableField = $DisableAttr ? 'disabled' : ''; ?>

                                    <div class="row mb-3">
                                        <label for="File" class="col-sm-2 label">Attachment
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="file" id="File" name="File[]"
                                                multiple {{ $businessProcessDisableField }} />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="BusinessNotes" class="col-sm-2 label">Notes
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <textarea {{ $businessProcessDisableField }} style="resize: none;" rows="3" required type="text"
                                                class="form-control" name="BusinessNotes" id="BusinessNotes" placeholder="Notes">{{ old('BusinessNotes') ?? $BusinessNotes }}</textarea>
                                        </div>
                                    </div>

                                    @if ($Status > 3)
                                        <div class="row mb-3">
                                            <label for="files" class="col-sm-2 label">Files</label>
                                            @foreach ($files as $file)
                                                <div class="col-md-3 parent" filename="{{ $file['File'] }}">
                                                    <div class="p-2 border border-1 rounded">
                                                        <div class="d-flex justify-content-between">
                                                            <a href="{{ asset('uploads/businessProcess/' . $file['File']) }}"
                                                                class="text-black fw-bold"
                                                                target="_blank">{{ $file['File'] }}</a>
                                                            <button type="button"
                                                                class="btn-close btnRemoveFilename"></button>
                                                        </div>
                                                        <span style="font-size:14px" class="text-muted">
                                                            {{ date('F d, Y', strtotime($file->created_at)) }}</span>

                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- ---------- END BUSINESS PROCESS ---------- -->
                                @elseif ($Status == 4 || Request::get('progress') == 'requirementSolution')
                                    <!-- ---------- REQUIREMENT AND SOLUTIONS ---------- -->

                                    <?php $requirementSolutionDisableField = $DisableAttr ? 'disabled' : ''; ?>

                                    <div class="card mb-3">
                                        <div class="card-header py-3">
                                            <h5 class="card-title mb-0">IN-SCOPE</h5>
                                        </div>
                                        <div class="card-body">

                                            @if ($requirementSolutionDisableField)
                                                <div id="tableContainer" class="mb-3">
                                                    <table id="inScopeTable" cellpadding="0" cellspacing="0"
                                                        class="table table-bordered"
                                                        style="min-width: 1200px; width: 100%; max-width: 1500px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%;">Requirement List</th>
                                                                <th style="width: 20%">Description</th>
                                                                <th style="width: 20%">Salesforce Modules</th>
                                                                <th style="width: 20%">Solutions Overview</th>
                                                                <th style="width: 20%">Assumptions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($reqSol) && count($reqSol) > 0)
                                                                @foreach ($reqSol as $index => $inscope)
                                                                    <tr>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Title }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Description }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Module }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Solution }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Assumption }}</small>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div id="tableContainer" class="mb-3">
                                                    <table id="inScopeTable" cellpadding="0" cellspacing="0"
                                                        class="table table-bordered"
                                                        style="min-width: 1200px; width: 100%; max-width: 1500px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 10px;"></th>
                                                                <th style="width: 20%;">Requirement List</th>
                                                                <th style="width: 20%">Description</th>
                                                                <th style="width: 20%">Salesforce Modules</th>
                                                                <th style="width: 20%">Solutions Overview</th>
                                                                <th style="width: 20%">Assumptions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($reqSol) && count($reqSol) > 0)
                                                                @foreach ($reqSol as $index => $inscope)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger btnDeleteRow"><i
                                                                                    class="bi bi-trash"></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="Title[]" class="form-control" rows="3" style="resize: none;">{{ $inscope->Title }}</textarea>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="Description[]" class="form-control" rows="3" style="resize: none;">{{ $inscope->Description }}</textarea>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="Module[]" class="form-control" rows="3" style="resize: none;">{{ $inscope->Module }}</textarea>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="Solution[]" class="form-control" rows="3" style="resize: none;">{{ $inscope->Solution }}</textarea>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name="Assumption[]" class="form-control" rows="3" style="resize: none;">{{ $inscope->Assumption }}</textarea>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <button type="button"
                                                                            class="btn btn-outline-danger btnDeleteRow"><i
                                                                                class="bi bi-trash"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="Title[]" class="form-control" rows="3" style="resize: none;"></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="Description[]" class="form-control" rows="3" style="resize: none;"></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="Module[]" class="form-control" rows="3" style="resize: none;"></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="Solution[]" class="form-control" rows="3" style="resize: none;"></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="Assumption[]" class="form-control" rows="3" style="resize: none;"></textarea>
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <button class="btn btn-outline-primary btnAddRow" type="button">
                                                    <i class="fas fa-plus"></i> Add Row
                                                </button>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header py-3">
                                            <h5 class="card-title mb-0">LIMITATIONS</h5>
                                        </div>
                                        <div class="card-body">

                                            @if ($requirementSolutionDisableField)
                                                <div id="tableContainer" class="mb-3">
                                                    <table id="outScopeTable" cellpadding="0" cellspacing="0"
                                                        class="table table-bordered"
                                                        style="min-width: 100%; width: 100%; max-width: 1000px;">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" style="width: 50%;">Out of Scope</th>
                                                                <th scope="col" style="width: 50%;">Comments</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($limitations) && count($limitations) > 0)
                                                                @foreach ($limitations as $index => $limitation)
                                                                    <tr>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $limitation->OutScope }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $limitation->Comment }}</small>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div id="tableContainer" class="mb-3">
                                                    <table id="outScopeTable" cellpadding="0" cellspacing="0"
                                                        class="table table-bordered"
                                                        style="min-width: 100%; width: 100%; max-width: 1000px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 10px;"></th>
                                                                <th scope="col" style="width: 50%;">Out of Scope</th>
                                                                <th scope="col" style="width: 50%;">Comments</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($limitations) && count($limitations) > 0)
                                                                @foreach ($limitations as $index => $limitation)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger btnDeleteRow"><i
                                                                                    class="bi bi-trash"></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <textarea class="form-control" rows="3" style="resize: none;" name="OutOfScope[]">{{ $limitation->OutScope }}</textarea>
                                                                        </td>
                                                                        <td>
                                                                            <textarea class="form-control" rows="3" style="resize: none;" name="Comment[]">{{ $limitation->Comment }}</textarea>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <button type="button"
                                                                            class="btn btn-outline-danger btnDeleteRow"><i
                                                                                class="bi bi-trash"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        <textarea class="form-control" rows="3" style="resize: none;" name="OutOfScope[]"></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <textarea class="form-control" rows="3" style="resize: none;" name="Comment[]"></textarea>
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button class="btn btn-outline-primary btnAddRowLimitation"
                                                    type="button">
                                                    <i class="fas fa-plus"></i> Add Row
                                                </button>
                                            @endif

                                        </div>
                                    </div>

                                    <!-- ---------- END REQUIREMENT AND SOLUTIONS ---------- -->
                                @elseif ($Status == 5 || Request::get('progress') == 'capability')
                                    <!-- ---------- CAPABILITY ---------- -->

                                    <?php 
                                        $capabilityDisableField = $DisableAttr || $data['IsCapable'] == 1 || $data['ThirdPartyStatus'] > 0 ? 'disabled' : ''; 
                                        $RequiredLabel = $capabilityDisableField ? '' : "<code>*</code>";
                                    ?>

                                    <div class="row mb-3">
                                        <label for="" class="col-sm-2 label">Capability 
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <select name="IsCapable" id="IsCapable" select2 required
                                                {{ $capabilityDisableField }}>
                                                <option value="" selected disabled>Select Capability</option>
                                                <option value="0" {{ $data['IsCapable'] == 0 ? 'selected' : '' }}>No Capability</option>
                                                <option value="2" {{ $data['IsCapable'] == 2 ? 'selected' : '' }}>Hybrid Capability</option>
                                                <option value="1" {{ $data['IsCapable'] == null || $data['IsCapable'] == 1 ? 'selected' : '' }}>Full Capability</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="isCapableDisplay" style="{{ $data['IsCapable'] == null || $data['IsCapable'] == 1 ? 'display: none;' : '' }}">
                                        <div class="row mb-3">
                                            <label for="" class="col-sm-2 label">Third Party
                                                <?= $RequiredLabel ?></label>
                                            <div class="col-sm-10">
                                                <select name="ThirdPartyId" id="ThirdPartyId" class="form-select" select2
                                                    {{ $capabilityDisableField }}>
                                                    <option value="" selected disabled>Select Third Party</option>

                                                    @foreach ($thirdParties as $dt)
                                                        <option value="{{ $dt['Id'] }}"
                                                            {{ isset($data['ThirdPartyId']) && $data['ThirdPartyId'] == $dt['Id'] ? 'selected' : '' }}>
                                                            {{ $dt['Name'] }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                                <div class="row" id="otherThirdPartyDisplay"
                                                    style="{{ isset($data['ThirdPartyId']) && $data['ThirdPartyId'] == config('constant.ID.THIRD_PARTIES.OTHERS') ? '' : 'display: none;' }}">
                                                    <div class="col-12 mt-3">
                                                        <div class="alert alert-warning">
                                                            <b>For non-accredited third party, please complete the following requirements:</b>
                                                            <ul>
                                                                <li>Apostilled Articles of Incorporation/ Certificate of
                                                                    Incorporation</li>
                                                                <li>Apostilled Tax Residency Certificate</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="ThirdPartyName"
                                                            placeholder="Enter Third Party"
                                                            value="{{ isset($data['ThirdPartyName']) ? $data['ThirdPartyName'] : '' }}"
                                                            {{ $capabilityDisableField }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <b><i class="bi bi-info-circle-fill"></i> NOTE: </b>
                                                    <small>Select the checbox to assign the requirement to third
                                                        party</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3">
                                            <div class="card-header py-3">
                                                <h5 class="card-title mb-0">IN-SCOPE</h5>
                                            </div>
                                            <div class="card-body">
                                                <div id="tableContainer" class="mb-3">
                                                    <table id="inScopeTable" cellpadding="0" cellspacing="0"
                                                        class="table table-bordered table-hover"
                                                        style="min-width: 1200px; width: 100%; max-width: 1500px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 100px;">Third Party</th>
                                                                <th style="width: 220px;">Requirement List</th>
                                                                <th style="width: 220px">Description</th>
                                                                <th style="width: 220px">Salesforce Modules</th>
                                                                <th style="width: 220px">Solutions Overview</th>
                                                                <th style="width: 220px">Assumptions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($reqSol) && count($reqSol) > 0)
                                                                @foreach ($reqSol as $index => $inscope)
                                                                    <input type="hidden"
                                                                        name="InScope[{{ $inscope['Id'] }}][Id]"
                                                                        value="{{ $inscope['Id'] }}">

                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <input type="checkbox" class="checkThirdParty" name="InScope[{{ $inscope['Id'] }}][Checked]"
                                                                                {{ $inscope->ThirdParty == 1 ? 'checked' : '' }}
                                                                                {{ $capabilityDisableField }}>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Title }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Description }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Module }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Solution }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $inscope->Assumption }}</small>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card mb-3">
                                            <div class="card-header py-3">
                                                <h5 class="card-title mb-0">LIMITATIONS</h5>
                                            </div>
                                            <div class="card-body">
                                                <div id="tableContainer" class="mb-3">
                                                    <table id="outScopeTable" cellpadding="0" cellspacing="0"
                                                        class="table table-bordered table-hover"
                                                        style="min-width: 100%; width: 100%; max-width: 1000px;">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" style="width: 50%;">Out of Scope</th>
                                                                <th scope="col" style="width: 50%;">Comments</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($limitations) && count($limitations) > 0)
                                                                @foreach ($limitations as $index => $limitation)
                                                                    <input type="hidden"
                                                                        name="Limitation[{{ $limitation['Id'] }}][Id]"
                                                                        value="{{ $limitation['Id'] }}">

                                                                    <tr>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $limitation->OutScope }}</small>
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $limitation->Comment }}</small>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row my-3">
                                            <label for="" class="col-sm-2 label">Link Attachment</label>
                                            <div class="col-sm-10">
                                                <input type="url" class="form-control" name="ThirdPartyAttachment"
                                                    value="{{ isset($data['ThirdPartyAttachment']) ? $data['ThirdPartyAttachment'] : '' }}"
                                                    {{ $DisableAttr }}>
                                            </div>
                                        </div>

                                        @if ($capabilityDisableField)
                                            <div class="row mb-3">
                                                <label for="" class="col-sm-2 label">Status
                                                    <?= $RequiredLabel ?></label>
                                                <div class="col-sm-10">
                                                    <select name="ThirdPartyStatus" id="ThirdPartyStatus" required select2
                                                        {{ $DisableAttr || ($data['ThirdPartyStatus'] == 3 && $data['Status'] >= 2) ? 'disabled' : '' }}>
                                                        <option value="" selected disabled>Select Status</option>
                                                        <option value="1"
                                                            {{ $data['ThirdPartyStatus'] == 1 ? 'selected' : '' }}>For
                                                            Accreditation</option>
                                                        <option value="2"
                                                            {{ $data['ThirdPartyStatus'] == 2 ? 'selected' : '' }}>
                                                            Accredited</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- ---------- END CAPABILITY ---------- -->
                                @elseif ($Status == 6 || Request::get('progress') == 'projectPhase')
                                    <!-- ---------- PROJECT PHASE ---------- -->

                                    <?php $projectPhaseDisableField = $DisableAttr ? 'disabled' : ''; ?>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <table id="mainTable" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" scope="col">PROJECT PHASES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($ProjectPhase as $index => $pp)
                                                        <input type="hidden"
                                                            name="projectPhase[{{ $pp['Id'] }}][Id]"
                                                            value="{{ $pp['Id'] }}">
                                                        <input type="hidden"
                                                            name="projectPhase[{{ $pp['Id'] }}][Title]"
                                                            value="{{ $pp['Title'] }}">
                                                        <input type="hidden"
                                                            name="projectPhase[{{ $pp['Id'] }}][Percentage]"
                                                            value="{{ $pp['Percentage'] }}">
                                                        <input type="hidden"
                                                            name="projectPhase[{{ $pp['Id'] }}][Required]"
                                                            value="{{ $pp['Required'] }}">

                                                        <tr>
                                                            <td>{{ $pp['Title'] }}

                                                                @if (count($pp['Details']) > 0)
                                                                    <table
                                                                        class="table table-bordered table-hover mt-2 mb-0">
                                                                        @foreach ($pp['Details'] as $SubDetail)
                                                                            <input type="hidden"
                                                                                name="projectPhase[{{ $pp['Id'] }}][Sub][{{ $SubDetail['Id'] }}][Id]"
                                                                                value="{{ $SubDetail['Id'] }}">
                                                                            <input type="hidden"
                                                                                name="projectPhase[{{ $pp['Id'] }}][Sub][{{ $SubDetail['Id'] }}][Title]"
                                                                                value="{{ $SubDetail['Title'] }}">
                                                                            <input type="hidden"
                                                                                name="projectPhase[{{ $pp['Id'] }}][Sub][{{ $SubDetail['Id'] }}][Required]"
                                                                                value="{{ $SubDetail['Required'] }}">

                                                                            <tr>
                                                                                <td>
                                                                                    <li>{{ $SubDetail['Title'] }}</li>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div
                                                                                        class="custom-control custom-checkbox">
                                                                                        <input type="checkbox"
                                                                                            class="custom-control-input"
                                                                                            id="subCheck"
                                                                                            name="projectPhase[{{ $pp['Id'] }}][Sub][{{ $SubDetail['Id'] }}][Checked]"
                                                                                            {{ $SubDetail['Required'] == 1 ? 'checked disabled' : ($SubDetail['Checked'] == 1 ? 'checked' : '') }}
                                                                                            {{ $projectPhaseDisableField }}>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="mainCheck"
                                                                        name="projectPhase[{{ $pp['Id'] }}][Checked]"
                                                                        {{ $pp['Required'] == 1 ? 'checked disabled' : ($pp['Checked'] == 1 ? 'checked' : '') }}
                                                                        {{ $projectPhaseDisableField }}>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ---------- END PROJECT PHASE ---------- -->
                                @elseif ($Status == 7 || Request::get('progress') == 'assessment')
                                    <!-- ---------- ASSESSMENT ---------- -->
                                    <div class="card mb-3 accordion ">
                                        <div id="tableContainer" class="accordion-item ">
                                            <a href="#" class="" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
                                                <div class="card-header py-3">
                                                    <h5 class="card-title mb-0">ENGAGEMENT EFFORT SUMMARY</h5>
                                                </div>
                                            </a>
                                            <div id="collapseOne" class="accordion-collapse collapse show card-body" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="table-responsive">
                                                    <table id="inScopeTable" cellpadding="0" cellspacing="0"
                                                        class="table table-bordered table-hover"
                                                        style="min-width: 1000px; width: 100%; max-width: 1500px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5px;">No.</th>
                                                                <th style="width: 150px;">Project Phase</th>
                                                                <th style="width: 150px;"></th>
                                                                <th class="text-center" style="width: 10px;">%</th>
                                                                <th style="width: 30px;">Effort (Hours)</th>
                                                                <th style="width: 2px;" class="bg-secondary"></th>
    
                                                                @if (isset($projectPhaseResources) && count($projectPhaseResources))
                                                                    @foreach ($projectPhaseResources as $dt)
                                                                    <?php ${"$dt->Initial"} = 0; ?>
                                                                        <th style="width: 10px;" class="text-center">{{ $dt->Initial }}</th>
                                                                    @endforeach
                                                                @endif
    
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($customerProjectPhases) && count($customerProjectPhases) > 0)
                                                            <?php $totalEngagementHours = 0.00; ?>
                                                                @foreach ($customerProjectPhases as $index => $cpp)
                                                                <?php $totalEngagementHours += $cpp['EffortHours'] ?>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            {{ $index+1 }}
                                                                        </td>
                                                                        <td>
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $cpp['Title'] }}</small>
                                                                        </td>
                                                                        <td>
                                                                            @foreach ($cpp['Resources'] as $resources)
                                                                            <small
                                                                            style="white-space: break-spaces;">{{ $resources['Initial'].':'.$resources['Percentage'].'%'}}</small>,
                                                                            @endforeach
                                                                            
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $cpp['Percentage'] }}</small>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <small
                                                                                style="white-space: break-spaces;">{{ $cpp['EffortHours'] }}</small>
                                                                        </td>
                                                                        <td class="bg-secondary"></td>
                                                                        @if (isset($projectPhaseResources) && count($projectPhaseResources))
                                                                            @foreach ($projectPhaseResources as $dt)
                                                                                <?php 
                                                                                    $manhour = $cpp['Resources']["{$dt->Initial}"]['resourceManhour'] ?? 0;
                                                                                    ${"$dt->Initial"} += $manhour;
                                                                                ?>
                                                                                <td class="text-center"><?= $manhour == 0 ? '' : $manhour ?></td>
                                                                            @endforeach
                                                                        @endif
                                                                            
                                                                    
                                                                    
                                                                        
                                                                    </tr>
                                                                @endforeach
                                                                <tr>
                                                                    <td colspan="4"><strong>Total Engagement Efforts</strong></td>
                                                                    <td class="text-center"><strong><?=$totalEngagementHours?></strong></td>
                                                                    <td class="bg-secondary"></td>
                                                                    @if (isset($projectPhaseResources) && count($projectPhaseResources))
                                                                        @foreach ($projectPhaseResources as $dt)
                                                                            <td class="text-center">{{ ${"$dt->Initial"} }}</td>
                                                                        @endforeach
                                                                    @endif
                                                                </tr>
                                                                <tr><td colspan="{{ count($projectPhaseResources) + 6 }}"></td></tr>
                                                                <tr>
                                                                    <td colspan="5"><strong>Total Weeks</strong></td>
                                                                    <td colspan="{{ count($projectPhaseResources) + 1 }}"><strong><?=round($totalEngagementHours/40,1)?></strong></td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-header py-3">
                                            <h5 class="card-title mb-0">OIC</h5>
                                            <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-html="true"
                                                title="Assign OIC"></i>
                                        </div>
                                        <div class="card-body">
                                            <select select2 name="OICId" id="OICId" class="form-select" required>
                                                <option value="0" selected disabled>Select OIC</option>
                                                @foreach ($users as $user)
                                                    <option {{ $data->OICId == $user->Id?'selected':'' }}  value="{{ $user->Id }}">
                                                        {{ $user->FirstName . ' ' . $user->LastName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-end mt-3">
                                                <a href="#" class="btn btn-info btnOIC text-white">Assign OIC</a>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header py-3">
                                            <h5 class="card-title mb-0">RESOURCES</h5>
                                            <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-html="true"
                                                title="Assign consultant(s)"></i>
                                        </div>
                                        <div class="card-body">
                                            <select name="ResourcesId[]" id="ResourcesId" class="form-select ACSelect"
                                                required multiple >

                                                @foreach ($users as $user)
                                                    <option {{ $assignedConsultants->contains('UserId',$user->Id) ? 'selected':'' }} value="{{ $user->Id }}">
                                                        {{ $user->FirstName . ' ' . $user->LastName }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            @if (in_array(Auth::id(), [$data->HeadId, $data->OICId]))
                                                <div class="text-end mt-3">
                                                    <a href="#" class="btn btn-info btnAssign text-white">Assign Users</a>
                                                </div>
                                            @endif
                                        </div>
                                        
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header py-3">
                                            <h5 class="card-title mb-0">IN-SCOPE</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="tableContainer" class="mb-3">
                                                <table id="inScopeTable" cellpadding="0" cellspacing="0"
                                                    class="table table-bordered table-hover"
                                                    style="min-width: 1400px; width: 100%; max-width: 1500px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 100px;">Third Party</th>
                                                            <th style="width: 220px;">Requirement List</th>
                                                            <th style="width: 220px">Description</th>
                                                            <th style="width: 220px">Salesforce Modules</th>
                                                            <th style="width: 220px">Solutions Overview</th>
                                                            <th style="width: 200px;">Manhours</th>
                                                            <th style="width: 220px">Assumptions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $isForSubmit = true; ?>

                                                        @if (!empty($reqSol) && count($reqSol) > 0)
                                                            @foreach ($reqSol as $index => $inscope)
                                                                <?php
                                                                    if (!$inscope->Manhour || $inscope->Manhour == 0) {
                                                                        $isForSubmit = false; 
                                                                    }
                                                                 ?>

                                                                <input name="RowId[]" type="hidden"
                                                                    value="{{ $inscope->Id }}">
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                            name="InScope[{{ $inscope['Id'] }}][Checked]"
                                                                            {{ $inscope->ThirdParty == 1 ? 'checked' : '' }}
                                                                            disabled>
                                                                    </td>
                                                                    <td>
                                                                        <small
                                                                            style="white-space: break-spaces;">{{ $inscope->Title }}</small>
                                                                    </td>
                                                                    <td>
                                                                        <small
                                                                            style="white-space: break-spaces;">{{ $inscope->Description }}</small>
                                                                    </td>
                                                                    <td>
                                                                        <small
                                                                            style="white-space: break-spaces;">{{ $inscope->Module }}</small>
                                                                    </td>
                                                                    <td>
                                                                        <small
                                                                            style="white-space: break-spaces;">{{ $inscope->Solution }}</small>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="0.01" name="Manhour[]"
                                                                            class="form-control text-center"
                                                                            value="{{ $inscope->Manhour }}">
                                                                    </td>
                                                                    <td>
                                                                        <small
                                                                            style="white-space: break-spaces;">{{ $inscope->Assumption }}</small>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-end mt-3">
                                                
                                            @if ($Status == $currentViewStatus)
                                             <div class="button-footer text-end">
                                                <?= $manhourButton ?>
                                                <?= $isForSubmit ? $button : '' ?>
                                            </div>
                                            @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header py-3">
                                            <h5 class="card-title mb-0">LIMITATIONS</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="tableContainer" class="mb-3">
                                                <table id="outScopeTable" cellpadding="0" cellspacing="0"
                                                    class="table table-bordered table-hover"
                                                    style="min-width: 100%; width: 100%; max-width: 1000px;">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" style="width: 50%;">Out of Scope</th>
                                                            <th scope="col" style="width: 50%;">Comments</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($limitations) && count($limitations) > 0)
                                                            @foreach ($limitations as $index => $limitation)
                                                                <input type="hidden"
                                                                    name="Limitation[{{ $limitation['Id'] }}][Id]"
                                                                    value="{{ $limitation['Id'] }}">

                                                                <tr>
                                                                    <td>
                                                                        <small
                                                                            style="white-space: break-spaces;">{{ $limitation->OutScope }}</small>
                                                                    </td>
                                                                    <td>
                                                                        <small
                                                                            style="white-space: break-spaces;">{{ $limitation->Comment }}</small>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ---------- END ASSESSMENT ---------- -->
                                @elseif ($Status == 8 || Request::get('progress') == 'proposal')
                                    <!-- ---------- PROPOSAL ---------- -->
                                    <div class="row mb-3">
                                        <label for="" class="col-sm-2 label">Status
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <select name="ProposalStatus" id="ProposalStatus" select2 required>
                                                <option value="" selected disabled>Select Status of Proposal</option>
                                                <option value="1" {{ $data['ProposalStatus'] == 1 ? 'selected' : '' }}>Ongoing creation of proposal</option>
                                                <option value="2" {{ $data['ProposalStatus'] == 2 ? 'selected' : '' }}>Submitted proposal </option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Notes <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <textarea {{ $DisableAttr }} style="resize: none;" rows="3" required type="text" class="form-control"
                                                name="Notes" id="Notes" placeholder="Notes">{{ old('Notes') ?? $Notes }}</textarea>
                                        </div>
                                    </div> --}}

                                    <div id="AttachmentDisplay" class="row mb-3" style="{{ $data['ProposalStatus'] == null || $data['ProposalStatus'] == 0 ? 'display: none;' : '' }}" >
                                        <label for="inputText" class="col-sm-2 label">Attachment</label>
                                        <div class="col-sm-10">
                                            <input class="form-control mb-3" type="file" id="FileProposal" name="FileProposal[]" multiple />
                                            <input style="display:none;" class="form-control" type="date" id="DateSubmitted" name="DateSubmitted" />
                                        </div>
                                    </div>
                                    <div id="DateSignedDisplay" class="row mb-3" style="display: none;" >
                                        <label for="inputText" class="col-sm-2 label">Date Submitted
                                            <?= $RequiredLabel ?></label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="date" id="DateSubmitted" name="DateSubmitted" />
                                        </div>
                                    </div>

                                    <!-- ---------- END PROPOSAL ---------- -->
                                @elseif ($Status == 9 || Request::get('progress') == 'success')
                                @else
                                @endif



                                @if ($Status != 0 && Request::get('progress') != 'information')
                            </div>
                    </div>
                    @endif

                </div>

                @if ($Status == $currentViewStatus)
                    @if($Status == 7 || Request::get('progress') == 'assessment')
                        <div class="button-footer text-end">
                        </div>
                    @else
                    <div class="button-footer text-end">
                        <a href="{{ $cancelRoute }}" class="btn btn-secondary">Cancel</a>
                        <?= $button ?>
                    </div>
                    @endif
                @endif



                </form>

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

            // ----- REMOVE FILE -----
            $(document).on('click', '.btnRemoveFilename', function() {
                let $parent = $(this).closest('.parent');
                let filename = $parent.attr('filename');

                $parent.fadeOut(500, function() {
                    $parent.remove();
                })
            })
            // ----- END REMOVE FILE -----

            // ----- BUTTON ADD ROW -----
            $(document).on('click', '.btnAddRow', function() {
                let html = `
                <tr>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btnDeleteRow"><i class="bi bi-trash"></i></button>
                    </td>
                    <td>
                        <textarea name="Title[]" class="form-control" rows="3" style="resize: none;"></textarea>    
                    </td>
                    <td>
                        <textarea required name="Description[]" class="form-control" rows="3" style="resize: none;"></textarea>
                    </td>
                    <td>
                        <textarea required name="Module[]" class="form-control" rows="3" style="resize: none;"></textarea>
                    </td>
                    <td>
                        <textarea required name="Solution[]" class="form-control" rows="3" style="resize: none;"></textarea>
                    </td>
                    <td>
                        <textarea required name="Assumption[]" class="form-control" rows="3" style="resize: none;"></textarea>
                    </td>
                </tr>`;

                $('#inScopeTable tbody').append(html);
                initSelect2();
            })
            // ----- END BUTTON ADD ROW -----

            // ----- BUTTON ADD LIMITATIONS ROW -----
            $(document).on('click', '.btnAddRowLimitation', function() {
                let html = `
                <tr>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btnDeleteRow"><i class="bi bi-trash"></i></button>
                    </td>
                    <td>
                        <textarea required name="OutOfScope[]" class="form-control" rows="3" style="resize: none;"></textarea>
                    </td>
                    <td>
                        <textarea required name="Comment[]" class="form-control" rows="3" style="resize: none;"></textarea>
                    </td>
                </tr>`;

                $('#outScopeTable tbody').append(html);
                initSelect2();
            })
            // ----- END BUTTON ADD LIMITATIONS ROW -----

            // CHECKBOX IF COMPLEX OR NOT

            $(document).on('click', 'input[name="IsComplex"]', function() {
                $('input[name="IsComplex"]').not(this).prop('checked', false);
                checkDisabled();
            });


            function checkDisabled() {
                const table = $('#mainTable');
                $('#IsComplex').prop('checked') ? table.show() : table.hide();
            }

            // UPDATE FOR ASSESSMENT
            $(document).on('click', '.btnUpdate', function(e) {
                e.preventDefault();
                let content = `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update manhour?</b>
                </div>`;

                let confirmation = $.confirm({
                    title: false,
                    content,
                    buttons: {

                        no: {
                            btnClass: 'btn-default',
                        },
                        yes: {
                            btnClass: 'btn-blue',
                            keys: ['enter'],
                            action: function() {
                                let data = [];
                                $("input[name='RowId[]']").each(function(index) {
                                    let obj = {
                                        rowId: $("input[name='RowId[]']").eq(index)
                                            .val(),
                                        manhourValue: $("input[name='Manhour[]']")
                                            .eq(index).val()
                                    }
                                    data.push(obj);
                                });
                                var method = 'PUT';
                                $.ajax({
                                    type: method,
                                    url: `{{ $Id }}/updateManhour`,
                                    data: {
                                        data
                                    },
                                    async: false,
                                    success: function(response) {
                                        window.location = response.url;
                                    }
                                })

                                confirmation.buttons.yes.setText(
                                    `<span class="spinner-border spinner-border-sm"></span> Please wait...`
                                );
                                confirmation.buttons.yes.disable();
                                confirmation.buttons.no.hide();

                                return false;
                            }
                        },
                    }
                });
            });
            // END UPDATE FOR ASSESSMENT

            // UPDATE FOR CONSULTANT ASSIGNMENT
            $(document).on('click', '.btnAssign', function(e) {
                e.preventDefault();
                let selectedConsultants = $('#ResourcesId').val();
                if (selectedConsultants && selectedConsultants.length) {
                    let message = "Are you sure you want to assign this user?";
                    if(selectedConsultants.length > 1){
                        message = "Are you sure you want to assign these users?";
                    }
                    let content = `
                    <div class="d-flex justify-content-center align-items-center flex-column text-center">
                        <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                        <b class="mt-4">${message}</b>
                    </div>`;
    
                    let confirmation = $.confirm({
                        title: false,
                        content,
                        buttons: {
    
                            no: {
                                btnClass: 'btn-default',
                            },
                            yes: {
                                btnClass: 'btn-blue',
                                keys: ['enter'],
                                action: function() {
                                    let method = 'PUT';
                                    confirmation.buttons.yes.setText(
                                        `<span class="spinner-border spinner-border-sm"></span> Please wait...`
                                    );
                                    confirmation.buttons.yes.disable();
                                    confirmation.buttons.no.hide();

                                    setTimeout(() => {
                                        $.ajax({
                                            type: method,
                                            url: `{{ $Id }}/updateConsultant`,
                                            data:{selectedConsultants},
                                            async: false,
                                            success: function(response) {
                                                window.location = response.url;
                                            }
                                        })
                                    }, 100);
    
                                    return false;
                                }
                            },
                        }
                    });
                } else {
                    $('#ResourcesId').focus();
                }
            });
            // END UPDATE FOR ASSESSMENT

            // ASSIGN OF OIC
            $(document).on('click', '.btnOIC', function(e) {
                e.preventDefault();
                let selectedOIC = $('#OICId').val();

                if (selectedOIC) {
                    let message = "Are you sure you want to assign this user to OIC?";
                    let content = `
                    <div class="d-flex justify-content-center align-items-center flex-column text-center">
                        <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                        <b class="mt-4">${message}</b>
                    </div>`;
    
                    let confirmation = $.confirm({
                        title: false,
                        content,
                        buttons: {
    
                            no: {
                                btnClass: 'btn-default',
                            },
                            yes: {
                                btnClass: 'btn-blue',
                                keys: ['enter'],
                                action: function() {
                                    let method = 'PUT';
                                    $.ajax({
                                        type: method,
                                        url: `{{ $Id }}/updateOIC`,
                                        data:{selectedOIC},
                                        async: false,
                                        success: function(response) {
                                            window.location = response.url;
                                        }
                                    })
    
                                    confirmation.buttons.yes.setText(
                                        `<span class="spinner-border spinner-border-sm"></span> Please wait...`
                                    );
                                    confirmation.buttons.yes.disable();
                                    confirmation.buttons.no.hide();
    
                                    return false;
                                }
                            },
                        }
                    });
                } else {
                    $(`[name="OICId"]`).focus();
                }
            });
            // END ASSIGN OF OIC

            // PROGRESS BAR
            const status = "{{ $Status }}";
            for (let i = 0; i <= status; i++) {
                $("#progressbar li").eq(i).addClass("active");
            }
            const dswStatus = parseFloat("{{ $DSWStatus }}") - 1;
            for (let i = 0; i <= dswStatus; i++) {
                $(".divSquare").eq(i).addClass("activeStatus");
            }
            // END PROGRESS BAR

            // SELECT2 PLACEHOLDER ASSIGNED CONSULTANT
            $(".ACSelect").select2({
                placeholder: "Select atleast one(1) consultant"
            });
            // END SELECT2 PLACEHOLDER ASSIGNED CONSULTANT




            // ----- SUBMIT FORM -----
            $(document).on('submit', '#customerForm', function(e) {

                let isValidated = $(this).attr('validated') == "true";
                let todo = $(this).attr('todo');

                // FOR COMPLEXITY PART
                if (status == 1) {
                    if ($('input.mainComplexity:checked').length === 0 && $(`[name="IsComplex"]`).prop(
                            'checked')) {
                        showToast('danger', 'Check atleast one(1) in the checklist');
                        isValidated = true;
                    }
                }

                if (!isValidated) {

                    e.preventDefault();
                    let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new customer?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this customer?</b>
                </div>`;

                    let confirmation = $.confirm({
                        title: false,
                        content,
                        buttons: {

                            no: {
                                btnClass: 'btn-default',
                            },
                            yes: {
                                btnClass: 'btn-blue',
                                keys: ['enter'],
                                action: function() {
                                    $('#customerForm').attr('validated', 'true')
                                        .submit();

                                    confirmation.buttons.yes.setText(
                                        `<span class="spinner-border spinner-border-sm"></span> Please wait...`
                                    );
                                    confirmation.buttons.yes.disable();
                                    confirmation.buttons.no.hide();

                                    return false;
                                }
                            },
                        }
                    });
                }
            })
            // ----- END SUBMIT FORM -----


            // ----- CHANGE WITH CAPABILITY -----
            $(document).on('change', `[name="IsCapable"]`, function() {
                let IsCapable = $(this).val();
                if (IsCapable == 1) {
                    $('#isCapableDisplay').hide()
                    $(`[name="ThirdPartyId"]`).attr('required', false).val('');
                    $(`[name="ThirdPartyName"]`).attr('required', false).val('');
                } else {
                    $(`[name="ThirdPartyId"]`).attr('required', true).trigger('change');
                    $('#isCapableDisplay').show();

                    if (IsCapable == 0) {
                        $('.checkThirdParty').prop('checked', true).trigger('change');
                    } else {
                        $('.checkThirdParty').prop('checked', false).trigger('change');
                    }
                }
            })
            // ----- END CHANGE WITH CAPABILITY -----

            // ----- CHANGE THIRD PARTY -----
            $(document).on('change', `[name="ThirdPartyId"]`, function() {
                let thirdPartyName = $('option:selected', this).text()?.trim();
                if (thirdPartyName == "Others") {
                    $('#otherThirdPartyDisplay').show();
                    $(`[name="ThirdPartyName"]`).attr('required', true).val('');
                } else {
                    $('#otherThirdPartyDisplay').hide();
                    $(`[name="ThirdPartyName"]`).attr('required', false).val(thirdPartyName);
                }
            })
            // ----- END CHANGE THIRD PARTY -----


            // ----- CHANGE MAIN COMPLEXITY -----
            $(document).on('change', `input[type=checkbox].mainComplexity`, function() {
                let isChecked = this.checked;
                let $table = $(this).closest('tr').find('table');
                let hasChecked = $table.find(`[type=checkbox].subComplexity:checked`).length;
                if (!isChecked) {
                    $table.find(`[type=checkbox].subComplexity`).prop('checked', false);
                } else {
                    !hasChecked && $table.find(`[type=checkbox].subComplexity`).prop('checked', true);
                }
            })
            // ----- END CHANGE MAIN COMPLEXITY -----


            // ----- CHANGE SUB COMPLEXITY -----
            $(document).on('change', `[type=checkbox].subComplexity`, function() {
                let $table = $(this).closest('table');
                let hasCheck = $table.find(`[type=checkbox].subComplexity:checked`).length;
                $table.closest('tr').find(`input[type=checkbox].mainComplexity`).prop('checked', hasCheck)
                    .trigger('change');
            })
            // ----- END CHANGE SUB COMPLEXITY -----

            // ----- PROPOSAL SELECT STATUS -----
            $(document).on('change', `[name="ProposalStatus"]`, function() {
                let ProposalStatus = $(this).val();
                if (ProposalStatus == 1) {
                    $('#AttachmentDisplay').show()
                } else {
                }
            })
            // ----- END PROPOSAL SELECT STATUS -----

            // ----- PROPOSAL SELECT STATUS -----
            $(document).on('change', `[name="FileProposal[]"]`, function() {
                let hasFile = $(this).length;
                console.log(hasFile)
                if (hasFile > 0) {
                    $('#DateSignedDisplay').show()
                    // $(`[name="ThirdPartyId"]`).attr('required', false).val('');
                    // $(`[name="ThirdPartyName"]`).attr('required', false).val('');
                } else {
                    $('#DateSignedDisplay').show()
                    // $(`[name="ThirdPartyId"]`).attr('required', true).trigger('change');
                    // $('#isCapableDisplay').show();

                    // if (ProposalStatus == 0) {
                    //     $('.checkThirdParty').prop('checked', true).trigger('change');
                    // } else {
                    //     $('.checkThirdParty').prop('checked', false).trigger('change');
                    // }
                }
            })
            // ----- END PROPOSAL SELECT STATUS -----


            // ----- DELETE TABLE ROW -----
            $(document).on('click', '.btnDeleteRow', function() {
                let hasData = $(this).closest('table').find('tbody tr').length > 1;
                if (hasData) {
                    let $parent = $(this).closest('tr');
                    $parent.fadeOut(500, function() {
                        $parent.remove();
                    })
                } else {
                    showToast('danger', 'Table must have at least one row data.');
                }
            })
            // ----- END DELETE TABLE ROW -----

        })
    </script>
@endsection
