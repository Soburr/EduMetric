<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolCalendar extends Model
{
    protected $fillable = ['term', 'session', 'days_opened'];
}