<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Supplier;
use Illuminate\Validation\Rule;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use App\MilkSpoilation;
use App\Purchase;
use App\Tax;
use App\Warehouse;
use Dotenv\Validator;
use Illuminate\Support\Facades\Mail;

class MilkSpoilationController extends Controller
{
    public function index()
    {
        // $role = Role::find(Auth::user()->role_id);
        // if($role->hasPermissionTo('suppliers-index')){
            // $permissions = Role::findByName($role->name)->permissions;
            // foreach ($permissions as $permission)
            //     $all_permission[] = $permission->name;
            // if(empty($all_permission))
            //     $all_permission[] = 'dummy text';
            $milk_spoilations = MilkSpoilation::latest()->get();
            $all_permission=null;
            return view('milk-spoilation.index',compact('milk_spoilations', 'all_permission'));
        // }
        // else
        //     return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        // $role = Role::find(Auth::user()->role_id);
        // if($role->hasPermissionTo('purchases-add')){
            $lims_supplier_list =   Supplier::where('is_active', true)->get();
            $stock              =   Helper::update_stock();
            $active             =   false;

            foreach($stock  as  $key  =>  $value)
            {
                if($value   >   0)
                {
                    $active =   true;
                }
            }

            $InvoiceNumber = 'MSV-' . date("Ymd") . '-'. date("his");

            return view('milk-spoilation.create', compact('lims_supplier_list','stock','active','InvoiceNumber'));
        // }
        // else
        //     return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'invoice_id'   =>  'required',
            'range'        =>  'required',
            'user_id'      =>  'required',
            'supplier_id'  =>  'required',
            'remarks'      =>  'required',
            'total_qty'    =>  'required',
            'total_amount' =>  'required',
        ]);

        $data      =   [];

        for($i=0; $i<3; $i++)
        {
            $data      +=   [
                $request->product_name[$i]  =>  [
                    'code'   =>  $request->code[$i],
                    'unit'   =>  $request->unit[$i],
                    'qty'    =>  $request->quantity[$i],
                    'rate'   =>  $request->rate[$i],
                    'amount' =>  $request->amount[$i],
                ],
            ];
        }

        $created    =   MilkSpoilation::create([
            'invoice'       =>  $request->invoice_id,
            'user_id'       =>  $request->user_id,
            'supplier_id'   =>  $request->supplier_id,
            'description'   =>  $request->remarks,
            'total_qty'     =>  $request->total_qty,
            'date'          =>  $request->range,
            'total_amount'  =>  $request->total_amount,
            'data'          =>  json_encode($data),
        ]);

        if($created)
        {
            $message = 'Data inserted successfully';
        }
        else
        {
            $message = 'Something went wrong while creating supplier';
        }

        return redirect()->route('milk-spoilation-index')->with('message', $message);
    }

    public function edit($id)
    {
        // $role = Role::find(Auth::user()->role_id);
        // if($role->hasPermissionTo('suppliers-edit')){

            $milk_spoilation    =   MilkSpoilation::where('id',$id)->first();
            $lims_supplier_list =   Supplier::where('is_active', true)->get();
            $stock              =   Helper::update_stock();

            return view('milk-spoilation.edit',compact('lims_supplier_list','milk_spoilation','stock'));
        // }
        // else
            // return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'invoice_id'   =>  'required',
            'range'        =>  'required',
            'user_id'      =>  'required',
            'supplier_id'  =>  'required',
            'remarks'      =>  'required',
            'total_qty'    =>  'required',
            'total_amount' =>  'required',
        ]);

        $milk_spoilation  =  MilkSpoilation::where('id',$id)->first();

        if($milk_spoilation)
        {
            $data      =   [];

            for($i=0; $i<3; $i++)
            {
                $data      +=   [
                    $request->product_name[$i]  =>  [
                        'code'   =>  $request->code[$i],
                        'unit'   =>  $request->unit[$i],
                        'qty'    =>  $request->quantity[$i],
                        'rate'   =>  $request->rate[$i],
                        'amount' =>  $request->amount[$i],
                    ],
                ];
            }

            $updated    =   $milk_spoilation->update([
                'invoice'       =>  $request->invoice_id,
                'user_id'       =>  $request->user_id,
                'supplier_id'   =>  $request->supplier_id,
                'description'   =>  $request->remarks,
                'total_qty'     =>  $request->total_qty,
                'date'          =>  $request->range,
                'total_amount'  =>  $request->total_amount,
                'data'          =>  json_encode($data),
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
            $message = 'record not found';
        }

        return redirect()->route('milk-spoilation-index')->with('message', $message);
    }

    public function deleteBySelection(Request $request)
    {
        dd($request->all());
        $supplier_id = $request['supplierIdArray'];
        foreach ($supplier_id as $id) {
            $lims_supplier_data = MilkSpoilation::findOrFail($id);
            $lims_supplier_data->is_active = false;
            $lims_supplier_data->save();
        }
        return 'Supplier deleted successfully!';
    }

    public function destroy($id)
    {
        $lims_supplier_data =   MilkSpoilation::where('id',$id)->first();

        if($lims_supplier_data)
        {
            $deleted  =   $lims_supplier_data->delete();

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
            $message = 'Record not found';
        }

        return redirect()->route('milk-spoilation-index')->with('not_permitted',$message);
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

    // public function getStock(Request $request, $param)
    // {
    //     $purchase   =   Purchase::get();
    //     $total_qty  =   0;

    //     if(count($purchase) >  0)
    //     {
    //         foreach ($purchase as $key => $value)
    //         {
    //             $data   =   $value->data    ?   json_decode($value->data)  :   null;

    //             if($data)
    //             {
    //                 foreach ($data as $d_key => $d_value)
    //                 {
    //                     if($d_key   ==  $param)
    //                     {
    //                         $qty_data   =   $d_value->qty;
    //                         $qty        =   0;

    //                         if($param   !=  'wanda')
    //                         {
    //                             $m_qty  =   $qty_data->m_qty    ?   $qty_data->m_qty    :   0;
    //                             $e_qty  =   $qty_data->e_qty    ?   $qty_data->e_qty    :   0;
    //                             $qty    =   $m_qty  +   $e_qty;
    //                         }
    //                         else
    //                         {
    //                             $qty    =   $qty_data;
    //                         }

    //                         $total_qty  +=  $qty;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'total_qty'      =>  $total_qty,
    //     ]);
    // }
}
