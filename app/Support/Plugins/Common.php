<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Common extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Common'))
            ->route('common-codes.index')
            ->icon('fas fa-parking')
            ->active(['common*'])->permissions(['common.index']); //->permissions(['common.create'])
    }
} 