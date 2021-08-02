<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = array('id');
    public static $rules = array(
    'user_id' => 'required',
    'message_id' => 'required',
    'comment' => 'required|max:120',
    );
    public function user() {
        return $this->belongsTo('App\Models\user');
    }
    public function message() {
        return $this->belongsTo('App\Models\message');
    }
}