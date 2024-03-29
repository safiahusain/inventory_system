@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
@endif
<section>
    <div class="container-fluid">
        @if(in_array("suppliers-add", $all_permission))
        <a href="{{route('purchases.create')}}" class="btn btn-info"><i class="dripicons-plus"></i> {{trans('file.Add Purchase')}}</a>
        {{-- <a href="#" data-toggle="modal" data-target="#importSupplier" class="btn btn-primary"><i class="dripicons-copy"></i> {{trans('file.Import Supplier')}}</a> --}}
        @endif
    </div>
    <div class="table-responsive">
        <table id="purchases-table" class="table">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.Supplier')}}</th>
                    <th>{{trans('file.Product Rate')}}</th>
                    <th>{{trans('file.product Qty')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $key => $purchase)
                <tr data-id="{{$purchase->id}}">
                    <td>{{$key}}</td>
                    <td>{{ $purchase->created_at}}</td>
                    <td>{{ $purchase->supplier ?   $purchase->supplier->name   :   ''}}</td>
                    @php
                        $data   =   $purchase->data ?   json_decode($purchase->data)    :   null;
                    @endphp
                    <td>
                        @foreach($data as $data_key =>  $value)
                            @php
                                $total_amount  =   $value->amount;
                            @endphp
                            {{$data_key.' ('.$total_amount.')'}}
                            <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($data as $data_key =>  $value)
                            @php
                                $qty    =   $value->qty;

                                if($data_key    !=  'wanda')
                                {
                                    $m_qty      =   $qty->m_qty;
                                    $e_qty      =   $qty->e_qty;
                                    $total_qty  =   $m_qty + $e_qty;
                                }
                                else
                                {
                                    $total_qty  =   $qty;
                                }
                            @endphp
                            {{$data_key.' ('.$total_qty.')'}}
                            <br>
                        @endforeach
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-link"><i class="dripicons-document-edit"></i></a>
                            {{ Form::open(['route' => ['purchases.destroy', $purchase->id], 'method' => 'DELETE'] ) }}
                                <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i></button>
                            {{ Form::close() }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<div id="importSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
	<div role="document" class="modal-dialog">
	  <div class="modal-content">
	  	{!! Form::open(['route' => 'purchase.import', 'method' => 'post', 'files' => true]) !!}
	    <div class="modal-header">
	      <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Import Supplier')}}</h5>
	      <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
	    </div>
	    <div class="modal-body">
	      <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
	       <p>{{trans('file.The correct column order is')}} (name*, image, company_name*, vat_number, email*, phone_number*, address*, city*,state, postal_code, country) {{trans('file.and you must follow this')}}.</p>
           <p>{{trans('file.To display Image it must be stored in')}} public/images/purchases {{trans('file.directory')}}</p>
	        <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{trans('file.Upload CSV File')}} *</label>
                        {{Form::file('file', array('class' => 'form-control','required'))}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{trans('file.Sample File')}}</label>
                        <a href="public/sample_file/sample_supplier.csv" class="btn btn-info btn-block btn-md"><i class="dripicons-download"></i> {{trans('file.Download')}}</a>
                    </div>
                </div>
            </div>
	        <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
		</div>
		{!! Form::close() !!}
	  </div>
	</div>
</div>

<script type="text/javascript">

    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
    $("ul#people #purchases-list-menu").addClass("active");

    var all_permission = <?php echo json_encode($all_permission) ?>;
    var supplier_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	function confirmDelete() {
	    if (confirm("Are you sure want to delete?")) {
	        return true;
	    }
	    return false;
	}

    $('#purchases-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
             "info":      '<small>{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
            "search":  '{{trans("file.Search")}}',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 1, 5]
            },
            {
                'checkboxes': {
                   'selectRow': true
                },
                'targets': 0
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '{{trans("file.PDF")}}',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    rows: ':visible',
                    stripHtml: false
                },
                customize: function(doc) {
                    for (var i = 1; i < doc.content[1].table.body.length; i++) {
                        if (doc.content[1].table.body[i][0].text.indexOf('<img src=') !== -1) {
                            var imagehtml = doc.content[1].table.body[i][0].text;
                            var regex = /<img.*?src=['"](.*?)['"]/;
                            var src = regex.exec(imagehtml)[1];
                            var tempImage = new Image();
                            tempImage.src = src;
                            var canvas = document.createElement("canvas");
                            canvas.width = tempImage.width;
                            canvas.height = tempImage.height;
                            var ctx = canvas.getContext("2d");
                            ctx.drawImage(tempImage, 0, 0);
                            var imagedata = canvas.toDataURL("image/png");
                            delete doc.content[1].table.body[i][0].text;
                            doc.content[1].table.body[i][0].image = imagedata;
                            doc.content[1].table.body[i][0].fit = [30, 30];
                        }
                    }
                },
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    rows: ':visible',
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column === 0 && (data.indexOf('<img src=') !== -1)) {
                                var regex = /<img.*?src=['"](.*?)['"]/;
                                data = regex.exec(data)[1];
                            }
                            return data;
                        }
                    }
                },
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    rows: ':visible',
                    stripHtml: false
                },
            },
            {
                extend: 'colvis',
                text: '{{trans("file.Column visibility")}}',
                columns: ':gt(0)'
            },
        ],
    } );

    if(all_permission.indexOf("suppliers-delete") == -1)
        $('.buttons-delete').addClass('d-none');

</script>
@endsection
