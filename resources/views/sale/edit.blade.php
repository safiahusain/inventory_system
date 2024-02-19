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
                        <h4>{{trans('file.Update Sale')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['sales.update', $lims_sale_data->id], 'method' => 'put', 'files' => true, 'id' => 'payment-form']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{trans('file.customer')}}</label>
                                                <select name="customer_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" required title="Select customer...">
                                                    @foreach($lims_customer_list as $customer)
                                                        <option value="{{$customer->id}}" @if($lims_sale_data->customer_id  ==  $customer->id) selected @endif>{{$customer->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="example1" class="table table-hover table-striped shadow" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>{{__('Product')}}</th>
                                                <th>{{__('Stock')}}</th>
                                                <th>{{__('AM Qty')}}</th>
                                                <th>{{__('PM Qty')}}</th>
                                                <th>{{__('Price')}}</th>
                                                <th>{{__('Paneer / Kahni Rate')}}</th>
                                                <th>{{__('Paneer / Kahni')}}</th>
                                                <th>{{__('Milk Rate Kg')}}</th>
                                                <th>{{__('Amount')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $data   =   $lims_sale_data->data  ?   json_decode($lims_sale_data->data) :   null;
                                            @endphp
                                            @foreach ($stock as $key =>  $value)
                                                @php
                                                    $data_key   =   $data->$key;
                                                    $qty        =   $data_key->qty;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input class="form-control" type="text" name="{{$key}}" value="{{ucfirst($key)}}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-stock"}}" type="text" id="{{"$key-stock"}}" name="{{"$key-stock"}}" value="{{$value}}" readonly>&nbsp;
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-m_qty"}}" type="text" id="{{"$key-m_qty"}}" name="{{"$key-m_qty"}}" onkeyup="calc(this)" value="{{$qty->m_qty}}">&nbsp;
                                                        <span class="qty_message"></span>
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-e_qty"}}" type="text" id="{{"$key-e_qty"}}" name="{{"$key-e_qty"}}" onkeyup="calc(this)" @if($key == 'wanda') readonly @endif value="{{$key != 'wanda'   ?   $qty->e_qty :   ''}}">
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-milk_rate"}}" type="text" id="{{"$key-milk_rate"}}" name="{{"$key-milk_rate"}}" onkeyup="calc(this)" value="{{$data_key->milk_rate}}">
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-pk_default_value"}}" type="text" id="{{"$key-pk_default_value"}}" name="{{"$key-pk_default_value"}}" onkeyup="calc(this)" @if($key == 'wanda') readonly @endif value="{{$key != 'wanda'   ?   $data_key->pk_default_value    :   ''}}">
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-pk_enter_value"}}" type="text" id="{{"$key-pk_enter_value"}}" name="{{"$key-pk_enter_value"}}" onkeyup="calc(this)" @if($key == 'wanda') readonly @endif value="{{$key != 'wanda'   ?   $data_key->pk_enter_value    :   ''}}">
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-pk_amount"}}" type="text" id="{{"$key-pk_amount"}}" name="{{"$key-pk_amount"}}" readonly value="{{$key != 'wanda'   ?   $data_key->pk_amount :   ''}}">
                                                    </td>
                                                    <td>
                                                        <input class="form-control {{"$key-amount"}}" type="text" id="{{"$key-amount"}}" name="{{"$key-amount"}}" readonly value="{{$data_key->amount}}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    function calc(x)
    {
        var index           =   $(x).parent().parent().index();
        let id              =   x.id;
        let target          =   id.split("-")[0];
        let pk_amount       =   target+"-pk_amount";
        var m_quantity      =   $("#"+target+"-m_qty").val();
        var e_quantity      =   $("#"+target+"-e_qty").val();
        var milk_rate       =   $("#"+target+"-milk_rate").val();
        var pk_default_value=   $("#"+target+"-pk_default_value").val();
        var pk_enter_value  =   $("#"+target+"-pk_enter_value").val();
        var stock           =   $("#"+target+"-stock").val();
        m_quantity          =   parseFloat(m_quantity) || 0;
        e_quantity          =   parseFloat(e_quantity) || 0;
        milk_rate           =   parseInt(milk_rate) || 0;
        pk_default_value    =   parseInt(pk_default_value) || 0;
        pk_enter_value      =   parseInt(pk_enter_value) || 0;
        stock               =   parseInt(stock);
        let new_milk_price  =   0;
        let total_pk_value  =   0;
        let negative_value  =   0;

        let total_quantity  =   m_quantity  +   e_quantity;

        if(target  == 'cow')
        {
            total_pk_value  =   pk_enter_value * pk_default_value;
            new_milk_price  =   total_pk_value / milk_rate;
            new_milk_price  =   parseFloat(new_milk_price.toFixed(2))   ||  0;
        }
        else if(target  == 'buffalo')
        {
            negative_value  =   pk_default_value - pk_enter_value;
            total_pk_value  =   (negative_value / pk_default_value) * milk_rate;
            total_pk_value  =   parseFloat(total_pk_value.toFixed(2))   ||  0;
            new_milk_price  =   milk_rate - total_pk_value;
        }
        else
        {
            new_milk_price  =   milk_rate;
        }

        if(target  != 'wanda')
        {
            $("#"+pk_amount).val(new_milk_price);
        }

        if(total_quantity > stock)
        {
            $(".qty_message").eq(index).show();
            $(".qty_message").eq(index).text("Qty must be less than Stock");
            $('#submit-btn').prop('disabled', true);
        }
        else
        {
            $(".qty_message").eq(index).hide();
            $('#submit-btn').prop('disabled', false);
            let total_price =   total_quantity  *   new_milk_price;
            total_price     =   parseFloat(total_price.toFixed(2))   ||  0;
            $("#"+target+"-amount").val(total_price);
        }
    }
</script>
@endsection @section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

@endsection
