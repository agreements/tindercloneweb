<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Repositories\SpotlightRepository;
use App\Components\Plugin;
use Auth;
use Hash;
use Mail;
use DB;
use stdClass;
use Validator;

class SpotlightController extends Controller
{
    protected $spotlightRepo;
    
    public function __construct(SpotlightRepository $spotlightRepo)
    {
        $this->spotlightRepo = $spotlightRepo;
    }

    public function addToSpotlight (Request $request) {

        try {

            if ($this->spotlightRepo->addToSpotlight(Auth::user()->id)) {

                Plugin::fire('spotlight_pay');                
                return response()->json([
                
                    'status'  => 'success', 
                    'id'      => $request->id, 
                    'message' => trans('app.add_success_spotlight')

                ]);

            } else {

                return response()->json([

                    'status'  => 'error', 
                    'id'      => Auth::user()->id, 
                    'message' => trans('app.low_credits')

                ]);
            }

        } catch (\Exception $e) {

            return response()->json([

                'status'  => 'error', 
                'message' => trans('app.fail_spotlight')
                
            ]);
        }
     
    }

}
