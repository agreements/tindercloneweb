@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.plugins_management',1)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.installed_plugins',1)}}</p>
                            <div class="dropdown dropdown-custom-right">
                                <!-- <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button> -->
                                <!-- <ul class="dropdown-menu">
                                    <li class="action" data-action="verify"><a href="javascript:;">VERIFY SELECTED</a></li>
                                    <li class="action" data-action="deactivate"><a href="javascript:;">DEACTIVATE SELECTED</a></li>
                                    <li id="delete_users"><a href="javascript:;">DELETE SELECTED</a></li>
                                    </ul> -->
                            </div>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <!-- <th><input type="checkbox" id="select_all_users" value=""></th> -->
                                    <th>{{trans_choice('admin.plugin_name',1)}}</th>
                                    <th>{{trans_choice('admin.author',1)}}</th>
                                    <th>{{trans_choice('admin.desc',1)}}</th>
                                    <th>{{trans_choice('admin.version',1)}}</th>
                                    <th>{{trans_choice('admin.website',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($plugins) > 0)
                                @foreach($plugins as $plugin)
                                <tr>
                                    <!--  <td><input class="user-checkbox" type="checkbox" data-user-id="" value=""></label></td> -->
                                    <td>{{{$plugin->name}}}</td>
                                    <td>{{{$plugin->author}}}</td>
                                    <td>{{{$plugin->description}}}</td>
                                    <td>{{{$plugin->version}}}</td>
                                    <td>{{{$plugin->website}}}</td>
                                    <td>
                                      @if($plugin->is_core)
                                          <label> {{{trans('admin.core_plugin')}}} </label>
                                        @else
                                          <label class="switch">
                                          <input class="switch-input installed-plugin-switch" data-item-id="{{{ $plugin->id }}}" data-item-name = "{{{$plugin->name}}}" type="checkbox" @if($plugin->isactivated == "activated") checked @endif/>
                                          <span class="switch-label" ></span> 
                                          <span class="switch-handle"></span> 
                                          </label>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach    
                                @else
                                <tr >
                                    <td colspan = "8" style = "text-align : center; color : red">{{trans_choice('admin.no_plugins_found', 1)}}</td>
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
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.loaded_plugins',1)}}</p>
                            <div class="dropdown dropdown-custom-right">
                                <!-- <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                    <ul class="dropdown-menu">
                                       <li class="action" data-action="verify"><a href="javascript:;">VERIFY SELECTED</a></li>
                                       <li class="action" data-action="deactivate"><a href="javascript:;">DEACTIVATE SELECTED</a></li>
                                       <li id="delete_users"><a href="javascript:;">DELETE SELECTED</a></li>
                                    </ul> -->
                            </div>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <!-- <th><input type="checkbox" id="select_all_users" value=""></th> -->
                                    <th>{{trans_choice('admin.plugin_name',1)}}</th>
                                    <th>{{trans_choice('admin.author',1)}}</th>
                                    <th>{{trans_choice('admin.desc',1)}}</th>
                                    <th>{{trans_choice('admin.version',1)}}</th>
                                    <th>{{trans_choice('admin.website',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($loadedPlugins) > 0)
                                @foreach($loadedPlugins as $plugin)
                                <tr>
                                    <!--  <td><input class="user-checkbox" type="checkbox" data-user-id="" value=""></label></td> -->
                                    <td>{{{$plugin->name}}}</td>
                                    <td>{{{$plugin->author()}}}</td>
                                    <td>{{{$plugin->description()}}}</td>
                                    <td>{{{$plugin->version()}}}</td>
                                    <td>{{{$plugin->website()}}}</td>
                                    <td>
                                        <label class="switch">
                                        <input class="switch-input loaded-plugin-switch" data-item-website = "{{{ $plugin->website() }}}" data-item-version = "{{{ $plugin->version() }}}" data-item-description = "{{{ $plugin->description() }}}" data-item-author = "{{{ $plugin->author() }}}" data-item-name="{{{ $plugin->name }}}" type="checkbox" @if($plugin->isInstalled) checked @endif/>
                                        <span class="switch-label" ></span> 
                                        <span class="switch-handle"></span> 
                                        </label>
                                    </td>
                                </tr>
                                @endforeach    
                                @else
                                <tr >
                                    <td colspan = "8" style = "text-align : center; color : red">{{trans_choice('admin.no_plugins_found', 1)}}</td>
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
@endsection
@section('scripts')
<script>
    $(".installed-plugin-switch").change(function(){
    
      toastr.options.closeButton = true;
      toastr.options.positionClass = 'toast-top-right';
    
      var url     = "";
      var flag    = 0;
      var plugname= $(this).data('item-name');
    
      if(this.checked){
    
          flag = 1;
          url = url + "{{{url('/admin/plugin/activate')}}}?id="+$(this).data('item-id')+"&plugin="+$(this).data('item-name');
      }
      else {
    
          flag = 0;
          url = url + "{{{url('/admin/plugin/deactivate')}}}?id="+$(this).data('item-id');
      }
    
      $.get(url, function(data){ 
    
        if (flag == 1) {
          toastr.success(plugname+' {{trans_choice('admin.success', 3)}} {{trans_choice('admin.activate', 2)}}.');
          window.location.reload();
        } else {
          toastr.success(plugname+' {{trans_choice('admin.success', 3)}} {{trans_choice('admin.deactivate', 2)}}.');
        }
    
      });
    
    
    });
    
    
    
    
    $(".loaded-plugin-switch").change(function(){
    
      // var url = "";
    
      // if(this.checked){
    
      //     url = url + "{{{url('/admin/plugin/install')}}}?plugin="+$(this).data('item-name');
      //     url = url + "&author="+$(this).data('item-author');
      //     url = url + "&description="+$(this).data('item-description');
      //     url = url + "&version="+$(this).data('item-version');
      //     url = url + "&website="+$(this).data('item-website');
      // } 
      
      var data            = {};
      data['_token']      = '{{{csrf_token()}}}';
      data['plugin']      = $(this).data('item-name');
      data['author']      = $(this).data('item-author');
      data['description'] = $(this).data('item-description');
      data['version']     = $(this).data('item-version');
      data['website']     = $(this).data('item-website');
      
      $.post("{{{url('/admin/plugin/install')}}}", data, function(response){ 

          if(response.status == "success"){
            toastr.success(response.message);

            window.location.reload();
          }
          else
            toastr.error(response.message);
          
          
    
       });
    
    
    });
    
</script>
@endsection