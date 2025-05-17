<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Item;
use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use Livewire\WithPagination;
use Filament\Notifications\Notification;

class Form extends Component
{

    use WithPagination;
    public ?Order $order = null;
    public  $searchProduct = null;
    public  $searchType = null;
    public int | string $perPage = 10;
    public $inputValues = [];
    public $selectedProducts = [];
    public $manualProduct = [];
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
        foreach ($this->consultaItems->get() as $item) {;
            if ($item->type == "service") {
                $this->inputValues[$item->id] =  1;
                continue;
            }
            $this->inputValues[$item->id] = $item->amount == 0 ? 0 : 1;
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
            })

            ->when($this->searchType, function ($query) {
                $query->where('type',   $this->searchType);
            });
    }

    public function selectItem($itemId)
    {
        $item = Item::find($itemId);

        if (isset($this->selectedProducts[$item->id])) {

            Notification::make()
                ->title('Producto ya añadido')
                ->body('El producto ya ha sido añadido modifiquelo desde productos seleccionados')
                ->warning()
                ->duration(3000)
                ->send();
        }
        $quantity = $this->getQuantityItem($item->id);

        if ($quantity <= 0) {
            Notification::make()
                ->title('Cantidad inválida')
                ->body('Debes seleccionar una cantidad válida mayor a 0.')
                ->danger()
                ->duration(3000)
                ->send();

            return;
        };

        if (!isset($this->selectedProducts[$item->id])) {
            $this->selectedProducts[$item->id] = [
                "detail_id" => null,
                "item_id" => $item->id,
                "item_name" => $item->name,
                "item_type" => $item->type,
                "price_unit" => $item->price,
                "price" => 0,
                "taxes" => $item->taxes,
                "taxesAmount" => 0,
                "price_with_taxes" => 0,
                "quantity" => 0,
                "total" => 0,
            ];
        }

        $taxesAmount = round((float)($quantity * $item->price * $item->taxes) / 100, 2);
        $price  = ($quantity * $item->price);
        $this->selectedProducts[$item->id]["price"] = $price;
        $this->selectedProducts[$item->id]["quantity"] = $quantity;
        $this->selectedProducts[$item->id]["total"] = $price + $taxesAmount;
        $this->selectedProducts[$item->id]["taxesAmount"] =  $taxesAmount;

        Notification::make()
            ->title('Producto añadido')
            ->body('Producto añadido correctamente')
            ->success()
            ->duration(3000)
            ->send();
        // Ahora tienes $itemId y $quantity
        // Lógica aquí...
    }

    public function deleteItem($id)
    {
        if (isset($this->selectedProducts[$id])) {

            unset($this->selectedProducts[$id]);
            Notification::make()
                ->title('Producto eliminado')
                ->body('Producto eliminado correctamente')
                ->success()
                ->duration(3000)
                ->send();
        }
    }

    private function getQuantityItem($id)
    {
        $raw = $this->inputValues[$id] ?? '0';

        // Conviértelo a número (int o float según corresponda)
        return is_numeric($raw) ? $raw + 0 : 0;
    }
    public function render()
    {
        return view('livewire.Sales.form', [
            'items' => $this->consultaItems->paginate($this->perPage),
            'customerList' => Customer::active()->get(),
        ]);
    }
}
