<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class AsignUser extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Asign User'))
            ->route('asignuser.index')
            ->icon('fas fa-home')
            ->active("asignuser*")->permissions(['asignproject.index']);
    }
}
