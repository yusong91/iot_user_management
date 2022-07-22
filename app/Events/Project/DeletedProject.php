<?php

namespace Vanguard\Events\Project;

use Vanguard\Model\Project;

class DeletedProject 
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getProject()
    {
        return $this->project;
    }
}