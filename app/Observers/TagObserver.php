<?php

namespace App\Observers;

use App\Tag;

class TagObserver
{
    public function saving(Tag $tag)
    {
        $tag->color = str_replace('#', '', $tag->color);
    }

    public function deleting(Tag $tag)
    {
        $tag->tasks()->detach();
    }
}
