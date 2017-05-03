@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    
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
.user-img-custom {
    cursor: pointer;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('admin.themes_management')}}</h1>
    </section>
    <!-- Main content -->


<div id="credits-screenshot" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" >
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title report_photo_title">{{{trans_choice('admin.add_screenshot',2)}}}</h4>
            </div>
            <form role="form" method = "POST" action = "{{{ url('/admin/setCreditsScreenshot') }}}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" class="theme_name" name="theme_name">
                
                <div class="modal-body">
                     <div class="form-group">
                    
                        <label class = "package-label package-bg-label-custom">Screenshot for Spotlight </label><br>
                        <label class="input-label-custom"><input type="file" name="spotlight" class="input-custom-style"/></label>
                    </div>
                    
                    <div class="form-group" >
                        <label class = "package-label package-bg-label-custom"> Screenshot for Rise Up </label><br>
                        <label class="input-label-custom"><input type="file" name="riseup" class="input-custom-style"/></label>
                    </div>
                </div>
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-default btn-upload-custom" data-dismiss="modal">{{{trans_choice('app.cancel',1)}}}</button>
                    <button type="submit" class="btn btn-primary btn-upload-custom">{{{trans_choice('app.upload',1)}}}</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <section class="content">

        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.intalled_theme', 1)}}</p>
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
                                    <th>{{trans_choice('admin.theme_name',1)}}</th>
                                    <th>{{trans_choice('admin.screenshot',1)}}</th>
                                    <th>{{trans_choice('admin.author',1)}}</th>
                                    <th>{{trans_choice('admin.desc',1)}}</th>
                                    <th>{{trans_choice('admin.version',1)}}</th>
                                    <th>{{trans_choice('admin.website',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                    <th>{{trans_choice('admin.action',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($themes[0]) > 0)
                                @foreach($themes[0] as $theme)
                                <tr>
                                    <!--  <td><input class="user-checkbox" type="checkbox" data-user-id="" value=""></label></td> -->
                                    <td>{{{$theme->name}}}</td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="background: url({{{url('admin/themes/screenshot')}}}/{{{$theme->name}}});background-size:contain;"></div>
                                    </td>
                                    <td>{{{$theme->author}}}</td>
                                    <td>{{{$theme->description}}}</td>
                                    <td>{{{$theme->version}}}</td>
                                    <td>{{{$theme->website}}}</td>
                                    @if($theme->isactivated == 'activated')
                                    <td>Currently Activated</td>
                                    @else
                                    <td>
                                        <label class="switch">
                                        <input class="switch-input installed-base-theme-switch" data-item-parent = "parent" data-item-theme = "{{{$theme->name}}}" data-item-id="{{{ $theme->id }}}" type="checkbox"/>
                                        <span class="switch-label" ></span> 
                                        <span class="switch-handle"></span> 
                                        </label>
                                    </td>
                                    @endif
                                    <td>
                                        <button class = "btn btn-info btn-addpackage btn-custom credits_screenshot" href="#credits-screenshot" data-toggle="modal">{{{trans_choice('admin.add_screenshot',1)}}}</button>
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
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.intalled_theme', 2)}}</p>
                            <div class="dropdown dropdown-custom-right">
                            </div>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{trans_choice('admin.theme_name',1)}}</th>
                                    <th>{{trans_choice('admin.screenshot',1)}}</th>
                                    <th>{{trans_choice('admin.author',1)}}</th>
                                    <th>{{trans_choice('admin.desc',1)}}</th>
                                    <th>{{trans_choice('admin.version',1)}}</th>
                                    <th>{{trans_choice('admin.website',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($childThemes[0]) > 0)
                                @foreach($childThemes[0] as $theme)
                                <tr>
                                    <td>{{{$theme->name}}}</td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="background: url({{{url('admin/themes/screenshot')}}}/{{{$theme->name}}});background-size:contain;"></div>
                                    </td>
                                    <td>{{{$theme->author}}}</td>
                                    <td>{{{$theme->description}}}</td>
                                    <td>{{{$theme->version}}}</td>
                                    <td>{{{$theme->website}}}</td>
                                    <td>
                                        <label class="switch">
                                        <input class="switch-input installed-child-theme-switch" data-item-parent = "child" data-item-theme = "{{{$theme->name}}}" data-item-id="{{{ $theme->id }}}" type="checkbox" @if($theme->isactivated == 'activated') checked @endif/>
                                        <span class="switch-label" ></span> 
                                        <span class="switch-handle"></span> 
                                        </label>
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
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.loaded_theme',1)}}</p>
                            <div class="dropdown dropdown-custom-right">
                            </div>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{trans_choice('admin.theme_name',1)}}</th>
                                    <th>{{trans_choice('admin.screenshot',1)}}</th>
                                    <th>{{trans_choice('admin.author',1)}}</th>
                                    <th>{{trans_choice('admin.desc',1)}}</th>
                                    <th>{{trans_choice('admin.version',1)}}</th>
                                    <th>{{trans_choice('admin.website',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($loadedThemes) > 0)
                                @foreach($loadedThemes as $theme)
                                <tr>
                                    <td>{{{$theme->name}}}</td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="background: url({{{url('admin/themes/screenshot')}}}/{{{$theme->name}}});background-size:contain;"></div>
                                    </td>
                                    <td>{{{$theme->author()}}}</td>
                                    <td>{{{$theme->description()}}}</td>
                                    <td>{{{$theme->version()}}}</td>
                                    <td>{{{$theme->website()}}}</td>
                                    <td>
                                        <label class="switch">
                                        <input class="switch-input loaded-base-theme-switch" data-item-website = "{{{ $theme->website() }}}" data-item-version = "{{{ $theme->version() }}}" data-item-description = "{{{ $theme->description() }}}" data-item-author = "{{{ $theme->author() }}}" data-item-theme="{{{ $theme->name }}}" data-item-parent = "parent" type="checkbox" @if($theme->isInstalled) checked @endif/>
                                        <span class="switch-label" ></span> 
                                        <span class="switch-handle"></span> 
                                        </label>
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
                <div class="col-md-12 user-dropdown-col">
                    <div class="table-responsive">
                        <div class="col-md-12 col-table-inside">
                            <p class="users-text">{{trans_choice('admin.loaded_theme',2)}}</p>
                            <div class="dropdown dropdown-custom-right">
                            </div>
                        </div>
                        <table class="table" id="user-table">
                            <thead>
                                <tr>
                                    <th>{{trans_choice('admin.theme_name',1)}}</th>
                                    <th>{{trans_choice('admin.screenshot',1)}}</th>
                                    <th>{{trans_choice('admin.author',1)}}</th>
                                    <th>{{trans_choice('admin.desc',1)}}</th>
                                    <th>{{trans_choice('admin.version',1)}}</th>
                                    <th>{{trans_choice('admin.website',1)}}</th>
                                    <th>{{trans_choice('admin.status',1)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($loadedChildThemes) > 0)
                                @foreach($loadedChildThemes as $theme)
                                <tr>
                                    <td>{{{$theme->name}}}</td>
                                    <td>
                                        <div class="col-md-2 user-img-custom" style="background: url({{{url('admin/themes/screenshot')}}}/{{{$theme->name}}});background-size:contain;"></div>
                                    </td>
                                    <td>{{{$theme->author()}}}</td>
                                    <td>{{{$theme->description()}}}</td>
                                    <td>{{{$theme->version()}}}</td>
                                    <td>{{{$theme->website()}}}</td>
                                    <td>
                                        <label class="switch">
                                        <input class="switch-input loaded-child-theme-switch" data-item-website = "{{{ $theme->website() }}}" data-item-version = "{{{ $theme->version() }}}" data-item-description = "{{{ $theme->description() }}}" data-item-author = "{{{ $theme->author() }}}" data-item-theme="{{{ $theme->name }}}" data-item-parent = "child"  type="checkbox" @if($theme->isInstalled) checked @endif/>
                                        <span class="switch-label" ></span> 
                                        <span class="switch-handle"></span> 
                                        </label>
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
  <div class="modal-dialog" style="position: fixed;left: 10%;width: 80vw;">

    <!-- Modal content-->
    <div class="modal-content" style="background-color: white;padding: 5px;border-radius: 5px;">
      <img src=""
      width="100%" height="100%">
    </div>

  </div>
</div>
@endsection
@section('scripts')
<script>

$(".user-img-custom").click(function(){
    var url = $(this).css('background-image');
    url = url.substring(5, url.length-2);
    $("#img-prev-modal > .modal-dialog > .modal-content > img").attr("src", url);
    $("#img-prev-modal").modal('show');
});

$('.credits_screenshot').on("click",function(){
    var theme = $(this).parent().parent().find('td:first-child').text();
    $('.theme_name').attr("value",theme);
  
});
    $(".installed-base-theme-switch").change(function(){
    
      var url = "";
    
      if(this.checked){
    
          url = url + "{{{url('/admin/theme/activate')}}}?theme="+$(this).data('item-theme');
          url = url + "&id="+$(this).data('item-id');
          url = url + "&role="+$(this).data('item-parent');
      } 
    
      $.get(url, function(data){ 
    
          window.location.reload();
    
       });
    
    
    });
    
    $(".installed-child-theme-switch").change(function(){
    
      var url = "";
    
      if(this.checked){
    
          url = url + "{{{url('/admin/theme/activate')}}}?theme="+$(this).data('item-theme');
          url = url + "&id="+$(this).data('item-id');
          url = url + "&role="+$(this).data('item-parent');
    
      } else {
    
          url = url + "{{{url('/admin/childtheme/deactivate')}}}";
    
      }
    
      $.get(url, function(data){ 
    
          window.location.reload();
    
       });
    
    
    });
    
    
    $(".loaded-base-theme-switch").change(function(){
    
      var url = "";
    
      if(this.checked){
    
          url = url + "{{{url('/admin/theme/install')}}}?theme="+$(this).data('item-theme');
          url = url + "&author="+$(this).data('item-author');
          url = url + "&role="+$(this).data('item-parent');
          url = url + "&description="+$(this).data('item-description');
          url = url + "&version="+$(this).data('item-version');
          url = url + "&website="+$(this).data('item-website');
      } 
    
      $.get(url, function(data){ 
    
          window.location.reload();
    
       });
    
    
    });
    
    
    $(".loaded-child-theme-switch").change(function(){
    
      var url = "";
    
      if(this.checked){
    
          url = url + "{{{url('/admin/theme/install')}}}?theme="+$(this).data('item-theme');
          url = url + "&author="+$(this).data('item-author');
          url = url + "&role="+$(this).data('item-parent');
          url = url + "&description="+$(this).data('item-description');
          url = url + "&version="+$(this).data('item-version');
          url = url + "&website="+$(this).data('item-website');
      } 
    
      $.get(url, function(data){ 
    
          window.location.reload();
    
       });
    
    
    });
</script>

@endsection