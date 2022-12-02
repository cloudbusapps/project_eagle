@extends('layouts.app')

@section('content')

<main id="main" class="main" modules="{{ json_encode(getModuleData()) }}">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Setup</a></li>
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <table class="table table-hover table-bordered" style="white-space: normal;" id="tableDesignation">
                                <thead>
                                    <tr class="bg-dark">
                                        <th class="text-center text-white fw-bold">DESIGNATION</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($designations as $dt)
                                    <tr class="btnDesignation" style="cursor: pointer;" id="{{ $dt['Id'] }}">
                                        <td>{{ $dt['Name'] }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-9 col-sm-12" id="displayPermission">
                            <div class="text-center py-5">
                                <img src="/assets/img/modal/select.svg" alt="Select Designation" width="200" height="200">
                                <h6>Please select designation</h6>
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

        // ----- GLOBAL VARIABLES -----
        let moduleList = JSON.parse($('#main').attr('modules'));
        // ----- END GLOBAL VARIABLES -----

        
        // ----- DATATABLES -----
        let tableDesignation = $('#tableDesignation')
            .css({ "min-width": "99%" })
            .removeAttr("width")
            .DataTable({
                scrollY: '400px',
                sorting: [],
                scrollCollapse: true,
                paginate: false,
                info: false,
                drawCallback: function() {
                    let $parent = $('#tableDesignation_filter').parent();
                    $parent.removeClass('col-md-6').addClass('col-md-12');
                }
            });

        function initDataTables() {
            if ( $.fn.DataTable.isDataTable('#tablePermission') ) {
                $('#tablePermission').DataTable().destroy();
            }
    
            let tablePermission = $('#tablePermission')
                .css({ "min-width": "99%" })
                .removeAttr("width")
                .DataTable({
                    scrollX: true,
                    scrollY: '400px',
                    ordering: false,
                    scrollCollapse: true,
                    paginate: false,
                    info: false,
                    columnDefs: [
                        { targets: 0,  width: 150 },
                        { targets: 1,  width: 100 },
                        { targets: 2,  width: 100 },
                        { targets: 3,  width: 100 },
                        { targets: 4,  width: 100 },
                    ],
                });
        }
        initDataTables();
        // ----- END DATATABLES -----


        // ----- SELECT DESIGNATION -----
        function getDesignationPermission(designationId = null) {
            let result = false;
            $.ajax({
                method: 'GET',
                url: `/admin/setup/permission/edit/${designationId}`,
                dataType: 'json',
                async: false,
                beforeSend: function() {
                    $('#displayPermission').html(PRELOADER);
                },
                success: function(data) {
                    result = data;
                }
            })
            return result;
        }

        $(document).on('click', '.btnDesignation', function() {
            $('#tableDesignation tbody tr').removeClass('active');
            $(this).addClass('active');

            $('#displayPermission').html(PRELOADER);

            setTimeout(() => {
                let designationId = $(this).attr('id');
                let designationPermission = getDesignationPermission(designationId);

                let tbodyHTML = '';
                if (moduleList && moduleList.length) {
                    moduleList.map((dt, index) => {
                        let { module, items } = dt;
                        tbodyHTML += `
                        <tr class="bg-secondary">
                            <td>
                                <div>
                                    <b>MODULE ${(index + 1)}:</b> 
                                    <span>${module}</span>    
                                </div>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="ReadAll" module="${module}">    
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="CreateAll" module="${module}">    
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="EditAll" module="${module}">    
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="DeleteAll" module="${module}">    
                            </td>
                        </tr>`;

                        if (items && items.length) {
                            items.map(item => {
                                let { id, Title, items:subCategory } = item;

                                if (subCategory && subCategory.length) {
                                    subCategory.map(sub => {
                                        let { id, Title } = sub;

                                        let permissionData = designationPermission.filter(dt => dt.ModuleId == id);
                                        let Read = 0, Create = 0, Edit = 0, Delete = 0;
                                        if (permissionData && permissionData.length) {
                                            Read   = permissionData[0].Read;
                                            Create = permissionData[0].Create;
                                            Edit   = permissionData[0].Edit;
                                            Delete = permissionData[0].Delete;
                                        }

                                        tbodyHTML += `
                                        <tr class="module" id="${id}">
                                            <td>${Title}</td>
                                            <td class="text-center">
                                                <input type="checkbox" module="${module}" name="Read" ${Read == 1 ? 'checked' : ''}>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" module="${module}" name="Create" ${Create == 1 ? 'checked' : ''}>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" module="${module}" name="Edit" ${Edit == 1 ? 'checked' : ''}>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" module="${module}" name="Delete" ${Delete == 1 ? 'checked' : ''}>
                                            </td>
                                        </tr>`;
                                    })
                                } else {
                                    let permissionData = designationPermission.filter(dt => dt.ModuleId == id);
                                    let Read = 0, Create = 0, Edit = 0, Delete = 0;
                                    if (permissionData && permissionData.length) {
                                        Read   = permissionData[0].Read;
                                        Create = permissionData[0].Create;
                                        Edit   = permissionData[0].Edit;
                                        Delete = permissionData[0].Delete;
                                    }

                                    tbodyHTML += `
                                    <tr class="module" id="${id}">
                                        <td>${Title}</td>
                                        <td class="text-center">
                                            <input type="checkbox" module="${module}" name="Read" ${Read == 1 ? 'checked' : ''}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" module="${module}" name="Create" ${Create == 1 ? 'checked' : ''}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" module="${module}" name="Edit" ${Edit == 1 ? 'checked' : ''}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" module="${module}" name="Delete" ${Delete == 1 ? 'checked' : ''}>
                                        </td>
                                    </tr>`;
                                }
                            })
                        }
                    })
                }

                let html = `
                <table class="table table-striped table-hover" id="tablePermission">
                    <thead>
                        <tr class="bg-dark text-center fw-bold">
                            <th class="text-white">Module</th>
                            <th class="text-white">Read</th>
                            <th class="text-white">Create</th>
                            <th class="text-white">Edit</th>
                            <th class="text-white">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tbodyHTML}
                    </tbody>
                </table>
                
                <div class="text-end border-top pt-3 mt-3">
                    <button type="button" class="btn btn-primary btnSave" designationId="${designationId}">Save</button>
                </div>`;

    
                $('#displayPermission').html(html);
                initDataTables();
            }, 500);

        })
        // ----- END SELECT DESIGNATION -----


        // ----- CHECK ALL -----
        $(document).on('click', `[name="ReadAll"]`, function() {
            let isChecked = this.checked;
            let module = $(this).attr('module');
            $(`[name="Read"][module="${module}"]`).prop('checked', isChecked);
        })
        
        $(document).on('click', `[name="CreateAll"]`, function() {
            let isChecked = this.checked;
            let module = $(this).attr('module');
            $(`[name="Create"][module="${module}"]`).prop('checked', isChecked);
        })

        $(document).on('click', `[name="EditAll"]`, function() {
            let isChecked = this.checked;
            let module = $(this).attr('module');
            $(`[name="Edit"][module="${module}"]`).prop('checked', isChecked);
        })

        $(document).on('click', `[name="DeleteAll"]`, function() {
            let isChecked = this.checked;
            let module = $(this).attr('module');
            $(`[name="Delete"][module="${module}"]`).prop('checked', isChecked);
        })
        // ----- END CHECK ALL -----


        // ----- SAVE PERMISSION -----
        function savePermission(confirmation = null, designationId = '') {
            let data = [];
            $('#tablePermission tbody tr.module').each(function() {
                let ModuleId = $(this).attr('id');
                let Read     = $(`[name="Read"]`, this).is(':checked') ? 1 : 0;
                let Create   = $(`[name="Create"]`, this).is(':checked') ? 1 : 0;
                let Edit     = $(`[name="Edit"]`, this).is(':checked') ? 1 : 0;
                let Delete   = $(`[name="Delete"]`, this).is(':checked') ? 1 : 0;
                data.push({ DesignationId: designationId, ModuleId, Read, Create, Edit, Delete });
            })

            $.ajax({
                method: 'POST',
                url: `/admin/setup/permission/edit/${designationId}/save`,
                data: { data },
                dataType: 'json',
                async: false,
                success:function(data) {
                    let { status } = data;
                    if (status == 'success') {
                        showToast('success', 'Permission successfully saved!');
                    } else {
                        showToast('danger', 'Failed to save permission!');
                    }
                }
            }).done(function() {
                confirmation.close();
            })
        }
        // ----- END SAVE PERMISSION -----


        // ----- BUTTON SAVE -----
        $(document).on('click', '.btnSave', function() {
            $(`[type="search"]`).val('').trigger('keyup');
            let designationId = $(this).attr('designationId');

            let content = `
            <div class="d-flex justify-content-center align-items-center flex-column text-center">
                <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                <b class="mt-4">Are you sure you want to save this permission?</b>
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
                            confirmation.buttons.yes.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
                            confirmation.buttons.yes.disable();
                            confirmation.buttons.no.hide();

                            setTimeout(() => {
                                savePermission(confirmation, designationId);
                            }, 500)
    
                            return false;
                        }
                    },
                }
            });
        })
        // ----- END BUTTON SAVE -----

    })

</script>

@endsection