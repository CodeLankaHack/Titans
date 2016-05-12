<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Log extends Eloquent{
    
    public $timestamps = false;
    protected $fillable = array('purse_id','lat','lan','time','event');    

}
?>