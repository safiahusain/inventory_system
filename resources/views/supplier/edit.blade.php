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
                        <h4>{{trans('file.Update Supplier')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['supplier.update', $lims_supplier_data->id], 'method' => 'put', 'files' => true]) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.code')}}</strong> </label>
                                    <input type="text" name="code" value="{{$lims_supplier_data->code}}" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Driver')}} {{trans('file.name')}}</strong> </label>
                                    <input type="text" name="name" value="{{$lims_supplier_data->name}}" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Phone Number')}}</label>
                                    <input type="text" name="phone_number" value="{{$lims_supplier_data->phone_number}}" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Res ph#')}}</label>
                                    <input type="text" name="res_phone" value="{{$lims_supplier_data->res_phone}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Office ph#')}}</label>
                                    <input type="text" name="office_phone" value="{{$lims_supplier_data->office_phone}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.City')}}</label>
                                    <input type="text" name="city"  value="{{$lims_supplier_data->city}}" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Address')}}</label>
                                    <input type="text" name="address" value="{{$lims_supplier_data->address}}" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Status')}}</strong> </label>
                                    <select name="status" class="form-control" id="inputPaymentOption">
                                        <option {{ $lims_supplier_data->is_active == 1 ? 'selected' : '' }} value="1">Active</option>
                                        <option {{ $lims_supplier_data->is_active == 0 ? 'selected' : '' }} value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{trans('file.Advance')}}</strong> </label>
                                    <div class="form-group">
                                        <input type="text" name="advance" value="{{$lims_supplier_data->advance}}" required class="form-control">
                                    </div>
                                </div>
                            </div>
                            @php
                                $data       =   $lims_supplier_data->data   ?   json_decode($lims_supplier_data->data)  :   null;
                                $cow_data       =   null;
                                $buffalo_data   =   null;
                                $wanda_data     =   null;

                            @endphp
                            <table id="example1" class="table table-hover table-striped shadow" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>{{__('Checked')}}</th>
                                        <th>{{__('Product')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Amount')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key =>  $value)
                                        @php
                                            $time   =  $value->time;
                                        @endphp
                                        <tr>
                                            <td><input class="ch_input" id="" type="checkbox" {{ $value->status ==  1   ?   'checked'   :   '' }} name="{{"$key-status"}}"></td>
                                            <td>
                                                <input class="form-control" type="text" name="{{$key}}" value="{{$key}}" disabled>
                                            </td>
                                            <td>
                                                @if ($key   !=  'wanda')
                                                    <input type="checkbox" name="{{"$key-morning"}}" {{ $time->m_status   ==  1   ?   'checked'   :   '' }}>&nbsp;&nbsp;
                                                    <label for="{{"$key-morning"}}">Morning</label><br>
                                                    <input type="checkbox" name="{{"$key-evening"}}" {{ $time->e_status   ==  1   ?   'checked'   :   '' }}>&nbsp;&nbsp;
                                                    <label for="{{"$key-evening"}}">Evening</label>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" name="{{"$key-amount"}}" value="{{$value->amount}}" class="form-control">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
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
    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
</script>
@endsection
