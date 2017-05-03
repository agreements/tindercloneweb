@extends('admin.layouts.admin')
@section('content')
@parent
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<style type="text/css">
    .set-default-language-div {
    width : 100%;
    }
    .accordion, .accordion * {
    -webkit-box-sizing:border-box; 
    -moz-box-sizing:border-box; 
    box-sizing:border-box;
    }
    .accordion {
    overflow: hidden;
    box-shadow: 0px 1px 3px rgba(0,0,0,0.25);
    border-radius: 3px;
    background: #333333;
    padding-left: 0 !important;
    }
    /*----- Section Titles -----*/
    .accordion-section-title {
    width: 100%;
    padding: 15px;
    display: inline-block;
    border-bottom: 1px solid #1a1a1a;
    background: #2D343C;
    transition: all linear 0.15s;
    font-size: 1.200em;
    text-shadow: 0px 1px 0px #1a1a1a;
    color: #fff;
    }
    .accordion-section
    {
    padding-left: 0 !important;
    }
    .accordion-section-title.active, .accordion-section-title:hover {
    background:#2D343C;
    /* Type */
    text-decoration:none;
    }
    .accordion-section:last-child .accordion-section-title {
    border-bottom:none;
    }
    /*----- Section Content -----*/
    .accordion-section-content {
    padding:15px;
    display:none;
    background: #38414A;
    }
    .on-search-form {
        display: none;
    }
    .status-msg {
        z-index: 9999999999;
        position: fixed;
        width: 80%;
        top: 10px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.profilefields',0)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        
        <div class="col-md-12 section-first-col section-updates-col">
            <div class="row">
                <form action = "{{{ url('/admin/profilefields/add_section/') }}}" method = "POST" id = "add_section" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <p class="add-credit-package-text">{{trans_choice('admin.add',0)}} {{trans_choice('admin.section',0)}} </p>
                    <div class="form-group">
                        <label style="color:white"> {{trans('admin.title')}} </label>
                        <input type="text" id = "new_section" placeholder="" name = "new_section" class="form-control  input-border-custom">
                    </div>
                    <button type="submit" id = "add_interest_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.add',0)}}</button>
                </form>
            </div>
            <div class="row">
                <form action = "{{{ url('/admin/profilefields/add_fieldstosection/') }}}" method = "POST" id = "add_section" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" id = "register"  name = "register" class="form-control  input-border-custom" value="no">
                    <input type="hidden" id = "search"  name = "search" class="form-control  input-border-custom" value="no">
                    <p class="add-credit-package-text">{{trans_choice('admin.add',0)}} {{trans_choice('admin.fieldstosection',0)}} </p>
                    <div class="form-group">
                        <label style="color:white"> {{trans_choice('admin.section',0)}} </label>
                        <select name = "section_id" class="form-control input-border-custom select-custom" id = "section">
                            @foreach($sections as $section)
                            <option value = "{{{$section->id}}}" >{{{$section->name}}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="color:white"> {{trans('admin.title')}} </label>
                        <input type="text" id = "new_field" placeholder="" name = "new_field" class="form-control  input-border-custom">
                    </div>
                    <div class="form-group">
                        <label style="color:white"> {{trans('admin.type')}} </label>
                        <select name = "type" class="form-control input-border-custom select-custom" id = "field-type">
                            <option value = "text" >{{trans('admin.text')}}</option>
                            <option value = "textarea" >{{trans('admin.textarea')}}</option>
                            <option value = "dropdown" >{{trans('admin.dropdown')}}</option>
                            <option value = "checkbox" >{{trans('admin.checkbox')}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <table class="table table-condensed">
                            <thead>
                                <tr >
                                    <th style="color:white">{{{trans('admin.on_registration')}}}</th>
                                    <th style="color:white" class="on-search-form">{{{trans('admin.on_search')}}}</th>
                                    <th style="color:white" class="on-search-form">{{{trans('admin.search_type')}}}</th>
                                    <th style="color:white" class="on-search-form unit-header">{{{trans('admin.type_unit')}}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <label class="switch">
                                        <input class="switch-input profile-switch" data-item-id="" data-item-name = "register" type="checkbox"/>
                                        <span class="switch-label"></span> 
                                        <span class="switch-handle"></span>
                                        </label>
                                    </td>
                                    <td class = "on-search-form">
                                        <label class="switch">
                                        <input class="switch-input profile-switch" data-item-id="" data-item-name = "search" type="checkbox"/>
                                        <span class="switch-label"></span> 
                                        <span class="switch-handle"></span> 
                                        </label>
                                    </td>
                                    <td class = "on-search-form">
                                        <select name = "search_type" class="form-control input-border-custom select-custom">
                                            <option value = "range" >{{{trans('admin.range')}}}</option>
                                            <option value = "dropdown" >{{{trans('admin.dropdown')}}}</option>
                                        </select>
                                    </td>
                                    <td class = "on-search-form">
                                        <input type="text"  placeholder="{{{trans('admin.enter_unit_type')}}}" name = "unit" class="form-control  input-border-custom">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" id = "add_interest_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.add',0)}}</button>
                </form>
            </div>
            <div class="row">
                <p class="add-credit-package-text">{{{trans('admin.custom_sections')}}}</p>
                <div class="main">
                    <div class="accordion">
                        
                        @if(count($sections) > 0)
                        @foreach($sections as $section)
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#{{{$section->id}}}">{{{$section->name}}}</a>
                            <div id="{{{$section->id}}}" class="accordion-section-content">
                                <div class="accordion-heading country">
                                    <h4>
                                        <form action="{{{url('admin/profilefields/delete_section')}}}"  class="form-horizontal" method="post">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="section_id" value="{{{ $section->id }}}" />
                                            <button type="submit" style="background:#38414A;border: none "><i class="fa fa-trash" style="color: white;"></i></button>
                                            <p class="add-credit-package-text" style="
                                                top: 0px;
                                                position: relative;
                                                display:inline-block;
                                                font-size: 13px;
                                                ">{{{trans('admin.delete_section')}}}</p>
                                        </form>
                                    </h4>
                                </div>
                                <div id="{{{ $section->code }}}" class="accordion-body">
                                    <div class="accordion-inner">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>{{{trans_choice('admin.field_attr',0)}}}</th>
                                                    <th>{{{trans_choice('admin.field_attr',1)}}}</th>
                                                    <th>{{{trans('admin.on_registration')}}}</th>
                                                    <th>{{{trans('admin.on_search')}}}</th>
                                                    <th>{{{trans_choice('admin.field_attr',2)}}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($section->fields as $field)
                                                <tr>
                                                    <td>
                                                        {{{ $field->name }}}
                                                        <form action="{{{url('admin/profilefields/delete_field')}}}"  class="deletefield form-horizontal" method="post" style="display: inline-block">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" name="field_id" value="{{{ $field->id }}}" />
                                                            @if($field->name != 'Gender') <button type="submit" style="background:#38414A;border: none "><i class="fa fa-trash" style="color: white"></i></button>@endif
                                                        </form>
                                                    </td>
                                                    <td>{{{ $field->type }}}</td>
                                                    <td>
                                                        <label class="switch">
                                                        <input class="switch-input edit-registeration-switch" data-item-id="{{{ $field->id }}}" data-item-name = "register" type="checkbox" @if($field->on_registration == 'yes') checked @endif />
                                                        <span class="switch-label"></span> 
                                                        <span class="switch-handle"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        @if($field->type != 'dropdown' && $field->type != 'checkbox')
                                                            {{{trans('admin.on_search_not_avail')}}}
                                                        @else
                                                            <label class="switch" >
                                                                <input class="switch-input edit-search-switch" data-item-id="{{{ $field->id }}}" data-item-name = "search" type="checkbox" @if($field->on_search == 'yes') checked @endif/>
                                                                <span class="switch-label"></span> 
                                                                <span class="switch-handle"></span>
                                                            </label>
                                                        @endif
                                                            
                                                    </td>
                                                    @if($field->type == "textarea" || $field->type == "text")
                                                    <td>-</td>
                                                    @else
                                                    <td>
                                                        <ul style="list-style: none">
                                                            @foreach($field->field_options as $option)
                                                            <li>
                                                                {{{ $option->name }}} {{{$field->unit}}}
                                                                <form action="{{{url('admin/profilefields/delete_option')}}}"  class="form-horizontal" method="post" style="display: inline-block">
                                                                    {!! csrf_field() !!}
                                                                    <input type="hidden" name="option_id" value="{{{ $option->id }}}" />
                                                                    @if(count($field->field_options) > 1)<button  type="submit" style="background:#38414A;border: none "><i class="fa fa-trash" style="color: white"></i></button>@endif
                                                                </form>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        <form action="{{{url('/admin/profilefields/add_fieldoption')}}}"  class="form-horizontal" method="post">
                                                            {!! csrf_field() !!}
                                                            <input type="text" name="optiontitle" style="color:black;margin-left: 41px;"
                                                                />
                                                            @if($field->on_search_type == 'range')
                                                                <label>{{{$field->unit}}}</label>
                                                                <input type="hidden" name="unit" value =" {{{$field->unit}}}">
                                                                <input type="hidden" name="on_search_type" value ="range">
                                                            @endif
                                                            <input type="hidden" name="field" value="{{{ $field->id }}}" />
                                                            <input type="submit" class="btn btn-success" style="padding: 2px 9px 2px 9px;margin-left: 8px;cursor: pointer"  value="+" />
                                                        </form>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--end .accordion-section-content-->
                        </div>
                        <!--end .accordion-section-->
                        @endforeach
                        @else
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="">{{{trans('admin.no_custom_section')}}}</a>
                        </div>
                        @endif
                    </div>
                    <!--end .accordion-->
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section("scripts")

<script>

        @if(session()->has('status'))
            @if(session('status') == 'success')
               toastr.success('{{{session('message')}}}');
            @else
                toastr.error('{{{session('message')}}}');
            @endif
        @endif



    $("#field-type").on('change', function(){
        var field_type = $(this).val();
        if(field_type == "dropdown") {
            $(".on-search-form").fadeIn();
        }else if(field_type == 'checkbox') {

            $(".on-search-form").fadeOut();
            $("input[name=unit]").val('');
            $("select[name=search_type]").prop("selectedIndex",1);

        }
         else {
            $(".on-search-form").fadeOut();
            $("#search").val('no');
            $("input[data-item-name=search]").prop('checked', false);
            $("input[name=unit]").val('');
        }
    });

    $("select[name=search_type]").on('change',function(){
        var search_type = $(this).val();

        if(search_type == 'dropdown') {
            $("input[name=unit]").val('');
            $("input[name=unit]").parent().fadeOut();
            $(".unit-header").fadeOut();
        } else {
            $("input[name=unit]").val('');
            $("input[name=unit]").parent().fadeIn();
            $(".unit-header").fadeIn();
        }
    });



    $(".profile-switch").change(function(){
    
      var field = $(this).data('item-name');
    
      if(this.checked){
    
          $('#'+field).val("yes");
      }
      else {
        
        $('#'+field).val("no");
      }
    
    });
    
    
    
    $(".edit-registeration-switch").change(function(){
    
      var field = $(this).data('item-name');
      
      
      var id= $(this).data('item-id');
      
      var checked= '';
    
      if(this.checked){
    
        checked = 'yes';
          
      }
      else {
        
            //$('#'+field).val("no");
            checked = 'no';
        
      }
      
      
        data={id:id,register:checked};
            
    
        
        $.ajax({
                      type: "POST",
                      url: "{{{ url('/admin/profilefields/edit_field') }}}",
                      data: data,
                      success: function(msg){
                            
                           toastr.success('Saved');                                     
                            
                      },
                      error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error("{{{trans_choice('app.error',1)}}}");
                      }
                      
       });
    
    
    });
    
    
    
     $(".edit-search-switch").change(function(){
    
      var field = $(this).data('item-name');
      
        var id= $(this).data('item-id');
      
      var checked= '';
    
      if(this.checked){
    
        checked = 'yes';
          
      }
      else {
        
            //$('#'+field).val("no");
            checked = 'no';
        
      }
      
      
        data={id:id,search:checked};
            
    
        
        $.ajax({
                      type: "POST",
                      url: "{{{ url('/admin/profilefields/edit_field') }}}",
                      data: data,
                      success: function(msg){
                            
                           toastr.success('Saved');                                     
                            
                      },
                      error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error("{{{trans_choice('app.error',1)}}}");
                      }
                      
       });
    
    
    });
    
</script>
<script>
    jQuery(document).ready(function() {
     close_accordion_section();
    function close_accordion_section() {
    jQuery('.accordion .accordion-section-title').removeClass('active');
    jQuery('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }
    
    jQuery('.accordion-section-title').click(function(e) {
    // Grab current anchor value
    var currentAttrValue = jQuery(this).attr('href');
    
    if(jQuery(e.target).is('.active')) {
        close_accordion_section();
    }else {
        close_accordion_section();
    
        // Add active class to section title
        jQuery(this).addClass('active');
        // Open up the hidden content panel
        jQuery('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
    }
    
    e.preventDefault();
    });
    });
</script>    
@endsection