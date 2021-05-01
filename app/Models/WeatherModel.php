<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class WeatherModel extends Model
{
    protected $table = 'weather_report';
}