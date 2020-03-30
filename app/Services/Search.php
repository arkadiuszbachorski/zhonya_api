<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Str;

class Search
{
    protected $search, $tags, $tasks, $attempts;

    public $scoreLimit = 15;

    const PARAMS = [
        'active' => '-a=',
        'tag' => '-t=',
    ];

    public function search(string $search, User $user)
    {
        $this->search = $search;
        $this->tasks = $user->tasks();
        $this->attempts = $user->attempts();
        $this->tags = $user->tags();

        $this->determineSearchType();

        return $this->getData();
    }

    protected function hasPrefix(string $prefix)
    {
        $hasPrefix = Str::startsWith($this->search, $prefix);

        if ($hasPrefix) {
            $this->search = Str::after($this->search, $prefix);
        }

        return $hasPrefix;
    }

    protected function determineSearchType()
    {
        if ($this->hasPrefix(self::PARAMS['tag'])) {
            $this->searchTasksInTags();
        } elseif ($this->hasPrefix(self::PARAMS['active'])) {
            $this->searchActive();
        } else {
            $this->searchNormal();
        }
    }

    protected function searchNormal()
    {
        $this->tags->search($this->search);
        $this->tasks->search($this->search);
        $this->attempts->search($this->search);
    }

    protected function searchTasksInTags()
    {
        $this->tags->search($this->search);
        $this->tasks->whereHas('tags', function ($query) {
            $query->where('name', 'LIKE',"%$this->search%");
        });
        $this->attempts = [];
    }

    protected function searchActive()
    {
        $this->tasks->active()->search($this->search);
        $this->attempts->active()->search($this->search);
        $this->tags = [];
    }

    protected function getTags()
    {
        if ($this->tags === []) return;

        $this->tags = $this->tags->withCount('tasks')
            ->orderBy('tasks_count', 'DESC')
            ->limit($this->scoreLimit)
            ->get();
    }

    protected function getTasks()
    {
        if ($this->tasks === []) return;

        $this->tasks = $this->tasks->orderBy('updated_at', 'DESC')
            ->limit($this->scoreLimit)
            ->get();
    }

    protected function getAttempts()
    {
        if ($this->attempts === []) return;

        $this->attempts = $this->attempts
            ->with('task')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->each(function ($attempt) {
                $attempt->append('short_description', 'relative_time');
                $attempt->addHidden('laravel_through_key', 'description');
                $attempt->task->addHidden('description', 'updated_at');
            });
    }

    protected function getData()
    {
        $this->getTags();
        $this->getTasks();
        $this->getAttempts();

        return [
            'tags' => $this->tags,
            'tasks' => $this->tasks,
            'attempts' => $this->attempts,
        ];
    }
}
