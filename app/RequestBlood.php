<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RequestBlood extends Model {

    protected $primaryKey = 'id';
    protected $table = 'request_blood';
    public $timestamps = false;
    
    

}
