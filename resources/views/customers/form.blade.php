@extends('layouts.app')

@section('content')
    <?php
    $PreviewStatus = '';
    $CustomerName = $DSWStatus = $Status = $ProjectName = $Address = $Industry = $Type = $ContactPerson = $Product = $Notes = $Link = $Complex = '';
    $editable = '';
    if ($type === 'insert') {
        $Status = 0;
        $todo = 'insert';
        $method = 'POST';
        $action = route('customers.save');
        $cancelRoute = route('customers');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } elseif ($type === 'edit') {
        // INITIALIZATION
        $CustomerName = !empty($data) ? $data['CustomerName'] ?? '' : '';
        $Address = !empty($data) ? $data['Address'] ?? '' : '';
        $ProjectName = !empty($data) ? $data['ProjectName'] ?? '' : '';
        $Industry = !empty($data) ? $data['Industry'] ?? '' : '';
        $Link = !empty($data) ? $data['Link'] ?? '' : '';
        $Type = !empty($data) ? $data['Type'] ?? '' : '';
        $ContactPerson = !empty($data) ? $data['ContactPerson'] ?? '' : '';
        $Product = !empty($data) ? $data['Product'] ?? '' : '';
        $Notes = !empty($data) ? $data['Notes'] ?? '' : '';
        $Complex = !empty($data) ? $data['Complex'] ?? '' : '';
        $Status = !empty($data) ? $data['Status'] ?? '' : '';
        $DSWStatus = !empty($data) ? $data['DSWStatus'] ?? '' : '';
        $button = '<button type="submit" class="btn btn-primary btnUpdateForm">Submit</button>';
        // <a href="forms/customers/delete/' .
        // $Id .
        // '" class="btn btn-danger btnDeleteForm">Delete</a>
        $todo = 'update';
        $method = 'PUT';
        $action = route('customers.update', ['Id' => $Id, 'Status' => $Status]);
        $cancelRoute = route('customers', ['Id' => $Id]);
    } else {
        return redirect()->back();
    }
    ?>

    <style>
        :root {
            --backgroundColor: lightgrey;
            --borderSize: 40px;
            --active-color: {{ $DSWStatus == 4 ? 'green' : '#eed202' }};
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
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey;
        }

        #progressbar .active {
            color: #000000;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 11%;
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
            content: "\f0ad";
        }

        #progressbar #ProjectInclusion:before {
            font-family: FontAwesome;
            content: "\f0ad";
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
            background: {{ $Status == 8 ? 'green' : '#eed202' }};
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
                <div class="card">

                    <div class="card-body">

                        <form validated="false" id="customerForm" class="row g-3" action="{{ $action }}"
                            todo="{{ $todo }}" method="POST">
                            @csrf
                            @method($method)
                            <div class="card-title">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        @foreach ($errors->all() as $error)
                                            <div>
                                                <i class="bi bi-exclamation-octagon me-1"></i>
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
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
                            </div>
                            <ul id="progressbar">
                                <li class="active" id="Information"><strong>Information</strong></li>
                                <li id="Complexity"><strong>Complexity</strong></li>
                                {{-- IF COMPLEX --}}
                                <li id="DSW"><strong>Deployment Strategy Workshop</strong></li>
                                {{-- END COMPLEX --}}
                                <li id="BP"><strong>Business Process</strong></li>
                                <li id="RaS"><strong>Requirements and Solutions</strong></li>
                                <li id="ProjectInclusion"><strong>Project Phase</strong></li>
                                <li id="Assessment"><strong>Assessment</strong></li>
                                <li id="Proposal"><strong>Proposal</strong></li>
                                <li id="Success"><strong>Success</strong></li>
                            </ul>

                            <div class="profile-overview">
                                @if ($Status == 0)
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Customer Name <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input {{ $editable }} value="{{ old('CustomerName') ?? $CustomerName }}"
                                                required type="text" class="form-control" name="CustomerName"
                                                id="CustomerName" placeholder="Customer Name">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Industry <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input {{ $editable }} value="{{ old('Industry') ?? $Industry }}" required
                                                type="text" class="form-control" name="Industry" id="Industry"
                                                placeholder="Industry">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Address <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input {{ $editable }} value="{{ old('Address') ?? $Address }}" required
                                                type="text" class="form-control" name="Address" id="Address"
                                                placeholder="Address">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Contact Person <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input {{ $editable }}
                                                value="{{ old('ContactPerson') ?? $ContactPerson }}" required
                                                type="text" class="form-control" name="ContactPerson" id="ContactPerson"
                                                placeholder="Contact Person">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Product <code>*</code></label>
                                        <div class="col-sm-10">
                                            <select required select2 name="Product" id="Product" class="form-select">
                                                <option value="" selected disabled>Select Product</option>
                                                <option value="1">Sales</option>
                                                <option value="2">Service</option>
                                                <option value="3">Marketing</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Type <code>*</code></label>
                                        <div class="col-sm-10">
                                            <select required select2 name="Type" id="Type" class="form-select">
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="1">Deployment</option>
                                                <option value="2">Enhancement</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Notes <code>*</code></label>
                                        <div class="col-sm-10">
                                            <textarea {{ $editable }} style="height: 82px;" required type="text" class="form-control" name="Notes"
                                                id="Notes" placeholder="Notes">{{ old('Notes') ?? $Notes }}</textarea>
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 1)
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Not Complex </label>

                                        <div class="col-sm-10">

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="notComplex"
                                                    name="complexCheckBox">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Complex </label>

                                        <div class="col-sm-10">
                                            <input type="checkbox" class="custom-control-input" id="isComplex"
                                                name="complexCheckBox">
                                            <table style="display: none" id="mainTable" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" scope="col">Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($complexities as $index => $complexity)
                                                        <tr>
                                                            <td>{{ $complexity['Title'] }}

                                                                @if (count($complexity['Details']) > 0)
                                                                    <table class="table table-bordered">
                                                                        @foreach ($complexity['Details'] as $SubDetail)
                                                                            <tr>
                                                                                <td>
                                                                                    <li>{{ $SubDetail['Title'] }}</li>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="custom-control custom-checkbox">
                                                                                        <input type="checkbox"
                                                                                            class="custom-control-input"
                                                                                            id="subCheck"
                                                                                            name="checkbox">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="mainCheck" name="checkbox">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 2)
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Current Progress for DSW
                                            <code>*</code></label>
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
                                                {{-- <div class="col-sm-2 dwsCon">
                                                    <div class="divSquare"></div>
                                                    <strong>Completed Sol Doc</strong>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 3)
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Attachment
                                            <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="file" id="formFileMultiple" multiple />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Notes <code>*</code></label>
                                        <div class="col-sm-10">
                                            <textarea {{ $editable }} style="height: 82px;" required type="text" class="form-control" name="Notes"
                                                id="Notes" placeholder="Notes">{{ old('Notes') ?? $Notes }}</textarea>
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 4)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">In-Scope</h5>
                                            <div id="tableContainer">
                                                <table id="inScopeTable" cellpadding="0" cellspacing="0"
                                                    class="table table-bordered">
                                                    <thead>
                                                        <th scope="col">Requirement List</th>
                                                        <th scope="col">Description</th>
                                                        <th scope="col">Salesforce Modules</th>
                                                        <th scope="col">Solutions Overview</th>
                                                        <th scope="col">Assumptions</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input></td>
                                                            <td>
                                                                <textarea></textarea>
                                                            </td>
                                                            <td>
                                                                <textarea></textarea>
                                                            </td>
                                                            <td>
                                                                <textarea></textarea>
                                                            </td>
                                                            <td>
                                                                <textarea></textarea>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <button class="btn btn-outline-primary btnAddRow" type="button">
                                                    <i class="fas fa-plus"></i> Add Row
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Limitations</h5>
                                            <div id="tableContainer">
                                                <table id="outScopeTable" cellpadding="0" cellspacing="0"
                                                    class="table table-bordered">
                                                    <thead>
                                                        <th scope="col">Out of Scope</th>
                                                        <th scope="col">Comments</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <textarea></textarea>
                                                            </td>
                                                            <td>
                                                                <textarea></textarea>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 5)
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Inclusions <code>*</code></label>
                                        <div class="col-sm-10">
                                            <table id="" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" scope="col">Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($requirements as $index => $requirement)
                                                        <tr>
                                                            <td>{{ $requirement->Name }}

                                                                @if ($requirement->hasDetails > 0)
                                                                    <table class="table table-bordered">
                                                                        @foreach ($subRequirements as $SubDetail)
                                                                            @if ($requirement->Id == $SubDetail->RequirementId)
                                                                                <tr>
                                                                                    <td>
                                                                                        <li>{{ $SubDetail->Details }}</li>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input"
                                                                                                id="subCheck"
                                                                                                name="checkbox">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    </table>
                                                                @endif

                                                            </td>

                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="mainCheck" name="checkbox">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 6)
                                    <div class="mb-3" id="RaSTable">
                                        <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                            <thead>
                                                <th scope="col">Requirement List</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Salesforce Features</th>
                                                <th scope="col">Solutions Overview</th>
                                                <th scope="col">Manhour Assessment</th>
                                                <th scope="col">Out of Scope</th>
                                                <th scope="col">Assumptions/Comments</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input></td>
                                                    <td>
                                                        <textarea></textarea>
                                                    </td>
                                                    <td>
                                                        <textarea></textarea>
                                                    </td>
                                                    <td>
                                                        <textarea></textarea>
                                                    </td>
                                                    <td>
                                                        <textarea></textarea>
                                                    </td>
                                                    <td>
                                                        <textarea></textarea>
                                                    </td>
                                                    <td>
                                                        <textarea></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="LeaveType" class="col-sm-2">Assigned Consultant(s)</label>
                                        <div class="col-sm-10">
                                            <select name="LeaveTypeId" id="LeaveTypeId" class="form-select" select2
                                                required multiple>
                                                <option value="" selected disabled>Select Consultant(s)</option>

                                                @foreach ($users as $user)
                                                    <option value="">
                                                        {{ $user->FirstName . ' ' . $user->LastName }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 7)
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Notes <code>*</code></label>
                                        <div class="col-sm-10">
                                            <textarea {{ $editable }} style="height: 82px;" required type="text" class="form-control" name="Notes"
                                                id="Notes" placeholder="Notes">{{ old('Notes') ?? $Notes }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Attachment
                                            <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="file" id="formFileMultiple" multiple />
                                        </div>
                                    </div>
                                @endif
                                @if ($Status == 8)
                                @endif
                            </div>
                            <div class="button-footer text-end">
                                <a href="{{ $cancelRoute }}" class="btn btn-secondary">Cancel</a>
                                <?= $button ?>
                            </div>



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

            // ----- BUTTON ADD ROW -----
            $(document).on('click', '.btnAddRow', function() {
                let html = `
                <tr>
            <td><input name="list"></td>
                                   <td>
                                      <textarea></textarea>
                                       </td>
                                   <td>
                            <textarea></textarea>
                          <td>
            <textarea></textarea>
                 </td>
                   <td>
                   <textarea></textarea>
                </td>
                 </tr>`;

                $('#inScopeTable tbody').append(html);
                initSelect2();
            })
            // ----- END BUTTON ADD ROW -----

            // CHECKBOX IF COMPLEX OR NOT

            $(document).on('click', 'input[name="complexCheckBox"]', function() {
                $('input[name="complexCheckBox"]').not(this).prop('checked', false);
                checkDisabled();
            });

            function checkDisabled() {
                const table = $('#mainTable');
                $('#isComplex').prop('checked') ? table.show() : table.hide();
            }



            // PLACEHOLDER FOR SELECT2
            $(".form-select").select2({
                placeholder: "Select one or more consultant"
            });

            // PROGRESS BAR
            const status = "{{ $Status }}";
            for (let i = 0; i <= status; i++) {
                $("#progressbar li").eq(i).addClass("active");
            }
            const dswStatus = "{{ $DSWStatus }}";
            for (let i = 0; i <= dswStatus; i++) {
                $(".divSquare").eq(i).addClass("activeStatus");
            }



            // CHECKBOX
            $.extend($.expr[':'], {
                unchecked: function(obj) {
                    return ((obj.type == 'checkbox' || obj.type == 'radio') && !$(obj).is(
                        ':checked'));
                }
            });

            $("#mainTable input:checkbox").on('change', function() {
                $(this).closest("td").prev('td').find('input:checkbox').prop('checked', $(this).prop(
                    "checked"));

                for (var i = $('#mainTable').find('td').length - 1; i >= 0; i--) {
                    $('#mainTable').find('table:eq(' + i + ')').closest('tr').next('input:checkbox').prop(
                        'checked',
                        function() {
                            console.log(this)
                            return $(this).next('table').find('input:unchecked').length === 0 ?
                                true : false;
                        });
                }
            });





            // ----- SUBMIT FORM -----
            $(document).on('submit', '#customerForm', function(e) {

                let isValidated = $(this).attr('validated') == "true";
                let todo = $(this).attr('todo');

                // FOR COMPLEXITY PART
                if (status == 1) {

                    if ($('input[name="complexCheckBox"]:not(:checked)').length == 2) {
                        showToast('danger', 'Choose the complexity of the Project');
                    }
                    if ($('input[name="checkbox"]:checked').length === 0 && $('#isComplex').prop(
                            'checked')) {
                        showToast('danger', 'Check atleast one(1) in the checklist');
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



            // ----- BUTTON DELETE FORM -----
            $(document).on('click', '.btnDeleteForm', function(e) {
                e.preventDefault();
                let href = $(this).attr('href');

                let confirmation = $.confirm({
                    title: false,
                    content: `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/delete.svg" class="py-5" height="200" width="200">
                    <b class="mt-4">Are you sure you want to delete this customer?</b>
                </div>`,
                    buttons: {

                        no: {
                            btnClass: 'btn-default',
                        },
                        yes: {
                            btnClass: 'btn-blue',
                            keys: ['enter'],
                            action: function() {

                                confirmation.buttons.yes.setText(
                                    `<span class="spinner-border spinner-border-sm"></span> Please wait...`
                                );
                                confirmation.buttons.yes.disable();
                                confirmation.buttons.no.hide();

                                window.location.replace(href);

                                return false;
                            }
                        },
                    }
                });
            })
            // ----- END BUTTON DELETE FORM -----

        })
    </script>
@endsection
