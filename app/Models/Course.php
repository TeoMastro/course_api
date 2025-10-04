<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\CourseStatus;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    public const DEFAULT_PAGINATION_SIZE = 15;

    protected $fillable = [
        'title',
        'description', 
        'status',
        'is_premium'
    ];

    protected $casts = [
        'status' => CourseStatus::class,
        'is_premium' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}