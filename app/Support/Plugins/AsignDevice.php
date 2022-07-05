<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class AsignDevice extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Asign Device'))
            ->route('')
            ->icon('fas fa-home')
            ->active("asigndevice")->permissions(['asigndevice.index']);
    }
}
