@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.deactivate',2)}} {{trans_choice('admin.user',1  )}} {{trans_choice('admin.manage',1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
         <div class="row">
            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{trans_choice('admin.user',2)}}</p>
                     <div class="dropdown dropdown-custom-right">
                        <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                        <ul class="dropdown-menu">
                           <!-- <li class="action" data-action="verify"><a href="javascript:;">VERIFY SELECTED</a></li> -->
                           <li class="action" data-action="activate"><a href="javascript:;">{{trans_choice('admin.activate',0)}} {{trans_choice('admin.select',2)}}</a></li>
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
                           <th>{{trans_choice('admin.email_id',1)}}</th>
                           <th> {{trans_choice('admin.sex',1)}}</th>
                           <th>{{trans_choice('admin.birth_date',1)}}</th>
                           <th>{{trans_choice('admin.location',1)}}</th>
                           <th>{{trans_choice('admin.date_joined',1)}}</th>
                           <th>{{trans_choice('admin.status',1)}}</th>
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
                           <td>{{{ $user->verified }}}</td>
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
                  {!! $users->render() !!}
                  </div>
               </div>
               
            </div>
         </div>
      </div>
   </section>
</div>
@endsection
@section('scripts')


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

})




</script>
@endsection