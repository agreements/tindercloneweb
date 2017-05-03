@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.page',0)}} {{trans_choice('admin.manage', 1 )}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action = "" method = "POST" id = "create-page-form">
                    {!! csrf_field() !!}
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans_choice('admin.create', 0 )}} {{trans_choice('admin.new', 0 )}} {{trans_choice('admin.page', 1 )}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans_choice('admin.title', 0 )}}</label>
                            <input type="text" id = "title" placeholder="{{trans_choice('admin.enter', 0 )}} {{trans_choice('admin.page', 1 )}} {{trans_choice('admin.title', 0 )}}" name = "title" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans_choice('admin.body', 0 )}} {{trans_choice('admin.content', 0 )}}</label>
                            <textarea class="form-control  input-border-custom" placeholder="{{trans_choice('admin.page_field_holder', 0 )}}" name = "body"  id= "body"></textarea>
                        </div> 
                        <div class="form-group">
                            <label class="package-label">{{{trans_choice('admin.route_url',1)}}}</label>
                            <input type="text" id = "route" placeholder="{{trans_choice('admin.page_field_holder', 1 )}}" name = "route" class="form-control  input-border-custom">
                        </div>
                        
                        <button type="button" id = "create-page-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.create', 0 )}} {{trans_choice('admin.page', 1 )}}</button>
                    </div>
                </form>
                <!-- add lists --> 
                <div class="col-md-12 user-dropdown-col user-ads-custom">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside ads-col-tableinside">
                            <p class="users-text">{{trans_choice('admin.page', 1 )}} {{trans_choice('admin.list', 1 )}}</p>
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
                                    <td>{{trans_choice('admin.title', 1 )}}</td>
                                    <td>{{trans_choice('admin.route', 1 )}}</td>
                                    <td>{{trans_choice('admin.status', 1 )}}</td>
                                    <td>{{trans_choice('admin.action', 1 )}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($pages) > 0)
                                @foreach($pages as $a)
                                <form action = "{{{url('admin/pages/create')}}}" method = "POST" id = "create-page-form">
                                    {!! csrf_field() !!}
                                    <tr id = "page-{{{$a->id}}}">
                                        <td><a href="{{{url($a->route)}}}" target="_blank">{{{$a->title}}}</a>  </td>
                                        <td> <a href="{{{url($a->route)}}}" target="_blank">{{{$a->route}}}</a></td>
                                        <td>@if(isset($a->deleted_at)) {{{trans_choice('admin.deactivate',2)}}} @else {{{trans_choice('admin.activate',2)}}} @endif</td>
                                        <td>
                                            <div class="dropup dropdown-custom-left">
                                                <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                                <ul class="dropdown-menu admin_list_dropup">
                                                    <li class="action" data-action = "delete" data-page-id ="{{{$a->id}}}" data-page-title = "{{{$a->title}}}" ><a href="javascript:;">{{{trans_choice('admin.delete',0)}}} {{{trans_choice('admin.page',1)}}}</a></li>
                                                    <li class="edit" data-page-id ="{{{$a->id}}}" data-page-title = "{{{$a->title}}}" data-page-body = "{{{$a->body}}}" data-page-route="{{{$a->route}}}"  data-page-layout="{{{$a->layout}}}" data-toggle="modal" data-target="#myModal"><a href="javascript:;">{{{trans_choice('admin.edit',0)}}} {{{trans_choice('admin.page',1)}}}</a></li>
                                                    @if(isset($a->deleted_at))
                                                        <li class="status" data-action = "activate" data-page-id ="{{{$a->id}}}" data-page-title = "{{{$a->title}}}" ><a href="javascript:;">{{{trans_choice('admin.activate',2)}}}</a></li>
                                                    @else
                                                        <li class="status" data-action = "deactivate" data-page-id ="{{{$a->id}}}" data-page-title = "{{{$a->title}}}" ><a href="javascript:;">{{{trans_choice('admin.deactivate',2)}}}</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                                @endforeach
                                @else
                                <tr >
                                    <td colspan = "4" style = "text-align : center; color : red">{{ trans('admin.no_found', ['text' => trans_choice('admin.layout',1)]) }}.</td>
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
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="add-credit-package-text">{{{trans_choice('admin.edit',0)}}} {{{trans_choice('admin.page',1)}}}</p>
            </div>
            <div class="modal-body">
                <form action = "{{{url('admin/pages/create')}}}" method = "POST" id = "create-page-form">
                    {!! csrf_field() !!}
                    <input type = "hidden" id = "page-id" value = "">
                    <div class="form-group">
                        <label class="package-label">{{trans_choice('admin.title', 0 )}}</label>
                        <input type="text" id = "page-title" placeholder="{{trans_choice('admin.enter', 0 )}} {{trans_choice('admin.page', 1 )}} {{trans_choice('admin.title', 0 )}}" name = "title" class="form-control  input-border-custom">
                    </div>
                    <div class="form-group">
                        <label class="package-label">{{trans_choice('admin.body', 0 )}} {{trans_choice('admin.content', 0 )}}</label>
                        <textarea class="form-control  input-border-custom" placeholder="{{trans_choice('admin.page_field_holder', 0 )}}" name = "body"  id= "page-body"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="package-label">Route Url</label>
                        <input type="text" id = "page-route" placeholder="{{trans_choice('admin.page_field_holder', 1 )}}" name = "route" class="form-control  input-border-custom">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-addpackage btn-custom" data-dismiss="modal">{{trans_choice('admin.close', 0)}} </button>
                <button id = "change_password_btn" type="submit" class="btn btn-info btn-addpackage btn-custom add-update-btn" style = "margin-right:5px;">UPDATE </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script src="{{{asset('core')}}}/ckeditor/ckeditor.js"></script>
