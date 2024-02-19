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
                        <h4>{{trans('file.Add Supplier')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'supplier.store', 'method' => 'post', 'files' => true]) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.code')}}</strong> </label>
                                        <input type="text" name="code" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Driver')}} {{trans('file.name')}}</label>
                                        <input type="text" name="name" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Phone Number')}}</label>
                                        <input type="text" name="phone_number" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Res ph#')}}</label>
                                        <input type="text" name="res_phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Office ph#')}}</label>
                                        <input type="text" name="office_phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.City')}}</label>
                                        <input type="text" name="city" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Address')}}</label>
                                        <input type="text" name="address" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Status')}}</strong> </label>
                                        <select name="status" class="form-control" id="inputPaymentOption">
                                            <option selected value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Advance')}}</strong> </label>
                                        <div class="form-group">
                                            <input type="text" name="advance" required class="form-control">
                                        </div>
                                    </div>
                                </div>
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
                                        <tr>
                                            <td><input class="ch_input" type="checkbox" name="cow_status"></td>
                                            <td>
                                                <input class="form-control" type="text" name="cow" value="Cow" disabled>
                                            </td>
                                            <td>
                                                <input class="ch_input" type="checkbox" name="cow_morning">&nbsp;&nbsp;
                                                <label for="cow_morning">Morning</label><br>
                                                <input class="ch_input" type="checkbox" name="cow_evening">&nbsp;&nbsp;
                                                <label for="cow_evening">Evening</label>
                                            </td>
                                            <td>
                                                <input type="text" name="cow_amount" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input class="ch_input" type="checkbox" name="buffalo_status"></td>
                                            <td>
                                                <input class="form-control" type="text" name="buffalo" value="Buffalo" disabled>
                                            </td>
                                            <td>
                                                <input class="ch_input" type="checkbox" name="buffalo_morning">&nbsp;&nbsp;
                                                <label for="buffalo_morning">Morning</label><br>
                                                <input class="ch_input" type="checkbox" name="buffalo_evening">&nbsp;&nbsp;
                                                <label for="buffalo_evening">Evening</label>
                                            </td>
                                            <td>
                                                <input type="text" name="buffalo_amount" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input class="ch_input" type="checkbox" name="wanda_status"></td>
                                            <td>
                                                <input class="form-control" type="text" name="wanda" value="Wanda" disabled>
                                            </td>
                                            <td>
                                                <input class="ch_input d-none" type="checkbox" name="wanda_morning">&nbsp;&nbsp;
                                                <label class="d-none" for="wanda_morning">Morning</label><br>
                                                <input class="ch_input d-none" type="checkbox" name="wanda_evening">&nbsp;&nbsp;
                                                <label class="d-none" for="wanda_evening">Evening</label>
                                            </td>
                                            <td>
                                                <input type="text" name="wanda_amount" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <div class="form-group mt-4">
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
    $("ul#people #supplier-create-menu").addClass("active");
</script>
@endsection
