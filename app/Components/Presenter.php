<?php

namespace App\Components;

class Presenter {
	
	
	public static function view($viewName, $vars = array()){
		
		if ($viewName[0] == 'plugin') {
			
			$viewName[2] = ucfirst($viewName[2]);
	
			$presenterClass =	"App\Custom\Presenters\Plugin\\".$viewName[1]."\\".$viewName[2]."PagePresenter";
	
		} else {
				
			if(count($viewName) == 1){
				
				$viewName[0] = ucfirst($viewName[0]);
				$presenterClass = 	"App\Custom\Presenters\\".$viewName[0]."PagePresenter";
			} else {
				
				$viewName[1] = ucfirst($viewName[1]);
				$presenterClass = 	"App\Custom\Presenters\\".$viewName[0]."\\".$viewName[1]."PagePresenter";
			}
		}
		
		$res = array();
		
		if(class_exists($presenterClass) ){
			
			$pagePresenterObject = new $presenterClass();
				
			if($pagePresenterObject->is_Active()) {
				$viewName = $pagePresenterObject->view();
			
				$res["custom"] = true;
				
				$res["data"] = $pagePresenterObject->mutate($vars);
				
				$res["view"] = $viewName;
			}	else {
				$res["custom"] = false;
			}
				
			
				
		} else {
				
			$res["custom"] = false;
		}
			
		return $res;	
	}
	
}	