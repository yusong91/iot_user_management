<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Projects extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Projects'))
            ->route('project.index')
            ->icon('fas fa-parking')
            ->active(['project*', 'device*'])->permissions(['project.index']);
    }
}
