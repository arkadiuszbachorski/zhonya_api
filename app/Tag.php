<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{

    //region Fields

    protected $guarded = [];

    protected $hidden = ['user_id', 'created_at', 'updated_at', 'pivot'];

    //endregion

    //region Logic

    public function appendHasQueriedTaskAttribute($taskId)
    {
        $this->attributes['has_queried_task'] = $this->tasks()->where('tasks.id', $taskId)->exists();
    }


    //endregion

    //region Mutators

    public function getShortDescriptionAttribute()
    {
        return Str::limit($this->description, 80);
    }

    //endregion

    //region Scopes

    public function scopeSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('name','LIKE', "%$value%")
                ->orWhere('description','LIKE', "%$value%");
        });
    }

    public function scopeWithoutTask($query, $taskId)
    {
        $query->whereDoesntHave('tasks', function ($query) use ($taskId) {
            $query->where('tasks.id', $taskId);
        });
    }

    //endregion

    //region Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    //endregion

}
