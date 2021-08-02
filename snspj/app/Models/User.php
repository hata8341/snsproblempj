<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $guarded = array('id');

    public static $rules = array(
        'name' => 'required|max:20',
        'email' => 'required|',
        'password' => 'required|min:6',
    );
    public function messages(){
        return $this->hasMany('App\Models\message');
    }
}