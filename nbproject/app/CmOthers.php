<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CmOthers extends Model {

    protected $primaryKey = 'id';
    protected $table = 'cm_others';
    public $timestamps = false;

//    public static function boot() {
//        parent::boot();
//        static::creating(function($post) {
//            $post->created_by = Auth::user()->id;
//            $post->updated_by = Auth::user()->id;
//        });
//
//        static::updating(function($post) {
//            $post->updated_by = Auth::user()->id;
//        });
//    }
//    public function govtJobExperience() {
//        return $this->hasMany('App\GovtJobExperience')->where('applicable_status', '=', '1');
//    }
//    public function politicalActivity() {
//        return $this->hasMany('App\PoliticalActivity');
//    }
//    
//    public function SpecialSkill(){
//        return $this->hasMany('App\SpecialSkill', 'recruit_id', 'id');
//    }

}
