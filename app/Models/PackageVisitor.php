<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageVisitor extends Model
{
    use HasFactory;
    protected $fillable = ['id','device','os','browser_info','bouncing_time','package_id','ip_address'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
