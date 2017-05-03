
<form action = "#" method = "POST" id = "{{{$mail_id}}}-form">
   {!! csrf_field() !!}
   <input type = "hidden" id = "{{{$mail_id}}}-title" name = "title" value = "{{{$title}}}">
   <input type = "hidden" id = "{{{$mail_id}}}-mailbody-key" name = "mailbodykey" value = "{{{$mailbodykey}}}">
   <input type = "hidden" id = "{{{$mail_id}}}-mailsubject-key" name = "mailsubjectkey" value = "{{{$mailsubjectkey}}}">
   <input type = "hidden" id = "{{{$mail_id}}}-email-type" name = "email_type" value = "{{{$email_type}}}">

   <div class="col-md-10 add-creditpackage-col mail-content-div">
      <p class="add-credit-package-text">{{{$heading}}}</p>
      <div class="form-group" >
         <label class="package-label">{{{$title}}} Mail Subject</label>
         <input type="text" placeholder="Enter Subject" id = "{{{$mail_id}}}-subject" name = "{{{$mailsubjectkey}}}" value = "@if(isset($subject)) {{{$subject}}} @endif" class="form-control  input-border-custom">
      </div>
      <label class="checkbox-inline checkbox-inline-custom"><input name="{{{$mail_id}}}-content-type" type="radio" value="1" checked="checked"><span class="input-text">{{{trans_choice('admin.email_content_chooser',0)}}}</span></label> 
      <label class="checkbox-inline checkbox-inline-custom"><input name="{{{$mail_id}}}-content-type" type="radio" value="0"><span class="input-text">{{{trans_choice('admin.email_content_chooser',1)}}}</span></label>
      <div class="form-group">
         <label class="package-label package-mail-body" style = "display:none">{{{$title}}} Mail Body</label>
         <textarea  class="form-control input-border-custom" placeholder="Enter Body" id = "{{{$mail_id}}}-body" name = "{{{$mailbodykey}}}" >@if($content_type == 0) @if(isset($body)) {{{$body}}} @endif @endif</textarea>
      </div>
      <div class="form-group">
      <select class="form-control select-custom" name="template" id = "{{{$mail_id}}}-template">
         <option value="none">None</option>
         @foreach($templates as $temp)
            <option value="{{{$temp}}}" @if($content_type == 1) @if($body == $temp) selected @endif @endif>{{{$temp}}}</option>
         @endforeach
      </select>
      </div>
      
      <button type="submit" class="btn btn-info btn-addpackage btn-custom">Save</button>
   </div>
</form>


<script src="{{{asset('core')}}}/ckeditor/ckeditor.js"></script>
<script src="{{{asset('core')}}}/ckeditor/config.js"></script>
<script>

CKEDITOR.replace('{{{$mail_id}}}-body', {
    filebrowserUploadUrl: '{{{url('/plugin/upload/email/image')}}}'
});

$('#{{{$mail_id}}}-form').submit(function(e){
   e.preventDefault();
   
   var title        = $('#{{{$mail_id}}}-title').val();
   var body_key     = $('#{{{$mail_id}}}-mailbody-key').val();
   var subject_key  = $('#{{{$mail_id}}}-mailsubject-key').val();
   var subject      = $('#{{{$mail_id}}}-subject').val();
   var body         = CKEDITOR.instances['{{{$mail_id}}}-body'].getData(); //$('#{{{$title}}}-body').val();
   var email_type   = $('#{{{$mail_id}}}-email-type').val();
   var content_type = $("input[name='{{{$mail_id}}}-content-type']:checked").val();
   var template     = $('#{{{$mail_id}}}-template').val();

   if (content_type == 1  && template == 'none') {
       toastr.error('{{{trans_choice('admin.email_content_msg',0)}}}');
       return false;
   }
   if(subject == "") {
      toastr.error(title+' {{{trans_choice('admin.email_content_msg',1)}}}');
      return false;
   }

   if(body == "" && template == "") {
      toastr.error( title +' {{{trans_choice('admin.email_content_msg',2)}}}');
      return false;
   } else {
      
      data                          = {};
      data['_token']                = "{{{csrf_token()}}}";
      data['title']                 = title;
      data['mailbodykey']           = body_key;
      data['mailsubjectkey']        = subject_key;
      data['{{{$mailsubjectkey}}}'] = subject;
      data['text_field']            = body;
      data['email_type']            = email_type;
      data['content_type']          = content_type;
      data['template']              = template;

      $.post("{{{url('/admin/settings/email/save')}}}", data, function(response){
         console.log(response);
         if(response.status == 'success') {

            toastr.success(title+' {{{trans_choice('admin.set_status_message',0)}}}');

         } else {
            toastr.error(title+'{{{trans_choice('admin.set_status_message',1)}}} {{{$title}}} {{{trans_choice('admin.set_status_message',2)}}}');     
         }

      });

   }

   

});

$('document').ready(function(){
	
	$("#{{{$mail_id}}}-template").css("display","block");
      $("#{{{$mail_id}}}-form div.cke").css("display","none");

$('input:radio[name="{{{$mail_id}}}-content-type"]').change(function(){
    if($(this).val() == 0){
       $("#{{{$mail_id}}}-template").css("display","none");
       $("#{{{$mail_id}}}-form div.cke").css("display","block");
    }
    else{
      $("#{{{$mail_id}}}-template").css("display","block");
      $("#{{{$mail_id}}}-form div.cke").css("display","none");
    }
});


});
// $(':radio[name="Visitor-content-type"][value="1"]').on("click",function(){
//    if ($(this).is(':checked')){
//        alert("fdgfdgf");
//    }
   
// });


</script>
<style type="text/css">

   #{{{$mail_id}}}-template{
      display: none;
   }
    #{{{$mail_id}}}-form  div.jqte{
      display: none;
   }
   /*#{{{$mail_id}}}-form > div > div:nth-child(2){
      display: none;
   }*/
   .cke{
      display: none;
   }
}
</style>