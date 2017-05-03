<?php

namespace App\Components;
// This interface contains some abstarct functions which must be implemented by every Presenter Class

interface PresenterInterface
{
	//abstact methods
	public function view(); //to retrive the view location
	public function mutate($vars); // to add new variables or change existing ones passed to the view
	public function is_Active(); // returns false or true 
}