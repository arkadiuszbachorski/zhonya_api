<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{

    //region Fields

    protected $guarded = [];

    protected $hidden = ['task_id', 'created_at', 'started_at', 'saved_relative_time'];

    protected $touches = ['task'];

    protected $dates = ['started_at'];
    //endregion

    //region Logic

/*    public function appendHidden($hidden)
    {
        $old = $this->getHidden();
        $new = gettype($hidden) === "array" ? array_merge($old, $hidden) : array_merge($old, [$hidden]);
        $this->setHidden($new);
    }
*/

    //endregion

    //region Mutators

    public function getActiveAttribute()
    {
        return $this->started_at !== null;
    }

    public function getRelativeTimeAttribute()
    {
        if (!$this->active) {
            return $this->saved_relative_time;
        }

        $diff = now()->diffInSeconds($this->started_at);

        return $this->saved_relative_time + $diff;
    }

    //endregion

    //region Scopes

    public function scopeSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('description','LIKE', "%$value%");
        });
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('started_at');
    }

    //endregion

    //region Relationships

    public function task()
    {
        return $this->belongsTo(Task::class);
    }


    //endregion

}
