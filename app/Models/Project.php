<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

class Project extends Model implements HasMedia
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use InteractsWithMedia;
    use HasFactory;

    public $table = 'projects';

    protected $appends = [
        'logo',
        'banner',
    ];

    protected $dates = [
        'startdate',
        'enddate',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'shortname',
        'slug',
        'user_id',
        'description',
        'shortdescription',
        'featured',
        'training',
        'startdate',
        'enddate',
        'contact',
        'contactdetails',
        'cost',
        'funding',
        'uri',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function projectResults()
    {
        return $this->hasMany(Result::class, 'project_id', 'id');
    }

    public function projectsAnswers()
    {
        return $this->belongsToMany(Answer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getLogoAttribute()
    {
        $file = $this->getMedia('logo')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getBannerAttribute()
    {
        $file = $this->getMedia('banner')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getStartdateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setStartdateAttribute($value)
    {
        $this->attributes['startdate'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getEnddateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEnddateAttribute($value)
    {
        $this->attributes['enddate'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function organisers()
    {
        return $this->belongsToMany(Country::class, 'country_project');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'project_topic');
    }

}
