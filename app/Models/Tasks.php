<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'date_done',
        'attachment'
    ];

    use HasFactory;

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
