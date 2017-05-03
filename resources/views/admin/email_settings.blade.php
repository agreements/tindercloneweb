@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
   
/* The snackbar - position it at the bottom and in the middle of the screen */
#snackbar {
    visibility: hidden; /* Hidden by default. Visible on click */
    min-width: 250px; /* Set a default minimum width */
    margin-left: -125px; /* Divide value of min-width by 2 */
    background-color: #333; /* Black background color */
    color: #fff; /* White text color */
    text-align: center; /* Centered text */
    border-radius: 2px; /* Rounded borders */
    padding: 16px; /* Padding */
    position: fixed; /* Sit on top of the screen */
    z-index: 1; /* Add a z-index if needed */
    left: 50%; /* Center the snackbar */
    top: 30px; /* 30px from the bottom */
   max-width: 419px;
}
#snackbar > .fa-times {
   position: absolute;
    right: -7px;
    top: -10px;
    font-size: 19px;
    cursor: pointer;
}

.danger-color {
   background-color : #D9534F !important;
}

.success-color {
   background-color : #4CAE4C !important;
}

/* Show the snackbar when clicking on a button (class added with JavaScript) */
.show {
    visibility: visible !important; /* Show the snackbar */

/* Add animation: Take 0.5 seconds to fade in and out the snackbar. 
However, delay the fade out process for 2.5 seconds */
    -webkit-animation: fadein 0.5s;
    animation: fadein 0.5s;
}

/* Animations to fade the snackbar in and out */
@-webkit-keyframes fadein {
    from {top: 0; opacity: 0;} 
    to {top: 30px; opacity: 1;}
}

@keyframes fadein {
    from {top: 0; opacity: 0;}
    to {top: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
    from {top: 30px; opacity: 1;} 
    to {top: 0; opacity: 0;}
}

@keyframes fadeout {
    from {top: 30px; opacity: 1;}
    to {top: 0; opacity: 0;}
}


</style>


<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.email_settings', 1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">
            <div class="col-md-12 credits-first-col">
               <p class="default-credit-settings">{{trans_choice('admin.email_primary', 1)}}</p>
               <!-- <div class="row"> -->
               <div class="form-group">
                  <label class="package-label">{{trans_choice('admin.driver', 1)}}</label>
                  <select name = "driver" id = "driver" class="form-control input-border-custom select-custom">
                     @if(isset($mail_driver))
                     <option value = "smtp" @if($mail_driver == 'smtp') selected @endif>SMTP</option>
                     <option value = "mandrill" @if($mail_driver == 'mandrill') selected @endif>MANDRILL</option>
                     @else
                     <option value = "">{{trans_choice('admin.choose_driver', 1)}}</option>
                     <option value = "smtp">SMTP</option>
                     <option value = "mandrill" >MANDRILL</option>
                     @endif
                  </select>
               </div>
               <button type="button" id = "set-driver-btn" class="btn btn-info btn-addpackage btn-custom">Save</button>
               <!-- </div> -->
            </div>


            <div class="col-md-12 credits-first-col">
               <p class="default-credit-settings">{{{trans('admin.test_mail_title')}}}</p>
               <!-- <div class="row"> -->
               <div class="form-group">
                  <input type="text" placeholder="{{{trans('admin.test_mail_id_placeholder')}}}" class="form-control input-border-custom" id = "test-mail-id">
               </div>
               <button type="button" id="test-mail-btn" class="btn btn-info btn-addpackage btn-custom">{{{trans('admin.test_mail_btn')}}}</button>
               <!-- </div> -->
            </div>



            <div class="col-md-5 add-creditpackage-col">
               <p class="add-credit-package-text">{{trans_choice('admin.set_smtp', 1)}}</p>
               <form action = "" method = "POST" id = "smtp-form">
                  {!! csrf_field() !!}
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.host', 1)}}</label>
                     <input type="text" value = "{{{$smtp->host}}}" id = "host" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.email', 1)}} {{trans_choice('admin.host', 1)}}" name = "host" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.port', 1)}}</label>
                     <input type="text" id = "port" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.email', 1)}} {{trans_choice('admin.port', 1)}}" name = "port" class="form-control  input-border-custom" value = "{{{$smtp->port}}}">
                  </div>
                  <div class="form-group">
                     <label class="package-label">Encryption</label>
                     <input type="text" id = "encryption" placeholder="Enter Email Enctryption Type Eg : tls" name = "encryption" class="form-control  input-border-custom" value = "{{{$smtp->encryption}}}">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.from', 1)}} {{trans_choice('admin.name', 1)}}</label>
                     <input type="text" id = "name" placeholder="{{trans_choice('admin.from_name_placeholder', 1)}}" name = "name" class="form-control  input-border-custom" value = "{{{$smtp->name}}}">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.from', 1)}} {{trans_choice('admin.email', 1)}}</label>
                     <input type="email" id = "from" placeholder="{{trans_choice('admin.from_mail_placeholder', 1)}}" name = "from" class="form-control  input-border-custom" value = "{{{$smtp->from}}}">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.username', 1)}}</label>
                     <input type="text" id = "username" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.username', 1)}}" name = "username" class="form-control  input-border-custom" value = "{{{$smtp->username}}}">
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.password', 1)}}</label>
                     <input type="password" name = "password" id = "password" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom input-border-custom" id="usr">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.confirm', 0)}} {{trans_choice('admin.password', 1)}}</label>
                     <input type="password" id = "password_confirmation" name = "password_confirmation" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.confirm', 0)}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom">
                  </div>
                  <button type="button" id = "set-smtp-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 1)}}</button>
               </form>
            </div>
            <div class="col-md-5 col-add-superpower">
               <p class="add-credit-package-text">{{trans_choice('admin.set_mandrill', 1)}}</p>
               <form action = "" method = "POST" id = "mandrill-form">
                  {!! csrf_field() !!}
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.host', 1)}}</label>
                     <input type="text" value = "{{{$mandrill->host}}}" id = "mhost" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.email', 1)}} {{trans_choice('admin.host', 1)}}" name = "host" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.port', 1)}}</label>
                     <input type="text" id = "mport" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.email', 1)}} {{trans_choice('admin.port', 1)}}" name = "port" class="form-control  input-border-custom" value = "{{{$mandrill->port}}}">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.username', 1)}}</label>
                     <input type="text" id = "musername" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.username', 1)}}" name = "username" class="form-control  input-border-custom" value = "{{{$mandrill->username}}}">
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.enter_key', 1)}}</label>
                     <input type="password" name = "password" id = "mpassword" placeholder="{{trans_choice('admin.enter_key', 1)}} {{trans_choice('admin.password', 1)}}" class="form-control input-border-custom input-border-custom" id="usr">
                  </div>
                  <button type="button" id = "set-mandrill-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 1)}}</button>
               </form>
            </div>
            </form>
         </div>
      </div>
   </section>
