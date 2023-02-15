<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\customer\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtilizationDashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\EmployeeDirectoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserStoryController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TimekeepingController;

// AUTH
Route::get('/', [LoginController::class, 'index'])->name('auth.login');
Route::post('/check', [LoginController::class, 'check'])->name('auth.check');
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
Route::get('/register', [RegisterController::class, 'index'])->name('auth.register');
Route::post('/register', [RegisterController::class, 'save'])->name('auth.save');

// AUTHENTICATION REQUIRED TO ACCESS MODULES
Route::group(['middleware' => 'auth'], function () {
    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // UTILIZATION DASHBOARD
    Route::get('/utilizationDashboard', [UtilizationDashboardController::class, 'index'])->name('utilizationDashboard');
    Route::get('/utilizationDashboard/filter/{type}', [UtilizationDashboardController::class, 'filterUtilization'])->name('utilizationDashboard.filter');
        
    // USER
    Route::prefix('user')->group(function () {
        // PROFILE
        Route::prefix('profile')->group(function () {
            // DEFAULT
            Route::get('/{Id?}', [UserProfileController::class, 'index'])->name('user.viewProfile');
            Route::get('/generate/{UserId}/{action}', [UserProfileController::class, 'generate'])->name('user.generate');

            // IMAGE
            Route::get('/edit/image/{Id}', [UserProfileController::class, 'editProfileImage'])->name('user.editProfileImage');
            Route::put('/edit/image/{Id}/update', [UserProfileController::class, 'updateProfileImage'])->name('user.updateProfileImage');

            // LEAVE
            Route::get('/edit/leave/{Id}', [UserProfileController::class, 'editLeaveBalance'])->name('user.editLeaveBalance');
            Route::post('/edit/leave/{Id}/update', [UserProfileController::class, 'updateLeaveBalance'])->name('user.updateLeaveBalance');

            // PERSONAL INFORMATION
            Route::get('/edit/personalInformation/{Id}', [UserProfileController::class, 'editPersonalInformation'])->name('user.editPersonalInformation');
            Route::put('/edit/personalInformation/{Id}/update', [UserProfileController::class, 'updatePersonalInformation'])->name('user.updatePersonalInformation');

            // CERTIFICATION
            Route::get('/add/certification/{Id}', [UserProfileController::class, 'addCertification'])->name('user.addCertification');
            Route::post('/add/certification/{Id}/save', [UserProfileController::class, 'saveCertification'])->name('user.saveCertification');
            Route::get('/edit/certification/{Id}', [UserProfileController::class, 'editCertification'])->name('user.editCertification');
            Route::put('/edit/certification/{Id}/update', [UserProfileController::class, 'updateCertification'])->name('user.updateCertification');
            Route::get('/delete/certification/{Id}', [UserProfileController::class, 'deleteCertification'])->name('user.deleteCertification');

            // AWARD
            Route::get('/add/award/{Id}', [UserProfileController::class, 'addAward'])->name('user.addAward');
            Route::post('/add/award/{Id}/save', [UserProfileController::class, 'saveAward'])->name('user.saveAward');
            Route::get('/edit/award/{Id}', [UserProfileController::class, 'editAward'])->name('user.editAward');
            Route::put('/edit/award/{Id}/update', [UserProfileController::class, 'updateAward'])->name('user.updateAward');
            Route::get('/delete/award/{Id}', [UserProfileController::class, 'deleteAward'])->name('user.deleteAward');

            // EXPERIENCE
            Route::get('/add/experience/{Id}', [UserProfileController::class, 'addExperience'])->name('user.addExperience');
            Route::post('/add/experience/{Id}/save', [UserProfileController::class, 'saveExperience'])->name('user.saveExperience');
            Route::get('/edit/experience/{Id}', [UserProfileController::class, 'editExperience'])->name('user.editExperience');
            Route::put('/edit/experience/{Id}/update', [UserProfileController::class, 'updateExperience'])->name('user.updateExperience');
            Route::get('/delete/experience/{Id}', [UserProfileController::class, 'deleteExperience'])->name('user.deleteExperience');

            // EDUCATION
            Route::get('/add/education/{Id}', [UserProfileController::class, 'addEducation'])->name('user.addEducation');
            Route::post('/add/education/{Id}/save', [UserProfileController::class, 'saveEducation'])->name('user.saveEducation');
            Route::get('/edit/education/{Id}', [UserProfileController::class, 'editEducation'])->name('user.editEducation');
            Route::put('/edit/education/{Id}/update', [UserProfileController::class, 'updateEducation'])->name('user.updateEducation');
            Route::get('/delete/education/{Id}', [UserProfileController::class, 'deleteEducation'])->name('user.deleteEducation');

            // SKILL
            Route::get('/getFormSkill/{Id}', [UserProfileController::class, 'getFormSkill'])->name('user.getFormSkill');
            Route::post('/saveSkill/{Id}', [UserProfileController::class, 'saveSkill'])->name('user.saveSkill');
        });
    });

    // EMPLOYEE DIRECTORY
    
    Route::prefix('directory')->group(function () {
        Route::get('/', [EmployeeDirectoryController::class, 'index'])->name('employeeDirectory');
        Route::get('/add', [EmployeeDirectoryController::class, 'add'])->name('employeeDirectory.add');
        Route::post('/save', [EmployeeDirectoryController::class, 'save'])->name('employeeDirectory.save');
    });

    //PROJECTS
    Route::prefix('projects')->group(function () {
        // DEFAULT
        Route::get('/projectView', [ProjectController::class, 'view'])->name('projects');
        Route::get('/projectDetails/{Id}', [ProjectController::class, 'viewProjectDetails'])->name('projects.projectDetails');
        Route::post('/add', [ProjectController::class, 'add'])->name('projects.add');
        Route::put('/update/{Id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::get('/delete/{Id}', [ProjectController::class, 'delete'])->name('projects.delete');
        Route::get('/add/project', [ProjectController::class, 'addProject'])->name('projects.addProject');
        Route::get('/edit/project/{Id}', [ProjectController::class, 'editProject'])->name('projects.editProject');

        //USER STORY
        Route::get('/add/userStory/{Id}', [ProjectController::class, 'addUserStory'])->name('projects.addUserStory');
        Route::post('/add/userStory/{Id}/save', [ProjectController::class, 'saveUserStory'])->name('projects.saveUserStory');
        Route::get('/edit/userStory/{Id}', [ProjectController::class, 'editUserStory'])->name('projects.editUserStory');
        Route::put('/edit/userStory/{Id}/update', [ProjectController::class, 'updateUserStory'])->name('projects.updateUserStory');
        Route::get('/view/userStory/details/{Id}', [UserStoryController::class, 'userStoryDetails'])->name('projects.userStoryDetails');
        Route::get('/delete/userStory/{Id}', [ProjectController::class, 'deleteUserStory'])->name('projects.deleteUserStory');

        //TASK
        Route::get('/addTask/{Id}', [TaskController::class, 'addTask'])->name('projects.addTask');
        Route::post('/save/task/{Id}', [TaskController::class, 'saveTask'])->name('projects.saveTask');
        Route::get('/edit/task/{Id}', [TaskController::class, 'editTask'])->name('projects.editTask');
        Route::get('/delete/task/{Id}', [TaskController::class, 'deleteTask'])->name('projects.deleteTask');
        Route::put('/update/task/{Id}', [TaskController::class, 'updateTask'])->name('projects.updateTask');

        // RESOURCE
        Route::get('/add/resource/{Id}', [ProjectController::class, 'addResource'])->name('projects.addResource');
        Route::get('/edit/resource/{Id}', [ProjectController::class, 'editResource'])->name('projects.editResource');
        Route::post('/add/resource/{Id}/save', [ProjectController::class, 'saveResource'])->name('projects.saveResource');
        Route::put('/update/resource/{Id}', [ProjectController::class, 'updateResource'])->name('projects.updateResource');
    });

    // NOTIFICATION
    Route::prefix('notification')->group(function () {
        Route::post('/update/{Id}', [NotificationController::class, 'updateNotification'])->name('notifications.updateNotification');
    });

    // LEAVE
    Route::prefix('leave')->group(function () {
        Route::get('/', [LeaveRequestController::class, 'index'])->name('leaveRequest');
        Route::get('/add', [LeaveRequestController::class, 'form'])->name('leaveRequest.add');
        Route::post('/save', [LeaveRequestController::class, 'save'])->name('leaveRequest.save');
        Route::get('/view/{Id}', [LeaveRequestController::class, 'view'])->name('leaveRequest.view');
        Route::get('/revise/{Id}', [LeaveRequestController::class, 'revise'])->name('leaveRequest.revise');
        Route::put('/revise/{Id}/update', [LeaveRequestController::class, 'update'])->name('leaveRequest.update');
        Route::post('/approve/{Id}/{UserId}', [LeaveRequestController::class, 'approve'])->name('leaveRequest.approve');
        Route::post('/reject/{Id}/{UserId}', [LeaveRequestController::class, 'reject'])->name('leaveRequest.reject');
    });

    // FORMS
    Route::prefix('forms')->group(function () {
        // OVERTIME REQUEST
        Route::prefix('overtimeRequest')->group(function () {
            Route::get('/', [OvertimeRequestController::class, 'index'])->name('overtimeRequest');
            Route::get('/overtimeDetails/{Id}', [OvertimeRequestController::class, 'overtimeDetails'])->name('overtimeDetails');
            Route::get('/add', [OvertimeRequestController::class, 'addOvertimeRequest'])->name('overtimeRequest.add');
            Route::post('/save', [OvertimeRequestController::class, 'saveOvertimeRequest'])->name('overtimeRequest.save');
            Route::get('/edit/{Id}', [OvertimeRequestController::class, 'editOvertimeRequest'])->name('overtimeRequest.edit');
            Route::put('/edit/{Id}/update', [OvertimeRequestController::class, 'updateOvertimeRequest'])->name('overtimeRequest.update');
            Route::get('/delete/{Id}', [OvertimeRequestController::class, 'deleteOvertimeRequest'])->name('overtimeRequest.delete');
        });
    });

    // CUSTOMER
    Route::prefix('opportunity')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers');
        Route::get('/add', [CustomerController::class, 'form'])->name('customers.add');
        Route::post('/save', [CustomerController::class, 'save'])->name('customers.save');
        Route::get('/edit/{Id}', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/edit/{Id}/update', [CustomerController::class, 'update'])->name('customers.update');
        Route::put('/edit/{Id}/updateConsultant', [CustomerController::class, 'updateConsultant'])->name('customers.updateConsultant');
        Route::put('/edit/{Id}/updateManhour', [CustomerController::class, 'updateManhour'])->name('customers.updateManhour');
        Route::put('/edit/{Id}/updateOIC', [CustomerController::class, 'updateOIC'])->name('customers.updateOIC');
        Route::post('/edit/{Id}/updateResourceCost', [CustomerController::class, 'updateResourceCost'])->name('customers.updateResourceCost');
        Route::put('/edit/{Id}/updateManualDSW', [CustomerController::class, 'updateManualDSW'])->name('customers.updateManualDSW');
        Route::post('/edit/{Id}/reviseManhour', [CustomerController::class, 'reviseManhour'])->name('customers.reviseManhour');
        Route::post('/edit/{Id}/convertToProject', [CustomerController::class, 'convertToProject'])->name('customers.convertToProject');
        Route::get('/delete/{Id}', [CustomerController::class, 'delete'])->name('customers.delete');
    });
    
    // TIMEKEEPING
    Route::prefix('projectUtilization')->group(function () {
        Route::prefix('timekeeping')->group(function () {
            Route::get('/', [TimekeepingController::class, 'index'])->name('timekeeping');
            Route::get('/add', [TimekeepingController::class, 'form'])->name('timekeeping.add');
            Route::post('/save', [TimekeepingController::class, 'save'])->name('timekeeping.save');
            Route::get('/edit/{Id}', [TimekeepingController::class, 'edit'])->name('timekeeping.edit');
            Route::put('/edit/{Id}/update', [TimekeepingController::class, 'update'])->name('timekeeping.update');
            Route::get('/delete/{Id}', [TimekeepingController::class, 'delete'])->name('timekeeping.delete');
        });
    });
});


