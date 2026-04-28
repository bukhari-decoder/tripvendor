<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    protected $guarded =['id'];

    public function packages()
    {
        return Package::whereJsonContains('guides', $this->code);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
