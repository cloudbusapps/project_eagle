@extends('layouts.app')

@section('content')

<?php
    if (isset($data) && !empty($data)) {
        $todo   = "update";
        $method = "PUT";
        $action = route('timekeeping.update', ['Id' => $data['Id']]);
        $button = '<a href="'. route('timekeeping.delete', ['Id' => $data['Id']]) .'" class="btn btn-danger btnDeleteForm">Delete</a>
        <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
    } else {
        $todo   = "insert";
        $method = "POST";
        $action = route('timekeeping.save');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    }

    $Others = config('constant.ID.PROJECTS.OTHERS');
?>

<main id="main" class="main" projects="{{ $projects }}" others="{{ $Others }}">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Project Utilization</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('timekeeping') }}">Timekeeping</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>  

    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <form action="{{ $action }}" method="POST" id="formTimekeeping" validated="false" todo="{{ $todo }}">
                        @csrf
                        @method($method)
    
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

                        <div class="card">
                            <div class="card-body pt-3">
                                <div class="row my-3">
                                    <label for="Date" class="col-sm-2">Date <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input type="text" 
                                            class="form-control" 
                                            name="Date" 
                                            cdaterangepicker
                                            required
                                            value="{{ date('F d, Y', strtotime(isset($data->Date) ? $data->Date : now())) }}">
                                    </div>
                                </div>
                                <div class="row mt-4 mb-3">
                                    <div class="card">
                                        <div class="card-header py-3">
                                            <h5 class="mb-0">DETAILS</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover nowrap" id="tableTimekeepingDetails">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Hours</th>
                                                            <th>Project Name</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    @if (isset($details) && count($details))
                                                        @foreach ($details as $dt)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btnDeleteRow"><i class="bi bi-trash"></i></button>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group mb-0">
                                                                        <input type="time" class="form-control" name="StartTime[]" value="{{ $dt->StartTime }}" required>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group mb-0">
                                                                        <input type="time" class="form-control" name="EndTime[]" value="{{ $dt->EndTime }}" required>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="hours">{{ $dt->Hours }}</div>
                                                                    <input type="hidden" name="Hours[]" value="{{ $dt->Hours }}" value="0.00">
                                                                </td>
                                                                <td>
                                                                    <div class="form-group mb-0">
                                                                        <select class="form-control" name="ProjectId[]" value="{{ $dt->ProjectId }}"
                                                                            select2
                                                                            required
                                                                            style="width: 100%;">
                                                                            <option value="" selected disabled>Select Project Name</option>

                                                                            @if (isset($projects) && count($projects))
                                                                                @foreach ($projects as $dt2)
                                                                                    <option value="{{ $dt2->Id }}"
                                                                                        {{ $dt->ProjectId == $dt2->Id ? 'selected' : '' }}>{{ $dt2->Name }}</option>
                                                                                @endforeach
                                                                            @endif

                                                                            <option value="{{ $Others }}"
                                                                                {{ $dt->ProjectId == $Others ? 'selected' : '' }}>Others</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group mb-0">
                                                                        <textarea name="Description[]" rows="3"
                                                                            class="form-control"
                                                                            style="resize: none;"
                                                                            required>{{ $dt->Description }}</textarea>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-outline-danger btnDeleteRow"><i class="bi bi-trash"></i></button>
                                                            </td>
                                                            <td>
                                                                <div class="form-group mb-0">
                                                                    <input type="time" class="form-control" name="StartTime[]" required>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group mb-0">
                                                                    <input type="time" class="form-control" name="EndTime[]" required>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="hours">0.00</div>
                                                                <input type="hidden" name="Hours[]" value="0.00">
                                                            </td>
                                                            <td>
                                                                <div class="form-group mb-0">
                                                                    <select class="form-control" name="ProjectId[]"
                                                                        select2
                                                                        required
                                                                        style="width: 100%;">
                                                                        <option value="" selected disabled>Select Project Name</option>

                                                                        @if (isset($projects) && count($projects))
                                                                            @foreach ($projects as $dt)
                                                                                <option value="{{ $dt->Id }}">{{ $dt->Name }}</option>
                                                                            @endforeach
                                                                        @endif

                                                                        <option value="{{ $Others }}">Others</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group mb-0">
                                                                    <textarea name="Description[]" rows="3"
                                                                        class="form-control"
                                                                        style="resize: none;"
                                                                        required></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <button class="btn btn-outline-primary btnAddRow" type="button">
                                                    <i class="fas fa-plus"></i> Add Row
                                                </button>
                                                <div>
                                                    <b>TOTAL HOURS: </b>
                                                    <span class="totalHours">{{ isset($data->TotalHours) ? $data->TotalHours : 0.00 }}</span>
                                                </div>
                                                <input type="hidden" name="TotalHours" value="{{ isset($data->TotalHours) ? $data->TotalHours : 0 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('timekeeping') }}" class="btn btn-secondary">Cancel</a>
                                <?= $button ?>
                            </div>
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {
        
        // ----- GLOBAL VARIABLES -----
        let projectList = JSON.parse($('main').attr('projects') ?? []);
        let others      = $('main').attr('others');
        // ----- END GLOBAL VARIABLES -----


        // ----- INIT DATERANGEPICKER -----
        function cInitDateRangePicker(minDate = null, maxDate = null) {
            $(`[cdaterangepicker]`).daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minDate,
                maxDate,
                locale: {
                    format: 'MMMM DD, YYYY'
                }
            });
        }
        cInitDateRangePicker(null, moment());
        // ----- END INIT DATERANGEPICKER -----


        // ----- BUTTON ADD ROW -----
        $(document).on('click', '.btnAddRow', function() {
            let projectOptions = '';
            projectList.map(dt => {
                let { Id, Name } = dt;
                projectOptions += `<option value="${Id}">${Name}</option>`;
            })

            let html = `
            <tr>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btnDeleteRow"><i class="bi bi-trash"></i></button>
                </td>
                <td>
                    <div class="form-group mb-0">
                        <input type="time" class="form-control" name="StartTime[]" required>
                    </div>
                </td>
                <td>
                    <div class="form-group mb-0">
                        <input type="time" class="form-control" name="EndTime[]" required>
                    </div>
                </td>
                <td class="text-center">
                    <div class="hours">0.00</div>
                    <input type="hidden" name="Hours[]" value="0.00">
                </td>
                <td>
                    <div class="form-group mb-0">
                        <select class="form-control" name="ProjectId[]"
                            select2
                            required>
                            <option value="" selected disabled>Select Project Name</option>
                            ${projectOptions}
                            <option value="${others}">Others</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group mb-0">
                        <textarea name="Description[]" rows="3"
                            class="form-control"
                            style="resize: none;"
                            required></textarea>
                    </div>
                </td>
            </tr>`;

            $('#tableTimekeepingDetails tbody').append(html);
            initSelect2();
        })
        // ----- END BUTTON ADD ROW -----


        // ----- DELETE TABLE ROW -----
        $(document).on('click', '.btnDeleteRow', function() {
            let hasData = $(this).closest('#tableTimekeepingDetails').find('tbody tr').length > 1;
            if (hasData) {
                let $parent = $(this).closest('tr');
                $parent.fadeOut(500, function() {
                    $parent.remove();
                    updateTotalHours();
                })
            } else {
                showToast('danger', 'Table must have at least one row data.');
            }
        })
        // ----- END DELETE TABLE ROW -----


        // ----- UPDATE TOTAL HOURS -----
        function updateTotalHours() {
            let totalHours = 0;
            $(`[name="Hours[]"]`).each(function() {
                totalHours += parseFloat($(this).val()?.replaceAll(',', ''));
            })
            totalHours = totalHours > 0 ? totalHours.toFixed(2) : 0;
            $(`[name="TotalHours"]`).val(totalHours);
            $(`.totalHours`).text(totalHours);
        }
        // ----- END UPDATE TOTAL HOURS -----


        // ----- SELECT TIME -----
        $(document).on('change', `[type="time"]`, function() {
            let $parent = $(this).closest('tr');
            let StartTime = $parent.find(`[name="StartTime[]"]`).val();
            let EndTime   = $parent.find(`[name="EndTime[]"]`).val();
            if (StartTime && EndTime) {
                let hours = moment.duration(moment(`2021-01-01 `+EndTime).diff(moment(`2021-01-01 `+StartTime))).asHours();
                    hours = hours > 0 ? hours : 0;

                $parent.find('.hours').text(hours.toFixed(2));
                $parent.find(`[name="Hours[]"]`).val(hours.toFixed(2));
            }

            updateTotalHours();
        })
        // ----- END SELECT TIME -----


        // ----- SUBMIT FORM -----
        $(document).on('submit', '#formTimekeeping', function(e) {
            let isValidated = $(this).attr('validated') == "true";
            let todo        = $(this).attr('todo');

            if (!isValidated) {
                e.preventDefault();

                let content = todo == 'insert' ? `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                    <b class="mt-4">Are you sure you want to add new timekeeping?</b>
                </div>` : `
                <div class="d-flex justify-content-center align-items-center flex-column text-center">
                    <img src="/assets/img/modal/update.svg" class="py-1" height="150" width="150">
                    <b class="mt-4">Are you sure you want to update this timekeeping?</b>
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
                            action: function(){
                                $('#formTimekeeping').attr('validated', 'true').submit();
        
                                confirmation.buttons.yes.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
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
                    <b class="mt-4">Are you sure you want to delete this timekeeping?</b>
                </div>`,
                buttons: {
                    no: {
                        btnClass: 'btn-default',
                    },
                    yes: {
                        btnClass: 'btn-blue',
                        keys: ['enter'],
                        action: function() {
    
                            confirmation.buttons.yes.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
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