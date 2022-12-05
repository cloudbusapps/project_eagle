@extends('layouts.app')

@section('content')

<main id="main" class="main" designations="{{ $designations }}" employees="{{ $employees }}">

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
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <select name="ModuleId" id="ModuleId" class="form-control" select2>
                                    <option value="" selected disabled>Select Module</option>
                                    
                                    @foreach ($modules as $index => $dt)
                                    <option value="{{ $dt->id }}">{{ $dt->Title }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12" id="approvalDisplay">
                            <div class="text-center py-5">
                                <img src="{{ asset('assets/img/modal/select.svg') }}" alt="Select Module" width="200" height="200">
                                <h6>Please select module</h6>
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
        let designationList = JSON.parse($('main').attr('designations'));
        let employeeList    = JSON.parse($('main').attr('employees'));
        // ----- END GLOBAL VARIABLES -----


        // ----- DATATABLES -----
        function initDataTables() {
            if ( $.fn.DataTable.isDataTable('#tableDesignation') ) {
                $('#tableDesignation').DataTable().destroy();
            }

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
        }
        // ----- END DATATABLES -----

        
        // ----- SELECT MODULE -----
        $(document).on('change', `[name="ModuleId"]`, function() {
            $('#approvalDisplay').html(PRELOADER);

            let moduleId = $(this).val();

            let designationHTML = '';
            designationList.map(dt => {
                designationHTML += `
                <tr class="btnDesignation" style="cursor: pointer;" moduleId="${moduleId}" designationId="${dt.Id}">
                    <td>${dt.Name}</td>
                </tr>`;
            })

            let html = `
            <div class="row my-3 pt-3">
                <div class="col-md-3 col-sm-12">
                    <table class="table table-hover table-bordered" style="white-space: normal;" id="tableDesignation">
                        <thead>
                            <tr class="bg-dark">
                                <th class="text-center text-white fw-bold">DESIGNATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${designationHTML}
                        </tbody>
                    </table>
                </div>
                <div class="col-md-9 col-sm-12" id="displayApprover">
                    <div class="text-center">
                        <img src="/assets/img/modal/select.svg" alt="Select Designation" width="200" height="200">
                        <h6>Please select designation</h6>
                    </div>
                </div>
            </div>`;

            setTimeout(() => {
                $('#approvalDisplay').html(html);
                initDataTables();
            }, 500);
        })
        // ----- END SELECT MODULE -----


        // ----- SAVE DESIGNATION APPROVER -----
        function saveDesignationApprover(confirmation, moduleId, designationId) {
            let Approver = [];
            $('#tableApprover tbody tr').each(function() {
                let id = $(this).attr('id');
                Approver.push(id);
            })

            $.ajax({
                method: 'POST',
                url: `/admin/setup/moduleApproval/edit/${moduleId}/${designationId}/save`,
                data: {Approver},
                dataType: 'json',
                async: false,
                success:function(data) {
                    let { status } = data;
                    if (status == 'success') {
                        showToast('success', 'Approvers successfully saved!');
                    } else {
                        showToast('danger', 'Failed to save approvers!');
                    }
                }
            }).done(function() {
                confirmation.close();
            })
        }
        // ----- END SAVE DESIGNATION APPROVER -----


        // ----- BUTTON SAVE -----
        $(document).on('click', '.btnSave', function() {
            let moduleId      = $(this).attr('moduleId');
            let designationId = $(this).attr('designationId');

            let content = `
            <div class="d-flex justify-content-center align-items-center flex-column text-center">
                <img src="/assets/img/modal/new.svg" class="py-3" height="150" width="150">
                <b class="mt-4">Are you sure you want to save this approvers?</b>
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
                            $('#formModuleApproval').attr('validated', 'true').submit();
    
                            confirmation.buttons.yes.setText(`<span class="spinner-border spinner-border-sm"></span> Please wait...`);
                            confirmation.buttons.yes.disable();
                            confirmation.buttons.no.hide();

                            saveDesignationApprover(confirmation, moduleId, designationId);
    
                            return false;
                        }
                    },
                }
            });
        })
        // ----- END BUTTON SAVE -----

        
        // ----- SELECT DESIGNATION -----
        function getDesignationApprover(moduleId = null, designationId = null) {
            let result = false;
            $.ajax({
                method: 'GET',
                url: `/admin/setup/moduleApproval/edit/${moduleId}/${designationId}`,
                dataType: 'json',
                async: false,
                beforeSend: function() {
                    $('#displayApprover').html(PRELOADER);
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

            $('#displayApprover').html(PRELOADER);

            setTimeout(() => {
                let moduleId      = $(this).attr('moduleId');
                let designationId = $(this).attr('designationId');
                let data = getDesignationApprover(moduleId, designationId);

                let employeeOption = '', tbodyHTML = '';
                if (employeeList.length) {
                    employeeList.forEach(function(data) {
                        let { Id, FirstName, LastName } = data;
                        employeeOption += `<option value="${ Id }">${ FirstName+' '+LastName }</option>`;
                    })
                }

                if (data && data.length) {
                    data.map(dt => {
                        let { FirstName, LastName, ApproverId, Level } = dt;

                        tbodyHTML += `
                        <tr id="${ApproverId}">
                            <td class="d-flex justify-content-between align-items-center py-1 px-0">
                                <div class="pl-2"> 
                                    <span class="badge bg-warning">Level ${Level}</span>
                                    <span class="px-2">${FirstName} ${LastName}</span>
                                </div>
                                <button class="btn btn-outline-danger btnDeleteApprover"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>`;
                    })
                }

                let html = `
                <div class="form-group">
                    <div class="d-flex mb-0">
                        <select name="Approver" id="Approver" class="form-control" select2>
                            <option value="" selected disabled>Select Approver</option>
                            ${employeeOption}
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success btnAddApprover" style="border-radius: 0 5px 5px 0;"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                    <table class="table table-hover mt-3" id="tableApprover">
                        <tbody>${tbodyHTML}</tbody>
                    </table>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-primary btnSave" moduleId="${moduleId}" designationId="${designationId}">Save</button>
                </div>`;
    
                $('#displayApprover').html(html);
                initSelect2();
            }, 500);

        })
        // ----- END SELECT DESIGNATION -----


        // ----- ADD APPROVER -----
        $(document).on('click', '.btnAddApprover', function() {
            let approverId   = $(`[name="Approver"]`).val();
            if (approverId) {
                let approverList = [];
                $('#tableApprover tbody tr').each(function() {
                    this.id && approverList.push(this.id);
                });

                if (!approverList.includes(approverId)) {
                    let approverName = $(`[name="Approver"] option:selected`).text()?.trim();
                    let count        = $('#tableApprover tbody tr').length + 1;
        
                    let html = `
                    <tr id="${approverId}">
                        <td class="d-flex justify-content-between align-items-center py-1 px-0">
                            <div class="pl-2"> 
                                <span class="badge bg-warning">Level ${count}</span>
                                <span class="px-2">${approverName}</span>
                            </div>
                            <button class="btn btn-outline-danger btnDeleteApprover"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>`;
                    
                    $('#tableApprover tbody').append(html);
                    $(`[name="Approver"]`).val('').trigger('change');
                } else {
                    showToast('danger', 'Approver already exists!');
                }

            }
        })
        // ----- END ADD APPROVER -----


        // ----- DELETE APPROVER -----
        $(document).on('click', '.btnDeleteApprover', function() {
            let $parent = $(this).closest('tr');
            $parent.fadeOut(500, function() {
                $parent.remove();

                $('#tableApprover span.bg-warning').each(function(index) {
                    $(this).text('Level '+ (index+1));
                })
            })
        })
        // ----- END DELETE APPROVER -----

    })

</script>

@endsection