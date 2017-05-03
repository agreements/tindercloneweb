<?php

namespace App\Repositories\Admin;
use App\Models\SocialLogins;

class SocialLoginsRepository {

    public function __construct(SocialLogins $socialLogins) {
        $this->socialLogins = $socialLogins;
    }


    public function getAllSocialLogins() {
        return $this->socialLogins->all();
    }
    
	public function save_priority($arr) {
        
    	foreach($arr as $key => $value) {
            $set = $this->socialLogins->where('plugin_id', $key)->first();
            $set->priority = $value;
            $set->save(); 
        }
    }

}