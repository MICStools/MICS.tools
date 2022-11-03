<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'countries';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'short_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function organisersProjects()
    {
        return $this->belongsToMany(Project::class, 'country_project');
    }

    public function participantsProjects()
    {
        return $this->belongsToMany(Project::class, 'country_project_participant');
    }

    public function observersProjects()
    {
        return $this->belongsToMany(Project::class, 'country_project_observer');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
