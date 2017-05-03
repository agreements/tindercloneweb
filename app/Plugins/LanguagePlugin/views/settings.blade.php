@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
   .set-default-language-div {
   width : 100%;
   }
  
.section-first-col{
    min-height: 0px;
}
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{{trans_choice('admin.language_header', 1)}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
         <div class="row">
            <div class="col-md-10 add-creditpackage-col set-default-language-div">
               <p class="add-credit-package-text">{{{trans_choice('admin.language_title', 1)}}}</p>
               <div class="form-group">
                  <label class="package-label">{{{trans_choice('admin.language_form_field', 0)}}}</label>
                  @if(count($langs) > 0)
                  <select name = "default-lang" class="form-control input-border-custom select-custom" id = "default-lang">
                  @foreach($langs as $lang)
                  <option vlaue = "{{{$lang}}}" @if($defaultLangValue == $lang) selected @endif>{{{$lang}}}</option>
                  @endforeach
                  </select>
                  @else
                  <select class="form-control input-border-custom select-custom">
                     <option value = "">{{{trans_choice('admin.language_form_field', 1)}}}</option>
                  </select>
                  @endif   
               </div>
               <button type="button" id = "default-lang-set-btn" class="btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.language_form_field', 6)}}}</button>
            </div>


            <div class="col-md-10 add-creditpackage-col set-default-language-div">
               <p class="add-credit-package-text">{{{trans('admin.create_new_language_title')}}}</p>
               <div class="form-group ">
                    <label class="package-label">{{trans('admin.new_language_name')}}</label>
                    <input type="text" id="new_language" placeholder="{{trans('admin.new_language_name_placeholder')}}" class="form-control  input-border-custom">
                </div>
               <button type="button" id="create_new_language" class="btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.create', 0)}}}</button>
            </div>

        
            <div class="col-md-10 add-creditpackage-col set-default-language-div">
               <p class="add-credit-package-text">{{{trans_choice('admin.language_form_field', 2)}}}</p>
               <div class="form-group">
                  <label class="package-label">{{{trans_choice('admin.language_form_field', 3)}}}</label>
                  @if(count($editable) > 0)
                  <select name = "" class="form-control input-border-custom select-custom" id = "edit-lang">
                  <option value = "-1">{{{trans_choice('admin.language_form_field', 4)}}}</option>
                     @foreach($editable as $lang => $lang_files)
                     <option value = "{{{$lang}}}">{{{$lang}}}</option>
                     @endforeach
                  </select>

                  @foreach($editable as $lang => $lang_files)
                    <select name = "" class="form-control input-border-custom select-custom lang-file-class"  id = "colapse-lang-files-{{{$lang}}}" style="display:none">
                        <option value = "-1">Choose Language File</option>
                         @foreach($lang_files as $lang_file)
                         <option value = "{{{$lang_file}}}">{{{str_replace('.php', '', $lang_file)}}}</option>
                         @endforeach
                    </select>
                    @endforeach
                    <button type="button"  class="edit-btn btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.edit', 0)}}}</button>
                  @else
                  <p class = "input-border-custom">{{{trans_choice('admin.language_form_field', 5)}}}</p>
                  @endif
               </div>
            </div>
         

         </div>
      </div>
   </section>
</div>
@endsection
@section('scripts')

<script>

   $( document ).ready(function() {


        $("#create_new_language").on("click", function(){

            var new_language = $("#new_language").val();


            if(new_language.length != 2) {
                toastr.error('{{trans('admin.lanuage_length_text')}}');
                return false;
            }

            window.location.href = "{{{url('/admin/languageSettings/edit')}}}?language="+new_language+"&language_file=app.php";

        });



        $(".edit-btn").on('click', function(){
            var language = $("#edit-lang").val();
            
            if (language == -1 || language == "") {
                toastr.warning('Select language');
                return false;
            }


            if (language_file == -1 || language_file == "") {
                toastr.warning('Select language file');
                return false;
            }

            window.location.href = "{{{url('/admin/languageSettings/edit')}}}?language="+language+"&language_file="+language_file;
        });

        language_file = '';
        $(".lang-file-class").on('change', function(){
            language_file = $(this).val();
        });
        
        language = '';
        $("#edit-lang").on('change', function(){
            var edit_lang = $(this).val();
            language = edit_lang;
            $(".lang-file-class").hide();
            $("#colapse-lang-files-"+edit_lang).trigger('change');
            $("#colapse-lang-files-"+edit_lang).show();
        });



    // $('.save-btn').click(function() {

    //     var form_id = $(this).data('form-id');
    //     var edit_lang = $(this).data('edit-lang');


    //     var data = $(form_id).serializeArray();
    //     data.push({_token:"{{{csrf_token()}}}" });

    //     $.post("{{{url('/admin/languageSettings')}}}/"+edit_lang, data, function(response){

    //         if(response.status == 'success'){
    //             toastr.success(edit_lang +' {{{trans_choice('admin.language_msg',0)}}}');
    //             $('.same').hide();
    //             $('#edit-select').val(-1);

    //         } else if(response.status == 'error') {
    //             toastr.error(response.message);                
    //         }

    //     });

    // });


   });
   
   
  

   
   
   $('#default-lang-set-btn').click(function(){
       
       data = {};
       data['defaultLang'] = $('#default-lang').val();
       data['_token'] = "{{{csrf_token()}}}";
   
       $.post("{{{url('/admin/languageSettings/default/save')}}}", data, function(response){
   
           if(response.status == 'error') {
   
               toastr.error(response.message);
   
           } else if (response.status == 'success') {
   
               toastr.success(response.message);
               setInterval(function(){
                window.location.reload();
               }, 1000);
           }
   
       });
   
   });
   
   
</script>

@endsection