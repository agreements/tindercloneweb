<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\CreditRepository;
use App\Repositories\HomeRepository;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Components\Plugin;


class ImporterController extends Controller {

    protected $creditRepo;
    protected $homeRepo;

    public function __construct (CreditRepository $creditRepo, HomeRepository $homeRepo) {

        $this->creditRepo = $creditRepo;
        $this->homeRepo = $homeRepo;
    }


	public function showimport()
	{
		return Plugin::view('ProfileImporter/importer');
	}
	
	public function import(Request $request) {

		


	}

}