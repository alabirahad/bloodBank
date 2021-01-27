<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Blood extends Model
{
	protected $primaryKey = 'id';
	protected $table = 'blood';
    public $timestamps = false;
}
