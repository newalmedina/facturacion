<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Item;
use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use Livewire\WithPagination;

class Form extends Component
{

    use WithPagination;
    public ?Order $order = null;
    public  $searchProduct = null;
    public int | string $perPage = 10;

    public $form = [
        'date' => '',
        'customer_id' => '',
        // Agrega aquí más campos si los necesitas
    ];

    public function mount(?Order $order = null)
    {
        $this->order = $order;

        if ($order) {
            $this->form = $order->only(array_keys($this->form));
        }
    }

    public function buscarProducto()
    {
        $this->resetPage();
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

    public function getConsultaItemsProperty()
    {
        return Item::active()
            ->when($this->searchProduct, function ($query) {
                $query->where('name', 'like', '%' . $this->searchProduct . '%');
            });
    }
    public function render()
    {
        return view('livewire.Sales.form', [
            'items' => $this->consultaItems->paginate($this->perPage),
            'customerList' => Customer::active()->get(),
        ]);
    }
}
