@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.bots_management', 1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">
            



            <form action = "{{{url('admin/plugin/bot/create')}}}" method = "POST" id = "bot-create-form" enctype="multipart/form-data">
               {!! csrf_field() !!}
               
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.create', 0)}} {{trans_choice('admin.new', 0)}} {{trans_choice('admin.bot', 1)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.name', 1)}}</label>
                     <input type="text" id = "name" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.bot', 1)}} {{trans_choice('admin.name', 0)}}" name = "name" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <div class="control-group">
                      <label for="join-date-picker" class="control-label package-label">{{trans_choice('admin.join', 3)}} {{trans_choice('admin.date', 1)}}</label>
                       <div class="controls controls-custom">
                           <div class="input-group">
                              <input id="join-date-picker" name="joining" type="text" class="date-picker form-control input-border-custom" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.join', 3)}} {{trans_choice('admin.date', 1)}} Format : DD/MM/YYYY"/>
                              <label for="join-date-picker" class="input-group-addon btn label-date-custom"><span class="glyphicon glyphicon-calendar glyphicon-calendar-custom"></span>
                              </label>
                           </div>
                       </div>
                   </div>
                  <div class="form-group" >
                    <div class="control-group">
                      <label for="dob-date-picker" class="control-label package-label">{{trans_choice('admin.birth_date', 0)}}</label>
                       <div class="controls controls-custom">
                           <div class="input-group">
                              <input id="dob-date-picker" name="dob" type="text" class="date-picker form-control input-border-custom" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.birth_date', 0)}} Format : DD/MM/YYYY"/>
                              <label for="dob-date-picker" class="input-group-addon btn label-date-custom"><span class="glyphicon glyphicon-calendar glyphicon-calendar-custom"></span>
                              </label>
                           </div>
                       </div>
                   </div>
                  </div>
                  
                  @foreach($field_sections as $section)
				    
					  @foreach($section->fields as $field)
								   
						@if($field->type == "dropdown")
								   		
							<div class="form-group">
								<label class="package-label">{{{trans("custom_profile.$field->code")}}}</label>
								<select class="form-control input-border-custom select-custom" name = {{{"$field->code"}}}>
									@foreach($field->field_options as $option)
										<option value={{{$option->id}}}>{{{trans("custom_profile.$option->code")}}}</option>
									@endforeach
	                     		</select>
	                  		</div>

                        @elseif($field->type == "checkbox")
                                        
                            <div class="form-group">
                                <label class="package-label">{{{trans("custom_profile.$field->code")}}}</label>
                                <div style="color:white">
                                @foreach($field->field_options as $option)
                                    <input cass= "form-control" type="checkbox" name="{{$field->code}}[]" value="{{$option->id}}">{{trans("custom_profile.{$option->code}")}}
                                @endforeach
                                </div>
                            </div>

										    
						@elseif($field->type == "text")
							<div class="form-group amount-us-credits">
								<label class="package-label">{{{trans("custom_profile.$field->code")}}}</label>
								<input type="text" name = "{{{$field->code}}}" class="form-control input-border-custom input-border-custom" >
							</div>
						
										
						@elseif($field->type == "textarea")
							<div class="form-group amount-us-credits">
								<label class="package-label">{{{trans("custom_profile.$field->code")}}}{{trans_choice('admin.you', 2)}}</label>
								<textarea name ={{{"$field->code"}}} class="form-control input-border-custom input-border-custom" ></textarea>
	                  		</div>
						@endif    
					  @endforeach
					@endforeach
            
                @if($gender)
                  <div class="form-group">
                    <label class="package-label">{{{trans("custom_profile.$gender->code")}}}</label>
                    <select  name="{{{ $gender->code }}}" class="form-control input-border-custom select-custom" id="{{{$gender->code}}}">
                       @foreach($gender->field_options as $option)
                        <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                       @endforeach
                    </select>
                  </div>
                @endif
 
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.status', 0)}}</label>
                     <input type="text" name = "status" placeholder="{{trans_choice('admin.write', 0)}} {{trans_choice('admin.your', 0)}} {{trans_choice('admin.status', 0)}}" class="form-control input-border-custom input-border-custom" >
                  </div>
                   <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.about', 0)}} {{trans_choice('admin.you', 2)}}</label>
                     <textarea name = "aboutme" placeholder="{{trans_choice('admin.write', 0)}} {{trans_choice('admin.about', 0)}} {{trans_choice('admin.you', 2)}}." class="form-control input-border-custom input-border-custom" ></textarea>
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.password', 0)}}</label>
                     <input type="password" name = "password" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.password', 0)}}" class="form-control input-border-custom input-border-custom" >
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.confirm', 0)}} {{trans_choice('admin.password', 0)}}</label>
                     <input type="password" name = "password_confirmation" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.confirm', 0)}} {{trans_choice('admin.password', 0)}}" class="form-control input-border-custom">
                  </div>

                  <div class="form-group">
                    <div class = "input-group">
                     <label class="package-label">{{trans_choice('admin.choose_picture', 0)}}</label>
                     <input type="file"  accept = ".jpg, .png" name = "profile_pic" class="form-control input-border-custom bot-input-custom">
                     </div>
                  </div>

                  <button type="submit" id = "bot-create-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.create', 0)}} {{trans_choice('admin.bot', 1)}}</button>
               </div>
            </form>



         </div>
      </div>
</div>
</section>
</div>

@endsection
@section('scripts')

<link href="{{{asset('admin_assets')}}}/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
<script src="{{{asset('admin_assets')}}}/js/moment.min.js"></script>
<script src="{{{asset('admin_assets')}}}/js/bootstrap-datetimepicker.js"></script>


<script type="text/javascript">
  
 $(document).ready(function(){
 
  $('#dob-date-picker').datetimepicker({format: 'DD/MM/YYYY'});
  $('#join-date-picker').datetimepicker({format: 'DD/MM/YYYY'});



//create bot
$("#bot-create-form").submit(function(e){
 
  //disable the default form submission
  e.preventDefault();
 
  //grab all form data  
  var formData = new FormData($(this)[0]);
 
  $.ajax({

       url        : '{{{ url('/admin/plugin/bot/create') }}}',
       type       : 'POST',
       data       : formData,
       async      : false,
       cache      : false,
       contentType: false,
       processData: false,

       success: function (response) {

         if(response.status == 'success') {

           toastr.success(response.message);
           $("#bot-create-form")[0].reset();
          
         } else if(response.status == 'warning') {

           toastr.warning(response.message);

         } else if(response.status == 'error') {

           toastr.error(response.message);
         }

       }

   });
 
});


});






</script>
<style type="text/css">
   .admin_list_dropup{
   margin-left: -152px;
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
</style>
@endsection