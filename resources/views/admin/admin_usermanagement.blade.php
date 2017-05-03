@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.user',1)}} {{trans_choice('admin.manage',1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
         <div class="row">
            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{trans_choice('admin.user',1)}}</p>
                     <div class="dropdown dropdown-custom-right" style="float:right">
                        <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                        <ul class="dropdown-menu">
                           <li class="action" data-action="verify"><a href="javascript:;">{{trans_choice('admin.verify',0)}} {{trans_choice('admin.select',2)}}</a></li>
                           <li class="action" data-action="deactivate"><a href="javascript:;">{{trans_choice('admin.deactivate',0)}} {{trans_choice('admin.select',2)}}</a></li
                           >
                           <li><a href="#" data-toggle="modal" data-target="#delete-user-dialog">{{{trans_choice('admin.delete', 0)}}} {{trans_choice('admin.select',2)}}</a></li>
                           <li><a href="#" data-toggle="modal" data-target="#credit-users-modal">{{trans('admin.credit_selected_users_menu_title')}}</a></li>
                           <li><a href="#" data-toggle="modal" data-target="#superpower-users-modal">{{trans('admin.superpower_selected_users_menu_title')}}</a></li>
                           <!-- <li id="delete_users"><a href="javascript:;">DELETE SELECTED</a></li> -->
                        </ul>
                     </div>
                  </div>
                  <table class="table" id="user-table">
                     <thead>
                        <tr>
                           <th><input type="checkbox" id="select_all_users" value=""></th>
                           <th>{{trans_choice('admin.photo',1)}}</th>
                           <th>{{trans_choice('admin.name',1)}}</th>
                           <th> {{trans_choice('admin.email_id',1)}}</th>
                           <th> {{trans_choice('admin.sex',1)}}</th>
                           <th> {{trans_choice('admin.birth_date',1)}}</th>
                           <th>{{trans_choice('admin.location',1)}}</th>
                           <th>{{trans_choice('admin.date_joined',1)}}</th>
                           <th>{{{trans('admin.social_links')}}}</th>
                        </tr>
                     </thead>
                     <tbody>


                     @if(count($users) > 0)
                     @foreach ($users as $user)

                      <tr>
                           <td><input class="user-checkbox" type="checkbox" data-user-id="{{{ $user->id }}}" value=""></label></td>
                           <td>
                              <a href = "{{{url('/profile')}}}/{{{$user->id}}}"><div class="col-md-2 user-img-custom" style="background: url({{{ $user->profile_pic_url() }}});background-size:contain;"></div></a>
                           </td>
                           <td><a href = "{{{url('/profile')}}}/{{{$user->id}}}">{{{ $user->name }}}</a></td>
                           <td>{{{ $user->username }}}</td>
                           <td>{{trans('custom_profile.'.$user->gender)}}</td>
                           <td>{{{ $user->getFormatedDob() }}}</td>
                           <td>{{{ $user->city }}}</td>
                           <td>{{{ $user->getJoining() }}}</td>
                           <td>
                           @foreach($user->get_social_links() as $link)
                              @if($link == 'facebook')<i class="fa fa-facebook-square ftg"></i>@endif
                              @if($link == 'google')<i class="fa fa-google ftg"></i>@endif
                           @endforeach

                           
                           </td>
                        
                        </tr>

                     @endforeach    
                     @else
                     <tr >
                           <td colspan = "8" style = "text-align : center; color : red">No {{trans_choice('admin.user',1)}}</td>
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
                  {!! $users->render() !!}
                  </div>
               </div>
               
            </div>
         </div>
      </div>
   </section>
</div>



<!-- user delete confirm modal -->
<div id="delete-user-dialog" class="modal fade" role="dialog">
  <div class="modal-dialog" id = "delete-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{{trans('admin.users_delete_confirmation')}}}</h4>
      </div>
      <div class="modal-body">
         <div style = "text-align:center;">
            <button type="button" class="btn btn-info btn-custom" id = "delete-confirm">{{{trans_choice('admin.delete',0)}}}</button>
            <button type="button" class="btn btn-info btn-custom" data-dismiss="modal">{{{trans('admin.cancel')}}}</button>
        </div>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>


