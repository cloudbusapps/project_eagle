<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\customer\CustomerBusinessProcess;
use App\Models\customer\CustomerBusinessProcessFiles;
use App\Models\customer\CustomerInscope;
use App\Models\customer\CustomerLimitation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;

class CustomerController extends Controller
{
    function index()
    {
        if (session()->has('LoggedUser')) {
            $customerData = Customer::all();
            $data = [
                'title'   => "Customer",
                'data'    => $customerData
            ];
            return view('customers.index', $data);
        } else {
            return view('/auth/login');
        }
    }

    function form()
    {
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

        $businessProcessData = CustomerBusinessProcess::select('customer_business_process.*')
            ->where('CustomerId', $Id)
            ->leftJoin('customer_business_process_files AS cbpf', 'cbpf.BusinessProcessId', '=', 'customer_business_process.Id')
            ->first();
        $files =$businessProcessData? CustomerBusinessProcessFiles::where('BusinessProcessId', $businessProcessData->Id)->orderBy('created_at', 'DESC')->get():[];


        $inscopes = CustomerInscope::where('CustomerId',$Id)->get();
        $limitations = CustomerLimitation::where('CustomerId',$Id)->get();
        
        $title = $this->getTitle($customerData->Status, $progress);

        $data = [
            'title'             => $title[0],
            'currentViewStatus' => $title[1],
            'type'            => 'edit',
            'data'            => $customerData,
            'Id'              => $Id,
            'complexities'    => $this->getComplexityData(),
            'ProjectPhase'    => $this->getProjectPhase(),
            'users'           => User::all(),
            'businessProcessData' => $businessProcessData,
            'files' => $files,
            'reqSol' => $inscopes,
            'limitations' => $limitations,

        ];


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
        } else if ($Status == 5 || $Progress == 'projectPhase') {
            return ['Project Phase', 5];
        } else if ($Status == 6 || $Progress == 'assessment') {
            return ['Assessment', 6];
        } else if ($Status == 7 || $Progress == 'proposal') {
            return ['Proposal', 7];
        } else {
            return ['Success', 8];
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
    function update(Request $request, $Id)
    {
        $now = Carbon::now('utc')->toDateTimeString();
        $customerData = Customer::find($Id);
        $customerName             = $customerData->CustomerName;
        $Id             = $customerData->Id;

        // CHECKING IF COMPLEX
        if ($customerData->Status == 1) {

            if (isset($request->checkbox)) {

                $customerData->Status  = 2;
                $customerData->DSWStatus  = 0;
            } else {
                $customerData->Status  = 3;
            }
        } else if ($customerData->Status == 2) {
            // IF COMPLEX
            if ($customerData->DSWStatus == 0) {
                $customerData->DSWStatus = 1;
            } else if ($customerData->DSWStatus == 1) {
                $customerData->DSWStatus = 2;
            } else if ($customerData->DSWStatus == 2) {
                $customerData->DSWStatus = 3;
            } else if ($customerData->DSWStatus == 3) {
                $customerData->DSWStatus = 4;
            } else if ($customerData->DSWStatus == 4) {
                $customerData->Status = 3;
            }
        } else if ($customerData->Status == 3) {
            $validator = $request->validate([
                'BusinessNotes'        => ['required'],
                'File'   => ['required'],
            ]);
            $destinationPath = 'uploads/businessProcess';


            $BusinessProcess = new CustomerBusinessProcess;
            $BusinessProcess->Note = $request->BusinessNotes;
            $BusinessProcess->CustomerId    = $Id;
            if ($BusinessProcess->save()) {
                $BusinessProcessId = $BusinessProcess->Id;
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
                            'BusinessProcessId' => $BusinessProcessId,
                            'File'           => $filename,
                            'CreatedById'    => Auth::id(),
                            'UpdatedById'    => Auth::id(),
                            'created_at'    => $now,
                        ];
                    }

                    CustomerBusinessProcessFiles::where('BusinessProcessId', $BusinessProcessId)->delete();
                    CustomerBusinessProcessFiles::insert($businessProcessFiles);
                }
            }


