@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.photo_reports',1)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                <div class="col-md-12 section-first">
                    <h4 class="user-statistics">{{trans_choice('admin.photo_abuse_stat',1)}}</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="total-users">{{trans_choice('admin.overall',1)}}</p>
                            <p class="total-users-count">{{{$totalreports}}}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="this-month">{{trans_choice('admin.this',1)}} {{trans_choice('admin.month',1)}}</p>
                            <p class="total-users-count">{{{$totalthismonth}}}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="today">{{trans_choice('admin.today',1)}}</p>
                            <p class="total-users-count">{{{$totaltoday}}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.unseen_reports',2)}}</p>
                            <div class="dropdown dropdown-custom-right">
                                <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                <ul class="dropdown-menu">
                                    <li class="seen_action_button" data-action="markseen"><a href="javascript:;">{{trans_choice('admin.mark_seen',1)}}</a></li>
                                    <li class="seen_action_button" data-action="remove"><a href="javascript:;">{{trans_choice('admin.delete_selected',1)}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all_unseens" value=""></th>
                                    <th>{{trans_choice('admin.reporting_user',1)}}</th>
                                    <th>{{trans_choice('admin.reported_user',1)}}</th>
                                    <th>{{trans_choice('admin.reported_photo',1)}}</th>
                                    <th>{{trans_choice('admin.reason',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($unseens->count > 0)
                                @foreach($unseens as $unseen)
                                <tr>
                                    <td><input class="unseen-checkbox" type="checkbox" data-unseen-id="{{{ $unseen->id }}}" value=""></label></td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="float:left;margin-right:10px;background: url({{{ $unseen->reportinguser->profile_pic_url() }}});background-size:contain;">
                                            
                                        </div><p>{{{$unseen->reportinguser->name}}}</p>
                                    </td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="float:left;margin-right:10px;background: url({{{ $unseen->reporteduser->profile_pic_url() }}});background-size:contain;">
                                            
                                        </div><p>{{{$unseen->reporteduser->name}}}</p>
                                    </td>
                                    <td>
                                        <div class="col-md-2 user-img-custom reported-photos" style="background: url({{{$unseen->photos()->withTrashed()->first()->photo_url()}}});background-size:contain;cursor:pointer"></div>
                                    </td>
                                    <td>{{{ $unseen->reason }}}</td>
                                    <td>
                                        @if(isset($unseen->photos()->withTrashed()->first()->deleted_at))
                                        User Deleted
                                        @else
                                        Unseen
                                        @endif
                                    </td>
                                </tr>
                                @endforeach    
                                @else
                                <tr >
                                    <td colspan = "8" style = "text-align : center; color : red">{{trans_choice('admin.no_record',1)}}</td>
                                </tr>
                                @endif    
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        <!--
                            <div class="dropup dropup-custom">
                               <button class="btn btn-primary dropdown-toggle dropdown-ul-custom" type="button" data-toggle="dropdown">10
                               <span class="fa fa-caret-up"></span></button>
                               <ul class="dropdown-menu drop-rows-custom">
                                  <li><a href="#">5</a></li>
                                  <li><a href="#">10</a></li>
                                  <li><a href="#">15</a></li>
                                  <li><a href="#">20</a></li>
                                  <li><a href="#">25</a></li>
                                  <li><a href="#">30</a></li>
                                  <li><a href="#">35</a></li>
                                  <li><a href="#">40</a></li>
                                  <li><a href="#">45</a></li>
                                  <li><a href="#">50</a></li>
                               </ul>
                            </div>
                            <p class="no-of-rows">Rows per Page:</p> -->
                        <div class="pagination pull-right">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive" style = "padding-left:0px">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text" style="padding-left: 21px">{{trans_choice('admin.seen_reports',2)}}</p>
                            <div class="dropdown dropdown-custom-right">
                                <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                <ul class="dropdown-menu">
                                    <li class="unseen_action_button" data-action="markunseen"><a href="javascript:;">{{trans_choice('admin.mark_unseen',2)}}</a></li>
                                    <li class="unseen_action_button" data-action="recover"><a href="javascript:;">{{trans_choice('admin.recover_selected',2)}}</a></li>
                                    <li class="unseen_action_button" data-action="remove"><a href="javascript:;">{{trans_choice('admin.delete_selected',2)}}</a></li>
                                    <!-- <li class="action" data-action="deactivate"><a href="javascript:;">DEACTIVATE SELECTED</a></li> -->
                                    <!-- <li id="delete_users"><a href="javascript:;">DELETE SELECTED</a></li> -->
                                </ul>
                            </div>
                        </div> 
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all_seens" value=""></th>
                                    <th>{{trans_choice('admin.reporting_user',1)}}</th>
                                    <th>{{trans_choice('admin.reported_user',1)}}</th>
                                    <th>{{trans_choice('admin.reported_photo',1)}}</th>
                                    <th>{{trans_choice('admin.reason',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($seens->count > 0)
                                @foreach($seens as $seen)
                                <tr>
                                    <td><input class="seen-checkbox" type="checkbox" data-seen-id="{{{ $seen->id }}}" value=""></label></td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="float:left;margin-right:10px;background: url({{{ $seen->reportinguser->profile_pic_url() }}});background-size:contain;">
                                            
                                        </div><p>{{{$seen->reportinguser->name}}}</p>
                                    </td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="float:left;margin-right:10px;background: url({{{ $seen->reporteduser->profile_pic_url() }}});background-size:contain;">
                                            
                                        </div>
                                        <p>{{{$seen->reporteduser->name}}}</p>
                                    </td>
                                    <td>
                                        <div class="col-md-2 user-img-custom reported-photos" style="background: url({{{ $seen->photos()->withTrashed()->first()->photo_url() }}});background-size:contain;cursor:pointer"></div>
                                    </td>
                                    <td>{{{ $seen->reason }}}</td>
                                    <td>
                                        @if($seen->status == 'deleted')
                                          Deleted
                                        @elseif(isset($seen->photos()->withTrashed()->first()->deleted_at))
                                          User Deleted
                                        @else
                                          Seen
                                        @endif
                                    </td>
                                </tr>
                                @endforeach    
                                @else
                                <tr >
                                    <td colspan = "8" style = "text-align : center; color : red">{{trans_choice('admin.no_record',1)}}</td>
                                </tr>
                                @endif    
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 user-col-footer">
                        <!--
                            <div class="dropup dropup-custom">
                               <button class="btn btn-primary dropdown-toggle dropdown-ul-custom" type="button" data-toggle="dropdown">10
                               <span class="fa fa-caret-up"></span></button>
                               <ul class="dropdown-menu drop-rows-custom">
                                  <li><a href="#">5</a></li>
                                  <li><a href="#">10</a></li>
                                  <li><a href="#">15</a></li>
                                  <li><a href="#">20</a></li>
                                  <li><a href="#">25</a></li>
                                  <li><a href="#">30</a></li>
                                  <li><a href="#">35</a></li>
                                  <li><a href="#">40</a></li>
                                  <li><a href="#">45</a></li>
                                  <li><a href="#">50</a></li>
                               </ul>
                            </div>
                            <p class="no-of-rows">Rows per Page:</p> -->
                        <div class="pagination pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="img-prev-modal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="">

    <!-- Modal content-->
    <div class="modal-content" style="background-color: white;padding: 5px;border-radius: 5px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <img src="" width="100%" height="100%">
    </div>

  </div>
</div>
@endsection
@section('scripts')
<script>
    var original_img_url = "{{url('/uploads/others/original')}}";

    $(".reported-photos").click(function(){
        
        var url = $(this).css('background-image');
        url = url.substring(5, url.length-2);
        $("#img-prev-modal > .modal-dialog > .modal-content > img").attr("src", url);
        $("#img-prev-modal").modal('show');
    });


    var selected_unseens = [];
    
    $(".unseen-checkbox").change(function(){
       var user_id = $(this).data('unseen-id');
       if(this.checked){
          selected_unseens.push(user_id);
       }
       else{
          var index = selected_unseens.indexOf(user_id);
          if (index > -1) {
             selected_unseens.splice(index, 1);
          }
       }
    
    
    
    });
    
    
    var selected_seens = [];
    
    $(".seen-checkbox").change(function(){
       var user_id = $(this).data('seen-id');
       if(this.checked){
          selected_seens.push(user_id);
       }
       else{
          var index = selected_seens.indexOf(user_id);
          if (index > -1) {
             selected_seens.splice(index, 1);
          }
       }
    
    
    
    });
    
    
    $("#select_all_unseens").change(function(){
    
       if(this.checked){
          $('.unseen-checkbox').each(function(){
             $(this).prop('checked', true);
              $(this).trigger('change');
    
          });
       }
       else{
    
            $('.unseen-checkbox').each(function(){
              $(this).prop('checked', false);
              $(this).trigger('change');
          });
       }
    
       
    
    });
    
    
    $("#select_all_seens").change(function(){
    
       if(this.checked){
          $('.seen-checkbox').each(function(){
             $(this).prop('checked', true);
              $(this).trigger('change');
    
          });
       }
       else{
    
            $('.seen-checkbox').each(function(){
              $(this).prop('checked', false);
              $(this).trigger('change');
          });
       }
    
       
    
    });
    
     
    
    
    $(".seen_action_button").click(function(){
    
       var action = $(this).data('action');
       var data = {};
       data['_task'] = action;
       data["_token"] = "{{{ csrf_token() }}}";
       data["data"] = selected_unseens.join(',');
       $.post("{{{url('/admin/misc/photoabuse')}}}",data, function(response){
    
          window.location.reload();
    
       });
    
    
    });
    
    
    $(".unseen_action_button").click(function(){
    
       var action = $(this).data('action');
       var data = {};
       data['_task'] = action;
       data["_token"] = "{{{ csrf_token() }}}";
       data["data"] = selected_seens.join(',');
       $.post("{{{url('/admin/misc/photoabuse')}}}",data, function(response){
    
          window.location.reload();
    
       });
    
    
    })
    
    
    
    
</script>
@endsection
