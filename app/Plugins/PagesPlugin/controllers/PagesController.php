<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\PagesRepository;
use App\Models\Settings;
use App\Models\Themes;
use App\Models\Pages;
use Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App;
use Config;
use stdClass;
use File;

class PagesController extends Controller
{
   	
    protected $pagesRepo;
    
    public function __construct(PagesRepository $pagesRepo)
    {
        $this->pagesRepo = $pagesRepo;
    }

    // Route:: /admin/languageSettings
    public function show_settings()
    {
       
        $allPages = Pages::withTrashed()->get();

        return Plugin::view('PagesPlugin/settings', 
            ['pages' => $allPages]
        );
    }

    public function save_settings (Request $request) {

        try {

            $existed_title = $this->pagesRepo->getPageByTitle($request->title);
            $existed_route = $this->pagesRepo->getPageByRoute($request->route);
            
            if ($existed_title) {

                return response()->json([
                    'status' => 'warning', 
                    'message' => trans_choice('app.page_named',0).' '. $request->title. ' '.trans('app.already_exists')
                ]);

            }

            if ($existed_route) {

                return response()->json([
                    'status' => 'warning', 
                    'message' => trans_choice('app.page_named',1).' '. $request->route. ' '.trans('app.already_exists')
                ]);

            }

            $this->pagesRepo->save_settings($request->all());

            return response()->json([
                'status' => 'success', 
                'message' => trans_choice('app.page_named',0).' '. $request->title.' '.trans('app.create_success')
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error', 
                'message' => trans_choice('app.page_named',0).' '. $request->title. ' '.trans('app.create_failed')
            ]);

        }
            
    }


    public function updatePage (Request $request) {

        try {

            $boolean = $this->pagesRepo->updatePage($request->all());
            if($boolean == true)
                return response()->json(['status' => 'success', 'message' => $request->title.' '.trans('app.success_page_update')]);
            else
                return response()->json(['status' => 'error', 'message' => trans('app.failed_update_page').' '. $request->title]);

      } catch (\Exception $e) {

          return response()->json(['status' => 'error', 'message' => trans('app.failed_update_page').' '. $request->title]);
      }

    }


    public function deletePage (Request $request) {

         try {

            $this->pagesRepo->deletePage($request->all());
          
          return response()->json([

            'status' => 'success', 
            'message' => trans('app.page').' ' . $request->title.' '.trans('app.delete_success')]);


        } catch (\Exception $e) {

          return response()->json([

            'status' => 'error', 
            'message' => trans('app.failed_delete_page').' '.$request->title]);
        }

    }

    public function activatePage (Request $request) {

        try {

            $this->pagesRepo->activatePage($request->all());
          
          return response()->json([

            'status' => 'success', 
            'message' => trans('app.page').' ' . $request->page_title.' '.trans('app.activate_success') ]);


        } catch (\Exception $e) {

          return response()->json([

            'status' => 'error', 
            'message' => trans('app.failed_activate_page').' ' . $request->page_title]);
        }
        

    }


    public function deactivatePage (Request $request) {

        try {

            $this->pagesRepo->deactivatePage($request->all());
          
          return response()->json([

            'status' => 'success', 
            'message' => trans('app.page').' ' . $request->page_title.' '.trans('app.deactivate_success')]);


        } catch (\Exception $e) {

          return response()->json([

            'status' => 'error', 
            'message' => trans('app.failed_deactivate_page').' '.$request->page_title]);
        }

    }

    public function page($page)
    {
        $p = $this->pagesRepo->getPageByRoute($page);
        
        return Theme::view('plugin.PagesPlugin.page', array('body' => $p->body));
    }

    public function interests($slug)
    {
        $interest = $this->pagesRepo->getInterestBySlug($slug);
        $users_interest = $this->pagesRepo->getUsersBySlug($slug);
        
        return Theme::view('plugin.PagesPlugin.interests', array('interest' => $interest,'users_interest' => $users_interest));
    }

    public function peoplenearby($city_route)
    {
        $users_city = $this->pagesRepo->getUsersByCity($city_route);
        return Theme::view('plugin.PagesPlugin.peoplenearby', array('users' => $users_city[0], 'city' => $users_city[1]));   
    }

    public function filter($city_route, Request $request)
    {
        $users = $this->pagesRepo->getUsersByFilter($request->all());
        return Theme::view('plugin.PagesPlugin.peoplenearby', array('users' => $users, 'city' => $city_route));
    }

    //this method uploads images for  pages
    public function uploadPageImage (Request $request) {
        
        $image = $request->upload;

        if ($image) {

            switch ($image->getMimeType()) {
                case 'image/png':
                    $ext = '.png';
                    break;

                case 'image/jpg':
                case 'image/jpeg':
                    $ext = '.jpg';
                    break;
                
                default:
                    $ext = '';
                    break;
            }
           

            if ($ext != '') {

                $fileName = uniqid('page'). rand(10000000, 99999999) . $ext;
 
                $path = public_path() . '/uploads/pages'; 

                if (!file_exists($path)) { mkdir($path); }

                $image->move($path, $fileName);

                $url     = url("uploads/pages/{$fileName}");
                $message = '';
 
                echo "<script type='text/javascript'> 
                        window.parent.CKEDITOR.tools.callFunction($request->CKEditorFuncNum, '".$url."', '".$message."');
                     </script>";
            }

        }       

    }


}

