<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Task extends Model
{

    //region Fields

    protected $guarded = [];

    //endregion

    //region Logic


    //endregion

    //region Mutators



    //endregion

    //region Scopes

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name','LIKE', "%$search%")
                ->orWhere('description','LIKE', "%$search%");
        });
    }

    public function scopeActive($query, $active)
    {
        //todo: Find active elements
        return $query;
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

    //endregion

}
