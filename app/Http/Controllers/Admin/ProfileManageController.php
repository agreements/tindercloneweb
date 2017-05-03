<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Admin\ProfileManageRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\ProfileRepository;
use Hash;
use Validator;



class ProfileManageController extends Controller {

    protected $profileMngRepo;
    protected $profileRepo;
    public function __construct (ProfileManageRepository $profileMngRepo, ProfileRepository $profileRepo) {

        $this->profileMngRepo = $profileMngRepo;
        $this->profileRepo   = $profileRepo;
    }

    public function edit_field(Request $request)
    {
        try{

            $this->profileMngRepo->edit_field($request->all());    
            return response()->json(['status' => 'success']);    
        }
        catch (\Exception $e) {
            return response()->json(['status' => 'error']);
        }
        
    }

    public function show_profilefields()
    {
        $arr = array();
        $sections = $this->profileRepo->get_fieldsections();
        return view('admin.admin_profilefields', ['sections' => $sections]);
    }

    public function post_add_section(Request $request){
        if($request->new_section != null) {
            $this->profileMngRepo->post_add_section($request->new_section);
            session()->flash('status','success');
            session()->flash('message',trans('admin.section_added'));
            return back();
        } else {
            session()->flash('status','error');
            session()->flash('message',trans('admin.section_error_msg'));
            return back();
        }
    }

    public function post_add_field(Request $request){

        if($request->new_field != null) {
            $this->profileMngRepo->post_add_field($request->all());
            session()->flash('status','success');
            session()->flash('message', trans('admin.field_added'));
        }
        else {
            session()->flash('status','error');
            session()->flash('message',trans('admin.field_error_msg'));
        }
        return back();

    }

     public function post_add_field_option(Request $request){
        
        if ($request->optiontitle == null) {
            session()->flash('status','error');
            session()->flash('message',trans('admin.option_error_msg'));
            return back();
        }

        if($request->on_search_type == 'range' && preg_match("/^[0-9]*[.]?[0-9]+$/", $request->optiontitle)){
            
            $this->profileMngRepo->post_add_field_option($request->all());
            session()->flash('status','success');
            session()->flash('message',trans('admin.option_added')); 
            return back();
        
        } else if($request->on_search_type != 'range') {
            $this->profileMngRepo->post_add_field_option($request->all());
            session()->flash('status','success');
            session()->flash('message', trans('admin.option_added'));
            return back();
        }

        session()->flash('status','error');
        session()->flash('message',trans('admin.option_error_msg_only_integers'));
        return back();
        
    }


    public function post_delete_section(Request $request){

        $this->profileMngRepo->delete_section($request->section_id);

        session()->flash('status','success');
        session()->flash('message', trans('admin.section_deleted'));
        return back();

    }

    public function post_delete_field(Request $request){

        $this->profileMngRepo->delete_field($request->field_id);

        session()->flash('status','success');
        session()->flash('message', trans('admin.field_deleted'));
        return back();

    }

     public function post_delete_option(Request $request){

        $this->profileMngRepo->delete_option($request->option_id);

        session()->flash('status','success');
        session()->flash('message', trans('admin.option_deleted'));
        return back();
        
    }

}