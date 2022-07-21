<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Question extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use HasFactory;

    public const TYPE_RADIO = [
        'checkboxes'   => 'Category (Multiple Answer)',
        'radiobuttons' => 'Category (Single Answer)',
        'likert'       => 'Likert',
    ];

    public $table = 'questions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order',
        'domain_id',
        'type',
        'title',
        'text',
        'help',
        'information',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();

        /* Question::creating(function ($model) {
            $model->order = Question::max('order') + 1;
        }); */
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function questionAnswers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id')->orderBy('order', 'asc');
    }

    public function questionBlocklists()
    {
        return $this->belongsToMany(Blocklist::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function recommendations()
    {
        return $this->belongsToMany(Recommendation::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
