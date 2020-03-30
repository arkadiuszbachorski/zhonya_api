<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{

    //region Fields

    protected $guarded = [];

    protected $hidden = ['user_id', 'created_at', 'pivot', 'time_max', 'time_min', 'time_avg', 'time_quartiles', 'time_sorted', 'time_quartiles', 'time_standard_deviation', 'time_sorted'];

    //endregion

    //region Logic

    public function appendHasQueriedTagAttribute($tagId)
    {
        $this->attributes['has_queried_tag'] = $this->tags()->where('tags.id', $tagId)->exists();
    }

    public function loadMissingCountableAttempts()
    {
        $this->loadMissing(['attempts' => function ($attemptsQuery) {
            $attemptsQuery->countable();
        }]);
    }

    //endregion

    //region Mutators

    public function getActiveAttribute()
    {
        $this->loadMissingCountableAttempts();
        return $this->attempts_count !== $this->attempts->count();
    }

    public function getTimeMaxAttribute()
    {
        $this->loadMissingCountableAttempts();
        return $this->attempts->max('saved_relative_time');
    }

    public function getTimeMinAttribute()
    {
        $this->loadMissingCountableAttempts();
        return $this->attempts->min('saved_relative_time');
    }

    public function getTimeAvgAttribute()
    {
        $this->loadMissingCountableAttempts();
        $avg = $this->attempts->avg('saved_relative_time');

        return $avg !== null ? round($avg) : null;
    }

    public function getTimeQuartilesAttribute()
    {
        $quartiles = $this->time_sorted->quartiles();

        foreach ($quartiles as $key => $item) {
            $quartiles[$key] = round($item);
        }

        return $quartiles;
    }

    public function getTimeStandardDeviationAttribute()
    {
        $standardDeviation = $this->time_sorted->standardDeviation();

        return $standardDeviation !== null ? round($standardDeviation) : null;
    }

    public function getTimeSortedAttribute()
    {
        $this->loadMissingCountableAttempts();
        return $this->attempts
            ->pluck('saved_relative_time')
            ->sort()
            ->values();
    }

    public function getTimeStatisticsAttribute()
    {
        $min = $this->time_min;
        $max = $this->time_max;
        $avg = $this->time_avg;

        return compact('min', 'max', 'avg');
    }

    public function getTimeStatisticsFullAttribute()
    {
        if ($this->time_sorted->count() === 0) {
            return null;
        }
        $min = $this->time_min;
        $max = $this->time_max;
        $avg = $this->time_avg;
        $range = $max - $min;
        $standardDeviation = $this->time_standard_deviation;
        $quartiles = $this->time_quartiles;
        $coefficientOfVariation = $standardDeviation / $avg;



        return compact('min', 'max', 'avg', 'range', 'standardDeviation', 'coefficientOfVariation', 'quartiles');
    }

    public function getTagsColorsAttribute()
    {
        return $this->tags->pluck('color');
    }

    public function getShortDescriptionAttribute()
    {
        return Str::limit($this->description, 60);
    }


    //endregion

    //region Scopes

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name','LIKE', "%$search%")
                ->orWhere('description','LIKE', "%$search%");
        });
    }

    public function scopeActive($query)
    {
        return $query->whereHas('attempts', function ($query) {
           $query->active();
        });
    }

    public function scopeWithTag($query, $tagId)
    {
        $query->whereHas('tags', function ($query) use ($tagId) {
           $query->where('tags.id', $tagId);
        });
    }

    public function scopeWithCountableAttempts($query)
    {
        return $query->with(['attempts' => function ($attemptsQuery) {
            $attemptsQuery->countable();
        }]);
    }

    public function scopeWithoutTag($query, $tagId)
    {
        $query->whereDoesntHave('tags', function ($query) use ($tagId) {
            $query->where('tags.id', $tagId);
        });
    }

    //endregion

    //region Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    //endregion

}