</div>
<div id="snackbar">
   <i class="fa fa-times"></i>
   <span class = "text" style="word-wrap: break-word;"></span>
</div>
@endsection
@section('scripts')
<script>

   $("#snackbar .fa-times").on("click", function(){

      $("#snackbar").attr("class", "");

   });



   $("#test-mail-btn").on('click', function(){

      var email_id = $("#test-mail-id").val();

      $.post("{{{url('admin/settings/email/test')}}}", {_token : "{{{csrf_token()}}}", email_id : email_id}, function(response){

         if (response.status == 'success') {

            $("#snackbar .text").text("{{{trans('admin.test_mail_send_success')}}}");
            $("#snackbar").attr("class", "");
            $("#snackbar").addClass("success-color");

         } else {

            $("#snackbar  .text").text(response.message);
            $("#snackbar").attr("class", "");
            $("#snackbar").addClass("danger-color");

         }

         $("#snackbar").addClass("show");

      });


   });






   $('#set-driver-btn').click(function(){
   
      var driver = $('#driver').val();
   
      var data = {};
      data['_token'] = "{{{csrf_token()}}}";
      data['driver'] = driver;
   
      $.post("{{{url('/admin/settings/email/driver/set')}}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
   
         }
   
   
      });
   
   });
   
   $('#set-smtp-btn').click(function(){
   
   
         var host                 = $("#host").val();
         var port                 = $("#port").val();
         var encryption           = $("#encryption").val();
         var name                 = $("#name").val();
         var from                 = $("#from").val();
         var username             = $("#username").val();
         var password             = $("#password").val();
         var password_confirmation= $("#password_confirmation").val();
   
   
         // if(encryption == "") {
      
         //    toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.encryption',1)] )}}.');
         //    return false;
         // }
   
         if(port == "") {
      
            
            toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.port',1)] )}}.');
            return false;
         }
   
         if(from == "") {
      
           
            toastr.error('{{trans_choice('admin.from', 1)}} {{trans('admin.require_attr', ['attr' => trans_choice('admin.email',1)] )}}.');
            return false;
         }
   
         if(host == "") {
      
           
            toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.host',1)] )}}.');
            return false;
         }
   
         if(name == "") {
      
           
            toastr.error('{{trans_choice('admin.from', 1)}} {{trans('admin.require_attr', ['attr' => trans_choice('admin.name',1)] )}}.');
            return false;
         }
      
         if(username == "") {
      
           
            toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.username',1)] )}}.');
            return false;
         }
      
      
         if(password == "") {
      
            
            toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.password',1)] )}}.');
            return false;
         } 
      
            
      
         if(password_confirmation == "") {
      
            
            toastr.error('{{trans_choice('admin.confirm', 0)}} {{trans('admin.require_attr', ['attr' => trans_choice('admin.pasword',1)] )}}.');
            return false;
      
         } else {      
      
            if(password != password_confirmation) {
   
              
               toastr.error('{{trans('admin.not_match', ['attr' => trans_choice('admin.pasword',1)] )}}.');  
               return false;
            }
      
         }
   
         var data = $('#smtp-form').serializeArray();
     
      $.post("{{{url('/admin/settings/email/smtp/set')}}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
   });
   
   $('#set-mandrill-btn').click(function(){
   
         var host    = $("#mhost").val();
         var port    = $("#mport").val();
        
         var username= $("#musername").val();
         var password= $("#mpassword").val();
         
   
   
   
         if(port == "") {
      
            
            toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.port',1)] )}}.');
            return false;
         }
   
   
         if(host == "") {
      
           
            toastr.error('{{trans('admin.require_attr', ['attr' => trans_choice('admin.host',1)] )}}.');
            return false;
         }
   
      
      
         if(password == "") {
      
            
            toastr.error('Mandrill API Key{{trans('admin.require_attr', ['attr' => '']) }}.');
            return false;
         } 
      
   
   
      var data = $('#mandrill-form').serializeArray();
      
   
      $.post("{{{url('/admin/settings/email/mandrill/set')}}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
   });
   
   
</script>
@endsection