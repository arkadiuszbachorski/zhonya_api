<?php

namespace App\Observers;

use App\Tag;

class TagObserver
{
    /**
     * Handle the tag "saving" event.
     *
     * @param  \App\Tag  $tag
     * @return void
     */
    public function saving(Tag $tag)
    {
        $tag->color = str_replace('#', '', $tag->color);
    }
}
