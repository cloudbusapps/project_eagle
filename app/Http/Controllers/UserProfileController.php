<?php

namespace App\Http\Controllers;

use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserCertification;
use App\Models\UserSkill;
use App\Models\UserAward;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserLeaveBalance;
use App\Models\admin\Department;
use App\Models\admin\Designation;
use App\Models\admin\LeaveType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class UserProfileController extends Controller
{
    public function getUserLeaveBalance($UserId = null) {
        $leaveBalanceData = [];

        $leaveTypes = LeaveType::where('Status', 1)->get();
        foreach ($leaveTypes as $dt) {
            $leaveBalanceData[] = [
                'Id'      => $dt->Id,
                'Name'    => $dt->Name,
                'Balance' => UserLeaveBalance::where('UserId', $UserId)
                    ->where('LeaveTypeId', $dt->Id)
                    ->orderBy('Year', 'DESC')
                    ->get()
            ];
        }

        return $leaveBalanceData;
    }

    public function index(Request $request) {
        $UserId = $request->Id ? $request->Id : Auth::id();
        try {
            $projects = DB::table('projects')
                ->select('projects.*')
                ->leftJoin('user_story', 'projects.Id', '=', 'user_story.ProjectId')
                ->leftJoin('tasks', 'user_story.Id', '=', 'tasks.UserStoryId')
                ->where('tasks.UserId', $UserId)
                ->groupBy('projects.Id')
                ->orderBy('projects.updated_at', 'DESC')
                ->get();

            $userData = DB::table('users AS u')
                ->select('u.*', DB::raw('d.Name AS department, d2.Name AS designation'))
                ->leftJoin('departments AS d', 'DepartmentId', '=', 'd.Id')
                ->leftJoin('designations AS d2', 'DesignationId', '=', 'd2.Id')
                ->where('u.Id', $UserId)
                ->first();

            

            $data = [
                'title'          => 'Profile',
                'userData'       => $userData,
                'certifications' => UserCertification::where('UserId', $UserId)->get(),
                'skills'         => UserSkill::where('UserId', $UserId)->get(),
                'awards'         => UserAward::where('UserId', $UserId)->get(),
                'experiences'    => UserExperience::where('UserId', $UserId)->get(),
                'educations'     => UserEducation::where('UserId', $UserId)->get(),
                'projects'       => $projects,
                'leaveBalance'   => $this->getUserLeaveBalance($UserId),
                'requestId'      => $UserId,
            ];
    
            return view('users.profile', $data);
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort('500');
        }
    }

    public function generate($UserId, $action = 'print') {
        $userData = DB::table('users AS u')
            ->select('u.*', DB::raw('d.Name AS department'))
            ->leftJoin('departments AS d', 'DepartmentId', '=', 'd.Id')
            ->where('u.Id', $UserId)
            ->first();

        $data = [
            'title'          => 'Profile',
            'userData'       => $userData,
            'certifications' => UserCertification::where('UserId', $UserId)->get(),
            'skills'         => UserSkill::where('UserId', $UserId)->get(),
            'awards'         => UserAward::where('UserId', $UserId)->get(),
            'experiences'    => UserExperience::where('UserId', $UserId)->get(),
            'educations'     => UserEducation::where('UserId', $UserId)->get(),
            'requestId'      => $UserId,
            'action'         => $action
        ];

        return view('users.generate', $data);
    }


    // ----- PERSONAL INFROMATION -----
    public function editPersonalInformation($Id) {
        try {
            $data = [
                'title'        => 'Edit Personal Information',
                'departments'  => Department::all(),
                'designations' => Designation::all(),
                'userData'     => User::where('Id', $Id)->first(),
            ];
    
            return view('users.formPersonalInformation', $data);
        } catch (\Exception $e) {   
            abort(500);
        }
    }


    public function updatePersonalInformation(Request $request, $Id) {
        $validator = $request->validate([
            'EmployeeNumber' => ['required', 'string', 'max:255',
                Rule::unique('users')->ignore($Id, 'Id')
            ],
            'About'     => ['required', 'string', 'max:500'],
            'FirstName' => ['required', 'string', 'max:255'],
            'LastName'  => ['required', 'string', 'max:255'],
            'DepartmentId'  => ['required'],
            'DesignationId' => ['required'],
            'email'     => ['required', 'string', 'email', 'max:255', 
                Rule::unique('users')->ignore($Id, 'Id')
            ],
        ]);

        $user = User::find($Id);
        $user->EmployeeNumber = $request->EmployeeNumber;
        $user->About          = $request->About;
        $user->FirstName      = $request->FirstName;
        $user->MiddleName     = $request->MiddleName;
        $user->LastName       = $request->LastName;
        $user->Gender         = $request->Gender;
        $user->Address        = $request->Address;
        $user->ContactNumber  = $request->ContactNumber;
        $user->DepartmentId   = $request->DepartmentId;
        $user->DesignationId  = $request->DesignationId;
        $user->email          = $request->email;

        if ($user->save()) {
            return redirect()->route('user.viewProfile')
                ->with('tab', 'Overview')
                ->with('success', 'Personal Information successfully updated!');
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }
    // ----- END PERSONAL INFROMATION -----


    // ----- CERTIFICATION -----
    public function addCertification($Id) {
        try {
            $data = [
                'title' => 'New Certification',
                'data' => [],
                'UserId' => $Id
            ];
    
            return view('users.formCertification', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function saveCertification(Request $request, $Id) {
        $validator = $request->validate([
            'Code'        => ['required', 'string', 'max:255'],
            'Description' => ['required', 'string', 'max:500'],
            'DateTaken'   => ['nullable', 'before_or_equal:today'],
            'Status'      => ['required'],
        ]);
        
        $Code = $request->Code;
        $UserCertification = new UserCertification();
        $UserCertification->UserId        = $request->Id;
        $UserCertification->Code          = $request->Code;
        $UserCertification->Description   = $request->Description;
        $UserCertification->DateTaken     = $request->DateTaken;
        $UserCertification->Status        = $request->Status;

        if ($UserCertification->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Certification')
                ->with('success', "<b>{$Code}</b> successfully saved!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Certification')
                ->with('fail', "<b>{$Code}</b> failed to save!");
        }
    }


    public function editCertification($Id) {
        try {
            $data = [
                'title'=> 'Edit Certification',
                'data' => UserCertification::where('Id', $Id)->first(),
                'Id'   => $Id
            ];
    
            return view('users.formCertification', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function updateCertification(Request $request, $Id) {
        $validator = $request->validate([
            'Code'        => ['required', 'string', 'max:255'],
            'Description' => ['required', 'string', 'max:500'],
            'DateTaken'   => ['nullable', 'before_or_equal:today'],
            'Status'      => ['required'],
        ]);

        $Code = $request->Code;
        $UserCertification = UserCertification::find($Id);
        $UserCertification->Code          = $request->Code;
        $UserCertification->Description   = $request->Description;
        $UserCertification->DateTaken     = $request->DateTaken;
        $UserCertification->Status        = $request->Status;

        if ($UserCertification->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Certification')
                ->with('success', "<b>{$Code}</b> successfully updated!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Certification')
                ->with('fail', "<b>{$Code}</b> failed to update!");
        }
    }


    public function deleteCertification($Id) {
        $UserCertification = UserCertification::find($Id);
        $Code = $UserCertification->Code;

        if ($UserCertification->delete()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Certification')
                ->with('success', "<b>{$Code}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Certification')
                ->with('fail', "<b>{$Code}</b> failed to delete!");
        }
    }
    // ----- END CERTIFICATION -----


    // ----- AWARD -----
    public function addAward($Id) {
        try {
            $data = [
                'title' => 'New Award',
                'data' => [],
                'UserId' => $Id
            ];
    
            return view('users.formAward', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function saveAward(Request $request, $Id) {
        $validator = $request->validate([
            'Title'       => ['required', 'string', 'max:255'],
            'Description' => ['required', 'string', 'max:500'],
            'Date'        => ['required', 'before_or_equal:today'],
        ]);
        
        $Title = $request->Title;
        $UserAward = new UserAward();
        $UserAward->UserId      = $request->Id;
        $UserAward->Title       = $request->Title;
        $UserAward->Description = $request->Description;
        $UserAward->Date        = $request->Date;

        if ($UserAward->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Award')
                ->with('success', "<b>{$Title}</b> successfully saved!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Award')
                ->with('fail', "<b>{$Title}</b> failed to save!");
        }
    }


    public function editAward($Id) {
        try {
            $data = [
                'title'=> 'Edit Award',
                'data' => UserAward::where('Id', $Id)->first(),
                'Id'   => $Id
            ];
    
            return view('users.formAward', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function updateAward(Request $request, $Id) {
        $validator = $request->validate([
            'Title'       => ['required', 'string', 'max:255'],
            'Description' => ['required', 'string', 'max:500'],
            'Date'        => ['required', 'before_or_equal:today'],
        ]);

        $Title = $request->Title;
        $UserAward = UserAward::find($Id);
        $UserAward->Title       = $request->Title;
        $UserAward->Description = $request->Description;
        $UserAward->Date        = $request->Date;

        if ($UserAward->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Award')
                ->with('success', "<b>{$Title}</b> successfully updated!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Award')
                ->with('fail', "<b>{$Title}</b> failed to update!");
        }
    }


    public function deleteAward($Id) {
        $UserAward = UserAward::find($Id);
        $Title = $UserAward->Title;

        if ($UserAward->delete()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Award')
                ->with('success', "<b>{$Title}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Award')
                ->with('fail', "<b>{$Title}</b> failed to delete!");
        }
    }
    // ----- END AWARD -----


    // ----- EXPERIENCE -----
    public function addExperience($Id) {
        try {
            $data = [
                'title' => 'New Experience',
                'data' => [],
                'UserId' => $Id
            ];
    
            return view('users.formExperience', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function saveExperience(Request $request, $Id) {
        $validator = $request->validate([
            'JobTitle'    => ['required', 'string', 'max:255'],
            'Company'     => ['required', 'string', 'max:255'],
            'Description' => ['required', 'string', 'max:500'],
            'StartDate'   => ['required', 'before_or_equal:today'],
            'EndDate'     => ['required', 'before_or_equal:today'],
        ]);
        
        $JobTitle = $request->JobTitle;
        $UserExperience = new UserExperience();
        $UserExperience->UserId      = $request->Id;
        $UserExperience->JobTitle    = $request->JobTitle;
        $UserExperience->Company     = $request->Company;
        $UserExperience->Description = $request->Description;
        $UserExperience->StartDate   = $request->StartDate;
        $UserExperience->EndDate     = $request->EndDate;

        if ($UserExperience->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Experience')
                ->with('success', "<b>{$JobTitle}</b> successfully saved!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Experience')
                ->with('fail', "<b>{$JobTitle}</b> failed to save!");
        }
    }


    public function editExperience($Id) {
        try {
            $data = [
                'title'=> 'Edit Experience',
                'data' => UserExperience::where('Id', $Id)->first(),
                'Id'   => $Id
            ];
    
            return view('users.formExperience', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function updateExperience(Request $request, $Id) {
        $validator = $request->validate([
            'JobTitle'    => ['required', 'string', 'max:255'],
            'Company'     => ['required', 'string', 'max:255'],
            'Description' => ['required', 'string', 'max:500'],
            'StartDate'   => ['required', 'before_or_equal:today'],
            'EndDate'     => ['required', 'before_or_equal:today'],
        ]);
        
        $JobTitle = $request->JobTitle;
        $UserExperience = UserExperience::find($Id);
        $UserExperience->JobTitle    = $request->JobTitle;
        $UserExperience->Company     = $request->Company;
        $UserExperience->Description = $request->Description;
        $UserExperience->StartDate   = $request->StartDate;
        $UserExperience->EndDate     = $request->EndDate;

        if ($UserExperience->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Experience')
                ->with('success', "<b>{$JobTitle}</b> successfully updated!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Experience')
                ->with('fail', "<b>{$JobTitle}</b> failed to update!");
        }
    }


    public function deleteExperience($Id) {
        $UserExperience = UserExperience::find($Id);
        $JobTitle = $UserExperience->JobTitle;

        if ($UserExperience->delete()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Experience')
                ->with('success', "<b>{$JobTitle}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Experience')
                ->with('fail', "<b>{$JobTitle}</b> failed to delete!");
        }
    }
    // ----- END EXPERIENCE -----


    // ----- EDUCATION -----
    public function addEducation($Id) {
        try {
            $data = [
                'title' => 'New Education',
                'data' => [],
                'UserId' => $Id
            ];
    
            return view('users.formEducation', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function saveEducation(Request $request, $Id) {
        $validator = $request->validate([
            'DegreeTitle'    => ['required', 'string', 'max:255'],
            'School'     => ['required', 'string', 'max:255'],
            'StartDate'   => ['required', 'before_or_equal:today'],
            'EndDate'     => ['required', 'before_or_equal:today'],
        ]);
        
        $DegreeTitle = $request->DegreeTitle;
        $UserEducation = new UserEducation();
        $UserEducation->UserId      = $request->Id;
        $UserEducation->DegreeTitle    = $request->DegreeTitle;
        $UserEducation->School     = $request->School;
        $UserEducation->Achievement = $request->Achievement;
        $UserEducation->StartDate   = $request->StartDate;
        $UserEducation->EndDate     = $request->EndDate;

        if ($UserEducation->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Education')
                ->with('success', "<b>{$DegreeTitle}</b> successfully saved!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Education')
                ->with('fail', "<b>{$DegreeTitle}</b> failed to save!");
        }
    }


    public function editEducation($Id) {
        try {
            $data = [
                'title'=> 'Edit Education',
                'data' => UserEducation::where('Id', $Id)->first(),
                'Id'   => $Id
            ];
    
            return view('users.formEducation', $data);
        } catch (\Exception $e) {   
            return redirect()->back()->withErrors('fail', $e->getMessage());
        }
    }


    public function updateEducation(Request $request, $Id) {
        $validator = $request->validate([
            'DegreeTitle'    => ['required', 'string', 'max:255'],
            'School'     => ['required', 'string', 'max:255'],
            'StartDate'   => ['required', 'before_or_equal:today'],
            'EndDate'     => ['required', 'before_or_equal:today'],
        ]);
        
        $DegreeTitle = $request->DegreeTitle;
        $UserEducation = UserEducation::find($Id);
        $UserEducation->DegreeTitle    = $request->DegreeTitle;
        $UserEducation->School     = $request->School;
        $UserEducation->Achievement = $request->Achievement;
        $UserEducation->StartDate   = $request->StartDate;
        $UserEducation->EndDate     = $request->EndDate;

        if ($UserEducation->save()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Education')
                ->with('success', "<b>{$DegreeTitle}</b> successfully updated!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Education')
                ->with('fail', "<b>{$DegreeTitle}</b> failed to update!");
        }
    }


    public function deleteEducation($Id) {
        $UserEducation = UserEducation::find($Id);
        $DegreeTitle = $UserEducation->DegreeTitle;

        if ($UserEducation->delete()) {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Education')
                ->with('success', "<b>{$DegreeTitle}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('user.viewProfile')
                ->with('tab', 'Education')
                ->with('fail', "<b>{$DegreeTitle}</b> failed to delete!");
        }
    }
    // ----- END EDUCATION -----


    // ----- SKILL -----
    public function getFormSkill($Id = '') {
        $method = "POST";
        $action = "/users/updateSkill/{$Id}/update";
        $data = UserSkill::where('UserId', $Id)->get();

        $skillHTML = '';
        if (count($data)) {
            foreach ($data as $dt) {
                $skillHTML .= '
                <tr>
                    <td class="d-flex justify-content-between align-items-center">
                        <div class="skill-title">'. $dt['Title'] .'</div>
                        <div>
                            <a href="#" class="text-secondary btnEditSpecificSkill mr-1"><i class="bi bi-pencil"></i></a>
                            <span class="ml-1"></span>
                            <a href="#" class="text-secondary btnDeleteSpecificSkill ml-1"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>';
            }
        }

        return '
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="input-group mb-0">
                        <input type="text" class="form-control" name="Title" placeholder="Skill">
                        <div class="input-group-append">
                            <button class="btn btn-outline-success btnSaveSkill" style="border-radius: 0 5px 5px 0;"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                    <div class="d-block invalid-feedback"></div>
                </div>
            </div>
            <div class="col-12" style="max-height: 300px; height: auto; overflow: auto;">
                <table class="table table-hover" id="tableSkill">
                    <tbody>
                        '. $skillHTML .'
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="modal-footer pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-warning btnUpdateSkill" id="'. $Id .'">Update</button>
        </div>';
    }


    public function saveSkill(Request $request, $Id) {
        $skills = $request->skills;
        
        $saveSkill = true;
        $deleteSkill = UserSkill::where('UserId', $Id)->delete();

        if ($skills && count($skills)) {
            $data = [];

            foreach ($skills as $skill) {
                $data[] = [
                    'Id'            => Str::uuid(),
                    'UserId'        => $Id,
                    'Title'         => $skill,
                    'CreatedById' => Auth::id(),
                    'UpdatedById' => Auth::id(),
                ];
            }

            $saveSkill = UserSkill::insert($data);
        }

        if ($saveSkill && $deleteSkill) {
            Session::flash('card', 'Skill'); 
            Session::flash('success', '<b>Skills</b> successfully updated!'); 
            return "true";
        } 
        return "false";
    }
    // ----- END SKILL -----


    // ----- PROFILE IMAGE -----
    public function editProfileImage($Id) {
        $user = User::find($Id);

        $image = asset('uploads/profile/'. $user->Profile ?? 'default.png' );
        $html = '
        <form method="POST" action="'. route('user.updateProfileImage', ['Id' => $Id]) .'" enctype="multipart/form-data">
            '. csrf_field() .'
            '. method_field('PUT') .'
            <div class="d-flex flex-column align-items-center">
                <div class="display-image">
                    <img src="'. $image .'" width="200" height="200" alt="Profile"
                        class="rounded-circle mb-2 preview-image">
                </div>
                <div id="myCamera" style="display: none;"></div>
                <div class="button-camera mt-3">
                    <button type="button" class="btn btn-dark btnCancelCapture" style="display: none;">Cancel</button>
                    <button type="button" class="btn btn-info btnCapture" style="display: none;">Capture</button>
                    <button type="button" class="btn btn-dark btnRetake" style="display: none;">Retake</button>
                    <button type="button" class="btn btn-success btnSaveCapture" style="display: none;">Save</button>
                </div>
                <input type="file" name="Profile" id="Profile" style="display: none;">
                <input type="hidden" id="ProfileStore" name="ProfileStore" value="">
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary" onclick="$(`#Profile`).trigger(`click`)">Browse</button>
                    <button type="button" class="btn btn-outline-info btnCamera">Camera</button>
                </div>
            </div>
            <div class="modal-footer mt-3 pb-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>';
        return $html;
    }


    public function updateProfileImage(Request $request, $Id) {
        $ProfileStore = $request->ProfileStore ?? null;
        $file = $request->file('Profile');

        $destinationPath = 'uploads/profile';

        $User = User::where('Id', $Id)->first();
        $EmployeeNumber = $User->EmployeeNumber;
        $filename = $User->Profile;

        if ($file) {
            $filenameArr = explode('.', $file->getClientOriginalName());
            $extension   = array_splice($filenameArr, count($filenameArr)-1, 1);
            $filename    = $EmployeeNumber.time().'.'.$extension[0];
    
            $validator = $request->validate([
                'Profile' => 'mimes:png,jpg,jpeg|max:2048'
            ]);

            $file->move($destinationPath, $filename);
        } else if ($ProfileStore) {
            $binary = base64_decode($ProfileStore);
            $filename = $EmployeeNumber.time().'.jpeg';
            file_put_contents($destinationPath."/".$filename, $binary);
        }


        try {
            $User = User::find($Id);
            $User->Profile = $filename;
            
            if ($User->save()) {
                return redirect()
                    ->route('user.viewProfile')
                    ->with('card', 'Image')
                    ->with('success', "Profile image successfully updated!");
            } 
        } catch (\Throwable $th) {
            return redirect()
                ->route('user.viewProfile')
                ->with('card', 'Image')
                ->with('fail', "There's an error occured. Please try again later...");
        }



    }
    // ----- END PROFILE IMAGE -----


    // ----- LEAVE BALANCE -----
    public function editLeaveBalance($Id) {
        $UserLeaveBalance = $this->getUserLeaveBalance($Id);

        $index = 0;
        $tbodyHTML   = '';

        foreach ($UserLeaveBalance as $dt) {
            $Name        = $dt['Name'];
            $LeaveTypeId = $dt['Id'];

            if (isset($dt['Balance']) && count($dt['Balance'])) {
                foreach ($dt['Balance'] as $i => $bal) {
                    $UserLeaveBalanceId = $bal['Id'] ?? Str::uuid();
                    $Year    = $bal['Year'];
                    $Credit  = $bal['Credit'];
                    $Accrued = $bal['Accrued'];
                    $Used    = $bal['Used'];

                    $LeaveTypeDisplay = $i == 0 ? '<td rowspan="'. count($dt['Balance']) .'">'. $Name .'</td>' : '';

                    $tbodyHTML .= '
                    <input type="hidden" name="LeaveBalance['.$index.'][Id]" value="'. $UserLeaveBalanceId .'">
                    <input type="hidden" name="LeaveBalance['.$index.'][Year]" value="'. $Year .'">
                    <input type="hidden" name="LeaveBalance['.$index.'][LeaveTypeId]" value="'. $LeaveTypeId .'">
                    <tr>
                        '. $LeaveTypeDisplay .'
                        <td>'. $Year .'</td>
                        <td class="text-center">
                            <input type="number" step="0.01" name="LeaveBalance['.$index.'][Credit]" value="'. $Credit .'" class="form-control text-center">
                        </td>
                        <td class="text-center">
                            <input type="number" step="0.01" name="LeaveBalance['.$index.'][Accrued]" value="'. $Accrued .'" class="form-control text-center">
                        </td>
                        <td class="text-center">
                            <input type="number" step="0.01" name="LeaveBalance['.$index.'][Used]" value="'. $Used .'" class="form-control text-center">
                        </td>
                    </tr>';

                    $index++;
                }
            } else {
                $UserLeaveBalanceId = Str::uuid();
                $Year = date('Y');

                $tbodyHTML .= '
                <input type="hidden" name="LeaveBalance['.$index.'][Id]" value="'. $UserLeaveBalanceId .'">
                <input type="hidden" name="LeaveBalance['.$index.'][Year]" value="'. $Year .'">
                <input type="hidden" name="LeaveBalance['.$index.'][LeaveTypeId]" value="'. $LeaveTypeId .'">
                <tr>
                    <td>'. $Name .'</td>
                    <td>'. $Year .'</td>
                    <td class="text-center">
                        <input type="number" step="0.01" name="LeaveBalance['.$index.'][Credit]" value="0" class="form-control text-center">
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" name="LeaveBalance['.$index.'][Accrued]" value="0" class="form-control text-center">
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" name="LeaveBalance['.$index.'][Used]" value="0" class="form-control text-center">
                    </td>
                </tr>';

                $index++;
            }
        }

        $html = '
        <form method="POST" action="'. route('user.updateLeaveBalance', ['Id' => $Id]) .'">
            '. csrf_field() .'
            <table class="table table-bordered table-hover my-2">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Year</th>
                        <th>Credit</th>
                        <th>Accrued</th>
                        <th>Used</th>
                    </tr>
                </thead>
                <tbody>
                    '.$tbodyHTML.'
                </tbody>
            </table>
            <div class="modal-footer mt-3 pb-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>';

        return $html;
    }


    public function updateLeaveBalance(Request $request, $Id) {
        $LeaveBalance = $request->LeaveBalance ?? [];
        // THERE IS ERROR HERE THAT BREAKS THE COMPUTATION OF BALANCES UPON UPDATING
        if (isset($LeaveBalance) && count($LeaveBalance)) {
            $data = [];
            foreach ($LeaveBalance as $key => $dt) {
                $UserLeaveBalanceId = $dt['Id'] ?? Str::uuid();
                $Year        = $dt['Year'] ?? '';
                $LeaveTypeId = $dt['LeaveTypeId'] ?? '';
                $Credit      = $dt['Credit'] ?? 0;
                $Accrued     = $dt['Accrued'] ?? 0;
                $Used        = $dt['Used'] ?? 0;
                $Balance     = ($Accrued + $Credit) - $Used;

                $data[] = [
                    'Id'          => $UserLeaveBalanceId,
                    'Year'        => $Year,
                    'LeaveTypeId' => $LeaveTypeId,
                    'UserId'      => $Id,
                    'Accrued'     => $Accrued,
                    'Credit'      => $Credit,
                    'Used'        => $Used,
                    'Balance'     => $Balance,
                    'CreatedById' => Auth::id(),
                    'UpdatedById' => Auth::id(),
                ];
            }

            if ($data && count($data)) {
                $save = UserLeaveBalance::upsert($data, 'Id');
                if ($save) {
                    return redirect()
                        ->back()
                        ->with('card', 'Leave')
                        ->with('success', "<b>Leave balance</b> successfully updated!");
                }
            }
        }

        return redirect()
            ->back()
            ->with('card', 'Leave')
            ->with('fail', "There's an error occured. Please try again later...");
    }
    // ----- END LEAVE BALANCE -----







    // UNUSED - BUT CAN BE REFERENCE IN THE FUTURE
    public function getFormPersonalInformation($Id = 'new') {
        $method = "PUT";
        $action = "/user/updatePersonalInformation/{$Id}/update";
        $EmployeeNumber = $LastName = $FirstName = $MiddleName = $Title = $email = '';

        if (!empty($Id)) {
            $data           = User::where('Id', $Id)->get()->first();
            $EmployeeNumber = $data['EmployeeNumber'];
            $LastName       = $data['LastName'];
            $FirstName      = $data['FirstName'];
            $MiddleName     = $data['MiddleName'];
            $Title          = $data['Title'];
            $email   = $data['email'];
        }

        return '
        <form method="POST" action="'. $action .'">
            '. csrf_field() .'
            '. method_field($method) .'
            <div class="row">
                <div class="col-md-6 col-sm-12 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="EmployeeNumber" name="EmployeeNumber" placeholder="Employee Number" required
                            value="'. ($EmployeeNumber ?? '') .'">
                        <label for="EmployeeNumber">Employee # <code>*</code></label>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" required
                            value="'. ($Title ?? '') .'">
                        <label for="Title">Title <code>*</code></label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last Name" required
                            value="'. ($LastName ?? '') .'">
                        <label for="LastName">Last Name <code>*</code></label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" required
                            value="'. ($FirstName ?? '') .'">
                        <label for="FirstName">First Name <code>*</code></label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="MiddleName" name="MiddleName" placeholder="Middle Name" 
                            value="'. ($MiddleName ?? '') .'">
                        <label for="MiddleName">Middle Name</label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required
                            value="'. ($email ?? '') .'">
                        <label for="email">Emai lAddress <code>*</code></label>
                    </div>
                </div>
            </div>

            <div class="modal-footer pb-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>';
    }

    public function getFormCertification($Id = 'new') {
        $method = "POST";
        $action = "/user/saveCertification";
        $button = '<button type="submit" class="btn btn-primary">Save</button>';
        $Code = $Description = $DateTaken = $Status = '';

        if (!empty($Id) && $Id != 'new') {
            $method = "PUT";
            $action = "/user/updateCertification/{$Id}/update";

            $data        = UserCertification::where('Id', $Id)->get()->first();
            $Code        = $data['Code'];
            $Description = $data['Description'];
            $DateTaken   = $data['DateTaken'];
            $Status      = $data['Status'];

            $button = "
            <a href='/user/deleteCertification/{$Id}' class='btn btn-danger'>Delete</a>
            <button type='submit' class='btn btn-warning'>Update</button>";
        }

        return '
        <form method="POST" action="'. $action .'">
            '. csrf_field() .'
            '. method_field($method) .'
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="Code" name="Code" placeholder="Code" required
                            value="'. ($Code ?? '') .'">
                        <label for="Code">Code <code>*</code></label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Description" id="Description" name="Description" style="height: 100px; resize: none;" required>'. ($Description ?? '') .'</textarea>
                        <label for="Description">Description <code>*</code></label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="DateTaken" name="DateTaken" placeholder="Date Taken"
                            value="'. ($DateTaken ?? '') .'">
                        <label for="DateTaken">Date Taken</label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <select class="form-control" id="Status" name="Status" required>
                            <option value="" selected disabled>Select Status</option>
                            <option value="To Take" '. ($Status == 'To Take' ? 'selected' : '') .'>To Take</option>
                            <option value="For Review" '. ($Status == 'For Review' ? 'selected' : '') .'>For Review</option>
                            <option value="Acquired" '. ($Status == 'Acquired' ? 'selected' : '') .'>Acquired</option>
                        </select>
                        <label for="Status">Status <code>*</code></label>
                    </div>
                </div>
            </div>

            <div class="modal-footer pb-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                '. $button .'
            </div>
        </form>';
    }
    // ---- END | UNUSED - BUT CAN BE REFERENCE IN THE FUTURE

}
