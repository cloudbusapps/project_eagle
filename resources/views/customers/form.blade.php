@extends('layouts.app')

@section('content')
    <?php
    $CustomerName = $ProjectName = $Address = $Industry = $Type = $ContactPerson = $Product = $Notes = $Link = $Complex = '';
    $editable = '';
    if ($type === 'insert') {
        $todo = 'insert';
        $method = 'POST';
        $action = route('customers.save');
        $cancelRoute = route('customers');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } elseif ($type === 'edit') {
        $todo = 'update';
        $method = 'PUT';
        $action = route('customers.update', ['Id' => $Id]);
        $cancelRoute = route('customers', ['Id' => $Id]);
    
        if ($OvertimeRequest->Status == 1) {
            $editable = 'readonly';
            $button = '';
        } else {
            $button =
                '<a href="forms/customers/delete/' .
                $Id .
                '" class="btn btn-danger btnDeleteForm">Delete</a>
                                                                                                                                                                                <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        }
    
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
    } else {
        return redirect()->back();
    }
    ?>

    <style>
        #customerForm fieldset:not(:first-of-type) {
            display: none;
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
            width: 20%;
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

        #progressbar #Assessment:before {
            font-family: FontAwesome;
            content: "\f007";
        }

        #progressbar #Manhours:before {
            font-family: FontAwesome;
            content: "\f017";
        }

        #progressbar #ToRM:before {
            font-family: FontAwesome;
            content: "\f1d9";
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
            background: green;
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
                                <li id="Assessment"><strong>Assessment</strong></li>
                                <li id="Manhours"><strong>Manhours</strong></li>
                                <li id="ToRM"><strong>Coordinate to RM</strong></li>
                                <li id="Success"><strong>Success</strong></li>
                            </ul>

                            {{-- 1 FIELDSET --}}
                            <fieldset>



                                <div class="profile-overview">


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
                                            <select required select2 name="Product" id="Product" class="form-select"
                                                id="floatingSelect">
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
                                            <select required select2 name="Type" id="Type" class="form-select"
                                                id="floatingSelect">
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="1">Deployment</option>
                                                <option value="2">Enhancement</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Notes <code>*</code></label>
                                        <div class="col-sm-10">
                                            <textarea {{ $editable }} style="height: 82px;" required type="text" class="form-control" name="Reason"
                                                id="Notes" placeholder="Notes">{{ old('Notes') ?? $Notes }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Link <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input {{ $editable }} value="{{ old('Link') ?? $Link }}" required
                                                type="text" class="form-control" name="Link" id="Link"
                                                placeholder="Link">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Is Complex? <code>*</code></label>
                                        <div class="col-sm-10">

                                            {{-- <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                    name="IsComplex[1]" value="2">
                                                <label class="form-check-label" for="inlineCheckbox2">No</label>
                                            </div> --}}
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                                    name="IsComplex[1]" value="1">
                                                <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 label">Task <code>*</code></label>
                                    <div class="col-sm-10">
                                        <select required select2 name="UsersId" id="UsersIdSelect" class="form-select"
                                            id="floatingSelect">
                                            <option value="" selected disabled>Select Task</option>
                                            @if (count($data) > 0)
                                                @foreach ($data as $task)
                                                    <option value="{{ $task->Id }}">
                                                        {{ $task->Title . ' ' . $task->Title }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div> --}}
                                </div>

                                <input type="button" name="next" class="btn btn-secondary next action-button"
                                    value="Save" />
                            </fieldset>
                            {{-- <div class="button-footer text-end">
                                <a href="{{ $cancelRoute }}" class="btn btn-secondary">Cancel</a>

                                <?= $button ?>
                            </div> --}}
                            {{-- 1 FIELDSET --}}
                            <fieldset>



                                <div class="profile-overview">

                                    <div class="text-danger fw-bold">Please do an assessment and indicate the manhours of the requirement.</div>


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
                                            <input {{ $editable }} value="{{ old('Industry') ?? $Industry }}"
                                                required type="text" class="form-control" name="Industry"
                                                id="Industry" placeholder="Industry">
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
                                        <label for="inputText" class="col-sm-2 label">Contact Person
                                            <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input {{ $editable }}
                                                value="{{ old('ContactPerson') ?? $ContactPerson }}" required
                                                type="text" class="form-control" name="ContactPerson"
                                                id="ContactPerson" placeholder="Contact Person">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Product <code>*</code></label>
                                        <div class="col-sm-10">
                                            <select required select2 name="Product" id="Product" class="form-select"
                                                id="floatingSelect">
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
                                            <select required select2 name="Type" id="Type" class="form-select"
                                                id="floatingSelect">
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="1">Deployment</option>
                                                <option value="2">Enhancement</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Notes <code>*</code></label>
                                        <div class="col-sm-10">
                                            <textarea {{ $editable }} style="height: 82px;" required type="text" class="form-control" name="Reason"
                                                id="Notes" placeholder="Notes">{{ old('Notes') ?? $Notes }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Link <code>*</code></label>
                                        <div class="col-sm-10">
                                            <input {{ $editable }} value="{{ old('Link') ?? $Link }}" required
                                                type="text" class="form-control" name="Link" id="Link"
                                                placeholder="Link">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Is Complex? <code>*</code></label>
                                        <div class="col-sm-10">

                                            {{-- <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                                    name="IsComplex[1]" value="2">
                                                <label class="form-check-label" for="inlineCheckbox2">No</label>
                                            </div> --}}
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                                    name="IsComplex[1]" value="1">
                                                <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 label">Select Consultant
                                            <code>*</code></label>
                                        <div class="col-sm-10">
                                            <select multiple required select2 name="UsersId" id="UsersIdSelect"
                                                class="form-select" id="floatingSelect">
                                               
                                                @if (count($data) > 0)
                                                    @foreach ($data as $user)
                                                        <option value="{{ $user->Id }}">
                                                            {{ $user->FirstName . ' ' . $user->LastName }}
                                                        </option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <input type="button" name="next" class="btn btn-secondary next action-button"
                                    value="Save" />
                            </fieldset>
                            {{-- <div class="button-footer text-end">
    <a href="{{ $cancelRoute }}" class="btn btn-secondary">Cancel</a>

    <?= $button ?>
</div> --}}



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

            // PLACEHOLDER FOR SELECT2
            $(".form-select").select2({
                placeholder: "Select one or more consultant"
            });

            // PROGRESS BAR

            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;

            $(".next").click(function() {

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            });

            $(".previous").click(function() {

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //Remove class active
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();

                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            });



            // CHECKBOX
            $("input:checkbox").on('click', function() {
                // in the handler, 'this' refers to the box clicked on
                var $box = $(this);
                if ($box.is(":checked")) {
                    // the name of the box is retrieved using the .attr() method
                    // as it is assumed and expected to be immutable
                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                    // the checked state of the group/box on the other hand will change
                    // and the current value is retrieved using .prop() method
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                    const value = $box.prop("checked", true).val();
                    if (value == 1) {
                        $('#Link').attr('disabled', true)
                    } else {
                        $('#Link').attr('disabled', false)
                    }
                } else {
                    $('#Link').attr('disabled', false)
                    $box.prop("checked", false);
                }
            });



            // ----- SUBMIT FORM -----
            $(document).on('submit', '#formOvertimeRequest', function(e) {
                let isValidated = $(this).attr('validated') == "true";
                let todo = $(this).attr('todo');

                if (!isValidated) {
                    e.preventDefault();

                    let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new overtime request?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this overtime request?</b>
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
                                    $('#formOvertimeRequest').attr('validated', 'true')
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
                    <b class="mt-4">Are you sure you want to delete this overtime request?</b>
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
