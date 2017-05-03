<?php use App\Components\Theme; ?>
@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{{trans_choice('admin.email_content_settings',1)}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">

        <div class="row">
          <div class="col-md-10 add-creditpackage-col mail-content-div mail-content-custom">
             <p class="add-credit-package-text">{{trans('admin.parser_keywords')}}</p>
             <label class="package-label">@email or @to_email -> {{trans('admin.@email')}}</label><br>
             <label class="package-label">@from_email -> {{trans('admin.@from_email')}}</label><br>
             <label class="package-label">@website_name -> {{trans('admin.@website_name')}}</label><br>
             <label class="package-label">@website_link -> {{trans('admin.@website_link')}}</label><br>
             <label class="package-label">@name or @to_name-> {{trans('admin.@name')}}</label><br>
             <label class="package-label">@from_name -> {{trans('admin.@from_name')}}</label><br>
             <label class="package-label">@profile_link or @to_profile_link-> {{trans('admin.@profile_link')}}</label><br>
             
             <label class="package-label">@from_profile_link -> {{trans('admin.@from_profile_link')}}</label><br>
             <label class="package-label">@forgot_password_link -> {{trans('admin.@forgot_password_link')}}</label><br>
             <label class="package-label">@activate_account_link -> {{trans('admin.@activate_account_link')}}</label><br>
          </div>
        </div>

      <div class="row">
        <form action = "{{{url('admin/email/footer/save')}}}" method = "POST" id = "">
         {!! csrf_field() !!}
         
         <div class="col-md-10 add-creditpackage-col mail-content-div">
            <p class="add-credit-package-text">{{{trans('admin.footer_emails')}}}</p>
            
            <div class="form-group">
               <textarea  class="form-control input-border-custom" placeholder="Enter Text" id="footer_text" name="footer_text">@if(isset($footer_text)){{{$footer_text}}}@endif</textarea>
            </div>
            
            <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{{trans('admin.save')}}}</button>
         </div>
      </form>
    </div>

         <div class="row">
         <script src="{{{asset('admin_assets')}}}/plugins_assets/jQuery/jQuery-2.1.4.min.js"></script>

        <link type="text/css" rel="stylesheet" href="{{{asset('admin_assets')}}}/css/jquery-te-1.4.0.css">







            {{{Theme::render('admin_email_content')}}}
      

         </div>
      </div>

</section>
</div>






@endsection
@section('scripts')

<script type="text/javascript" src="{{{asset('admin_assets')}}}/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>

<style type="text/css">
   
.mail-content-div{
   width : 100%;
  /* padding-left: 10%;
   padding-right: 10%;*/
}

.section-first-col{
    min-height: 0px;
}

</style>
@endsection