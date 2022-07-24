<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentToken extends Model
{
    use HasFactory;
    protected $table="PaymentTokenTbl";
    public function Payment()
    {
       return $this->belongsTo(Payment::class,'PaymentId','Id');
    }
}
