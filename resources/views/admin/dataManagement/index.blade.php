@extends('layouts.app')

@section('content')

<main id="main" class="main">

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

            @if ($errors->any())
            @foreach ($errors->all() as $err)
            <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                <i class="bi bi-exclamation-octagon me-1"></i>
                <?= $err ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endforeach
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
                    <?= Session::get('danger') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body pt-3">
                    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTabJustified" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array(Session::get('tab'), ['Import', null]) ? 'active' : '' }}" id="import-tab" data-bs-toggle="tab" data-bs-target="#import-content" type="button" role="tab">Import</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ in_array(Session::get('tab'), ['Export']) ? 'active' : '' }}" id="export-tab" data-bs-toggle="tab" data-bs-target="#export-content" type="button" role="tab">Export</button>
                        </li>

                        
                    </ul>
                    <div class="tab-content pt-4 px-4">
                        <div class="tab-pane fade {{ in_array(Session::get('tab'), ['Import', null]) ? 'show active' : '' }}" id="import-content" role="tabpanel">
                            <table class="table table-striped table-hover" id="tableImport">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>MODULE / RELATED</th>
                                        <th>TEMPLATE</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($moduleData) && count($moduleData))
                                    <?php $count = 0; ?>
                                        @foreach ($moduleData as $index => $dt)
                                            @if ($dt['TableName'])
                                                <tr>
                                                    <td>{{ ++$count }}</td>
                                                    <td>{{ $dt['Title'] }}</td>
                                                    <td>
                                                        <a href="{{ route('dataManagement.moduleTemplate', ['TableName' => $dt['TableName'], 'FileName' => str_replace(' ', '', $dt['Title']).'.csv']) }}" 
                                                            target="_blank"
                                                            class="mx-2" 
                                                            style="font-style: italic; text-decoration: underline;">
                                                            {{ str_replace(' ', '', $dt['Title']).'.csv' }}
                                                        </a>
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-primary btnImport"
                                                            TableName="{{ $dt['TableName'] }}">Import CSV</button>
                                                    </td>
                                                </tr>
                                            @endif

                                            @if (isset($dt['Related']) && count($dt['Related']))
                                                @foreach ($dt['Related'] as $dt2)
                                                    @if ($dt2['TableName'])
                                                        <tr>
                                                            <td>{{ ++$count }}</td>
                                                            <td>{{ $dt['Title'] .' / '. $dt2['Title'] }}</td>
                                                            <td>
                                                                <a href="{{ route('dataManagement.moduleTemplate', ['TableName' => $dt2['TableName'], 'FileName' => str_replace(' ', '', $dt['Title'].$dt2['Title']).'.csv']) }}" 
                                                                    target="_blank"
                                                                    class="mx-2" style="font-style: italic; text-decoration: underline;"
                                                                    TableName="{{ $dt2['TableName'] }}">
                                                                    {{ str_replace(' ', '', $dt['Title'].$dt2['Title']).'.csv' }}
                                                                </a>
                                                            </td>
                                                            <td class="text-end">
                                                                <button class="btn btn-primary btnImport"
                                                                    TableName="{{ $dt2['TableName'] }}">Import CSV</button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade {{ in_array(Session::get('tab'), ['Export']) ? 'show active' : '' }}" id="export-content" role="tabpanel">
                            <table class="table table-striped table-hover" id="tableExport">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>MODULE / RELATED</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($moduleData) && count($moduleData))
                                    <?php $count = 0; ?>
                                        @foreach ($moduleData as $index => $dt)
                                            @if ($dt['TableName'])
                                                <tr>
                                                    <td>{{ ++$count }}</td>
                                                    <td>{{ $dt['Title'] }}</td>
                                                    <td>
                                                        <a href="{{ route('dataManagement.exportModuleData', ['TableName' => $dt['TableName'], 'FileName' => str_replace(' ', '', $dt['Title'])]) }}" 
                                                            target="_blank"
                                                            class="mx-2 btn btn-outline-success" >
                                                            Export CSV
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif

                                            @if (isset($dt['Related']) && count($dt['Related']))
                                                @foreach ($dt['Related'] as $dt2)
                                                    @if ($dt2['TableName'])
                                                        <tr>
                                                            <td>{{ ++$count }}</td>
                                                            <td>{{ $dt['Title'] .' / '. $dt2['Title'] }}</td>
                                                            <td>
                                                                <a href="{{ route('dataManagement.exportModuleData', ['TableName' => $dt2['TableName'], 'FileName' => str_replace(' ', '', $dt['Title'].$dt2['Title'])]) }}" 
                                                                    target="_blank"
                                                                    class="mx-2 btn btn-outline-success" 
                                                                    TableName="{{ $dt2['TableName'] }}">
                                                                    Export CSV
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
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

        let tableImport = $('#tableImport')
            .css({ "min-width": "99%" })
            .removeAttr("width")
            .DataTable({
                scrollX: true,
                scrollY: '400px',
                scrollCollapse: true,
                columnDefs: [
                    { targets: 0,  width: 50  },
                    { targets: 1,  width: 300 },
                    { targets: 2,  width: 150 },
                    { targets: 3,  width: 150 },
                ],
            });

        let tableExport = $('#tableExport')
            .css({ "min-width": "99%" })
            .removeAttr("width")
            .DataTable({
                scrollX: true,
                scrollY: '400px',
                scrollCollapse: true,
                columnDefs: [
                    { targets: 0,  width: 50  },
                    { targets: 1,  width: 300 },
                    { targets: 2,  width: 150 },
                ],
            });

        
        // ----- CLICK TAB -----
        $(document).on('click', '.nav-link', function() {
            tableImport.columns.adjust().draw();
            tableExport.columns.adjust().draw();
        })
        // ----- END CLICK TAB -----

        
        // ----- BUTTON IMPORT -----
        $(document).on('click', '.btnImport', function() {
            let TableName = $(this).attr('TableName');
            
            $.ajax({
                method: 'GET',
                url: '/admin/dataManagement/importModuleData',
                data: { TableName },
                async: false,
                dataType: 'html',
                success: function(data) {
                    let html = data;

                    $('#custom-modal .modal-title').text('Import CSV');
                    $('#custom-modal .modal-body').html(html);
                    $('#custom-modal').modal('show');
                }
            })
        })
        // ----- END BUTTON IMPORT -----

        
        // ----- SELECT IMPORT FILE -----
        $(document).on('change', `[name="File"]`, function() {
            let TableName = $(`[name="TableName"]`).val()

            let formData = new FormData();
            formData.append('TableName', TableName);
            formData.append('File', this.files[0]);

            $.ajax({
                method: 'POST',
                url: '/admin/dataManagement/validateModuleHeader',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                async: false,
                dataType: 'json',
                success: function(data) {
                    let html = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> No errors found.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                    let valid = 'true';

                    if (data && data.length) {
                        html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">`;
                        data.map(err => {
                            html += `<div><i class="bi bi-exclamation-octagon me-1"></i> ${err}</div>`;
                        })
                        html += `<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        valid = 'false';
                    }
                    $(`button[type="submit"]`).attr('valid', valid);
                    $('#invalidFeedback').html(html);
                }
            })
        })
        // ----- END SELECT IMPORT FILE -----


        // ----- FORM SUBMIT -----
        $(document).on('submit', '#formImport', function(e) {
            if ($(`button[type="submit"]`).attr('valid') == 'false') {
                e.preventDefault();
                showToast('danger', "Please select correct file.");
            }
        })
        // ----- END FORM SUBMIT -----
    
    })

</script>

@endsection