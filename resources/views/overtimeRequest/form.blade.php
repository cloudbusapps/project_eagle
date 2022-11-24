@extends('layouts.app')

@section('content')
    <?php
    $Agenda = $Date = $TimeIn = $TimeOut = $Reason = $editable = '';
    
    if ($type === 'insert') {
        $todo = 'insert';
        $method = 'POST';
        $action = route('overtimeRequest.save');
        $button = '<button type="submit" class="btn btn-primary btnSubmitForm">Save</button>';
    } elseif ($type === 'edit') {
        $todo = 'update';
        $method = 'PUT';
        $action = route('overtimeRequest.update', ['Id' => $Id]);
    
        if ($OvertimeRequest->Status == 1) {
            $editable = 'readonly';
            $button = '';
        } else {
            $button =
                '<a href="/overtimeRequest/delete/' .
                $Id .
                '" class="btn btn-danger btnDeleteForm">Delete</a>
                                    <button type="submit" class="btn btn-warning btnUpdateForm">Update</button>';
        }
    
        $Agenda = !empty($OvertimeRequest) ? $OvertimeRequest['Agenda'] ?? '' : '';
        $Date = !empty($OvertimeRequest) ? $OvertimeRequest['Date'] ?? '' : '';
        $TimeIn = !empty($OvertimeRequest) ? $OvertimeRequest['TimeIn'] ?? '' : '';
        $TimeOut = !empty($OvertimeRequest) ? $OvertimeRequest['TimeOut'] ?? '' : '';
        $Reason = !empty($OvertimeRequest) ? $OvertimeRequest['Reason'] ?? '' : '';
    } else {
        return redirect()->back();
    }
    ?>


    <main id="main" class="main">

        <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h4 class="mb-0">{{ $title }}</h4>
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('overtimeRequest') }}">Overtime Request</a></li>
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

                        <form validated="false" id="formOvertimeRequest" class="row g-3" action="{{ $action }}"
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

                            <div class="profile-overview">


                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 label">Agenda <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input {{ $editable }} value="{{ old('Agenda') ?? $Agenda }}" required type="text"
                                            class="form-control" name="Agenda" id="Agenda" placeholder="Agenda">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label label" for="floatingTextarea">Date
                                        <code>*</code></label>
                                    <div class="col-sm-10">
                                        <input {{ $editable }} value="{{ old('Date') ?? $Date }}" type="date" required
                                            class="form-control" name="Date" placeholder="Date" id="Date">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 label" for="floatingTextarea">Time In<code>*</code></label>
                                    <div class="col-sm-10">
                                        <input {{ $editable }} value="{{ old('TimeIn') ?? $TimeIn }}" required placeholder="Time In"
                                            name="TimeIn" id="TimeIn" type="time" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label label" for="floatingTextarea">Time
                                        Out<code>*</code></label>
                                    <div class="col-sm-10">
                                        <input {{ $editable }} value="{{ old('TimeOut') ?? $TimeOut }}" required placeholder="Time Out"
                                            name="TimeOut" id="TimeOut" type="time" class="form-control">

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 label">Reason <code>*</code></label>
                                    <div class="col-sm-10">
                                        <textarea {{ $editable }} style="height: 82px;" required type="text" class="form-control" name="Reason" id="Reason"
                                            placeholder="Reason">{{ old('Reason') ?? $Reason }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-footer text-end">
                                <a href="{{ route('overtimeRequest') }}" class="btn btn-secondary">Cancel</a>

                                <?= $button ?>
                            </div>

                    </div>

                    </form>
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
