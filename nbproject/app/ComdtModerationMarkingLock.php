<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ComdtModerationMarkingLock extends Model {

    protected $primaryKey = 'id';
    protected $table = 'comdt_moderation_marking_lock';
    public $timestamps = false;

    public static function boot() {
        parent::boot();

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }

}
