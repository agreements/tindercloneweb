<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Repositories\LandingPageRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;

class LandingPagesController extends Controller {

    public function showSetting () {

        $landing_pages       = LandingPageRepository::getLandingPages();
        $supported_languages = (new LanguageRepository)->getSupportedLanguages(); 

        return Plugin::view('LandingPagesPlugin/admin_landing_pages_setting', [
            'landing_pages'                 => $landing_pages,
            'supported_languages'           => $supported_languages,
            'custom_landing_page'           => UtilityRepository::get_setting('custom_landing_page'),
            'landing_page_video_mode'       => (UtilityRepository::get_setting('landing_page_video_mode') == "true") ? 'true' : 'false',
            'landing_page_video_poster_url' => UtilityRepository::get_setting("landing_page_video_poster_url"),
            'landing_page_video_url'        => UtilityRepository::get_setting("landing_page_video_url")
        ]);

    }


    public function saveSetting (Request $request) {

        try {

            $landing_page = $request->landing_page;
            UtilityRepository::set_setting('custom_landing_page', $landing_page);

            return response()->json(['status' => 'success', 'message' => trans('LandingPagesPluginAdmin.save_success')]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => trans('LandingPagesPluginAdmin.save_error')]);
        }
            
    }



    public function editLandingPage (Request $req) {
 
        $landing_page  = $req->input('landing-page');
        $edit_language = $req->input('language');

        $instance = LandingPageRepository::landingPageInstance($landing_page);
        $language_file   = $instance->languageFile();
        $lang_array = LandingPageRepository::languageFileArray($edit_language, $language_file);

        $contentSections = [];
        foreach ($instance->contentSections() as $section) {
            $contentSections[$section] = isset($lang_array[$section]) ? $lang_array[$section] : "";
        }


        $imageSections = $instance->imageSections();
        
        $image_array_file = base_path("storage/LandingPages/{$language_file}_images.stub");
        if (file_exists($image_array_file)) {
            $serialize_array = unserialize(file_get_contents($image_array_file));

            foreach ($imageSections as $key => $arr) {
                $imageSections[$key] = isset($serialize_array[$key]) ? $serialize_array[$key] : $imageSections[$key];
            }
        }



        $linkSections = $instance->linkSections();
        
        $link_array_file = base_path("storage/LandingPages/{$language_file}_links.stub");
        if (file_exists($link_array_file)) {
            $serialize_array = unserialize(file_get_contents($link_array_file));

            foreach ($linkSections as $key => $arr) {
                $linkSections[$key] = isset($serialize_array[$key]) ? $serialize_array[$key] : $linkSections[$key];
            }
        }
        

        return Plugin::view('LandingPagesPlugin/edit_landing_page', [
            "landing_page"    => $landing_page,
            "edit_language"   => $edit_language,
            "language_file"   => $language_file,
            "contentSections" => $contentSections,
            "imageSections"   => $imageSections,
            "linkSections"    => $linkSections,
        ]);
    }


    public function contentSectionSave (Request $req) {
        
        $contents = $req->contents;
        unset($contents[0]);
            
        $edit_language = $req->edit_language;
        $landing_page  = $req->landing_page;
        $language_file = $req->language_file;

        if (LandingPageRepository::saveContentSections($contents, $edit_language, $language_file)) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error']);
    }


    public function imageSectionSave (Request $req) {
        $images = $req->images;
        unset($images[0]);

        $edit_language = $req->edit_language;
        $landing_page  = $req->landing_page;
        $language_file = $req->language_file;

        if (LandingPageRepository::saveImageSections($images, $edit_language, $language_file)) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error']);
    }


    public function imageSectionUploadImage (Request $req) {

        $file = $req->image;
        if (UtilityRepository::validImage($file, $ext)) {
            $fileName = UtilityRepository::generate_image_filename("landing_", $ext); 
            $file->move(public_path()."/plugins/LandingPagesPlugin", $fileName);
            return response()->json(['status' => "success", 'url' => url('plugins/LandingPagesPlugin/'.$fileName)]);
        }

        return response()->json(['status' => "error"]);
    }


    public function linkSectionSave (Request $req) {
        $links = $req->links;
        unset($links[0]);

        $edit_language = $req->edit_language;
        $landing_page  = $req->landing_page;
        $language_file = $req->language_file;

        if (LandingPageRepository::saveLinkSections($links, $edit_language, $language_file)) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error']);
    }



    public function saveVideoMode (Request $req) {
        $landing_page_video_mode = $req->landing_page_video_mode == 'true' ? 'true' : 'false';
        UtilityRepository::set_setting('landing_page_video_mode', $landing_page_video_mode);
        return response()->json(['status' => "success"]);
    }


    public function saveVideoSettings (Request $req) {
        $landing_page_video_url = $req->landing_page_video_url;
        $landing_page_video_poster_url = $req->landing_page_video_poster_url;

        UtilityRepository::set_setting("landing_page_video_poster_url", $landing_page_video_poster_url);
        UtilityRepository::set_setting("landing_page_video_url", $landing_page_video_url);

        return response()->json(['status' => "success"]);
    }

    public function uploadPosterImage (Request $req) {
        try {

            $file = $req->poster_image;
            if (UtilityRepository::validImage($file, $ext)) {
                $fileName = UtilityRepository::generate_image_filename("landing_video_poster", $ext); 
                $file->move(public_path()."/plugins/LandingPagesPlugin", $fileName);
                return response()->json(['status' => "success", 'url' => url('plugins/LandingPagesPlugin/'.$fileName)]);
            }


        } catch (\Exception $e){
            return response()->json(['status' => "error"]);
        }
 
        
    }


    public function uploadVideo (Request $req) {
        try {

            $file = $req->video;
            if (LandingPageRepository::validVideo($file, $ext)) {
                $fileName = LandingPageRepository::generate_video_filename("landing_video", $ext); 
                $file->move(public_path()."/plugins/LandingPagesPlugin", $fileName);
                return response()->json(['status' => "success", 'url' => url('plugins/LandingPagesPlugin/'.$fileName)]);
            }
        } catch (\Exception $e){
            return response()->json(['status' => "error"]);
        }
    }
    


}