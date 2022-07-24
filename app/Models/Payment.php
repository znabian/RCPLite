<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table="PaymentTbl";
    
    public function Tokens()
    {
        return $this->hasMany(PaymentToken::class,'PaymentId','Id');
    }
    
    public function Product()
    {
        return $this->belongsTo(Product::class,'AppId','Id');
    }
    public function User()
    {
        return $this->belongsTo(User::class,'UserId','Id');
    }
}
