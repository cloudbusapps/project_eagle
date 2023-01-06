<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\customer\CustomerBusinessProcess;
use App\Models\customer\CustomerBusinessProcessFiles;
use App\Models\customer\CustomerConsultant;
use App\Models\customer\CustomerInscope;
use App\Models\customer\CustomerProposal;
use App\Models\customer\CustomerProposalFiles;
use App\Models\customer\CustomerLimitation;
use App\Models\customer\CustomerComplexity;
use App\Models\customer\CustomerComplexityDetails;
use App\Models\customer\CustomerProjectPhases;
use App\Models\customer\CustomerProjectPhasesDetails;
use App\Models\customer\CustomerRemarks;
use App\Models\admin\ThirdParty;
use App\Models\admin\Designation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemNotification;
use App\Mail\CustomerMail;

class CustomerController extends Controller
{
    private $MODULE_ID = 18;

    function index()
    {
        isReadAllowed($this->MODULE_ID, true);

        $customerData = Customer::all();
        $data = [
            'title'   => "Customer",
            'data'    => $customerData
        ];
        return view('customers.index', $data);
    }

    function form()
    {
        isCreateAllowed($this->MODULE_ID, true);

        $title = $this->getTitle();
        $data = [
            'title'   => $title[0],
            'data'    => User::all(),
            'type'    => 'insert',
        ];

        return view('customers.form', $data);
    }
    function edit(Request $request, $Id)
    {
        $customerData = Customer::find($Id);
        $progress = $request->progress ?? '';
        $title = $this->getTitle($customerData->Status, $progress);

        // BASED ON STATUS
        if ($title[1] == 1) { // COMPLEXITY
            $data = [
                'complexities' => $this->getComplexityData($Id),
            ];
        } else if ($title[1] == 3) { // BUSINESS PROCESS
            $businessProcess = CustomerBusinessProcess::select('customer_business_process.*')
                ->where('customer_business_process.CustomerId', $Id)
                ->leftJoin('customer_business_process_files AS cbpf', 'cbpf.CustomerId', '=', 'customer_business_process.Id')
                ->first();
            $businessProcessData = $businessProcess ? $businessProcess : '';
            $files = $businessProcessData !== '' ? CustomerBusinessProcessFiles::where('CustomerId', $Id)->orderBy('created_at', 'DESC')->get() : [];
            
            $data = [
                'businessProcessData' => $businessProcessData,
                'files'               => $files,
            ];
        } else if (in_array($title[1], [4, 5, 7])) { // REQUIREMENT AND SOLUTIONS, CAPABILITIES, ASSESSMENT
            $inscopes = CustomerInscope::where('CustomerId', $Id)->orderBy('created_at', 'DESC')->get();
            $limitations = CustomerLimitation::where('CustomerId', $Id)->get();
            $assignedConsultants = CustomerConsultant::where('customer_assigned_consultants.CustomerId', $Id)
            ->leftJoin('users AS U','U.Id', '=', 'customer_assigned_consultants.UserId' )
            ->get(['customer_assigned_consultants.*','U.FirstName','U.LastName']);
            $totalManhour = CustomerInscope::where('CustomerId', $Id)->sum('Manhour');
            $customerResources = DB::table('customer_assessment_resources')
                ->where('CustomerId', $Id)
                ->get();
            $users = User::where('DesignationId', config('constant.ID.DESIGNATIONS.TECHNICAL_CONSULTANT'))
                ->orWhere('DesignationId',config('constant.ID.DESIGNATIONS.FUNCTIONAL_CONSULTANT'))
                ->get();
            
            $data = [
                'reqSol'              => $inscopes,
                'limitations'         => $limitations,
                'thirdParties'        => ThirdParty::orderBy('created_at', 'DESC')->get(),
                'users'               => $users,
                'OIC'               => User::where('Id',config('constant.ID.USERS.ALVIN_AGATO'))->get(),
                'totalManhour'        => $totalManhour,
                'assignedConsultants' => $assignedConsultants,
                'projectPhaseResources' => $this->getProjectPhaseResources($Id),
                'customerProjectPhases' => $this->getCustomerProjectPhase($Id),
                'customerResources'    => $customerResources,
                'manhourRemarks'      => CustomerRemarks::where('CustomerId',$Id)->get(),
            ];
        } else if ($title[1] == 6) { // PROJECT PHASE
            $data = [
                'ProjectPhase'        => $this->getProjectPhase($Id),
            ];
        } else {
            $data = [
                'customerProposalFiles' => CustomerProposalFiles::where('CustomerId',$Id)->get(),
                'customerProposal'      => CustomerProposal::where('CustomerId',$Id)->first(),
            ];
        }

        $data = array_merge($data, [
            'title'               => $title[0],
            'currentViewStatus'   => $title[1],
            'type'                => 'edit',
            'data'                => $customerData,
            'Id'                  => $Id,
            'MODULE_ID'           => $this->MODULE_ID,
        ]);

        return view('customers.form', $data);
    }

