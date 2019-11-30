<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Task extends Model
{

    //region Fields

    protected $guarded = [];

    protected $hidden = ['user_id', 'created_at', 'pivot'];

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

    public function scopeActive($query)
    {
        //todo: Find active elements
        return $query;
    }

    public function scopeTag($query, $tagId)
    {
        $query->whereHas('tags', function ($query) use ($tagId) {
           $query->where('id', $tagId);
        });
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

    //endregion

}
