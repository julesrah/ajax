<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// class Customer extends Model
// {
//     use HasFactory;

//     protected $table = "customers";

//     // protected $guarded = ["id"];

//     protected $primaryKey = "customer_id";

//     protected $fillable = ['title', 'fname', 'lname','addressline','town','zipcode','phone','creditlimit','level','user_id'];

//     public $timestamps = false;

// }


class Customer extends Model
{
    use HasFactory;
    public $table = 'customers';
    public $primaryKey = 'customer_id';
    public $timestamps = false;
    protected $guarded = ['customer_id'];
    

    protected $fillable = ['fname','lname',
        'title','addressline','town','zipcode',
        'phone','email','user_id'
    ];

     public function orders(){

        return $this->hasMany('App\Models\Order','customer_id');

    }
}

