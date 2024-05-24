<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $table="activity_log";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_name', 'description', 'properties', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'ipAdresse', 'userAgent'
    ];
}