            $customerData->Status = 4;
        } else if ($customerData->Status == 4) {

            $validator = $request->validate([
                'Title' => ['required'],
                'Description' => ['required'],
                'Module' => ['required'],
                'Solution' => ['required'],
                'Assumption' => ['required'],
                'OutOfScope' => ['required'],
                'Comment' => ['required'],
            ]);

            $Title = $request->Title;
            $Description = $request->Description;
            $Module = $request->Module;
            $Solution = $request->Solution;
            $Assumption = $request->Assumption;
            $OutOfScope = $request->OutOfScope;
            $Comment = $request->Comment;
            if ($Title && count($Title)) {
                $inscopeData = [];
                $limitationData = [];
                foreach ($Title as $i => $title) {
                    $inscopeData[] = [
                        'Id'           => Str::uuid(),
                        'CustomerId' => $Id,
                        'Title'        => $title,
                        'Description'       => $Description[$i],
                        'Module'       => $Module[$i],
                        'Solution'       => $Solution[$i],
                        'Assumption'       => $Assumption[$i],
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }

                CustomerInscope::insert($inscopeData);
                foreach ($OutOfScope as $i => $outscope) {
                    $limitationData[] = [
                        'Id'           => Str::uuid(),
                        'CustomerId' => $Id,
                        'OutScope'        => $outscope,
                        'Comment'        => $Comment[$i],
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }
                CustomerLimitation::insert($limitationData);
            }
            $customerData->Status = 5;
        } else if ($customerData->Status == 5) {

            $customerData->Status = 6;
        } else if ($customerData->Status == 6) {

            $customerData->Status = 7;
        }


        // } else if ($customerData->Status  == 2 && $customerData->DSWStatus == 4) {
        //     $customerData->Status = 3;
        // }

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
    function getComplexityData()
    {
        $data = [];

        $complexity = DB::table('complexity')->get();

        foreach ($complexity as $index => $complex) {
            $temp = [
                'Id' => $complex->Id,
                'Title' => $complex->Title,
                'Status'  => $complex->Status,
                'CreatedById'  => $complex->CreatedById,
                'UpdatedById'  => $complex->UpdatedById,
                'created_at'  => $complex->created_at,
                'updated_at'  => $complex->updated_at,
                'Details' => []
            ];

            $complexityDetails = DB::table('complexity_details')
                ->where('Status', 1)
                ->where('ComplexityId', $complex->Id)
                ->get();

            foreach ($complexityDetails as $complexityDetail) {

                if ($complexityDetail->ComplexityId == $complex->Id) {
                    $temp['Details'][] = [
                        'Id'        => $complexityDetail->Id,
                        'ComplexityId'     => $complexityDetail->ComplexityId,
                        'Title'    => $complexityDetail->Title,
                        'Status'  => $complexityDetail->Status,
                        'CreatedById'  => $complexityDetail->CreatedById,
                        'UpdatedById'  => $complexityDetail->UpdatedById,
                        'created_at'  => $complexityDetail->created_at,
                        'updated_at'  => $complexityDetail->updated_at,
                    ];
                }
            }
            $data[] = $temp;
        }
        // echo "<pre>";
        // print_r($complexity);
        // exit;
        return $data;
    }
    function getProjectPhase()
    {
        $data = [];

        $projectPhase = DB::table('project_phases')->get();

        foreach ($projectPhase as $index => $pp) {
            $temp = [
                'Id' => $pp->Id,
                'Title' => $pp->Title,
                'Status'  => $pp->Status,
                'Required'  => $pp->Required,
                'Percentage'  => $pp->Percentage,
                'CreatedById'  => $pp->CreatedById,
                'UpdatedById'  => $pp->UpdatedById,
                'created_at'  => $pp->created_at,
                'updated_at'  => $pp->updated_at,
                'Details' => []
            ];

            $projectPhasesDetails = DB::table('project_phases_details')
                ->where('Status', 1)
                ->where('ProjectPhaseId', $pp->Id)
                ->get();

            foreach ($projectPhasesDetails as $ppd) {

                if ($ppd->ProjectPhaseId == $pp->Id) {
                    $temp['Details'][] = [
                        'Id'        => $ppd->Id,
                        'ProjectPhaseId'     => $ppd->ProjectPhaseId,
                        'Title'    => $ppd->Title,
                        'Status'  => $ppd->Status,
                        'Required'  => $ppd->Required,
                        'CreatedById'  => $ppd->CreatedById,
                        'UpdatedById'  => $ppd->UpdatedById,
                        'created_at'  => $ppd->created_at,
                        'updated_at'  => $ppd->updated_at,
                    ];
                }
            }
            $data[] = $temp;
        }
        // echo "<pre>";
        // print_r($complexity);
        // exit;
        return $data;
    }
}
