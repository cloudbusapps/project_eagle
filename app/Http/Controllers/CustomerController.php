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
        $customerData = Customer::find($Id)->first();
        $requirements = DB::table('requirements')
            ->select('requirements.*', DB::raw('COUNT(sr."Details") as "hasDetails"'))
            ->leftJoin('sub_requirements AS sr', 'sr.RequirementId', 'requirements.Id')
            ->groupBy('requirements.Id')
            ->orderBy('hasDetails', 'asc')
            ->get();
        $subRequirements = DB::table('sub_requirements')
            ->select('sub_requirements.*')
            ->get();

        $data = [

            'title'           => $this->getTitle($customerData->Status),
            'type'            => 'edit',
            'data'            => $customerData,
            'Id'              => $Id,
            'requirements'    => $requirements,
            'subRequirements' => $subRequirements

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
                return 'Assessment';
            case 6:
                return 'Success';
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
        $customerData = Customer::find($Id)->first();
        $customerName             = $customerData->CustomerName;
        $Id             = $customerData->Id;
        if (isset($request->checkbox)) {
            $customerData->Status  = 2;
        } else {
            $customerData->Status  = 3;
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
}
