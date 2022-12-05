<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;

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
            'title'          => 'Add Customer',
            'data'    => User::all(),
            'type'           => 'insert',
        ];

        return view('customers.form', $data);
    }
    function editCustomer($Id)
    {

        $data = [

            'title'       => 'Edit Customer',
            'type'           => 'edit',

        ];

        return view('customers.form', $data);
    }



    function saveCustomer(Request $request, $Id)
    {
    }
    function updateCustomer(Request $request, $Id)
    {
    }

    function deleteCustomer($Id)
    {
    }
}
