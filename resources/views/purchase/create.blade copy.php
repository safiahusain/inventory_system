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
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'purchases.store', 'method' => 'post', 'files' => true, 'id' => 'purchase-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{trans('file.Supplier')}}</label>
                                            <select name="supplier_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" onchange="get_data(this)" title="Select supplier...">
                                                @foreach($lims_supplier_list as $supplier)
                                                    <option value="{{$supplier->id}}" data-supplier="{{$supplier->data}}">{{$supplier->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="table_data">

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
                                            if (key   !=  'wanda')
                                            {
                                                let time   =   value.time;
                                                if (time.m_status    ||  time.e_status)
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
                                                                                    supplier_data   +=  "<input class='form-control "+ key +"-m_qty' type='text' id='"+ key +"-m_qty' name='"+ key +"-m_qty' value='' onkeyup='calc(this)'>&nbsp;";
                                                                                }
                                                                                if (time.e_status)
                                                                                {
                                                                                    supplier_data   +=  "<input class='form-control "+ key +"-e_qty' type='text' id='"+ key +"-e_qty' name='"+ key +"-e_qty' value='' onkeyup='calc(this)'>";
                                                                                }
                                                    supplier_data   +=      "</td>\
                                                                            <td>\
                                                                                <input type='text' id='"+ key +"-amount' name='" + key+"-amount' value='"+ value.amount +"' onkeyup='calc(this)' class='form-control "+ key +"-amount'>\
                                                                                <input type='text' hidden id='amount' name='amount' value='' onkeyup='calc(this)' class='form-control amount'>\
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
                                                                            <input class='form-control' type='text' name='qty' value='' onkeyup='calc(this)'>\
                                                                        </td>\
                                                                        <td>\
                                                                            <input type='text' name='"+ key+"-amount' value='"+ value.amount +"' class='form-control' onkeyup='calc(this)'>\
                                                                            <input type='text' hidden id='amount' name='amount' value='' onkeyup='calc(this)' class='form-control amount'>\
                                                                            </td>\
                                                                            </tr>\
                                                                            <input type='text' hidden id='total_amount' name='total_amount' value='' onkeyup='calc(this)' class='form-control total_amount'>";
                                            }
                                        }
                                    });
        supplier_data   +=      "</tbody>\
                            </table>";

        $('#table_data').empty().html(supplier_data);
    }
</script>
@endsection @section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

@endsection
