<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PaymentGateway extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateways';

    
    public $timestamps = true;

}