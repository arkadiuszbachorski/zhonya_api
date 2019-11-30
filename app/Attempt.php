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

    public function appendHidden($hidden)
    {
        $old = $this->getHidden();
        $new = gettype($hidden) === "array" ? array_merge($old, $hidden) : array_merge($old, [$hidden]);
        $this->setHidden($new);
    }

    //endregion

    //region Mutators



    //endregion

    //region Scopes



    //endregion

    //region Relationships

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    //endregion

}
