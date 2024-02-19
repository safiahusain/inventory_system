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
                                            <label>{{ trans('file.Supplier') }}</label>
                                            <input type="text" name="supplier_name" value="{{ $supplier->name }}" readonly class="form-control">
                                            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                        </div>
                                    </div>
                                    @php
                                        $data           =   $supplier->data   ?   json_decode($supplier->data)  :   null;
                                    @endphp
                                    <table id="example1" class="table table-hover table-striped shadow" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>{{__('Product')}}</th>
                                                <th>{{__('Time')}}</th>
                                                <th>{{__('Qty')}}</th>
                                                <th>{{__('Amount')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key =>  $value)
                                                @if ($value->status)
                                                    @if ($key   !=  'wanda')
                                                        @php
                                                            $time   =   $value->time;
                                                        @endphp
                                                        @if ($time->m_status    ||  $time->e_status)
                                                            <tr>
                                                                <td>
                                                                    <input class="form-control" type="text" name="{{$key}}" value="{{ucfirst($key)}}" readonly>
                                                                </td>
                                                                <td>
                                                                    @if ($time->m_status)
                                                                        <input class="form-control" type="text" name="{{"$key-m_status"}}" value="Morning" readonly>&nbsp;
                                                                    @endif
                                                                    @if ($time->e_status)
                                                                        <input class="form-control" type="text" name="{{"$key-e_status"}}" value="Evening" readonly>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($time->m_status)
                                                                        <input class="form-control" type="text" name="{{"$key-m_qty"}}" value="">&nbsp;
                                                                    @endif
                                                                    @if ($time->e_status)
                                                                        <input class="form-control" type="text" name="{{"$key-e_qty"}}" value="">
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="{{"$key-amount"}}" value="{{$value->amount}}" class="form-control">
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        {{-- @php
                                                            $time   =   $value->time;
                                                        @endphp
                                                        @foreach ($time as $t_key   =>  $t_value)
                                                            @if ($t_value   ==  1)
                                                                <tr>
                                                                    <td>
                                                                        <input class="form-control" type="text" name="{{$key}}" value="{{$key}}" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control" type="text" name="{{$t_key}}" value="{{$t_key == 'm_status' ?   'Morning'   :   'Evening'}}" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control" type="text" name="{{$t_key == 'm_status'    ?   'm_qty' :   'e_qty'}}" value="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="{{"$key-amount"}}" value="{{$value->amount}}" class="form-control">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach --}}
                                                    @else
                                                        <tr>
                                                            <td>
                                                                <input class="form-control" type="text" name="{{$key}}" value="{{ucfirst($key)}}" readonly>
                                                            </td>
                                                            <td>
                                                                <input class="form-control" type="text" name="" value="" readonly>
                                                            </td>
                                                            <td>
                                                                <input class="form-control" type="text" name="qty" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="{{"$key-amount"}}" value="{{$value->amount}}" class="form-control">
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
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


</script>
@endsection @section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

@endsection
