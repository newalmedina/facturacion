<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Order;

class Form extends Component
{
    public ?Order $order = null;

    public $form = [
        'customer_name' => '',
        'total' => '',
        // Agrega aquí más campos si los necesitas
    ];

    public function mount(?Order $order = null)
    {
        $this->order = $order;

        if ($order) {
            $this->form = $order->only(array_keys($this->form));
        }
    }

    public function save()
    {
        /*$this->validate([
            'form.customer_name' => 'required|string',
            'form.total' => 'required|numeric',
        ]);*/

        if ($this->order) {
            $this->order->update($this->form);
            session()->flash('success', 'Venta actualizada.');
        } else {
            Order::create($this->form);
            session()->flash('success', 'Venta creada.');
        }

        return redirect()->to('admin/sales');
    }

    public function render()
    {
        return view('livewire.Sales.form');
    }
}
