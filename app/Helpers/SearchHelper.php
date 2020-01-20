<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Str;

class SearchHelper
{
    private $search, $tags, $tasks, $attempts;

    const SCORE_LIMIT = 15;

    public function __construct(string $search, User $user)
    {
        $this->search = $search;
        $this->tags = $user->tags();
        $this->tasks = $user->tasks();
        $this->attempts = $user->attempts();

        $this->determineSearchType();
    }

    protected function determineSearchType()
    {
        if (Str::startsWith($this->search, '#')) {
            $this->search = substr($this->search, 1);
            $this->searchTasksInTags();
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
    }

    protected function getTags()
    {
        $this->tags = $this->tags->withCount('tasks')
            ->orderBy('tasks_count', 'DESC')
            ->limit(self::SCORE_LIMIT)
            ->get();
    }

    protected function getTasks()
    {
        $this->tasks = $this->tasks->orderBy('updated_at', 'DESC')
            ->limit(self::SCORE_LIMIT)
            ->get();
    }

    protected function getAttempts()
    {
        $this->attempts = $this->attempts
            ->with('task')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->each(function ($attempt) {
                $attempt->append('short_description', 'relative_time');
                $attempt->addHidden('laravel_through_key', 'description', 'updated_at');
                $attempt->task->addHidden('description', 'updated_at');
            });
    }

    public function getData()
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
