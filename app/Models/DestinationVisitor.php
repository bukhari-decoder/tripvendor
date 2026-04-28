<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationVisitor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
