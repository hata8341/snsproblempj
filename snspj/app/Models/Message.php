<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = array('id');
    public static $rules = array(
    'user_id' => 'required',
    'message' => 'required|max:120',
    );
    public function user() {
        return $this->belongsTo('App\Models\user');
    }

}