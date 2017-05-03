<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Admin\ThemeManageRepository;
use App\Repositories\Admin\UtilityRepository;
use Hash;
use Validator;



class ThemeController extends Controller {

    protected $themeRepo;
    public function __construct (ThemeManageRepository $themeRepo) {
        $this->themeRepo = $themeRepo;
    }

    public function showThemes() { 

        $loadedThemes = $this->themeRepo->getLoadedThemes();
        $themes       = $this->themeRepo->getThemes();

        return view('admin.admin_theme', [
            'themes'            => $themes[0],
            'childThemes'       => $themes[1], 
            'loadedThemes'      => $loadedThemes[0], 
            'loadedChildThemes' => $loadedThemes[1]]
        );
    }

    public function renderScreenshot ($themename) {

        $image = $this->themeRepo->getScreenshot($themename);
        return response($image[0])->header('Content-Type', $image[1]);
    }


    public function activateTheme (Request $request) {

        UtilityRepository::clearCacheViews();

        if ($request->role == 'parent') {
            $this->themeRepo->activateParentTheme($request->id);
        } else {
            $this->themeRepo->activateChildTheme($request->id, $request->theme);
        }
            
        return redirect('/admin/themes');
       
    }



    public function deactivateChildTheme (Request $request) {

        UtilityRepository::clearCacheViews();
        $this->themeRepo->deactivateChildTheme();

        return redirect('/admin/themes');
    }



    public function installTheme (Request $request) {

        try
        {
            //creating theme directory in public folder with js, css, fonts, images
            $this->themeRepo->createThemeDir($request->theme);
            $this->themeRepo->copyAssets($request->theme);   

            $this->themeRepo->addTheme([

                'name'        => $request->theme,
                'author'      => $request->author,
                'description' => $request->description,
                'version'     => $request->version,
                'website'     => $request->website,
                'isactivated' => 'deactivated',
                'role'        => $request->role,

            ]);


        }
        catch(\Exception $e)
        {
            session(['installStatus' => $e->getMessage()]);
            
        }

        return redirect('/admin/themes');
       
    }



    public function setCreditsScreenshot (Request $request) {
        $spotlight_image = $request->spotlight;

        if ($spotlight_image) {
            $this->themeRepo->saveSpotlightImage($spotlight_image, $request->theme_name);
        } 


        $riseup_image = $request->riseup;
        if ($riseup_image) {
            $this->themeRepo->saveRiseupImage($riseup_image, $request->theme_name);
        } 
        
        return back();
    }



}