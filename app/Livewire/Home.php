<?php

namespace App\Livewire;

use App\Models\Fixture;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home', [
            'fixtures' => Fixture::query()->orderBy('start_at')->get()
        ]);
    }
}
