<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use Illuminate\Validation\Rule;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;

class SupplierController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('suppliers-index'))
        {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[]   =   'dummy text';
                $lims_supplier_all  =   Supplier::latest()->get();
            return view('supplier.index',compact('lims_supplier_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('suppliers-add')){
            return view('supplier.create');
        }
        else
        {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code'          =>  'required',
            'name'          =>  'required',
            'phone_number'  =>  'required',
            'res_phone'     =>  'required',
            'office_phone'  =>  'required',
            'city'          =>  'required',
            'address'       =>  'required',
            'status'        =>  'required',
        ]);

        $products      =   [];

        if(isset($request->cow_status)  &&  (!$request->cow_morning  &&  !$request->cow_evening))
        {
            $message = 'Select atleast one time zone of Cow';
            return redirect()->back()->with('message', $message);
        }

        if(isset($request->buffalo_status)  &&  (!$request->buffalo_morning  &&  !$request->buffalo_evening))
        {
            $message = 'Select atleast one time zone of Buffalo';
            return redirect()->back()->with('message', $message);
        }

        $default_products   =   config('default.data');

        foreach($default_products   as $key =>  $value)
        {
            $status     =   $key.'_status';
            $morning    =   $key.'_morning';
            $evening    =   $key.'_evening';
            $amount     =   $key.'_amount';

            $products      +=   [
                $key    =>  [
                    'status'    =>  $request->$status   ?   1   :   0,
                    'time'      =>  [
                        'm_status'  =>  $request->$morning   ?   1   :   0,
                        'e_status'  =>  $request->$evening   ?   1   :   0,
                    ],
                    'amount'    =>  $request->$amount   ?   $request->$amount   :   null,
                ]
            ];
        }

        $created    =   Supplier::create([
            'code'          =>  $request->code,
            'name'          =>  $request->name,
            'phone_number'  =>  $request->phone_number,
            'res_phone'     =>  $request->res_phone,
            'office_phone'  =>  $request->office_phone,
            'city'          =>  $request->city,
            'driver'        =>  $request->driver,
            'address'       =>  $request->address,
            'advance'       =>  $request->advance   ?   $request->advance   :   null,
            'is_active'     =>  $request->status  ==  '1'    ?   true    :   false,
            'data'          =>  json_encode($products),
        ]);

        if($created)
        {
            $message = 'Data inserted successfully';
        }
        else
        {
            $message = 'Something went wrong while creating supplier';
        }

        return redirect('supplier')->with('message', $message);
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('suppliers-edit')){
            $lims_supplier_data = Supplier::where('id',$id)->first();
            return view('supplier.edit',compact('lims_supplier_data'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code'          =>  'required',
            'name'          =>  'required',
            'phone_number'  =>  'required',
            'res_phone'     =>  'required',
            'office_phone'  =>  'required',
            'city'          =>  'required',
            'address'       =>  'required',
            'status'        =>  'required',
        ]);

        $supplier           =  Supplier::where('id',$id)->first();

        if($supplier)
        {
            $products      =   [];

            if(isset($request->cow_status)  &&  (!$request->cow_morning  &&  !$request->cow_evening))
            {
                $message = 'Select atleast one time zone of Cow';
                return redirect()->back()->with('message', $message);
            }

            if(isset($request->buffalo_status)  &&  (!$request->buffalo_morning  &&  !$request->buffalo_evening))
            {
                $message = 'Select atleast one time zone of Buffalo';
                return redirect()->back()->with('message', $message);
            }

            $default_products   =   config('default.data');

            foreach($default_products   as $key =>  $value)
            {
                $status     =   $key.'-status';
                $morning    =   $key.'-morning';
                $evening    =   $key.'-evening';
                $amount     =   $key.'-amount';

                $products      +=   [
                    $key    =>  [
                        'status'    =>  $request->$status   ?   1   :   0,
                        'time'      =>  [
                            'm_status'  =>  $request->$morning   ?   1   :   0,
                            'e_status'  =>  $request->$evening   ?   1   :   0,
                        ],
                        'amount'    =>  $request->$amount   ?   $request->$amount   :   null,
                    ]
                ];
            }

            $updated    =   $supplier->update([
                'code'          =>  $request->code,
                'name'          =>  $request->name,
                'phone_number'  =>  $request->phone_number,
                'res_phone'     =>  $request->res_phone,
                'office_phone'  =>  $request->office_phone,
                'city'          =>  $request->city,
                'address'       =>  $request->address,
                'advance'       =>  $request->advance   ?   $request->advance   :   null,
                'is_active'     =>  $request->status  ==  '1'    ?   true    :   false,
                'data'          =>  json_encode($products),
            ]);

            if($updated)
            {
                $message = 'Data updated successfully';
            }
            else
            {
                $message = 'Something went wrong while updating supplier';
            }
        }
        else
        {
            $message = 'Supplier not found';
        }

        return redirect('supplier')->with('message', $message);
    }

    public function deleteBySelection(Request $request)
    {
        $supplier_id = $request['supplierIdArray'];
        foreach ($supplier_id as $id) {
            $lims_supplier_data = Supplier::findOrFail($id);
            $lims_supplier_data->is_active = false;
            $lims_supplier_data->save();
        }
        return 'Supplier deleted successfully!';
    }

    public function destroy($id)
    {
        $lims_supplier_data =   Supplier::where('id',$id)->first();

        if($lims_supplier_data)
        {
            $deleted            =   $lims_supplier_data->delete();

            if($deleted)
            {
                $message = 'Data deleted successfully';
            }
            else
            {
                $message = 'Something went wrong while updating supplier';
            }
        }
        else
        {
            $message = 'Supplier not found';
        }

        return redirect('supplier')->with('not_permitted',$message);
    }

    public function importSupplier(Request $request)
    {
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
        $filePath=$upload->getRealPath();
        //open and read
        $file=fopen($filePath, 'r');
        $header= fgetcsv($file);
        $escapedHeader=[];
        //validate
        foreach ($header as $key => $value) {
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
                continue;
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
           $data= array_combine($escapedHeader, $columns);

           $supplier = Supplier::firstOrNew(['company_name'=>$data['companyname']]);
           $supplier->name = $data['name'];
           $supplier->image = $data['image'];
           $supplier->vat_number = $data['vatnumber'];
           $supplier->email = $data['email'];
           $supplier->phone_number = $data['phonenumber'];
           $supplier->address = $data['address'];
           $supplier->city = $data['city'];
           $supplier->state = $data['state'];
           $supplier->postal_code = $data['postalcode'];
           $supplier->country = $data['country'];
           $supplier->is_active = true;
           $supplier->save();
           $message = 'Supplier Imported Successfully';
           if($data['email']){
                try{
                    Mail::send( 'mail.supplier_create', $data, function( $message ) use ($data)
                    {
                        $message->to( $data['email'] )->subject( 'New Supplier' );
                    });
                }
                catch(\Excetion $e){
                    $message = 'Supplier imported successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                }
            }
        }
        return redirect('supplier')->with('message', $message);
    }
}
