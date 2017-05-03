@extends('admin.layouts.admin')
@section('content')
@parent

<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{{trans('admin.detected_nude_photos')}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
   <div class="col-md-12 section-first-col">
         <div class="row"> 
            
            <form action = "{{{url('/admin/plugin/nude-photo-percentage-save')}}}" method = "POST" id = "set-stripe-form">
                {!! csrf_field() !!}
               <div class="col-md-12 col-table-inside">
                  <p class="users-text">{{trans_choice('admin.nudity_percentage', 0 )}}</p>
                  <div class="form-group">
                     <input type="text" id = "nudity_percentage" placeholder="{{trans_choice('admin.nudity_percentage', 0 )}}" value = "@if(isset($nudity_percentage)){{{$nudity_percentage}}}@endif" name = "nudity_percentage" class="form-control  input-border-custom users-text">
                  </div>
                  
                  
                  <button type="button" id = "set-nudity-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0 )}}</button>
               </div>
            </form>
            
         </div>
      </div>
   </section>
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
         <div class="row">
            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{{trans('admin.nude_photos_list')}}}</p>
                     <div class="dropdown dropdown-custom-right" style="float:right">
                        <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                        <ul class="dropdown-menu">
                           <li class="seen-selected"><a href="javascript:;">{{{trans_choice('admin.nude_photo_options',0)}}}</a></li>
                           <li class="delete-selected"><a href="javascript:;">{{{trans_choice('admin.nude_photo_options',1)}}}</a></li>
                           <li class="recover-selected"><a href="javascript:;">{{{trans_choice('admin.nude_photo_options',2)}}}</a></li>
                        </ul>
                     </div>
                  </div>

                  
                      <table class="table" id="user-table">
                     <thead>
                        <tr>
                           <th><input type="checkbox" id="select_all_users" value=""></th>
                           <th>{{{trans_choice('admin.user',0)}}}</th>
                           <th>{{{trans_choice('admin.photo', 1)}}}</th>
                           <th>{{{trans('admin.status')}}}</th>
                           <th>{{{trans('admin.detected')}}}</th>
                        </tr>
                     </thead>
                     <tbody>


                     @if(count($nude_photos) > 0)
                     @foreach ($nude_photos as $nude_photo)

                        <tr @if($nude_photo->isPhotoExists() && ($nude_photo->status=='deleted' || $nude_photo->photo()->deleted_at))
                          style="background-color: rgba(255, 0, 0, 0.10)"
                          @elseif($nude_photo->status=='seen')
                          style="background-color: rgba(0, 128, 0, 0.10)"
                          @endif>
                           <td><input class="user-checkbox" type="checkbox" data-photo-id="{{{ $nude_photo->id }}}" value=""></label></td>
                           <td>
                              <a href = "{{{url('/profile')}}}/{{{$nude_photo->user->id}}}"><div class="col-md-2 user-img-custom" style="background: url({{{ $nude_photo->user->profile_pic_url() }}});background-size:contain;"></div></a>
                           </td>
                           <td>
                              <div class="col-md-2 user-img-custom nude-photos" data-photo-name="{{$nude_photo->photo_name}}" style="background: url('{{{asset('uploads/others/thumbnails')}}}/{{{ $nude_photo->photo_name }}}');background-size:contain;cursor:pointer;"></div>
                           </td>
                           <td>
                           @if($nude_photo->isPhotoExists() && $nude_photo->photo()->deleted_at)
                            {{{trans('admin.nude_photo_deleted')}}}
                           @else
                           {{{ $nude_photo->status() }}}
                           @endif
                          </td>
                           <td>{{{ $nude_photo->detected_at() }}}</td>
                        </tr>

                     @endforeach    
                     @else
                     <tr >
                           <td colspan = "5" style = "text-align : center; color : red"></td>
                     </tr>
                     @endif    



                     </tbody>
                  </table>

               </div>
               <div class="col-md-12 user-col-footer">
              
                  <div class="pagination pull-right">
                  {!! $nude_photos->render() !!}
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

<script type="text/javascript">

$(document).ready(ajustamodal);
  $(window).resize(ajustamodal);
  function ajustamodal() {
    var altura = $(window).height() - 155; //value corresponding to the modal heading + footer
    $(".ativa-scroll").css({"height":altura,"overflow-y":"auto"});
  }

var original_img_url = "{{url('/uploads/others/original')}}";

$(".nude-photos").click(function(){
    
    var url = original_img_url + "/" + $(this).data('photo-name');
    $("#img-prev-modal > .modal-dialog > .modal-content > img").attr("src", url);
    $("#img-prev-modal").modal('show');
});

$('#set-nudity-btn').click(function(e){
    e.preventDefault();

    var data = $('#set-stripe-form').serializeArray();
    $.post("{{{url('/admin/plugin/nude-photo-percentage-save')}}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});


$(".seen-selected").on('click', function(){
    var photo_ids = selected_photos.join(',');
    $.post("{{{url('admin/plugin/nude-photo-seen')}}}",
    {_token:"{{{csrf_token()}}}", photo_ids:photo_ids},
    function(res){
        if(res.status == "success") {
            toastr.success(res.msg);
            window.location.reload();
        } else if(res.status == "error") {
            toastr.error(res.msg);
        }
    });


});

$(".delete-selected").on('click', function(){
    var photo_ids = selected_photos.join(',');
    $.post("{{{url('/admin/plugin/nude-photo-delete')}}}",
    {_token:"{{{csrf_token()}}}", photo_ids:photo_ids},
    function(res){
        if(res.status == "success") {
            toastr.success(res.msg);
            window.location.reload();
        } else if(res.status == "error") {
            toastr.error(res.msg);
        }
    });


});

$(".recover-selected").on('click', function(){
    var photo_ids = selected_photos.join(',');
    $.post("{{{url('/admin/plugin/nude-photo-recover')}}}",
    {_token:"{{{csrf_token()}}}", photo_ids:photo_ids},
    function(res){
        if(res.status == "success") {
            toastr.success(res.msg);
            window.location.reload();
        } else if(res.status == "error") {
            toastr.error(res.msg);
        }
    });


});




var selected_photos = [];
$(".user-checkbox").change(function(){
   var photo_id = $(this).data('photo-id');
   if(this.checked){
      selected_photos.push(photo_id);
   }
   else{
      var index = selected_photos.indexOf(photo_id);
      if (index > -1) {
    selected_photos.splice(index, 1);
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
</script>

<style type="text/css">
  .section-first-col {
    min-height: none;
  }

</style>
@endsection