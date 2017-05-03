@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.admin', 2)}} {{trans_choice('admin.manage', 1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">
            @if(session('createAdmin') == 'Admin Created')
            <div class="alert alert-success">
               <strong>Success!</strong> {{trans_choice('admin.admin', 1)}} {{trans_choice('admin.create', 2)}}.
            </div>
            @elseif(session()->has('createAdmin'))
            <div class="alert alert-danger">
               <strong>Oops!</strong> {{{session('createAdmin')}}}.
            </div>
            @endif
            {{{session()->forget('createAdmin')}}}
            <form action = "{{{url('admin/users/adminmanagement')}}}" method = "POST" id = "admin_create">
               {!! csrf_field() !!}
               <input type = "hidden" name = "_task" value = "createAdmin">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.create', 0)}} {{trans_choice('admin.new', 1)}} {{trans_choice('admin.admin', 1)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.admin', 1)}}  {{trans_choice('admin.name', 1)}} </label>
                     <input type="text" id = "name" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.admin', 1)}}  {{trans_choice('admin.name', 1)}}" name = "name" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.admin', 1)}} {{trans_choice('admin.username', 1)}}</label>
                     <input type="text" id= "username" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.admin', 1)}} {{trans_choice('admin.username', 1)}}" name = "username" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.password', 1)}}</label>
                     <input type="password" name = "password" id = "password" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom input-border-custom" id="usr">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.confirm', 0 )}} {{trans_choice('admin.password', 1)}}</label>
                     <input type="password" id = "confirm_password" name = "confirm_password" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.confirm', 0 )}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom">
                  </div>
                  <button type="submit" id = "create_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.create', 0)}} {{trans_choice('admin.admin', 1)}}</button>
               </div>
            </form>
            <!-- admin lists --> 
            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{trans_choice('admin.admin', 1)}} {{trans_choice('admin.list', 1   )}}</p>
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
                           <th>{{trans_choice('admin.username', 1)}}</th>
                           <th>{{trans_choice('admin.last_login',1)}}</th>
                           <th>{{trans_choice('admin.last_ip',1)}}</th>
                           <th>{{trans_choice('admin.action', 2)}}</th>
                        </tr>
                     </thead>
                     <tbody>

                     @if(count($admins) > 0)
                     @foreach($admins as $admin)
                        <tr id = "admin_row_{{{$admin->id}}}">
                           <td>{{{$admin->name}}}</td>
                           <td>{{{$admin->username}}}</td>
                           <td>{{{$admin->last_login}}}</td>
                           <td>{{{$admin->last_ip}}}</td>
                           <td>
                              <div class="dropup dropdown-custom-left">
                                 <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                 <ul class="dropdown-menu admin_list_dropup">
                                    <li class="action_password_change" data-admin-id ="{{{$admin->id}}}" data-admin-name = "{{{$admin->name}}}" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false"><a href="javascript:;">{{trans_choice('admin.change', 0)}} {{trans_choice('admin.password', 1)}}</a></li>
                                    <li class="action_delete" data-admin-name = "{{{$admin->name}}}" data-admin-id ="{{{$admin->id}}}"><a href="javascript:;">{{trans_choice('admin.delete', 0)}}</a></li>
                                    
                                 </ul>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                     @else
                        <tr >
                           <td colspan = "5" style = "text-align : center; color : red">{{trans_choice('admin.no_record', 1)}} </td>
                        </tr>
                     @endif
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- admin lists end-->
         </div>
      </div>
</div>
</section>
</div>



<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{trans_choice('admin.admin', 1)}}  {{trans_choice('admin.change', 0)}}  {{trans_choice('admin.password', 1)}}  {{trans_choice('admin.for', 0)}}  : <span id ="change_pass_admin_name"></span></h4>
      </div>
      <div class="modal-body">
         
      <!-- <form action = "" method = "POST" id = "change_password"> -->
         <input type = "hidden" id = "change_pass_admin_id" value = "">
         <div class="form-group">
            <label class="package-label">{{trans_choice('admin.password',1)}}</label>
            <input type="password" id = "update_password" placeholder="Enter Password" name = "password" class="form-control  input-border-custom">
         </div>

         <div class="form-group">
            <label class="package-label">{{trans_choice('admin.confirm',0)}} {{trans_choice('admin.password',1)}}</label>
            <input type="password" id = "update_confirm_password" placeholder="Enter Confirm Password" name = "confirm_password" class="form-control  input-border-custom">
         </div>
        
      <!-- </form> -->
         


      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-info btn-addpackage btn-custom" data-dismiss="modal">{{trans_choice('admin.close', 0)}} </button>
        <button id = "change_password_btn" type="submit" class="btn btn-info btn-addpackage btn-custom" style = "margin-right:5px;">{{trans_choice('admin.change', 0)}} </button>
      </div>
    </div>

  </div>
</div>












@endsection
@section('scripts')

<script>
   
