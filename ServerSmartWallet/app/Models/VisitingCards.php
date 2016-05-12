<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class VisitingCard extends Eloquent{
    
    protected $table = 'visiting_cards';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purse_id','firstname','lastname','designation','tel','mobile','address','email']; 

}
?>