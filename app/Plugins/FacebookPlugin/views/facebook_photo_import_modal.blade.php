<div id="facebook-photo-upload-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">{{{trans('app.choose_photos_to_upload')}}}
        <i class="fa fa-times close" data-dismiss="modal"></i></h2>
      </div>
      <div class="modal-body">
            <div class="body-top-div">
                <label>{{{trans('app.photos_of_me')}}} <span class="badge" id="fb-photos-counter"></span></label>
                <!-- <input type="checkbox" name="select-all"> Select all -->
            </div>
            <div class= "body-down-div">
                <div class="fb-photo-loader"></div>
                <!-- <div class="img-container" data-photo-id = "1212" data-checked="false">
                    <img src="http://thumbs.dreamstime.com/z/cute-kid-yellow-t-shirt-28403704.jpg">
                    <i class="fa fa-check-circle fb-photo-check"></i>
                    <div class="select-curtain">
                    </div>
                </div> -->

            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id = "fb-import-photo-submit">{{{trans('app.fb_photos_add_btn')}}}</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

var fb_import_photos = [];

$(".body-down-div").on('click', '.img-container', function(){

    var checked = $(this).attr('data-checked');

    if(checked == 'true') {

        var photo_id = $(this).data('photo-id');
        var photo_source = $(this).find('img').attr('src');
        fb_import_photos = fb_import_photos.filter(function(obj){
            return obj.photo_id !== photo_id;
        });
        $(this).find('.fb-photo-check').css('visibility', 'hidden');
        $(this).attr('data-checked', 'false');
        console.log(fb_import_photos);

    } else {

        var photo_id = $(this).data('photo-id');
        var photo_source = $(this).find('img').attr('src');
        fb_import_photos.push({photo_id:photo_id, photo_source:photo_source});  
        $(this).find('.fb-photo-check').css('visibility', 'visible');
        $(this).attr('data-checked', 'true');
        console.log(fb_import_photos);
    }

}); 


$("#fb-import-photo-submit").on('click', function(){

    if(fb_import_photos.length > 0) {
        $(".fb-photo-loader").show();
        var imported_photos = JSON.stringify(fb_import_photos);
        var data = {};
        data._token = "{{{csrf_token()}}}";
        data.imported_photos = imported_photos;
        data.fb_user_id = fb_user_id;
        
        $.post("{{{url('/plugin/facebook/save-imported-photos')}}}", data, function(res){
            if(res.status == 'success') {
                toastr.success('{{{trans('app.fb_photos_uploaded_success')}}}');
                window.location.reload();
            } else if(res.status == 'error') {
                toastr.error('{{{trans('app.fb_photos_uploaded_error')}}}');
            }
            $(".fb-photo-loader").hide();
            $("#facebook-photo-upload-modal").modal('hide');
        });

    } else {
        toastr.error('{{{trans('app.no_photos_selected')}}}');
        return false;
    }
});


</script>

<style type="text/css">

    .fb-photo-loader {
        background: url("@plugin_asset('FacebookPlugin/ring.svg')") no-repeat center;
        position: absolute;
        width: 100%;
        height: 100%;
        display: none;
        background-color: rgba(255, 255, 255, 0.78);
        left: 0px;
        top: 0px;
    }

    #facebook-photo-upload-modal{
        overflow: visible;
        top: -160px;
    }
    #facebook-photo-upload-modal > div > .modal-content {
        border-radius: 20px;
        text-align: left;
        color: black;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-header {
        background-color: #2B65F8;
        border-radius: 16px 16px 0px 0px;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-header > h2 {
        padding: 8px 60px 8px 11px;
        color: #fff;
        text-align: left;
        font-size: 1.429em;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-header > h2 > .close {
        position: absolute;
        right: 16px;
        color: white;
        opacity: 1;
        font-size: 17px;
        top: 30px;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-footer {
        text-align: center;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-footer > button {
        border-color: white;
        border-radius: 50px;
        padding: 12px 30px 12px 30px;
        font-size: 16px;
        background-color: rgba(43, 101, 248, 0.85);
        color: white;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-footer > button:focus {
        outline: 0;
        background-color:#2B65F8;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-footer > button:hover {
        outline: 0;
        background-color:#2B65F8;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body {
        padding-left: 26px;
    }
    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-top-div > label {
        font-size: 16px;
        opacity: 0.8;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-top-div > label >.badge {
        background-color: #2b65f8;
        font-size: 16px;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div {
        width: 100%;
        background-color: rgba(0, 0, 0, 0.1);
        height: 400px;
        overflow-y: scroll;
        display: block;
        padding: 5px;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div > .img-container {
        width: 128px;
        height: 130px;
        padding: 5px;
        background-color: rgba(43, 101, 248, 0.47);
        float: left;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div > .img-container >img {
        width: 100%;
        height: 100%;
        object-fit:cover;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div > .img-container > .select-curtain {
        width: 100%;
        height: 122px;
        background-color: black;
        opacity: 0;
        position: static;
        transition: 0.5s all;
        cursor: pointer;
        margin-top: -25px;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div > .img-container:hover > .select-curtain {
        opacity: 0.4;
    }

    #facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div > .img-container > i {
        font-size: 22px;
        background-color: white;
        color: #fefefe;
        background: transparent;
        position: static;
        display: block;
        margin-top: -118px;
        margin-left: 4px;
        display: block;
        visibility: hidden;
    }

</style>  