$(document).ready(function(){


   $('.action_delete').click(function(){
      
      toastr.options.closeButton = true;
      toastr.options.positionClass = 'toast-bottom-right';

      var row = $('#admin_row_'+$(this).data('admin-id'));
      var adminname = $(this).data('admin-name');
      var data = {};
      data['_task'] = 'delete_admin';
      data["_token"] = "{{{ csrf_token() }}}";
      data["id"] = $(this).data('admin-id');
      $.post("{{{url('admin/users/adminmanagement')}}}",data, function(response){

         if(response.status == 'error') {

            toastr.error(response.message);

         } else if(response.status == 'success') {

            row.remove();
            toastr.success(adminname +' {{trans_choice('admin.success', 3)}}  {{trans_choice('admin.delete', 2)}}.');
            
         }

      });
   });

});
   

$('.action_password_change').click(function(){

   $('#change_pass_admin_id').val( $(this).data('admin-id') );
   $('#change_pass_admin_name').text( $(this).data('admin-name') );
   $('#update_password').val("");
   $('#update_confirm_password').val("");

});


$('#change_password_btn').click(function(e){
   e.preventDefault();
//toastr.error('Name must be less that 100 character.');
   var pass = $('#update_password').val();
   var cnf_passw = $('#update_confirm_password').val();

   if(pass == "") {
            
         toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.password',1)] )}}.');
      
   } else {
   
      if(pass.length < 8 || pass.length > 200) {

         toastr.error('{{trans('admin.pass_length', ['min' => 8, 'max' => 200])}}.');
      }
   
   }
   
         
   
   if(cnf_passw == "") {

      toastr.error('{{trans_choice('admin.confirm', 0)}} {{trans('admin.require_attr', ['attr' => trans_choice('admin.password',1)] )}}.');

   } else { 

      if(cnf_passw.length < 8 || cnf_passw.length > 200) {

         toastr.error('{{trans_choice('admin.confirm', 0)}} {{trans('admin.pass_length', ['min' => 8, 'max' => 200])}}.');

      } else {

         if(pass != cnf_passw) {

            toastr.error('{{ trans('admin.not_match', ['attr' => trans_choice('admin.password',1)]) }}.');  

         } else {

            id              = $('#change_pass_admin_id').val();
            var data        = {};
            data['_task']   = 'change_password';
            data["_token"]  = "{{{ csrf_token() }}}";
            data["id"]      =  id;
            data["password"]=  pass;
            
            $.post("{{{url('admin/users/adminmanagement')}}}",data, function(response){

               if(response.status == 'error') {

                  toastr.error(response.message);

               } else if(response.status == 'success') {

                  if(id == {{{session('admin_id')}}}) {

                     $.get("{{{url('admin/logout')}}}", function(data){
                        window.location.reload();
                     });
                  }

                  toastr.success('{{trans_choice('admin.password', 0)}}  {{trans_choice('admin.change', 2)}}  {{trans_choice('admin.success', 3)}} .');
                  
               }

            });


         }

      }

   }

});
   
   
   //create admin form validation
   $("#create_btn").click(function(e){
   
      toastr.options.closeButton = true;
      toastr.options.positionClass = 'toast-top-right';
   
      var name = $("#name").val();
      var username = $("#username").val();
      var password = $("#password").val();
      var confirm_password = $("#confirm_password").val();
   
      if(name == "") {
   
         e.preventDefault();
         toastr.error('{{ trans('admin.require_attr', ['attr' => trans_choice('admin.name',1)]) }}');
      }
   
      if(name.length > 100) {
   
         e.preventDefault();
         toastr.error('{{ trans('admin.compare_than', ['attr' => trans('admin.name'), 'cmp' => 'less', 'num' => 100 ] ) }}.');
      }
   
      if(username == "") {
   
         e.preventDefault();
         toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.username',1)] )}}.');
      }
   
      if(username.length > 200) {
   
         e.preventDefault();
         toastr.error('{{ trans('admin.compare_than', ['attr' => trans('admin.username'), 'cmp' => 'less', 'num' => 200 ] ) }}.');
      }
   
      if(password == "") {
   
         e.preventDefault();
         toastr.error('{{ trans('admin.require_attr', ['attr' => trans_choice('admin.password',1)]) }}.');
      
      } else {
   
         if(password.length < 8 || password.length > 200) {
   
            e.preventDefault();
            toastr.error('{{trans_choice('admin.confirm', 1)}} {{trans('admin.pass_length', ['min' => 8, 'max' => 200])}}.');
         }
   
      }
   
         
   
      if(confirm_password == "") {
   
         e.preventDefault();
         toastr.error('{{trans_choice('admin.confirm', 0)}} {{ trans('admin.require_attr', ['attr' => trans_choice('admin.password',1)]) }}.');
   
      } else { 
   
         if(confirm_password.length < 8 || confirm_password.length > 200) {
   
            e.preventDefault();
            toastr.error('{{trans_choice('admin.confirm', 1)}} {{trans('admin.pass_length', ['min' => 8, 'max' => 200])}}.');
   
         } else {
   
            if(password != confirm_password) {
   
               e.preventDefault();
               toastr.error('{{ trans('admin.not_match', ['attr' => trans_choice('admin.password',1)]) }}.');  
            }
   
         }
   
      }
   
      
   
   });
   
   
   
</script>
<style type="text/css">
   .admin_list_dropup{
   margin-left: -181px;
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
   padding-left: 32px;
   padding-right: 32px;
}

</style>
@endsection