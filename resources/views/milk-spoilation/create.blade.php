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
                        <h4>{{trans('file.Milk Spoilation')}}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'milk-spoilation-store', 'method' => 'post', 'files' => true, 'id' => 'milk-spoilation-form']) !!}
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <label>{{trans('file.Invoice')}}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="invoice_id" value="{{$InvoiceNumber}}" readonly class="form-control" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <label>{{trans('file.Date')}}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        @if(Session::get('range'))
                                                            <input  type="text" name="range" value="{{ Session::get('range') }}" class="form-control float-right"  required id="reservation" style="background-color:#efefef;" readonly />
                                                        @else
                                                            <input type="text" name="range"  required value="" class="form-control float-right" id="reservation" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-3">
                                                <label>{{trans('file.Prepared By')}}</label>
                                            </div>
                                            <div class="col-9">
                                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}" class="form-control" >
                                                <input type="text" name="user_name" value="{{Auth::user()->name}}" readonly class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-3">
                                                <label>{{trans('file.Driver')}}</label>
                                            </div>
                                            <div class="col-9">
                                                <select name="supplier_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select supplier...">
                                                    <option value="" selected disabled>Select Driver</option>
                                                    @foreach($lims_supplier_list as $key => $value)
                                                        <option value="{{$value->id}}" required>{{$value->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-3">
                                                <label>{{trans('file.Remarks')}}</label>
                                            </div>
                                            <div class="col-9">
                                                <input type="text" name="remarks" value="" required class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="box col-12">
                                            <div class="box-inner">
                                                <div class="box-content">
                                                    <table class="table table-striped table-bordered bootstrap-datatable responsive invoice-table" id="myTable">
                                                        <thead>
                                                            <tr>
                                                                <th width="100">{{trans('file.Code')}}</th>
                                                                <th width="100">{{trans('file.item')}}</th>
                                                                <th width="100">{{trans('file.unit')}}</th>
                                                                <th width="100">{{trans('file.stock')}}</th>
                                                                <th width="100">{{trans('file.Qty')}}</th>
                                                                <th width="100">{{trans('file.rate')}}</th>
                                                                <th width="100">{{trans('file.Amount')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="TBody">
                                                            @php
                                                                $i  = 0;
                                                            @endphp
                                                            @foreach ($stock as $key => $value)
                                                                @php
                                                                    $i++;
                                                                @endphp
                                                                <tr id="TRow">
                                                                    <!-- ********************product id************************************* -->
                                                                    <td>
                                                                        <input class="form-control combine num_only_integer_quantity code vertical"
                                                                            type="text"  name="code[]" tabIndex="-1" readonly value="{{$i}}" style="width:100px;"/>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control combine num_only_integer_quantity product vertical"
                                                                        type="text"  name="product_name[]" tabIndex="-1" value="{{$key}}" readonly style="width:100px;"/>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control combine num_only_integer_quantity unit vertical"
                                                                            type="text"  name="unit[]" tabIndex="-1" value="kg" readonly style="width:100px;"/>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control combine num_only_integer_quantity vertical total_quantity"
                                                                            type="text"  name="total_quantity[]" tabIndex="-1" value="{{$value}}" style="width:100px;"/>
                                                                    </td>
                                                                    <td style="padding-right: 3px">
                                                                        <input class="form-control combine num_only_integer_quantity qty vertical"
                                                                        type="text"  name="quantity[]" tabIndex="-1" value="" onkeyup="calc(this)" style="width:100px;"/>
                                                                        <span class="qty_message"></span>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control combine num_only_integer_quantity milk_rate vertical"
                                                                            type="text"  name="rate[]" tabIndex="-1" value="" onkeyup="calc(this)" style="width:100px;"/>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control combine num_only_integer_quantity amount vertical"
                                                                            type="text"  name="amount[]" tabIndex="-1" value="" readonly style="width:100px;"/>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <!-- ***************************start next *************************** -->
                                                        <tfoot id="tfoot">
                                                            <tr>
                                                                <td colspan="3">
                                                                </td>
                                                                <td colspan="2" class="total">Total Qty:</td>
                                                                <td colspan="2">
                                                                    <input type="text" id="total_qty" class="form-control total_value" value="" readonly tabIndex="-1" name="total_qty" style="width:190px;">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <td colspan="2" class="discount">Total Amount:</td>
                                                                <td colspan="2" class="discount-td">
                                                                    <input readonly class="form-control form-control discount-val num_only_decimal vertical" value="0.00" id="total_amount"
                                                                    type="text" name="total_amount" readonly tabIndex="-1" style="width:190px;"/>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="submit-btn" @if(!$active)  disabled @endif>{{trans('file.submit')}}</button>
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

    // function get_stock(x)
    // {
    //     var index           =   $(x).parent().parent().parent().index();
    //     var selectedOption  =   x.options[x.selectedIndex];
    //     var param           =   selectedOption.value;
    //     var route           =   "{{ route('get-stock', ':param') }}";
    //     route               =   route.replace(':param', param);

    //     $.ajax(
    //     {
    //         url         :   route,
    //         type        :   'get',
    //         success     :   function(success_resp)
    //         {
    //             let total_qty   =   success_resp.total_qty;
    //             console.log(total_qty,index);
    //             $('.total_qty').eq(index).val(total_qty);
    //         }
    //     });
    // }

    function calc(x)
    {
        var index       =   $(x).parent().parent().index();
        var quantity    =   document.getElementsByClassName("qty")[index].value;
        var price       =   document.getElementsByClassName("milk_rate")[index].value;
        let stock       =   document.getElementsByClassName("total_quantity")[index].value;
        quantity        =   parseInt(quantity) || 0;
        stock           =   parseInt(stock);

        if(quantity>stock)
        {
            $(".qty_message").eq(index).show();
            $(".qty_message").eq(index).text("Qty must be less than Total Quantity");
            $('#submit-btn').prop('disabled', true);
        }
        else
        {
            $(".qty_message").eq(index).hide();
            var total       =   quantity * price ;
            $('#submit-btn').prop('disabled', false);
            $('.amount').eq(index).val(total);

            var total_amount    =   0;
            var amount          =   document.getElementsByClassName("amount");

            for(let index = 0; index < amount.length; index++)
            {
                var pre_amount      =   amount[index].value;
                var total_amount    =   +(total_amount) + +(pre_amount);
            }

            $("#total_amount").val(total_amount);

            var total_quantity    =   0;
            var quantity          =   document.getElementsByClassName("qty");

            for(let index = 0; index < quantity.length; index++)
            {
                var pre_quantity      =   quantity[index].value;
                var total_quantity    =   +(total_quantity) + +(pre_quantity);
            }

            $("#total_qty").val(total_quantity);
        }
    }

    $(function() 
    {
        $('#reservation').daterangepicker({
            "autoapply"       : true,
            "linkedCalendars": false,
            "showDropdowns"   : false,  // Disable month and year dropdowns
            "showCustomRange": false,   // Disable custom range option
            "locale"          : {
                cancelLabel : 'Clear'
            }
        }, function(start, end, label) {
            $('#reservation').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
        });

        $('.drp-calendar.right').hide();
        $('.drp-calendar.left').addClass('single');
    });

    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>
@endsection @section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

@endsection
