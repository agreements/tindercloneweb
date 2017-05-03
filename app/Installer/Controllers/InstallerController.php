<?php

namespace App\Installer\Controllers;

use App\Http\Controllers\Controller;
use App\Installer\Repositories\InstallerRepository;
use Hash;
use Illuminate\Http\Request;
use Storage;
use Validator;
use DB;



class InstallerController extends Controller
{

    public function __construct(InstallerRepository $installerRepo)
    {
        $this->installerRepo = $installerRepo;
    }
          

    public function showInstall()
    {        
        
        if(!isset($_COOKIE['installation'])) {
            $this->installerRepo->setCookie("installation", "start", time() + (10 * 365 * 24 * 60 * 60));
            return redirect('/installer');
        }

            
        switch ($_COOKIE['installation']) {

            case 'start': 
                $error = []; 
                $this->installerRepo->checkReadWritePermissions($error);
                $response = view('Installer.install', ['errors' => $error]);
                break;


            case 'step1' :  
                $response = view('Installer.install_step1');//database config
                break;



            case 'step2' :  
                $response = view('Installer.install_step2'); //admin and other installation
                break;


            default : 
                $this->installerRepo->setCookie(
                    "installation", 
                    "start", 
                    time() + (10 * 365 * 24 * 60 * 60)
                );
                $response = redirect('/installer');
        }
        
        return $response;
    }




    public function postInstall(Request $request)
    {
        ini_set('max_execution_time', 0);

        $response = '';
        switch ($request->installation) {
            
            case 'step1':
                $this->installerRepo->setCookie("installation", "step1", time() + (10 * 365 * 24 * 60 * 60));
                $response = redirect('/installer');
                break;
            

            case 'step2':
                $config = [
                    'host' => $request->host,
                    'database' => $request->database,
                    'username' => $request->username,
                    'password' => $request->password,
                ];
                $this->installerRepo
                    ->setLicenseKey($request->license_key)
                    ->checkLicenseKey($errors)
                    ->checkDB($config, $errors)
                    ->setupDatabase($errors);

                if(isset($errors['error_type'])) {
                    $this->installerRepo->setCookie("installation", "step1", time() + (10 * 365 * 24 * 60 * 60));
                    session(['status' => $errors['status']]);
                } else {
                    $this->installerRepo->setCookie("installation", "step2", time() + (10 * 365 * 24 * 60 * 60));
                }

                $response = redirect('/installer');
                break;


            case 'step3':
                $this->installerRepo
                    ->setServerIP($request->ip())
                    ->validateAdminAndWebsiteTitle($request->all(), $errors)
                    ->setDomain($request->domain)
                    ->startInstallation($errors)
                    ->finishInstalltion();

                if(isset($errors['error_type'])) {
                    $this->installerRepo->setCookie("installation", "step2", time() + (10 * 365 * 24 * 60 * 60)); 
                    $response = response()->json($errors);
                    break;
                } 

                $response = response()->json(['status' => 'completed', 'cron_error' => $this->installerRepo->cron_error]);
                
                break;


        }

        return $response;

  }


    
}