<script src="{{{asset('core')}}}/ckeditor/config.js"></script>

<link href="{{{asset('Admin')}}}/css/toastr.css" rel="stylesheet"/>
<script src="{{{asset('Admin')}}}/js/toastr.js"></script>
<script>

CKEDITOR.replace('body', {
    //  filebrowserBrowseUrl: '/browser/browse.php?type=Files',
    filebrowserUploadUrl: '{{{url('/pages/upload/image')}}}'
});

CKEDITOR.replace('page-body', {
    //  filebrowserBrowseUrl: '/browser/browse.php?type=Files',
    filebrowserUploadUrl: '{{{url('/pages/upload/image')}}}'
});


    $('.edit').click(function(){
    
    
        var page_id = $(this).data('page-id');
        var page_title = $(this).data('page-title');
        var page_body = $(this).data('page-body');
        var page_layout = $(this).data('page-layout');
        var page_route = $(this).data('page-route');
    
   
        $('#page-id').val(page_id);
        $('#page-title').val(page_title);
        $('#page-layout').val(page_layout);
        $('#page-body').val(page_body);
        CKEDITOR.instances['page-body'].setData(page_body);
        $('#page-route').val(page_route);
    
    });
    
    
    
    var func = function(){
    
        
        var page_id =  $('#page-id').val();
        var page_title =  $('#page-title').val();
        var page_body = CKEDITOR.instances['page-body'].getData(); 
        var page_layout = $('#page-layout').val();
        var page_route =  $('#page-route').val();

    
        if(page_body == '') { 
            toastr.warning( '{{trans('admin.require_attr', ['attr' => trans_choice('admin.advertise',1).' '.trans_choice('admin.html',1).' '.trans_choice('admin.code',1)] )}} ');
            return false;
        }
    
        if(page_title == '') {
            toastr.warning('{{trans('admin.require_attr', ['attr' => trans_choice('admin.title',1)])}}');
            return false;
        }
    
        if(page_route == '') {
            toastr.warning('{{trans('admin.require_attr', ['attr' => trans_choice('admin.route',1)])}}');
            return false;
        }
    
        var data = {};
    
        data['id'] = page_id;
        data['title'] = page_title;
        data['body'] = page_body;
        data['layout'] = page_layout;
        data['route'] = page_route;
        data['_token'] = "{{{csrf_token()}}}";
    
        $.post("{{{url('/admin/pages/update')}}}", data, function(response){
            if(response.status == 'success'){
                toastr.success(response.message);
                    setTimeout(function(){
                   window.location.reload();
                }, 1000); 
            }
            else if (response.status == 'error')
                toastr.error(response.message);
        });
    
        
    };
    
    $('.add-update-btn').click(func);
    
    
    
    
    
    
    $('.status').click(function(){
    
     var action = $(this).data('action');
    
    var page_id = $(this).data('page-id');
    var page_title = $(this).data('page-title');
    
     if(action == 'activate') {
    
            
    
                var data = {};
    
                data['page_id'] = page_id;
                data['page_title'] = page_title;
                data['_token'] = "{{{csrf_token()}}}";
    
                $.post("{{{url('/admin/page/activate')}}}", data, function(response){
                    if(response.status == 'success'){
                        toastr.success(response.message);
                        setTimeout(function(){
                   window.location.reload();
                }, 1000);   
                    }
                    else if (response.status == 'error')
                        toastr.error(response.message);
                });
     
     } else if(action == 'deactivate')  {
    
    
    
                var data = {};
    
                data['page_id'] = page_id;
                data['page_title'] = page_title;
                data['_token'] = "{{{csrf_token()}}}";
    
                $.post("{{{url('/admin/page/deactivate')}}}", data, function(response){
                    if(response.status == 'success'){
                        toastr.success(response.message);
                        setTimeout(function(){
                   window.location.reload();
                }, 1000);
                    }
                    else if (response.status == 'error')
                        toastr.error(response.message);
                });
    
     }
     
     
    
    });
    
    
    
    
    $('.action').click(function(){
    
        var row = $(this).data('page-id');
        var data = {};
    
        data['id'] = row;
        data['title'] = $(this).data('page-title');
        data['_token'] = "{{{csrf_token()}}}";
    
        $.post("{{{url('/admin/pages/delete')}}}", data, function(response){
            if(response.status == 'success'){
                toastr.success(response.message);
    
                $('#page-'+row).remove();
    
            }
            else if (response.status == 'error')
                toastr.error(response.message);
        });
    
    });
    
    
    $('#create-page-btn').click(function(e){
        e.preventDefault();
    
        var title = $('#title').val();
        var body = CKEDITOR.instances.body.getData(); 
        var route = $('#route').val();
        var layout = $('#layout').val();

        $('#body').val(body);
        
        if(title == '') {
            toastr.warning("{{trans('admin.require_attr', ['attr' => trans_choice('admin.page',1).' '.trans_choice('admin.title',1)] )}}.");
            return false;
        }
    
        if(body == '') {
            toastr.warning("{{trans('admin.require_attr', ['attr' => trans_choice('admin.page',1).' '.trans_choice('admin.body',1).' '.trans_choice('admin.code',1)] )}}.");
            return false;
        }
     
        if(route == '') {
            toastr.warning("{{trans('admin.require_attr', ['attr' => trans_choice('admin.page',1).' '.trans_choice('admin.route',1)] )}}.");
            return false;
        }
    
        if(layout == '') {
            toastr.warning("{{trans('admin.require_attr', ['attr' => trans_choice('admin.page',1).' '.trans_choice('admin.layout',1)] )}}.");
            return false;
        }
    
        var data = $('#create-page-form').serializeArray();
        $.post("{{{url('admin/pages/create')}}}", data, function(response){
    
            if(response.status == 'success'){
                toastr.success(response.message);
    
    
                setTimeout(function(){
                   window.location.reload();
                }, 1000);
    
            }
            else if (response.status == 'error'){
                toastr.error(response.message);
            } else {
                toastr.warning(response.message);
            }
    
        });
    
    });
    
    
    
</script>
<style type="text/css">
    .admin_list_dropup{
    margin-left: -155px;
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
    
    .section-first-col{
    min-height: 0px;
    }

</style>
@endsection