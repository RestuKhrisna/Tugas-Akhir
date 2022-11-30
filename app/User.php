<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public static function getData($id=null){
        if($id==null){
            return self::all();
        }
        else{
            return self::find($id);
        }
    }
    public static function updateData($data){
        self::where('id',$data['id'])->update($data);
    }
    public static function hapusData($id){
        self::where('id',$id)->delete();
    }
}
