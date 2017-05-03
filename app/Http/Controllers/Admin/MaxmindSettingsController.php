<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MaxmindGEOIPRepository;

class MaxmindSettingsController extends Controller
{
   
    public function __construct(MaxmindGEOIPRepository $maxmindGEOIPRepo)
    {
        $this->maxmindGEOIPRepo = $maxmindGEOIPRepo;
    }

    public function showMaxmindSettings()
    {
        return view('admin.admin_maxmind_settings', [
            'maxmind_app_id'      => $this->maxmindGEOIPRepo->getAppID(),
            'maxmind_license_key' => $this->maxmindGEOIPRepo->getLicenseKey(),
            "maxmind_enabled"     => $this->maxmindGEOIPRepo->enabled()
        ]);
    }

    public function saveMaxMindSettings(Request $request)
    {
        $enable = $request->maxmind_enable == 'true' ? true : false;
        $maxmind = $this->maxmindGEOIPRepo
            ->setAppID($request->maxmind_app_id)
            ->setLicenseKey($request->maxmind_license_key)
            ->enable($enable)
            ->persist();

        return response()->json(["status" => "success"]);
    }

}