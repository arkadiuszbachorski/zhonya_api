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

    public function appendHasQueriedTagAttribute($tagId)
    {
        $this->attributes['has_queried_tag'] = $this->tags()->where('tags.id', $tagId)->exists();
    }

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