// ----- EXTERNAL ACTIONS -----
Route::prefix('leaveRequest')->group(function () {
    Route::get('/approve/{Id}', [LeaveRequestController::class, 'externalApprove'])->name('external.leaveRequest.approve');
    Route::get('/reject/{Id}', [LeaveRequestController::class, 'externalReject'])->name('external.leaveRequest.reject');
});
// ----- END EXTERNAL ACTIONS -----


// ----- ADMIN -----
use App\Http\Controllers\admin\ModuleController;
use App\Http\Controllers\admin\DataManagementController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\DesignationController;
use App\Http\Controllers\admin\ModuleApprovalController;
use App\Http\Controllers\admin\LeaveTypeController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\ComplexityController;
use App\Http\Controllers\admin\ProjectPhaseController;
use App\Http\Controllers\admin\CompanySettingController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    // MODULE
    Route::prefix('module')->group(function () {
        Route::get('/', [ModuleController::class, 'index'])->name('module');
        Route::get('/add', [ModuleController::class, 'form'])->name('module.add');
        Route::post('/save', [ModuleController::class, 'save'])->name('module.save');
        Route::get('/edit/{id}', [ModuleController::class, 'edit'])->name('module.edit');
        Route::put('/edit/{id}/update', [ModuleController::class, 'update'])->name('module.update');
        Route::get('/delete/{id}', [ModuleController::class, 'delete'])->name('module.delete');
    });

    // IMPORT
    Route::prefix('dataManagement')->group(function () {
        Route::get('/', [DataManagementController::class, 'index'])->name('dataManagement');
        Route::get('/moduleTemplate', [DataManagementController::class, 'moduleTemplate'])->name('dataManagement.moduleTemplate');
        Route::get('/exportModuleData', [DataManagementController::class, 'exportModuleData'])->name('dataManagement.exportModuleData');
        Route::get('/importModuleData', [DataManagementController::class, 'importModuleData'])->name('dataManagement.importModuleData');
        Route::post('/importModuleData/save', [DataManagementController::class, 'importModuleDataSave'])->name('dataManagement.importModuleData.save');
        Route::post('/validateModuleHeader', [DataManagementController::class, 'validateModuleHeader'])->name('dataManagement.validateModuleHeader');



        Route::get('/add', [DataManagementController::class, 'form'])->name('dataManagement.add');
        Route::post('/save', [DataManagementController::class, 'save'])->name('dataManagement.save');
        Route::get('/edit/{id}', [DataManagementController::class, 'edit'])->name('dataManagement.edit');
        Route::put('/edit/{id}/update', [DataManagementController::class, 'update'])->name('dataManagement.update');
        Route::get('/delete/{id}', [DataManagementController::class, 'delete'])->name('dataManagement.delete');
    });

    // SETUP
    Route::prefix('setup')->group(function () {
        // DEPARTMENT
        Route::prefix('department')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('department');
            Route::get('/add', [DepartmentController::class, 'form'])->name('department.add');
            Route::post('/save', [DepartmentController::class, 'save'])->name('department.save');
            Route::get('/edit/{Id}', [DepartmentController::class, 'edit'])->name('department.edit');
            Route::put('/edit/{Id}/update', [DepartmentController::class, 'update'])->name('department.update');
            Route::get('/delete/{Id}', [DepartmentController::class, 'delete'])->name('department.delete');
        });

        // DESIGNATION
        Route::prefix('designation')->group(function () {
            Route::get('/', [DesignationController::class, 'index'])->name('designation');
            Route::get('/add', [DesignationController::class, 'form'])->name('designation.add');
            Route::post('/save', [DesignationController::class, 'save'])->name('designation.save');
            Route::get('/edit/{Id}', [DesignationController::class, 'edit'])->name('designation.edit');
            Route::put('/edit/{Id}/update', [DesignationController::class, 'update'])->name('designation.update');
            Route::get('/delete/{Id}', [DesignationController::class, 'delete'])->name('designation.delete');
        });

        // LEAVE TYPE
        Route::prefix('leaveType')->group(function () {
            Route::get('/', [LeaveTypeController::class, 'index'])->name('leaveType');
            Route::get('/add', [LeaveTypeController::class, 'form'])->name('leaveType.add');
            Route::post('/save', [LeaveTypeController::class, 'save'])->name('leaveType.save');
            Route::get('/edit/{Id}', [LeaveTypeController::class, 'edit'])->name('leaveType.edit');
            Route::put('/edit/{Id}/update', [LeaveTypeController::class, 'update'])->name('leaveType.update');
            Route::get('/delete/{Id}', [LeaveTypeController::class, 'delete'])->name('leaveType.delete');
        });

        // PERMISSION
        Route::prefix('permission')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('permission');
            Route::get('/edit/{Id}', [PermissionController::class, 'edit'])->name('permission.edit');
            Route::post('/edit/{Id}/save', [PermissionController::class, 'save'])->name('permission.save');
        });

        // APPROVAL
        Route::prefix('moduleApproval')->group(function () {
            Route::get('/', [ModuleApprovalController::class, 'index'])->name('moduleApproval');
            Route::get('/edit/{id}', [ModuleApprovalController::class, 'edit'])->name('moduleApproval.edit');
            Route::get('/edit/{id}/{designationId}', [ModuleApprovalController::class, 'editDesignation'])->name('moduleApproval.edit.designation');
            Route::post('/edit/{id}/{designationId}/save', [ModuleApprovalController::class, 'saveDesignation'])->name('moduleApproval.edit.designation.save');
        });

        // COMPLEXITY
        Route::prefix('complexity')->group(function () {
            Route::get('/', [ComplexityController::class, 'index'])->name('complexity');
            Route::get('/add', [ComplexityController::class, 'form'])->name('complexity.add');
            Route::post('/save', [ComplexityController::class, 'save'])->name('complexity.save');
            Route::get('/edit/{Id}', [ComplexityController::class, 'edit'])->name('complexity.edit');
            Route::put('/edit/{Id}/update', [ComplexityController::class, 'update'])->name('complexity.update');
            Route::get('/delete/{Id}', [ComplexityController::class, 'delete'])->name('complexity.delete');
        });

        // PROJECT PHASE
        Route::prefix('projectPhase')->group(function () {
            Route::get('/', [ProjectPhaseController::class, 'index'])->name('projectPhase');
            Route::get('/add', [ProjectPhaseController::class, 'form'])->name('projectPhase.add');
            Route::post('/save', [ProjectPhaseController::class, 'save'])->name('projectPhase.save');
            Route::get('/edit/{Id}', [ProjectPhaseController::class, 'edit'])->name('projectPhase.edit');
            Route::put('/edit/{Id}/update', [ProjectPhaseController::class, 'update'])->name('projectPhase.update');
            Route::get('/delete/{Id}', [ProjectPhaseController::class, 'delete'])->name('projectPhase.delete');
        });

        // COMPANY SETTING
        Route::prefix('companySetting')->group(function () {
            Route::get('/', [CompanySettingController::class, 'index'])->name('companySetting');
            Route::get('/add', [CompanySettingController::class, 'form'])->name('companySetting.add');
            Route::post('/save', [CompanySettingController::class, 'save'])->name('companySetting.save');
            Route::get('/edit/{Id}', [CompanySettingController::class, 'edit'])->name('companySetting.edit');
            Route::put('/edit/{Id}/update', [CompanySettingController::class, 'update'])->name('companySetting.update');
            Route::get('/delete/{Id}', [CompanySettingController::class, 'delete'])->name('companySetting.delete');
        });
    });
});
// ----- END ADMIN -----