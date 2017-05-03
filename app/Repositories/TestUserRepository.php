<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;



class TestUserRepository extends BaseRepository {

	public function __construct(){
		
		$this->model = new User;
	}

}
