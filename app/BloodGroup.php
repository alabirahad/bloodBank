<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BloodGroup extends Model
{
	protected $primaryKey = 'id';
	protected $table = 'blood_group';
    public $timestamps = false;
}
