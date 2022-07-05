<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class AsignProject extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Asign'))
            ->route('asignproject.index')
            ->icon('fas fa-users-cog')
            ->active(['asignproject*', 'asignuser*'])->permissions(['asignproject.index']);
    }
}
