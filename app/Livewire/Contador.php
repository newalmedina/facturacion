<?php

namespace App\Livewire;

use Livewire\Component;

class Contador extends Component
{
    public $count = 0;

    public function incrementar()
    {
        $this->count++;
    }

    public function decrementar()
    {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.contador');
    }
}
