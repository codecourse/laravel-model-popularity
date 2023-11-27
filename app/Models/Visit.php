<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'data'
    ];

    protected $casts = [
        'data' => 'json'
    ];

    public function visitable()
    {
        return $this->morphTo();
    }
}
