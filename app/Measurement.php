<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{

    //region Fields

    protected $guarded = ['created_at', 'updated_at'];

    //endregion

    //region Logic



    //endregion

    //region Mutators



    //endregion

    //region Scopes



    //endregion

    //region Relationships

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    //endregion

}
