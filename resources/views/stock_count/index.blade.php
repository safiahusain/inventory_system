@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif

<section>
    <div class="table-responsive">
        <table id="stock-count-table" class="table stock-count-list">
            <thead>
                <tr class="text-center">
                    <th>{{trans('#Sr')}}</th>
                    <th>{{trans('file.product')}}</th>
                    <th>{{trans('file.stock')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i =  0;
                @endphp
                @foreach ($stock as $key => $value)
                    @php
                        $i++;
                    @endphp
                    <tr class="text-center">
                        <td>{{ $i }}</td>
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
