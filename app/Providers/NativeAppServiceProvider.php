<?php

namespace App\Providers;

use App\Jobs\SyncFixture;
use Native\Laravel\Facades\ContextMenu;
use Native\Laravel\Facades\Dock;
use Native\Laravel\Facades\Window;
use Native\Laravel\Facades\GlobalShortcut;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Menu\Menu;

class NativeAppServiceProvider
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        SyncFixture::dispatchSync();
        MenuBar::create()
            ->icon(storage_path('menuBarIcon.png'));
    }
}
