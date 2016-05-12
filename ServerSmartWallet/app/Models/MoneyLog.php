<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class MoneyLog extends Eloquent{
    
    protected $table = 'money_logs';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purse_id','amount','time','lat','lan','description']; 

}
?>