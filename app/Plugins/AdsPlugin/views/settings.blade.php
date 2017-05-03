@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.advertise',1)}} {{trans_choice('admin.manage',1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "{{{url('admin/ads/add_banner')}}}" method = "POST" id = "create-add-form">
               {!! csrf_field() !!}
               <input type = "hidden" name = "_task" value = "createAdmin">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.create',0)}} {{trans_choice('admin.new',1)}} {{trans_choice('admin.advertise',0)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.advertise',0)}} {{trans_choice('admin.name',1)}}</label>
                     <input type="text" id = "name" placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.advertise',0)}} {{trans_choice('admin.name',1)}}" name = "name" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.write',0)}} {{trans_choice('admin.html',0)}} {{trans_choice('admin.code',1)}}</label>
                     <textarea placeholder="{{trans_choice('admin.write_html_holder',0)}}" name = "htmlcode" id= "htmlcode" class="form-control  input-border-custom"></textarea>
                  </div>
                  
                  <button type="button" id = "create-add-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.create',0)}} {{trans_choice('admin.add',0)}}</button>
               </div>
            </form>


            <form action = "" method = "POST" id = "assign-add-form">
               {!! csrf_field() !!}
               <input type = "hidden" name = "_task" value = "createAdmin">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.assign_banner',0)}}</p>

                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.assign_banner_field',0)}}</label>
                     <select name = "leftsidebar" class="form-control  input-border-custom select-custom">
                      <option value = "0" > none</option>
                     @if(count($adds) > 0)
                       @foreach($adds as $add)
                       
                        <option value = "{{{$add->id}}}" @if($active) @if($active->leftsidebar == $add->id) selected @endif @endif > {{{$add->name}}}</option>
                       
                       @endforeach
                       </select>
                     @else
                      
                        <option value = "-1">{{ trans('admin.no_found', ['text' => trans_choice('admin.advertise',1)]) }}.</option>
                       </select>
                     @endif
                     
                        
                  </div>

                 <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.assign_banner_field',1)}}</label>
                     <select name = "rightsidebar" class="form-control  input-border-custom select-custom">
                      <option value = "0" > none</option>
                     @if(count($adds) > 0)
                       @foreach($adds as $add)
                       
                        <option value = "{{{$add->id}}}" @if($active) @if($active->rightsidebar == $add->id) selected @endif @endif > {{{$add->name}}}</option>
                       
                       @endforeach
                       </select>
                     @else
                      
                        <option value = "-1">{{ trans('admin.no_found', ['text' => trans_choice('admin.advertise',1)]) }}.</option>
                       </select>
                     @endif
                  </div>

                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.assign_banner_field',2)}}</label>
                     <select name = "topbar" class="form-control  input-border-custom select-custom">
                      <option value = "0" > none</option>
                     @if(count($adds) > 0)
                       @foreach($adds as $add)
                       
                        <option value = "{{{$add->id}}}" @if($active) @if($active->topbar == $add->id) selected @endif @endif > {{{$add->name}}}</option>
                       
                       @endforeach
                       </select>
                     @else
                      
                        <option value = "-1">{{ trans('admin.no_found', ['text' => trans_choice('admin.advertise',1)]) }}.</option>
                       </select>
                     @endif
                  </div>

                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.assign_banner_field',3)}}</label>
                    <select name = "bottombar" class="form-control  input-border-custom select-custom">
                      <option value = "0" > none</option>
                     @if(count($adds) > 0)
                       @foreach($adds as $add)
                       
                        <option value = "{{{$add->id}}}" @if($active) @if($active->bottombar == $add->id) selected @endif @endif > {{{$add->name}}}</option>
                       
                       @endforeach
                       </select>
                     @else
                      
                        <option value = "-1">{{ trans('admin.no_found', ['text' => trans_choice('admin.advertise',1)]) }}.</option>
                       </select>
                     @endif
                  </div>
                  <input type = "hidden" value = "" id = "show-add" name = "show_add">
                  <div class="form-group">
                     <label class="package-label">{{{trans('admin.show_add_label')}}}</label>
                     <label class="switch">
                        <input class="switch-input" type="checkbox" id = "show-add-switch" @if($active && $active->show_add == 'true')  checked @endif>
                        <span class="switch-label"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                  </div>
                  
                  <button type="button" id = "assign-add-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.assign',0)}}</button>
               </div>
            </form>







            <!-- add lists --> 
            <div class="col-md-12 user-dropdown-col user-ads-custom">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside ads-col-tableinside">
                     <p class="add-credit-package-text">{{trans_choice('admin.advertise',1)}} {{trans_choice('admin.list',1)}}</p>
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
                            <td>{{trans_choice('admin.name',0)}}</td>
                            <td>{{trans_choice('admin.html',0)}} {{trans_choice('admin.code',1)}}</td>
                            <td>{{trans_choice('admin.action',1)}}</td>
                        </tr>
                     </thead>
                     <tbody>

                     @if(count($adds) > 0)
                       @foreach($adds as $a)
                        <tr id = "add-{{{$a->id}}}">
                             <td>{{{$a->name}}}</td>
                             <td>
                             <textarea id = "code-{{{$a->id}}}" data-add-id ="{{{$a->id}}}" data-add-name = "{{{$a->name}}}" class="form-control  input-border-custom code-area">{{{$a->code}}}</textarea>

                             <button type="button" data-add-id ="{{{$a->id}}}" data-add-name = "{{{$a->name}}}" class="btn btn-info btn-addpackage btn-custom add-update-btn">UPDATE</button>
                             </td>

                             <td>
                                <div class="dropup dropdown-custom-left">
                                   <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                   <ul class="dropdown-menu admin_list_dropup">
                                      <li class="action" data-add-id ="{{{$a->id}}}" data-add-name = "{{{$a->name}}}" ><a href="javascript:;">{{trans_choice('admin.delete',0)}} {{trans_choice('admin.advertise',1)}}</a></li>
                                      
                                      
                                   </ul>
                                </div>
                             </td>

                          </tr>
                       @endforeach
                     @else
                        <tr >
                           <td colspan = "3" style = "text-align : center; color : red">{{trans_choice('admin.no_record',3)}}</td>
                        </tr>
                     @endif
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- add lists end-->




            
         </div>
      </div>

