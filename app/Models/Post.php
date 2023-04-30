<?php

namespace App\Models;

use App\Events\PostCreatedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'website_id',
    ];
    
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