    public function getTitle($Status = 0, $Progress = '')
    {
        if ($Status == 0 || $Progress == 'information') {
            return ['Information', 0];
        } else if ($Status == 1 || $Progress == 'complexity') {
            return ['Complexity', 1];
        } else if ($Status == 2 || $Progress == 'dsw') {
            return ['Deployment Strategy Workshop', 2];
        } else if ($Status == 3 || $Progress == 'businessProcess') {
            return ['Business Process', 3];
        } else if ($Status == 4 || $Progress == 'requirementSolution') {
            return ['Requirements and Solutions', 4];
        } else if ($Status == 5 || $Progress == 'capability') {
            return ['Capability', 5];
        } else if ($Status == 6 || $Progress == 'projectPhase') {
            return ['Project Phase', 6];
        } else if ($Status == 7 || $Progress == 'assessment') {
            return ['Assessment', 7];
        } else if ($Status == 8 || $Progress == 'proposal') {
            return ['Proposal', 8];
        } else if ($Status == 9 || $Progress == 'success'){
            return ['Success', 9];
        } else {
            return ['Lost', 10];
        }
    }



    function save(Request $request)
    {
        $validator = $request->validate([
            'CustomerName'   => ['required'],
            'Industry'       => ['required'],
            'Address'        => ['required'],
            'ContactPerson'  => ['required'],
            'Product'        => ['required'],
            'Type'           => ['required'],
            'Notes'          => ['required'],
        ]);

        $customer = new Customer;
        $customerName             = $request->CustomerName;
        $customer->CustomerName   = $customerName;
        $customer->Industry       = $request->Industry;
        $customer->Address        = $request->Address;
        $customer->ContactPerson  = $request->ContactPerson;
        $customer->Product        = $request->Product;
        $customer->Type           = $request->Type;
        $customer->Notes          = $request->Notes;
        $customer->Status         = 1;
        $customer->HeadId         = getDepartmentHeadId(config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION'));
        $customer->CreatedById    = Auth::id();

        if ($customer->save()) {
            return redirect()
                ->route('customers.edit', ['Id' => $customer->Id])
                ->with('success', "<b>{$customerName}</b> successfully saved!");
        } else {
            return redirect()
                ->route('customers')
                ->with('fail', "Something went wrong, please try again");
        }
    }

    function updateCapability($request, $Id, $customerData)
    {
        $ThirdPartyStatus = $request->ThirdPartyStatus;

        if ($ThirdPartyStatus > 0) {
            if ($ThirdPartyStatus == 2) { // COMPLETED THIRD PARTY REQUIREMENTS
                // SAVE THIRD PARTY TO MASTER LIST
                if ($customerData->ThirdPartyId == config('constant.ID.THIRD_PARTIES.OTHERS')) {
                    DB::table('third_parties')->insert([
                        'Id'          => Str::uuid(),
                        'Name'        => $customerData->ThirdPartyName,
                        'CreatedById' => Auth::id(),
                        'UpdatedById' => Auth::id(),
                    ]);
                }
                $customerData->Status = 6; // PROCEED TO PROJECT PHASES
            }
            $customerData->ThirdPartyAttachment = $request->ThirdPartyAttachment;
            $customerData->ThirdPartyStatus     = $ThirdPartyStatus;
        } else {
            $IsCapable = $request->IsCapable;
            if ($IsCapable == 1) {
                $customerData->Status = 6; // PROCEED TO PROJECT PHASES
            } else {
                // THIRD PARTY
                $validator = $request->validate([
                    'ThirdPartyId'   => ['required'],
                    'ThirdPartyName' => ['required'],
                ]);

                $customerData->ThirdPartyId         = $request->ThirdPartyId;
                $customerData->ThirdPartyName       = $request->ThirdPartyName;
                $customerData->ThirdPartyAttachment = $request->ThirdPartyAttachment;
                $customerData->ThirdPartyStatus     = 0; // PENDING

                $InScope         = $request->InScope ?? [];
                $ThirdPartyCount = 0;
                $InScopeCount    = count($InScope);
                $ThirdPartyData  = [];

                if (!empty($InScope) && count($InScope)) {
                    foreach ($InScope as $key => $dt) {
                        $Checked = isset($InScope[$key]['Checked']) ? 1 : 0;
                        $CustomerInscope = CustomerInscope::where('Id', $dt['Id']);
                        
                        if ($Checked) {
                            $ThirdPartyCount++;
                            $ThirdPartyData[] = $CustomerInscope->first();
                        } 

                        $CustomerInscope->update(['ThirdParty' => $Checked]);
                    }
                }

                // NOTIFY TECHCON FOR THIRD PARTY ASSESSMENT
                $CreatedById    = $customerData->CreatedById;
                $CustomerName   = $customerData->CustomerName;
                $ThirdPartyName = $customerData->ThirdPartyName;
                $User = User::find($CreatedById);

                $Title       = "Customer";
                $Description = "
                <div>
                    <div><b>Customer - {$CustomerName}</b></div>
                    <div>{$ThirdPartyCount} out of {$InScopeCount} requirements are assigned to Third Party - {$ThirdPartyName}</div>
                </div>";
                $Link        = "/opportunity/edit/{$Id}?progress=capability";
                $Icon        = '/assets/img/icons/default.png';

                $NotificationData = [
                    'Id'          => $Id,
                    'Title'       => $Title,
                    'Description' => $Description,
                    'Link'        => $Link,
                    'Icon'        => $Icon,
                    'ThirdParty'  => $ThirdPartyData
                ];
                // Mail::to($User->email)->send(new CustomerMail($customerData, $User, $NotificationData, 'Capability'));
                Notification::sendNow($User, new SystemNotification($Id, $Title, $Description, $Link, $Icon));
            }

            $customerData->IsCapable = $IsCapable;
        }
    }

    function updateComplexity($request, $Id, $customerData)
    {
        $IsComplex = 0;

        $complexity = $request->complexity ?? [];
        if (count($complexity)) {
            CustomerComplexity::where('CustomerId', $Id);
            CustomerComplexityDetails::where('CustomerId', $Id);

            $complexitySubData = [];
            foreach ($complexity as $key => $dt) {
                $CustomerComplexityId = Str::uuid();
                $Checked = isset($dt['Selected']) ? 1 : 0;
                if ($Checked) $IsComplex = 1;

                $data = [
                    'Id'           => $CustomerComplexityId,
                    'CustomerId'   => $Id,
                    'ComplexityId' => $dt['Id'],
                    'Title'        => $dt['Title'],
                    'Checked'      => $Checked,
                ];
                $insert = CustomerComplexity::insert($data);

                if (isset($dt['Sub']) && count($dt['Sub'])) {
                    foreach ($dt['Sub'] as $key2 => $dt2) {
                        $complexitySubData[] = [
                            'Id'                   => Str::uuid(),
                            'CustomerComplexityId' => $CustomerComplexityId,
                            'CustomerId'           => $Id,
                            'ComplexityId'         => $dt['Id'],
                            'ComplexityDetailId'   => $dt2['Id'],
                            'Title'                => $dt2['Title'],
                            'Checked'              => isset($dt2['Selected']) ? 1 : 0,
                        ];
                    }
                }
            }
            CustomerComplexityDetails::insert($complexitySubData);

            $customerData->Status    = 2; // PROCEED TO DSW
            $customerData->DSWStatus = 1; // STARTED DSW
        }

        $customerData->IsComplex = $IsComplex;
        if (!$IsComplex) {
            $customerData->Status    = 3; // PROCEED TO BUSINESS PROCESS
            $customerData->DSWStatus = 0; // NOT APPLICABLE DSW
        }
    }

    function updateDSW($request, $Id, $customerData)
    {
        if ($customerData->DSWStatus == 1) { // DSW STARTED
            $customerData->DSWStatus = 2;
        } else if ($customerData->DSWStatus == 2) { // ONGOING DSW
            $customerData->DSWStatus = 3;
        } else if ($customerData->DSWStatus == 3) { // COMPLETED DSW
            $customerData->DSWStatus = 4;
        } else if ($customerData->DSWStatus == 4) { // FOR CONSOLIDATION
            $customerData->DSWStatus = 5;
        } else if ($customerData->DSWStatus == 5) { // COMPLETED REQUIREMENTS
            $customerData->Status = 3; // PROCEED TO BUSINESS PROCESS
        }
    }

    function updateBusinessProcess($request, $Id, $customerData)
    {
        $now = Carbon::now('utc')->toDateTimeString();

        $validator = $request->validate([
            'BusinessNotes' => ['required'],
            'File'          => ['required'],
        ]);
        $destinationPath = 'uploads/businessProcess';

        $BusinessProcess = new CustomerBusinessProcess;
        $BusinessProcess->Note = $request->BusinessNotes;
        $BusinessProcess->CustomerId = $Id;
        if ($BusinessProcess->save()) {
            $files = $request->file('File');
            if ($files && count($files)) {
                $businessProcessFiles = [];
                foreach ($files as $index => $file) {
                    $filenameArr = explode('.', $file->getClientOriginalName());
                    $extension   = array_splice($filenameArr, count($filenameArr) - 1, 1);
                    $filename    = 'BP-[' . $index . ']' . time() . '.' . $extension[0];

                    $file->move($destinationPath, $filename);

                    $businessProcessFiles[] = [
                        'Id'             => Str::uuid(),
                        'CustomerId'     => $Id,
                        'File'           => $filename,
                        'Note'           => $request->BusinessNotes,
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                        'created_at'     => now(),
                    ];
                }

                CustomerBusinessProcessFiles::where('CustomerId', $Id)->delete();
                CustomerBusinessProcessFiles::insert($businessProcessFiles);
            }
        }

        $customerData->Status = 4;
    }

    function updateRequirementSolution($request, $Id, $customerData)
    {
        $validator = $request->validate([
            'Title.*'       => ['required'],
            'Description.*' => ['required'],
            'Module.*'      => ['required'],
            'Solution.*'    => ['required'],
            'Assumption.*'  => ['required'],
            'OutOfScope.*'  => ['required'],
            'Comment.*'     => ['required'],
        ],[
            'Title.*.required'       => 'Title is required',
            'Description.*.required' => 'Description is required',
            'Module.*.required'      => 'Module is required',
            'Solution.*.required'    => 'Solution is required',
            'Assumption.*.required'  => 'Assumption is required',
            'OutOfScope.*.required'  => 'OutOfScope is required',
            'Comment.*.required'     => 'Comment is required',
        ]);

        $Title       = $request->Title;
        $Description = $request->Description;
        $Module      = $request->Module;
        $Solution    = $request->Solution;
        $Assumption  = $request->Assumption;
        $OutOfScope  = $request->OutOfScope;
        $Comment     = $request->Comment;

        if ($Title && count($Title)) {
            $inscopeData = [];
            $limitationData = [];
            foreach ($Title as $i => $title) {
                $inscopeData[] = [
                    'Id'          => Str::uuid(),
                    'CustomerId'  => $Id,
                    'Title'       => $title,
                    'Description' => $Description[$i],
                    'Module'      => $Module[$i],
                    'Solution'    => $Solution[$i],
                    'Assumption'  => $Assumption[$i],
                    'CreatedById' => Auth::id(),
                    'UpdatedById' => Auth::id(),
                ];
            }

            CustomerInscope::insert($inscopeData);
            foreach ($OutOfScope as $i => $outscope) {
                $limitationData[] = [
                    'Id'          => Str::uuid(),
                    'CustomerId'  => $Id,
                    'OutScope'    => $outscope,
                    'Comment'     => $Comment[$i],
                    'CreatedById' => Auth::id(),
                    'UpdatedById' => Auth::id(),
                ];
            }
            CustomerLimitation::insert($limitationData);
        }

        $customerData->Status = 5;
    }

    function updateProjectPhase($request, $Id, $customerData)
    {
        $projectPhase = $request->projectPhase ?? [];
        if (!empty($projectPhase) && count($projectPhase)) {

            CustomerProjectPhases::where('CustomerId', $Id)->delete();
            CustomerProjectPhasesDetails::where('CustomerId', $Id)->delete();

            foreach ($projectPhase as $key => $dt) {
                $CustomerProjectPhaseId = Str::uuid();
                $ProjectPhaseId         = $dt['Id'];
                $data = [
                    'Id'             => $CustomerProjectPhaseId,
                    'CustomerId'     => $Id,
                    'ProjectPhaseId' => $ProjectPhaseId,
                    'Title'          => $dt['Title'],
                    'Percentage'     => $dt['Percentage'],
                    'Checked'        => $dt['Required'] == 1 || isset($dt['Checked']) ? 1 : 0,
                    'CreatedById'    => Auth::id(),
                    'UpdatedById'    => Auth::id(),
                ];
                CustomerProjectPhases::insert($data);

                if (isset($dt['Sub']) && count($dt['Sub'])) {
                    $subData = [];
                    foreach ($dt['Sub'] as $dt2) {
                        $subData[] = [
                            'Id'                     => Str::uuid(),
                            'CustomerProjectPhaseId' => $CustomerProjectPhaseId,
                            'CustomerId'             => $Id,
                            'ProjectPhaseId'         => $ProjectPhaseId,
                            'ProjectPhaseDetailId'   => $dt2['Id'],
                            'Title'                  => $dt2['Title'],
                            'Checked'                => $dt2['Required'] == 1 || isset($dt2['Checked']) ? 1 : 0,
                            'CreatedById'            => Auth::id(),
                            'UpdatedById'            => Auth::id(),
                        ];
                    }
                    CustomerProjectPhasesDetails::insert($subData);
                }
            }

            // NOTIFY BA HEAD FOR ASSESSMENT
            $HeadId         = $customerData->HeadId ?? getDepartmentHeadId(config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION'));
            $CustomerName   = $customerData->CustomerName;
            $User = User::find($HeadId);

            $Title       = "Opportunity";
            $Description = "<b>Customer - {$CustomerName}</b> is now ready for assessment and manhours";
            $Link        = url("/opportunity/edit/{$Id}?progress=assessment");
            $Icon        = '/assets/img/icons/default.png';

            $NotificationData = [
                'Id'          => $Id,
                'Title'       => $Title,
                'Description' => $Description,
                'Link'        => $Link,
                'Icon'        => $Icon,
            ];
            // Mail::to($User->email)->send(new CustomerMail($customerData, $User, $NotificationData, 'Project Phase'));
            Notification::sendNow($User, new SystemNotification($Id, $Title, $Description, $Link, $Icon));
        }

        $customerData->Status = 7;
    }

    function updateConsultant(Request $request, $Id)
    {
        $consultants = $request->selectedConsultants;

        if ($consultants && count($consultants)) {
            $consultantsData = [];
            foreach ($consultants as $i => $consultant) {
                $consultantsData[] = [
                    'Id'          => Str::uuid(),
                    'CustomerId'  => $Id,
                    'UserId'      => $consultant,
                    'CreatedById' => Auth::id(),
                    'UpdatedById' => Auth::id(),
                ];
            }
            CustomerConsultant::where('CustomerId', $Id)->delete();
            $update = CustomerConsultant::insert($consultantsData);

            if ($update) {
                $customerData = Customer::find($Id);
                $CustomerName = $customerData->CustomerName;

                foreach ($consultantsData as $dt) {

                    // NOTIFY CONSULTANT
                    $User = User::find($dt['UserId']);
        
                    $Title = "Customer";
                    $Description = " <div>Please do an assessment and indicate the manhours of the requirement for {$CustomerName}</div>";
                    $Link = "/opportunity/edit/{$Id}?progress=assessment";
                    $Icon = '/assets/img/icons/default.png';
        
                    $NotificationData = [
                        'Id'          => $Id,
                        'Title'       => $Title,
                        'Description' => $Description,
                        'Link'        => $Link,
                        'Icon'        => $Icon,
                    ];
                    // Mail::to($User->email)->send(new CustomerMail($customerData, $User, $NotificationData, 'Assessment'));
                    Notification::sendNow($User, new SystemNotification($Id, $Title, $Description, $Link, $Icon));
                }
    
                $request->session()->flash('success', '<b>Consulant</b> successfully updated!');
                return response()->json(['url' => url('opportunity/edit/' . $Id)]);
            }
        }

        $request->session()->flash('fail', 'Something went wrong, please try again later');
        return response()->json(['url' => url('opportunity/edit/' . $Id)]);
    }
    function updateOIC(Request $request, $Id)
    {
        // $validator = $request->validate([
        //     'selectedOIC' => ['required'],
        // ]);
        $OICId = $request->selectedOIC;
        $update = Customer::where('Id', $Id)
                ->update(['OICId' => $OICId]);
        if ($update) {
            $request->session()->flash('success', '<b>OIC</b> successfully updated');
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        } else {
            $request->session()->flash('fail', 'Something went wrong, please try again later');
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        }
    }

    function updateManhour(Request $request, $Id)
    {
        // $validator = $request->validate([
        //     'Title' => ['required'],
        // ]);

        $data = Customer::find($Id);
        $assignedConsultants = CustomerConsultant::where('CustomerId', $Id)->get();

        // CHECK IF USER IS VALID TO UPDATE MANHOUR
        if(Auth::id() == $data->HeadId || Auth::id() == config('constant.ID.USERS.ADMIN')|| Auth::id()==$data->OICId||$assignedConsultants->contains('Id',Auth::id())){
            foreach ($request->data as $assessment) {
                $inscope = CustomerInscope::find($assessment['rowId']);
                $inscope->Manhour = $assessment['manhourValue'];
                $update = $inscope->save();
            }
            if ($update) {
                $request->session()->flash('success', 'Manhours updated successfully!');
                return response()->json(['url' => url('opportunity/edit/' . $Id)]);
            } else {
                $request->session()->flash('fail', 'Something went wrong, please try again later');
                return response()->json(['url' => url('opportunity/edit/' . $Id)]);
            }
        } else{
            $request->session()->flash('fail', "You don't have permission");
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        }
    }
    function updateAssessment($request, $Id, $customerData)
    {
        //  FOR CONSULTANT: CHECK IF ANY FIELD ID NULL
        $hasNull = CustomerInscope::where('CustomerId', $Id)
            ->whereNull('Manhour')
            ->get();
        if (count($hasNull) > 0) {
            return redirect()
                ->route('customers.edit', ['Id' => $Id])
                ->with('fail', "<b>Fill out</b> all the manhours");
        } else {
            $assignedConsultants = CustomerConsultant::where('CustomerId', $Id)->get();
            // IF USER IS DEPT.HEAD GO TO PROPOSAL
            if (Auth::id() == getDepartmentHeadId(config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION'))) {
                $customerData->Status = 8;
            } else if ($assignedConsultants->contains('UserId', Auth::id())) {
                // NOTIFY DEPT. HEAD

            } else {
                $customerData->Status = 8;
                // return redirect()
                // ->route('customers.edit', ['Id' => $Id])
                // ->with('fail', "You don't have permission to submit");
            }
        }
    }
    function updateProposal($request, $Id, $customerData)
    {
        $validator = $request->validate([
            'ProposalProgress' => ['required'],
        ]);
        $files = $request->file('FileProposal');
        $fileSigned = $request->file('FileSigned');
        $destinationPath = 'uploads/Proposal';

        if($request->ProposalStatus == 3){

            if($fileSigned&&count($fileSigned)){
                $validator = $request->validate([
                    'SignedDateSubmitted' => ['required'],
                ]);
                $upsert = CustomerProposal::updateOrCreate(
                    ['CustomerId' => $Id],
                    [
                        'SignedDateSubmitted' => $request->SignedDateSubmitted, 
                        'discounted'          => 1,
                        'Status'              => 1,
                        'CreatedById'         => Auth::id(),
                        'UpdatedById'         => Auth::id(),
                        'created_at'          => now(),
                    ]
                );
                if($upsert){
                $proposalFile = [];
                foreach ($fileSigned as $index => $file) {
                    $filenameArr = explode('.', $file->getClientOriginalName());
                    $extension   = array_splice($filenameArr, count($filenameArr) - 1, 1);
                    $nameWithoutExtension   = array_splice($filenameArr,0);
                    $filename    =  $nameWithoutExtension[0].'_['.$index.']'. time() . '.' . $extension[0];
    
                    $file->move($destinationPath, $filename);
    
                    $proposalFile[] = [
                        'Id'             => Str::uuid(),
                        'CustomerId'     => $Id,
                        'File'           => $filename,
                        'Date'           => $request->SignedDateSubmitted,
                        'Status'         => 1,
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];
                }
    
                CustomerProposalFiles::where('CustomerId', $Id)
                ->where('Status',1)
                ->delete();
                CustomerProposalFiles::insert($proposalFile);
                }
            }
            $customerData->ProposalStatus = $request->ProposalStatus;
            $customerData->Status = 9;
        } else if($request->ProposalStatus == 4){
            $validator = $request->validate([
                'ProposalAction' => ['required'],
            ]);
            if($request->ProposalAction==11){
                $customerData->Status = 7;
                $customerData->ProposalStatus = null;
            } else if($request->ProposalAction==10){
                $customerData->Status = 10;
                $customerData->ProposalStatus = $request->ProposalStatus;
            }

        } else{
            if ($files&&count($files)) {
                $validator = $request->validate([
                    'DateSubmitted' => ['required'],
                ]);
                $upsert = CustomerProposal::updateOrCreate(
                    ['CustomerId' => $Id],
                    [
                        'DateSubmitted'       => $request->DateSubmitted, 
                        'discounted'          => 1,
                        'Status'              => 1,
                        'CreatedById'         => Auth::id(),
                        'UpdatedById'         => Auth::id(),
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ]
                );
                if($upsert){
                    $proposalFile = [];
                    foreach ($files as $index => $file) {
                        $filenameArr = explode('.', $file->getClientOriginalName());
                        $extension   = array_splice($filenameArr, count($filenameArr) - 1, 1);
                        $nameWithoutExtension   = array_splice($filenameArr,0);
                        $filename    =  $nameWithoutExtension[0].'_['.$index.']'. time() . '.' . $extension[0];
        
                        $file->move($destinationPath, $filename);
        
                        $proposalFile[] = [
                            'Id'             => Str::uuid(),
                            'CustomerId'     => $Id,
                            'File'           => $filename,
                            'Date'           => $request->DateSubmitted,
                            'Status'         => 0,
                            'CreatedById'    => Auth::id(),
                            'UpdatedById'    => Auth::id(),
                            'created_at'     => now(),
                        ];
                    }
        
                    CustomerProposalFiles::where('CustomerId', $Id)
                    ->where('Status',0)
                    ->delete();
                    CustomerProposalFiles::insert($proposalFile);
                }
            }
    
            $customerData->ProposalProgress = $request->ProposalProgress;
        }
    }

    function updateResourceCost(Request $request, $Id) 
    {
        $data = [];
        $customerData = Customer::find($Id);
        $resourceData = $request->resourceData;
        if(Auth::id() == $customerData->HeadId || Auth::id() == config('constant.ID.USERS.ADMIN')|| Auth::id()==$customerData->OICId){

            if (isset($resourceData) && count($resourceData)) {
                DB::table('customer_assessment_resources')->where('CustomerId', $Id)->delete();
    
                foreach ($resourceData as $dt) {
                    $data[] = [
                        'Id'            => Str::uuid(),
                        'CustomerId'    => $Id,
                        'DesignationId' => $dt['DesignationId'],
                        'Initial'       => $dt['Initial'],
                        'Level'         => $dt['Level'],
                        'Rate'          => $dt['Rate'],
                        'Cost'          => $dt['Cost'],
                        'CreatedById'   => Auth::id(),
                        'UpdatedById'   => Auth::id(),
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
    
                if ($data && count($data)) {
                    DB::table('customer_assessment_resources')->insert($data);
    
                    $request->session()->flash('success', 'Resouces updated successfully!');
                    return response()->json(['url' => url('opportunity/edit/' . $Id)]);
                }
            } 
    
            $request->session()->flash('fail', 'Something went wrong, please try again later');
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        } else{
            $request->session()->flash('fail', "You don't have permission");
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        }
    }

    //FOR MANUAL CHANGE OF DSW
    function updateManualDSW(Request $request, $Id){
        $validator = $request->validate([
            'NameNotYet' => ['required'],
        ]);
        $customerData = Customer::find($Id);
        $customerName = $customerData->CustomerName;
        $customerData->DSWStatus = $request->NameNotYet;

        if ($customerData->update()) {
            $request->session()->flash('success', "<b>{$customerName}</b> successfully updated!");
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        } else {
            $request->session()->flash('fail', 'Something went wrong, please try again later');
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        }

    }
    function reviseManhour(Request $request, $Id){
        $validator = $request->validate([
            'reviseRemarks' => ['required'],
        ]);
        $customerData = Customer::find($Id);
        $customerName = $customerData->CustomerName;

        $data = [
            'Id'             => Str::uuid(),
            'CustomerId'     => $Id,
            'Remark'         => $request->reviseRemarks,
            'Status'         => 0,
            'CreatedById'    => Auth::id(),
            'UpdatedById'    => Auth::id(),
            'created_at'     => now(),
        ];

        if (CustomerRemarks::insert($data)) {
            $request->session()->flash('success', "<b>{$customerName}</b> successfully updated!");
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        } else {
            $request->session()->flash('fail', 'Something went wrong, please try again later');
            return response()->json(['url' => url('opportunity/edit/' . $Id)]);
        }

    }

    function update(Request $request, $Id)
    {
        $customerData = Customer::find($Id);
        $customerName = $customerData->CustomerName;
        $Id           = $customerData->Id;

        // COMPLEXITY
        if ($customerData->Status == 1) {
            $this->updateComplexity($request, $Id, $customerData);
        }
        // DSW
        else if ($customerData->Status == 2) {
            $this->updateDSW($request, $Id, $customerData);
        }
        // BUSINESS PROCESS
        else if ($customerData->Status == 3) {
            $this->updateBusinessProcess($request, $Id, $customerData);
        }
        // REQUIREMENTS AND SOLUTIONS
        else if ($customerData->Status == 4) {
            $this->updateRequirementSolution($request, $Id, $customerData);
        }
        // CAPABILITY
        else if ($customerData->Status == 5) {
            $this->updateCapability($request, $Id, $customerData);
        }
        // PROJECT PHASE
        else if ($customerData->Status == 6) {
            $this->updateProjectPhase($request, $Id, $customerData);
        }
        // ASSESSMENT
        else if ($customerData->Status == 7) {
            $this->updateAssessment($request, $Id, $customerData);
        }
        // PROPOSAL
        else if ($customerData->Status == 8) {
            $this->updateProposal($request, $Id, $customerData);
        }


        if ($customerData->update()) {
            return redirect()
                ->route('customers.edit', ['Id' => $Id])
                ->with('success', "<b>{$customerName}</b> successfully updated!");
        } else {
            return redirect()
                ->route('customers')
                ->with('fail', "Something went wrong, please try again");
        }
    }

    function delete($Id)
    {
    }



    // HELPER FOR CUSTOMER CONTROLLER
    function getComplexityData($Id = null)
    {
        $data = [];

        $complexity = DB::table('complexity AS c')
            ->leftJoin('customer_complexity AS cc', function ($join) use ($Id) {
                $join->on('cc.ComplexityId', 'c.Id');
                $join->on('cc.CustomerId', DB::raw("'{$Id}'"));
            })
            ->where('Status', 1)
            ->get(['c.*', 'cc.Checked']);

        foreach ($complexity as $index => $complex) {
            $temp = [
                'Id'      => $complex->Id,
                'Title'   => $complex->Title,
                'Checked' => $complex->Checked,
                'Details' => []
            ];

            $complexityDetails = DB::table('complexity_details AS cd')
                ->leftJoin('customer_complexity_details AS ccd', function ($join) use ($Id) {
                    $join->on('ccd.ComplexityDetailId', 'cd.Id');
                    $join->on('ccd.CustomerId', DB::raw("'{$Id}'"));
                })
                ->where('Status', 1)
                ->where('cd.ComplexityId', $complex->Id)
                ->get(['cd.*', 'ccd.Checked']);

            foreach ($complexityDetails as $complexityDetail) {

                if ($complexityDetail->ComplexityId == $complex->Id) {
                    $temp['Details'][] = [
                        'Id'           => $complexityDetail->Id,
                        'ComplexityId' => $complexityDetail->ComplexityId,
                        'Title'        => $complexityDetail->Title,
                        'Status'       => $complexityDetail->Status,
                        'Checked'      => $complexityDetail->Checked,
                    ];
                }
            }
            $data[] = $temp;
        }

        return $data;
    }

    function getProjectPhase($Id = null)
    {
        $data = [];

        $projectPhase = DB::table('project_phases AS pp')
            ->leftJoin('customer_project_phases AS cpp', function ($join) use ($Id) {
                $join->on('cpp.ProjectPhaseId',  'pp.Id');
                $join->on('cpp.CustomerId', DB::raw("'{$Id}'"));
            })
            ->where('Status', 1)
            ->get(['pp.*', 'cpp.Checked']);

        foreach ($projectPhase as $index => $pp) {
            $temp = [
                'Id'         => $pp->Id,
                'Title'      => $pp->Title,
                'Status'     => $pp->Status,
                'Required'   => $pp->Required,
                'Percentage' => $pp->Percentage,
                'Checked'    => $pp->Checked,
                'Details'    => []
            ];

            $projectPhasesDetails = DB::table('project_phases_details AS ppd')
                ->leftJoin('customer_project_phases_details AS cppd', function ($join) use ($Id) {
                    $join->on('cppd.ProjectPhaseDetailId', 'ppd.Id');
                    $join->on('cppd.CustomerId', DB::raw("'{$Id}'"));
                })
                ->where('Status', 1)
                ->where('ppd.ProjectPhaseId', $pp->Id)
                ->get(['ppd.*', 'cppd.Checked']);

            foreach ($projectPhasesDetails as $ppd) {

                if ($ppd->ProjectPhaseId == $pp->Id) {
                    $temp['Details'][] = [
                        'Id'             => $ppd->Id,
                        'ProjectPhaseId' => $ppd->ProjectPhaseId,
                        'Title'          => $ppd->Title,
                        'Status'         => $ppd->Status,
                        'Required'       => $ppd->Required,
                        'Checked'        => $ppd->Checked,
                    ];
                }
            }
            $data[] = $temp;
        }

        return $data;
    }

    function getCustomerProjectPhase($Id = null)
    {
        $data = [];
        $totalManhour = CustomerInscope::where('CustomerId', $Id)->sum('Manhour');
        $customerProjectPhase = DB::table('customer_project_phases AS ccp')
            ->leftJoin('project_phases AS pp', function ($join) use ($Id) {
                $join->on('ccp.ProjectPhaseId', 'pp.Id');
                $join->on('ccp.ProjectPhaseId', 'pp.Id');
            })
            ->where('pp.Status', 1)
            ->where('ccp.CustomerId', $Id)
            ->get(['ccp.*', 'pp.Title AS ProjectPhaseTitle', 'pp.Status']);

        foreach ($customerProjectPhase as $index => $cpp) {
            $percentInDecimal = $cpp->Percentage / 100;
            if(config('constant.ID.PROJECT_PHASES.BUILD')==$cpp->ProjectPhaseId){
                $temp = [
                    'Id'                => $cpp->Id,
                    'Title'             => $cpp->Title,
                    'ProjectPhaseTitle' => $cpp->ProjectPhaseTitle,
                    'ProjectPhaseId'    => $cpp->ProjectPhaseId,
                    'Status'            => $cpp->Status,
                    'EffortHours'       => $totalManhour,
                    // 'Required'          => $cpp->Required,
                    'Percentage'        => $cpp->Percentage,
                    'Checked'           => $cpp->Checked,
                    'Details'           => [],
                    'Resources'         => [],
                    'ResourceHeader'            => []
                ];
            } else{
                $temp = [
                    'Id'                => $cpp->Id,
                    'Title'             => $cpp->Title,
                    'ProjectPhaseTitle' => $cpp->ProjectPhaseTitle,
                    'ProjectPhaseId'    => $cpp->ProjectPhaseId,
                    'Status'            => $cpp->Status,
                    'EffortHours'       => $totalManhour * $percentInDecimal,
                    // 'Required'          => $cpp->Required,
                    'Percentage'        => $cpp->Percentage,
                    'Checked'           => $cpp->Checked,
                    'Details'           => [],
                    'Resources'         => [],
                    'ResourceHeader'            => []
                ];
            }
            

            $customerProjectPhasesDetails = DB::table('customer_project_phases_details AS cppd')
                ->leftJoin('project_phases_details AS ppd', function ($join) use ($Id) {
                    $join->on('cppd.ProjectPhaseDetailId', 'ppd.Id');
                    $join->on('cppd.CustomerId', DB::raw("'{$Id}'"));
                })
                ->where('ppd.Status', 1)
                ->where('cppd.CustomerProjectPhaseId', $cpp->Id)
                ->where('cppd.CustomerId', $Id)
                ->get(['cppd.*', 'ppd.Title AS ProjectPhaseDetailTitle', 'ppd.Status']);

            foreach ($customerProjectPhasesDetails as $cppd) {

                if ($cppd->CustomerProjectPhaseId == $cpp->Id) {
                    $temp['Details'][] = [
                        'Id'                      => $cppd->Id,
                        'ProjectPhaseId'          => $cppd->ProjectPhaseId,
                        'Title'                   => $cppd->Title,
                        'ProjectPhaseDetailTitle' => $cppd->ProjectPhaseDetailTitle,
                        'Status'                  => $cppd->Status,
                        // 'Required'                => $cppd->Required,
                        'Checked'                 => $cppd->Checked,
                    ];
                }
            }

            $projectPhaseResources = DB::table('project_phases_resources AS ppr')
                ->leftJoin('customer_project_phases AS cpp', function ($join) use ($Id) {
                    $join->on('ppr.ProjectPhaseId', 'cpp.ProjectPhaseId');
                })
                ->leftJoin('project_phases AS pp', function ($join) use ($Id) {
                    $join->on('cpp.ProjectPhaseId', 'pp.Id');
                })
                ->leftJoin('designations AS d', function ($join) use ($Id) {
                    $join->on('ppr.DesignationId', 'd.Id');
                })
                ->where('pp.Status', 1)
                ->where('d.Status', 1)
                ->where('cpp.CustomerId', $Id)
                ->get(['ppr.*', 'd.Name','d.Initial']); 

            foreach ($projectPhaseResources as $ppr) {
                
                $percentToDecimal = $ppr->Percentage / 100;

                if ($ppr->ProjectPhaseId == $cpp->ProjectPhaseId) {
                    $temp['Resources'][$ppr->Initial] = [
                        'Id'              => $ppr->Id,
                        'ppId'            => $cpp->ProjectPhaseId,
                        'DId'             => $ppr->DesignationId,
                        'Name'            => $ppr->Name,
                        'Initial'         =>$ppr->Initial,
                        'Percentage'      => $ppr->Percentage,
                        'ResourceManhour' => $temp['EffortHours'] * $percentToDecimal
                    ];
                }
            }

            $data[] = $temp;
        }

        // echo '<pre>';
        // print_r($data);
        // exit;

        return $data;
    }

    function getProjectPhaseResources($Id)
    {
        $data = [];
        $projectPhaseResources = DB::table('project_phases_resources AS ppr')
            ->leftJoin('customer_project_phases AS cpp', function ($join) use ($Id) {
                $join->on('ppr.ProjectPhaseId', 'cpp.ProjectPhaseId');
            })
            ->leftJoin('project_phases AS pp', function ($join) use ($Id) {
                $join->on('cpp.ProjectPhaseId', 'pp.Id');
            })
            ->leftJoin('designations AS d', function ($join) use ($Id) {
                $join->on('ppr.DesignationId', 'd.Id');
            })
            ->where('pp.Status', 1)
            ->where('d.Status', 1)
            ->where('cpp.CustomerId', $Id)
            ->groupBy('d.Id')
            ->get(['d.Id']); 
        
        foreach ($projectPhaseResources as $dt) {
            $data[] = Designation::select('designations.*', 'car.Level', 'car.Rate', 'car.Cost')
                ->leftJoin('customer_assessment_resources AS car', function ($join) use ($Id) {
                    $join->on('car.DesignationId', 'designations.Id');
                    $join->on('car.CustomerId', DB::raw("'$Id'"));
                })
                ->where('designations.Id', $dt->Id)
                ->first();
        }

        // echo '<pre>';
        // print_r($data);
        // exit;

        return $data;
    }

    function getInitials($name)
    {
        $string = $name;
        $expr = '/(?<=\s|^)\w/iu';
        preg_match_all($expr, $string, $matches);
        $result = implode('', $matches[0]);
        return strtoupper($result);
    }
}
