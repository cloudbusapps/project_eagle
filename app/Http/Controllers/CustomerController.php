<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

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
        $data = [
            'title'   => $this->getTitle(),
            'data'    => User::all(),
            'type'    => 'insert',
        ];

        return view('customers.form', $data);
    }
    function edit($Id)
    {
        $customerData = Customer::find($Id);
        if ($customerData->Status == 6) {
            $data = [
                'users'           => User::all(),
            ];
        }
        $data = [
            'title'           => $this->getTitle($customerData->Status),
            'type'            => 'edit',
            'data'            => $customerData,
            'Id'              => $Id,
            'complexities'    => $this->getComplexityData()

        ];

        return view('customers.form', $data);
    }

    public function getTitle($Status = null)
    {
        switch ($Status) {
            case 1:
                return 'Complexity';
            case 2:
                return 'Deployment Strategy Workshop';
            case 3:
                return 'Business Process';
            case 4:
                return 'Requirements and Solutions';
            case 5:
                return 'Project Phase';
            case 6:
                return 'Assessment';
            case 7:
                return 'Proposal';
            default:
                return 'Information';
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
            $customerData->Status = 4;
        } else if ($customerData->Status == 4) {
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
}
