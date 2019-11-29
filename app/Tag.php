<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tag extends Model
{

    //region Fields

    protected $guarded = [];

    //endregion

    //region Logic


    //endregion

    //region Mutators



    //endregion

    //region Scopes

    public function scopeSearch($query, $value)
    {
        dump($value);
        return $query->where(function ($query) use ($value) {
            $query->where('name','LIKE', "%$value%")
                ->orWhere('description','LIKE', "%$value%")
                ->orWhere('color','LIKE', "%$value%");
        });
    }

    //endregion

    //region Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //endregion

}
