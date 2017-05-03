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
        <h1 class="content-header-head">{{{trans_choice('admin.edit', 0)}}} {{{trans_choice('admin.language', 1)}}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col user-section-first-col">
            <div class="row">
                
                <div class="col-md-10 add-creditpackage-col set-default-language-div">
                   
                    <p class="add-credit-package-text">{{trans('admin.add_new_word_title')}}</p>
                    <div class="form-group ">
                        <label class="package-label">{{trans('admin.new_keyword')}}</label>
                        <input type="text" id="new_keyword" placeholder="{{trans('admin.new_keyword_placeholder')}}" class="form-control  input-border-custom">
                    </div>
                    <div class="form-group">
                        <label class="package-label">{{trans('admin.new_keyword_text')}}</label>
                        <input type="text" id="new_keyword_text" placeholder="{{trans('admin.new_keyword_text_placeholder')}}" class="form-control  input-border-custom">
                    </div>
                    <button type="button" class="btn btn-info btn-addpackage btn-custom" id ="add_new_word">
                    {{{trans_choice('admin.save', 1)}}}</button>
                </div>


                <div class="col-md-10 add-creditpackage-col set-default-language-div">
                    <form action = "" method = "POST" id = "lang-edit-form">
                        {!! csrf_field() !!}
                        <!-- <div class="col-md-10 add-creditpackage-col set-default-language-div"> -->
                        <p class="add-credit-package-text">{{{trans_choice('admin.edit', 0)}}} {{{str_replace('.php', '', $language_file)}}} {{{trans('admin.for')}}} {{{$language}}} <button type="button" class="save-btn btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.save', 1)}}}</button></p>
                        <input type="hidden" name="language_edit_short_name_parameter" value = "{{{$language}}}">
                        <input type="hidden" name="language_file_edit_short_name_parameter" value = "{{{$language_file}}}">
                        <span id = "new_word_space"></span>
                        @if(count($editabe_terms) > 0)
                        @foreach($editabe_terms[$language] as $key => $term)
                        <div class="form-group">
                            <i class="fa fa-trash" style="color:red;cursor:pointer"></i>
                            <label class="package-label">{{{$term->mcode}}} => {{{$term->left_lang}}}</label>
                            <input type="text" value = "{{{$term->right_lang}}}" placeholder="" name = "{{{$term->mcode}}}" class="form-control  input-border-custom">
                        </div>
                        @endforeach
                        @endif
                        <button type="button" class="save-btn btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.save', 1)}}}</button>
                        <!--  </div> -->
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>
    $( document ).ready(function() {
        
        function saveLanguageKeywords()
        {
            var form_id = "#lang-edit-form";

            var data = $(form_id).serializeArray();   

            $.post("{{{url('/admin/languageSettings/edit/save')}}}  ", data, function(response){
        
                if(response.status == 'success'){
                    toastr.success('{{{$language_file}}} {{{trans_choice('admin.language_msg',0)}}}');

                } else if(response.status == 'error') {
                    toastr.error(response.message);                
                }

            });
        }


        $("#lang-edit-form").on("click", ".fa-trash", function(){

            if(confirm("{{trans('admin.delete_confirm_text')}}") == true) {
                $(this).parent().remove();
                saveLanguageKeywords();
            }

        });



        $("#add_new_word").on('click', function(){

            var new_keyword = $("#new_keyword").val();
            var new_keyword_text = $("#new_keyword_text").val();

            if(new_keyword == '' || new_keyword_text == '') {
                return false;
            }

            var new_form_group = $('<div class="form-group "></div>');
            var new_label = $('<label class="package-label">'+new_keyword+' => '+new_keyword_text+'</label>');
            var new_input = $('<input type="text" value = "'+new_keyword_text+'" name = "'+new_keyword+'" class="form-control  input-border-custom">');
            var new_delete_icon = $('<i class="fa fa-trash" style="color:red;cursor:pointer"></i>');
            new_form_group.append(new_delete_icon);
            new_form_group.append(new_label);
            new_form_group.append(new_input);

            console.log(new_form_group);

            $("#lang-edit-form").find('#new_word_space').prepend(new_form_group);


            $("#new_keyword").val('');
            $("#new_keyword_text").val('');

            saveLanguageKeywords();

        });




       
        $('.save-btn').click(function() {
            saveLanguageKeywords();
        });
    
    
    });
  
    
</script>
@endsection