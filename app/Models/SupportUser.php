<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportUser extends Model
{
    use HasFactory;
    protected $table="SupportUserTbl";
    public function User()
    {
        return $this->belongsTo(User::class,'UserId','Id');
    }
    public function Support()
    {
        return $this->belongsTo(User::class,'SupportId','Id');
    }
}
