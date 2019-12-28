<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{

    //region Fields

    protected $guarded = [];

    protected $hidden = ['task_id', 'created_at'];

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

    public function scopeSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('description','LIKE', "%$value%");
        });
    }

    public function scopeActive($query)
    {
        /*
         * TODO: Implement active checking
         * */
        return $query;
    }

    //endregion

    //region Scopes



    //endregion

    //region Relationships

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }

    //endregion

}
