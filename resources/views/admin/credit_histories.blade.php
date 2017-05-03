@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{{trans("admin.credit_header")}}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{{trans("admin.credit_header")}}}</p>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{{trans_choice("admin.credit_table_header",0)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",1)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",2)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",3)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",4)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",5)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",6)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",7)}}}</th>
                                    <th>{{{trans_choice("admin.credit_table_header",8)}}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($credit_histories) > 0)
                                @foreach($credit_histories as $credit)
                                <tr>
                                    <td>
                                        <a href = "{{{url('/profile')}}}/{{{$credit->user->id}}}">
                                            <div class="col-md-2 user-img-custom" style="background: url({{{ $credit->user->profile_pic_url() }}});background-size:contain;">
                                                
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href = "{{{url('/profile')}}}/{{{$credit->user->id}}}">{{{ $credit->user->name }}}</a>
                                    </td>
                                    <td>{{{date("d.m.Y", strtotime($credit->created_at))}}}</td>
                                 
                                    @if($credit->transTable_id == 0)
                                        <td>------------</td>
                                        <td>------------</td>
                                        <td>------------</td>
                                        <td>success</td>
                                        <td>-{{{$credit->credits}}}</td>
                                        <td>{{{$credit->activity}}}</td>
                                        
                                    @else
                                        <td>{{{$credit->transaction->transaction_id}}}</td>
                                        <td>{{{$credit->transaction->gateway}}}</td>
                                        <td>{{{$credit->transaction->amount}}}</td>
                                        <td>{{{$credit->transaction->status}}}</td>
                                        <td>+{{{$credit->credits}}}</td>
                                        <td>{{{$credit->activity}}}</td>
                                    @endif
                                  
                                   
                                </tr>
                                @endforeach
                            @else
                                <tr >
                                    <td colspan = "9" style = "text-align : center; color : red">{{{trans("admin.no_record")}}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        <div class="pagination pull-right">
                            {!! $credit_histories->render() !!}
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