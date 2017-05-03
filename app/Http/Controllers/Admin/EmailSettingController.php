<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Components\Theme;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\Admin\EmailSettingRepository;
use Hash;
use Validator;
use Mail;


class EmailSettingController extends Controller {

    protected $emailRepo;
    public function __construct (EmailSettingRepository $emailRepo) {

        $this->emailRepo = $emailRepo;
    }

    public function showEmailSettings () {
 
        $smtp     = $this->emailRepo->getSMTPSettings();
        $mandrill = $this->emailRepo->getMANDRILLSettings();
        
        $driver   = $this->emailRepo->getMailDriver();

        return view('admin.email_settings', [
            'smtp'        => $smtp, 
            'mandrill'    => $mandrill, 
            'mail_driver' => $driver
        ]);
    }


     public function setMailDriver (Request $request) {

        try {

            switch ($request->driver) {
                
                case 'smtp' :  
                        if ( $this->emailRepo->setSMTPDriver()) {

                            return response()->json(['status' => 'success', 'message' => trans_choice('admin.set_mail_driver_msg', 0)]);
                        } else {
                            return response()->json(['status' => 'error', 'message' => trans_choice('admin.set_mail_driver_msg', 1)]);
                        }


                case 'mandrill' :   
                        if ($this->emailRepo->setMANDRILLDriver()) {

                            return response()->json(['status' => 'success', 'message' => trans_choice('admin.set_mail_driver_msg', 2)]);
                        } else {
                            return response()->json(['status' => 'error', 'message' => trans_choice('admin.set_mail_driver_msg', 3)]);
                        }

                default :   
                        return response()->json(['status' => 'error', 'message' => trans_choice('admin.set_mail_driver_msg', 4)]);
                        break;
            }



            

        } catch (\Exception $e) {

            // return response()->json(['status' => 'error', 'message' => trans_choice('admin.set_mail_driver_msg', 5)]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
            
    }



    public function addSMTPEmailSettings (Request $request) {

        $validator = $this->validateSMTPData($request->all());

        if($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => trans('admin.validation_failed')]);
        }


        $data = [

            'smtp_name'       => $request->name,
            'smtp_from'       => $request->from,
            'smtp_username'   => $request->username,
            'smtp_password'   => $request->password,
            'smtp_host'       => $request->host,
            'smtp_port'       => $request->port,
            'smtp_encryption' => $request->encryption,

        ];

        try {

            UtilityRepository::add_settings($data);
            $this->emailRepo->setSMTPDriver();
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.email_config_msg', 0)]);
        }
        catch(\Exception $e)
        {
           return response()->json(['status' => 'error', 'message' => trans_choice('admin.email_config_msg', 1)]);
        }
    }


    protected function validateSMTPData ($request_data) {

        return Validator::make($request_data, [

           'password'   => 'required|confirmed',   
           'port'       => 'required',
           'host'       => 'required',
           // 'encryption' => 'required',
           'name'       => 'required',
           'from'       => 'required|email',
           'username'   => 'required',

        ]);
    }


    public function addMANDRILLEmailSettings (Request $request) {

        $validator = $this->validateMANDRILLData($request->all());

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => trans('admin.validation_failed')]);
        }


        $data = [
        
            'mandrill_username' => $request->username,
            'mandrill_password' => $request->password,
            'mandrill_host'     => $request->host,
            'mandrill_port'     => $request->port,
        ];

        try {

            UtilityRepository::add_settings($data);
            $this->emailRepo->setMANDRILLDriver();
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.email_config_msg', 2)]);
        }
        catch(\Exception $e)
        {
           return response()->json(['status' => 'error', 'message' => trans_choice('admin.email_config_msg', 3)]);
        }
    }


    protected function validateMANDRILLData ($request_data) {

        return Validator::make($request_data, [

           'password' => 'required',
           'port'     => 'required',
           'host'     => 'required',
           'username' => 'required',

        ]);
    }
    




    public function testMailSend (Request $req) {

        $email_id = $req->email_id;
        
        try {

            Mail::raw('Test email check successfull', function ($message) use($email_id) {
                $message->to($email_id, 'Test Mail User')->subject("Test Mail");
            });

            return response()->json(["status" => "success"]);


        } catch (\Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
            

    }



}