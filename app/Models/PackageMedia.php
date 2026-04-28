<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageMedia extends Model
{
    use HasFactory;

    protected $fillable =['id','driver','image','package_id'];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
