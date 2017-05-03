<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperpowerPackagesGateway extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'superpowerPackagesGateways';

    public $timestamps = true;
    
    public function gateway()
    {
        return $this->belongsTo('App\Models\PaymentGateway','gateway_id');
    }

	public function package()
    {
        return $this->belongsTo('App\Models\SuperPowerPackage','package_id');
    }

}