<div id="credit-users-modal" class="modal fade" role="dialog">
  <div class="modal-dialog" id = "delete-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{{trans('admin.credit_users_modal_title')}}}</h4>
      </div>
      <div class="modal-body">
         <div style = "text-align:center;">
            <input type="" class="form-control input-border-custom" id ="credit-amount" placeholder="{{trans('admin.credit_users_amount_input_placeholder')}}">
            <button type="button" class="btn btn-info btn-custom" id = "credit-users-btn">{{{trans_choice('admin.credit',0)}}}</button>
        </div>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>


<div id="superpower-users-modal" class="modal fade" role="dialog">
  <div class="modal-dialog" id = "delete-modal">

  
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{{trans('admin.superpower_users_modal_title')}}}</h4>
      </div>
      <div class="modal-body">
         <div style = "text-align:center;">
            <input type="" class="form-control input-border-custom" id ="superpower-duration" placeholder="{{trans('admin.superpower_users_duration_input_placeholder')}}">
            <button type="button" class="btn btn-info btn-custom" id = "superpower-users-btn">{{{trans_choice('admin.activate',0)}}}</button>
        </div>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>











@endsection
@section('scripts')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"> -->
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
   $(document).ready(function() {
    $('#user-table').DataTable({
      "pageLength": 100
    });
} );
</script>
<script>

var selected_users = [];

$(".user-checkbox").change(function(){
   var user_id = $(this).data('user-id');
   if(this.checked){
      selected_users.push(user_id);
   }
   else{
      var index = selected_users.indexOf(user_id);
      if (index > -1) {
    selected_users.splice(index, 1);
         }
   }




});



$("#superpower-users-btn").on("click", function(){

    var duration = $("#superpower-duration").val();
    $.post("{{url('/admin/users/usermanagement/activate-superpower-users')}}",
        {data:selected_users.join(','), duration:duration}, 
        function(response){
            if(response.status = "success")
            toastr.success("{{trans_choice('admin.activate',2)}}")
        });


});



$("#credit-users-btn").on("click", function(){

    var credit_amount = $("#credit-amount").val();
    $.post("{{url('/admin/users/usermanagement/credit-users')}}",
        {data:selected_users.join(','), credit_amount:credit_amount}, 
        function(response){
            if(response.status = "success")
            toastr.success("{{trans_choice('admin.credit_all_msg',1)}}")
        });

});




$("#select_all_users").change(function(){

   if(this.checked){
      $('.user-checkbox').each(function(){
         $(this).prop('checked', true);
          $(this).trigger('change');

      });
   }
   else{

        $('.user-checkbox').each(function(){
          $(this).prop('checked', false);
          $(this).trigger('change');
      });
   }

   

});


$(".action").click(function(){

   var action = $(this).data('action');
   var data = {};
   data['_action'] = action;
   data["_token"] = "{{{ csrf_token() }}}";
   data["data"] = selected_users.join(',');
   $.post("{{{ url('/admin/users/usermanagement/doaction') }}}",data, function(response){

      //console.log(response);
      window.location.reload();

   });

});


$("#delete-confirm").click(function (){

   if (selected_users.join(',').length == 0) 
   {
      toastr.warning('{{{trans('admin.no_users_selected')}}}');
      return false;
   }

   var data = {};
   data["_token"] = "{{{ csrf_token() }}}";
   data["user_ids"] = selected_users.join(',');

   $.post("{{{url('/admin/usermanagement/users/delete')}}}", data, function(response) {

      if (response.status == "success") {

         toastr.success(response.message);
         window.location.reload();

      } else if (response.status == "error") {

         toastr.error(response.message);
      }

   });



});




</script>


<style type="text/css">
  #user-table_length > label, #user-table_info{
    display: none;
  } 
 

   #delete-modal {
    width: 30%;
    
   }


.modal-content{
  background-color: #38414A; 
}
.modal-title{
   color: white;
}

</style>
@endsection