</section>
</div>






@endsection
@section('scripts')

<link type="text/css" rel="stylesheet" href="{{{asset('admin_assets')}}}/css/jquery-te-1.4.0.css">
<script type="text/javascript" src="{{{asset('admin_assets')}}}/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>

<script>

$('#show-add-switch').change(function(){
  if($('#show-add-switch').is(":checked")) {
    $('#show-add').val('true');
  } else {
    $('#show-add').val('false');
  }
});



 
var html =  '';
$('.code-area').focusin(function(){
  html = $(this).val();
});

var func = function(){

  
  var name = $(this).data('add-name');
  var id = $(this).data('add-id');
  
  var code = $('#code-'+id).val();

  if( code == html) {
    return false;
  }

  if(code == '') {
    toastr.warning("{{{trans_choice('admin.advertise_status_msg',0)}}} "+ name +" {{{trans_choice('admin.advertise_status_msg', 1)}}}");
        return false;
  }

  var data = {};

  data['id'] = id;
  data['name'] = name;
  data['htmlcode'] = code;
  data['_token'] = "{{{csrf_token()}}}";

  $.post("{{{url('admin/ads/update')}}}", data, function(response){
    if(response.status == 'success'){
            toastr.success(response.message);

        }
        else if (response.status == 'error')
            toastr.error(response.message);
  });
  html = '';
};

$('.code-area').focusout(func);
$('.add-update-btn').click(func);

$('.action').click(function(){

  var row = $(this).data('add-id');
  var data = {};

  data['id'] = row;
  data['name'] = $(this).data('add-name');
  data['_token'] = "{{{csrf_token()}}}";

  $.post("{{{url('admin/ads/delete')}}}", data, function(response){
    if(response.status == 'success'){
            toastr.success(response.message);

            $('#add-'+row).remove();

            setTimeout(function(){
         window.location.reload();
      }, 1000);

        }
        else if (response.status == 'error')
            toastr.error(response.message);
  });

});


$('#create-add-btn').click(function(e){
    e.preventDefault();

    var name = $('#name').val();
    var htmlcode = $('#htmlcode').val();
    
    if(name == '') {
        toastr.warning("{{{trans_choice('admin.advertise_status_msg',2)}}}");
        return false;
    }

    if(htmlcode == '') {
        toastr.warning("{{{trans_choice('admin.advertise_status_msg',3)}}}");
        return false;
    }

    var data = $('#create-add-form').serializeArray();
    $.post("{{{url('admin/ads/add_banner')}}}", data, function(response){

        if(response.status == 'success'){
            toastr.success(response.message);


            setTimeout(function(){
         window.location.reload();
      }, 1000);

        }
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});


$('#assign-add-btn').click(function(e){
    e.preventDefault();


    var data = $('#assign-add-form').serializeArray();
    $.post("{{{url('admin/ads/placeholder')}}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});




</script>
<style type="text/css">
   .admin_list_dropup{
   margin-left: -155px;
   background-color: #353E47;
   }


.admin-create-div{
   width : 100%;
  /* padding-left: 10%;
   padding-right: 10%;*/
}


.section-first-col{
    min-height: 0px;
}
.jqte_tool.jqte_tool_1 .jqte_tool_label{
  height: 30px;
}
.jqte_editor{
  min-height: 234px;
}
.jqte{
  margin-top: 6px;
}
</style>
@endsection