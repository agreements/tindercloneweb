@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
   .admin_list_dropup{
   margin-left: -152px;
   background-color: #353E47;
   }
   .modal {
   text-align: center;
   }
   @media screen and (min-width: 768px) { 
   .modal:before {
   display: inline-block;
   vertical-align: middle;
   content: " ";
   height: 100%;
   }
   }
   .modal-dialog {
   display: inline-block;
   text-align: left;
   vertical-align: middle;
   }
   .modal-content{
   background-color: #38414A; 
   }
   .modal-title{
   color: white;
   }
   .admin-create-div{
   width : 100%;
   }
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.bots_management', 1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">
            


            <div class="col-md-12 section-first">

              <h4 class="user-statistics">{{trans_choice('admin.bot_stat', 0)}}</h4>
               <div class="row">
                <div class="col-md-4">
                  <p class="total-users">{{trans_choice('admin.overall_bots', 0)}}</p>
                  <p class="total-users-count">{{{$overall}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="this-month">{{trans_choice('admin.overall_bots_acnt', 0)}}</p>
                  <p class="total-users-count">{{{$totalaccount}}}</p>
                </div>
                
               </div>
            </div>


       






            <form action = "{{{url('admin/plugin/bot/settings')}}}" method = "POST" id = "bot-filter-form" enctype="multipart/form-data">
               {!! csrf_field() !!}
               
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.bot_general_settings', 1)}}</p>
                  
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.no_bots', 1)}}</label>
                     <input type="text" name = "no_of_bots" placeholder="Enter Number of Bots to be Created" class="form-control input-border-custom" value = "{{{$settings['no_of_bots']}}}">
                  </div>
  
                  <button type="submit" id = "bot-filter-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.update', 0)}}</button>
               </div>
            </form>




             <!-- bot lists --> 
            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{trans_choice('admin.bot', 1)}} {{trans_choice('admin.list', 1)}}</p>
                     <!-- 
                        <div class="dropdown dropdown-custom-right">
                           <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                           <ul class="dropdown-menu">
                              <li class="action" data-action="verify"><a href="javascript:;">VERIFY SELECTED</a></li>
                              <li class="action" data-action="deactivate"><a href="javascript:;">DEACTIVATE SELECTED</a></li>
                           </ul>
                        </div> -->
                  </div>
                  <table class="table" id="user-table">
                     <thead>
                        <tr>
                           <th>{{trans_choice('admin.name', 1)}}</th>
                           <th>{{trans_choice('admin.photo', 1)}}</th>
                           <th>{{trans_choice('admin.no_of_bot_photos', 1)}}</th>
                           <th>{{trans_choice('admin.join', 3)}} {{trans_choice('admin.date', 1)}}</th>
                           <th>{{trans_choice('admin.age', 0)}}</th>
                           <th>{{trans_choice('admin.sex', 1)}}</th>
                           <th>{{trans_choice('admin.status', 1)}}</th>
                           <th>{{trans_choice('admin.action', 1)}}</th>
                        </tr>
                     </thead>
                     <tbody>

                     @if(count($bots) > 0)
                     @foreach($bots as $bot)
                        <tr id = "bot-row-{{{$bot->id}}}">
                           <td>{{{$bot->name}}}</td>
                           <td>
                              <div class="col-md-2 user-img-custom" style="background: url({{{ $bot->thumbnail_pic_url() }}});background-size:contain;"></div>
                           </td>
                           <td>{{{$bot->photos()->count()}}}</td>
                           <td>{{{$bot->joining()}}}</td>
                           <td>{{{$bot->age()}}}</td>
                           <td>{{{$bot->gender}}}</td>
                           <td>{{{$bot->status()}}}</td>
                           <td>
                              <div class="dropup dropdown-custom-left">
                                 <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                 <ul class="dropdown-menu admin_list_dropup">
                                    <li class="upload-photo-item" data-bot-id ="{{{$bot->id}}}" data-bot-name = "{{{$bot->name}}}" data-toggle="modal" data-target="#bot-photo-modal" ><a href="javascript:;">{{trans('admin.add_bot_photos')}}</a></li>
                                    <li class="action-delete" data-bot-id ="{{{$bot->id}}}" data-bot-name = "{{{$bot->name}}}" ><a href="javascript:;">{{trans('admin.delete_bot')}}</a></li>
                                    <li class="@if($bot->status() == 'active') action-deactivate @else action-activate @endif" data-bot-name = "{{{$bot->name}}}" data-bot-id ="{{{$bot->id}}}"><a href="javascript:;"> @if($bot->status() == 'active') {{trans('admin.deactivate_bot')}} @else {{trans('admin.activate_bot')}} @endif</a></li>
                                    
                                 </ul>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                     @else
                        <tr>
                           <td colspan = "5" style = "text-align : center; color : red">No Bots Found</td>
                        </tr>
                     @endif
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- bot lists end-->


         </div>
      </div>
</div>
</section>
</div>
<div id="bot-photo-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:white">{{trans('admin.bot_photos_title')}}</h4>
      </div>
      <div class="modal-body" style="min-height: 500px;overflow-y: scroll;height: 500px;" id ="bot-photos-container">
        
        

      </div>
      <div class="modal-footer">

        <form action="{{url('admin/plugin/bot/upload-photos')}}" method="POST" enctype="multipart/form-data" id="upload-photos-form">
        {{csrf_field()}}
        <input type="hidden" name="bot_id" id = "uplaod-photo-form-bot-id">
        <div class="input-group" style="background: #131619;">
            <input type="file" name="photos[]" multiple>
            <span class="input-group-btn">
                <button type="submit" class="btn btn-default">{{trans('admin.upload')}}</button>
            </span>
        </div>
        </form>

      </div>
    </div>

  </div>
</div>
@endsection
@section('scripts')

<link href="{{{asset('admin_assets')}}}/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
<script src="{{{asset('admin_assets')}}}/js/moment.min.js"></script>
<script src="{{{asset('admin_assets')}}}/js/bootstrap-datetimepicker.js"></script>

<script>
  arr = [];

$('.gender').on('click',function(){
  arr=[];
  $(".gender:checked").each(function(){
     arr.push($(this).val());
  });

})
  
  $("#bot-filter-form").submit(function(e){
     e.preventDefault();

      $("#gender").val(arr);
    
   //grab all form data  
   var formData = new FormData($(this)[0]);

  
     return false;
  });

</script>

<script type="text/javascript">
  
 $(document).ready(function(){
 
  $('#dob-date-picker').datetimepicker({format: 'DD/MM/YYYY'});
  $('#join-date-picker').datetimepicker({format: 'DD/MM/YYYY'});

});

</script>


<script>
  
$(".upload-photo-item").on("click", function(){

    var bot_id = $(this).data('bot-id');
    $("#uplaod-photo-form-bot-id").val( bot_id );


    $.get('{{url('admin/plugin/bot/photos')}}?bot_id='+bot_id, function(response){

        if(response.status == 'success') {
            $("#bot-photos-container").empty();
            for(index in response.photos) {
                
                $("#bot-photos-container").append('<img src="'+response.photos[index].original_photo_url+'" style="width:274px;height:274px;">');
            }
        }

    });

});


//deactivate bot
$('.action-deactivate').click(function(){

  var id = $(this).data('bot-id');
  var data = {};
  data['_token'] = "{{{csrf_token()}}}";
  data['id'] = id;
  data['name'] = $(this).data('bot-name');




  $.post("{{{url('admin/plugin/bot/deactivate')}}}", data, function(response){

    if(response.status == 'success') {

        toastr.success(response.message);
        
        setTimeout(function(){
           window.location.reload();
        }, 1000);
        
       
      } else if(response.status == 'error') {

        toastr.error(response.message);
      }

  });

});



//activate bot
$('.action-activate').click(function(){

  var id         = $(this).data('bot-id');
  var data       = {};
  data['_token'] = "{{{csrf_token()}}}";
  data['id']     = id;
  data['name']   = $(this).data('bot-name');




  $.post("{{{url('admin/plugin/bot/activate')}}}", data, function(response){

    if(response.status == 'success') {

        toastr.success(response.message);
        
        setTimeout(function(){
           window.location.reload();
        }, 1000);
        
       
      } else if(response.status == 'error') {

        toastr.error(response.message);
      }

  });

});




//delete bot
$('.action-delete').click(function(){

  
   var id         = $(this).data('bot-id');
   var data       = {};
   data['_token'] = "{{{csrf_token()}}}";
   data['id']     = id;
   data['name']   = $(this).data('bot-name');




  $.post("{{{url('admin/plugin/bot/delete')}}}", data, function(response){

    if(response.status == 'success') {

        toastr.success(response.message);
        
        $('#bot-row-'+id).remove();
       
      } else if(response.status == 'error') {

        toastr.error(response.message);
      }

  });

});



//bot filter settings
//create bot
$("#bot-filter-form").submit(function(e){
 
  //disable the default form submission
  e.preventDefault();
 
  //grab all form data  
  var formData = new FormData($(this)[0]);
 
  $.ajax({
    url: '{{{ url('/admin/plugin/bot/settings') }}}',
    type: 'POST',
    data: formData,
    async: false,
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if(response.status == 'success') {

        toastr.success(response.message);
       
      } else if(response.status == 'warning') {

        toastr.warning(response.message);
      } else if(response.status == 'error') {

        toastr.error(response.message);
      }

    }
  });
 
});



</script>

@endsection