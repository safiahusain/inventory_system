@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Add Purchase')}}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{trans('file.Supplier')}}</label>
                                            <select name="supplier_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" onchange="get_data(this)" title="Select supplier...">
                                                @foreach($lims_supplier_list as $supplier)
                                                    <option value="{{$supplier->id}}" data-supplier="{{$supplier->data}}">{{$supplier->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{trans('file.Purchase Status')}}</label>
                                            <select name="status" class="form-control">
                                                <option value="1">{{trans('file.Recieved')}}</option>
                                                <option value="2">{{trans('file.Partial')}}</option>
                                                <option value="3">{{trans('file.Pending')}}</option>
                                                <option value="4">{{trans('file.Ordered')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="table_data">

                                </div>
                                <div class="row mt-3">
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('file.Order Tax')}}</label>
                                            <select class="form-control" name="order_tax_rate">
                                                <option value="0">{{trans('file.No Tax')}}</option>
                                                @foreach($lims_tax_list as $tax)
                                                <option value="{{$tax->rate}}">{{$tax->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>
                                                <strong>{{trans('file.Discount')}}</strong>
                                            </label>
                                            <input type="number" name="order_discount" class="form-control" step="any" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>
                                                <strong>{{trans('file.Shipping Cost')}}</strong>
                                            </label>
                                            <input type="number" name="shipping_cost" class="form-control" step="any" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{trans('file.Note')}}</label>
                                            <textarea rows="5" class="form-control" name="note"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="submit-btn">{{trans('file.submit')}}</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    function get_data(x)
    {
        var selectedOption  =   x.options[x.selectedIndex];
        var supplierData    =   selectedOption.getAttribute('data-supplier');
        supplierData        =   JSON.parse(supplierData);
        let supplier_data   =   "";
        let total_amount    =   0;

        supplier_data   =  "<table id='example1' class='table table-hover table-striped shadow' style='width: 100%'>\
                                <thead>\
                                    <tr>\
                                        <th>{{__('Product')}}</th>\
                                        <th>{{__('Time')}}</th>\
                                        <th>{{__('Qty')}}</th>\
                                        <th>{{__('Amount')}}</th>\
                                    </tr>\
                                </thead>\
                                <tbody>";
                                    $.each(supplierData, function (key, value)
                                    {
                                        if (value.status)
                                        {
                                            total_amount += parseFloat(value.amount);
                                            if (key   !=  'wanda')
                                            {
                                                let time   =   value.time;
                                                if (time.m_status   ||  time.e_status)
                                                {
                                                    supplier_data   +=  "<tr>\
                                                                            <td>\
                                                                                <input class='form-control' type='text' name='"+ key +"' value='"+ key[0].toUpperCase() +key.slice(1) +"' readonly>\
                                                                            </td>\
                                                                            <td>";
                                                                                if (time.m_status)
                                                                                {
                                                                                    supplier_data   +=  "<input class='form-control' type='text' name='"+ key +"-m_status' value='Morning' readonly>&nbsp;";
                                                                                }
                                                                                if (time.e_status)
                                                                                {
                                                                                    supplier_data   +=  "<input class='form-control' type='text' name='"+ key +"-e_status' value='Evening' readonly>";
                                                                                }
                                                    supplier_data   +=      "</td>\
                                                                            <td>";
                                                                                if (time.m_status)
                                                                                {
                                                                                    supplier_data   +=  "<input class='form-control' type='text' id='"+ key +"-m_qty' name='"+ key +"-m_qty' onchange='calc(this)' value=''>&nbsp;";
                                                                                }
                                                                                if (time.e_status)
                                                                                {
                                                                                    supplier_data   +=  "<input class='form-control' type='text' id='"+ key +"-e_qty' name='"+ key +"-e_qty' onchange='calc(this)' value=''>";
                                                                                }
                                                    supplier_data   +=      "</td>\
                                                                            <td>\
                                                                                <input type='text' name='" + key+"-amount' value='"+ value.amount +"' class='form-control'>\
                                                                                <input type='hidden' value='"+ value.amount +"' id='" + key+"-amount' class='form-control'>\
                                                                            </td>\
                                                                        </tr>";
                                                }
                                            }
                                            else
                                            {
                                                supplier_data   +=  "<tr>\
                                                                        <td>\
                                                                            <input class='form-control' type='text' name='"+ key +"' value='"+ key[0].toUpperCase() +key.slice(1) +"' readonly>\
                                                                        </td>\
                                                                        <td>\
                                                                            <input class='form-control' type='text' name='' value='' readonly>\
                                                                        </td>\
                                                                        <td>\
                                                                            <input class='form-control' type='text' name='qty' onchange='calc(this)' value=''>\
                                                                        </td>\
                                                                        <td>\
                                                                            <input type='text' name='"+ key+"-amount' id='" + key+"-amount' value='"+ value.amount +"' class='form-control'>\
                                                                            <input type='hidden' value='"+ value.amount +"' id='" + key+"-amount' class='form-control'>\
                                                                        </td>\
                                                                    </tr>";
                                            }
                                        }
                                    });
        supplier_data   +=      "</tbody>\
                                    <tfoot class='tfoot'>\
                                        <th>{{trans('file.Total')}}</th>\
                                        <th></th>\
                                        <th id='total_qty'>0.00</th>\
                                        <th id='total_amount'>"+total_amount+"</th>\
                                    </tfoot>\
                                    <input type='hidden'>\
                            </table>";

        $('#table_data').empty().html(supplier_data);
    }

    function calc(x)
    {
        let id                  =   x.id;
        let target              =   id.split("-")[0];
        let pre_cow_amount      =   $('#cow-amount').val();
        let pre_buffalo_amount  =   $('#buffalo-amount').val();
        let pre_wanda_amount    =   $('#wanda-amount').val();
        let m_quantity          =   $("#"+target+"-m_qty").val();
        let e_quantity          =   $("#"+target+"-e_qty").val();
        m_quantity              =   parseFloat(m_quantity);
        e_quantity              =   parseFloat(e_quantity);

        let total_qty           =   m_quantity+e_quantity;

        console.log(x.id);

    }
</script>
@endsection @section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

@endsection
