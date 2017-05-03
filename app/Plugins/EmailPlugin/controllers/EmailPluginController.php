<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Components\Plugin;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\EmailPluginRepository;
use App\Repositories\Admin\EmailSettingRepository;

class EmailPluginController extends Controller {
    
    protected $emailRepo;
    protected $emailPluginRepo;

    public function __construct(EmailSettingRepository $emailRepo, EmailPluginRepository $emailPluginRepo) {

        $this->emailRepo = $emailRepo;
        $this->emailPluginRepo = $emailPluginRepo;
    }

    public function send_mail (Request $request) {
        Plugin::Fire ($request->action , $request->arr);
    }

    public function saveFooterValue(Request $request)
    {
        $this->emailPluginRepo->saveFooterValue($request->all());
        return back();
    }

    public function uploadEmailImage (Request $request) {
        
        $image = $request->upload;

        if (UtilityRepository::validImage($image, $ext)) {

            $fileName = UtilityRepository::generate_image_filename('email_', $ext);
            
            $path = public_path() . '/uploads/email'; 
            
            if (!file_exists($path)) { 
                mkdir($path); 
            }
            
            $image->move($path, $fileName);

            $url     = url("uploads/email/{$fileName}");
            $message = '';
            echo <<<CKEDITOR_RESPONSE
                <script type='text/javascript'> 
                    window.parent.CKEDITOR.tools.callFunction($request->CKEditorFuncNum, '{$url}', '{$message}');
                     </script>";
CKEDITOR_RESPONSE;
        }

    }


    public function showEmailContents () {

        $footer_text = UtilityRepository::get_setting('footer_text');
        return view('admin.admin_mail_content', array('footer_text' => $footer_text));
    }




    protected function validateEmailSettingsData ($request_data) {
        return Validator::make($request_data, [

           'mailbodykey'    => 'required',
           'mailsubjectkey' => 'required',
           'title'          => 'required',

        ]);
    }


    public function saveEmailSettings (Request $request) {

        $validator = $this->validateEmailSettingsData($request->all());
        if ($validator->fails()) {
            return response()->json(['status' => 'error']);
        }

        try {

            if ($request->content_type == 0) {
                $body = $request->mailbodykey; 
                $sub  = $request->mailsubjectkey;;
                
                $body = $request->text_field;
                $sub  = $request->$sub;
                $this->emailRepo->saveEmailSettings($sub, $body,$request->content_type,$request->email_type);
            }
            else
            {   
                $sub  = $request->mailsubjectkey;;
                $sub  = $request->$sub;
                $body = $request->template;
                $this->emailRepo->saveEmailSettings($sub, $body,$request->content_type,$request->email_type);   
            }

            return response()->json(['status' => 'success']);

        }
        catch(\Exception $e)
        {
            return response()->json(['status' => $e->getMessage()]);
        }

    }







}