@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{{trans("admin.superpower_header")}}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{{trans("admin.superpower_header")}}}</p>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{{trans_choice("admin.superpower_table_header", 0)}}}</th>
                                    <th>{{{trans_choice("admin.superpower_table_header", 1)}}}</th>
                                    <th>{{{trans_choice("admin.superpower_table_header", 2)}}}</th>
                                    <th>{{{trans_choice("admin.superpower_table_header", 3)}}}</th>
                                    <th>{{{trans_choice("admin.superpower_table_header", 4)}}}</th>
                                    <th>{{{trans_choice("admin.superpower_table_header", 5)}}}</th>
                                    <th>{{{trans_choice("admin.superpower_table_header", 6)}}}</th>
                                    <th>{{{trans_choice("admin.superpower_table_header", 7)}}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($superpower_histories) > 0)
                                @foreach($superpower_histories as $superpower)
                                <tr>
                                    <td>
                                        <a href = "{{{url('/profile')}}}/{{{$superpower->user->id}}}">
                                            <div class="col-md-2 user-img-custom" style="background: url({{{ $superpower->user->profile_pic_url() }}});background-size:contain;">
                                                
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href = "{{{url('/profile')}}}/{{{$superpower->user->id}}}">{{{ $superpower->user->name }}}</a>
                                    </td>
                                    <td>{{{date("d.m.Y", strtotime($superpower->created_at))}}}</td>
                                    <td>{{{$superpower->transaction->transaction_id}}}</td>
                                    <td>{{{$superpower->transaction->gateway}}}</td>
                                    <td>{{{$superpower->superpower_package->package_name}}}</td>
                                    <td>{{{$superpower->transaction->amount}}}</td>
                                    <td>{{{$superpower->transaction->status}}}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr >
                                    <td colspan = "8" style = "text-align : center; color : red">{{{trans("admin.no_record")}}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        <div class="pagination pull-right">
                            {!! $superpower_histories->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"> -->
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
     $('#user-table').DataTable();
    } );
</script>

@endsection