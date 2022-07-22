<?php

namespace Vanguard\Listeners\Project;

use Carbon\Carbon;
use Vanguard\Events\Project\Created;
use Vanguard\Repositories\Project\ProjectRepository;

class CreatedProject
{
    /**
     * @var UserRepository
     */
    private $project;
   
    public function __construct(ProjectRepository $project)
    {
        $this->project = $project;
    }

    /**
     * Handle the event.
     *
     * @param Created $event
     * @return void
     */
    public function handle()
    {
        return $this->project;
    }
}
