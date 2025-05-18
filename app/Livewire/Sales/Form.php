<?php

namespace App\Livewire\Sales;

use App\Models\{Customer, Item, Order};
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Form extends Component
{
    use WithPagination;

    public ?Order $order = null;
    public string $searchProduct = '';
    public string $searchType = '';
    public int|string $perPage = 10;

    public array $inputValues = [];
    public array $selectedProducts = [];
    public array $getGeneralTotals = ["total" => 0, "taxes_amount" => 0];
    public array $manualProduct = [];

    public array $form = [
        'date' => '',
        'customer_id' => '',
    ];

    public function mount(?Order $order = null): void
    {
        $this->resetManualProduct();
        $this->order = $order;

        if ($order) {
            $this->form = $order->only(array_keys($this->form));
        }

        $this->form['date'] = $this->form['date'] ?: Carbon::now()->format("Y-m-d");

        foreach ($this->consultaItems->get() as $item) {
            $this->inputValues[$item->id] = $item->type === 'service' ? 1 : ($item->amount == 0 ? 0 : 1);
        }
    }

    public function updated($propertyName): void
    {
        if (str_starts_with($propertyName, 'manualProduct.')) {
            $this->calculateManualProduct();
        }
    }

    public function buscarProducto(): void
    {
        $this->resetPage();
    }

    public function resetManualProduct(): void
    {
        $this->manualProduct = [
            "product_name" => "",
            "price" => null,
            "quantity" => null,
            "taxes" => null,
            "taxes_amount" => null,
            "price_with_taxes" => null,
            "total" => null,
        ];
    }

    public function calculateManualProduct(): void
    {
        if (!$this->isManualProductComplete()) {
            $this->manualProduct['taxes_amount'] = $this->manualProduct['price_with_taxes'] = $this->manualProduct['total'] = null;
            return;
        }

        $quantity = $this->manualProduct['quantity'];
        $price = $this->manualProduct['price'];
        $tax = $this->manualProduct['taxes'];

        $subtotal = $quantity * $price;
        $taxes = round(($subtotal * $tax) / 100, 2);

        $this->manualProduct['taxes_amount'] = $taxes;
        $this->manualProduct['price_with_taxes'] = $subtotal + $taxes;
        $this->manualProduct['total'] = $subtotal + $taxes;
    }

    protected function isManualProductComplete(): bool
    {
        return $this->manualProduct['quantity'] && $this->manualProduct['price'] && $this->manualProduct['taxes'];
    }

    public function validateManualProduct(): void
    {
        $this->validate(
            [
                'manualProduct.product_name' => ['required', 'string'],
                'manualProduct.price' => ['required', 'numeric', 'min:0'],
                'manualProduct.quantity' => ['required', 'integer', 'min:1'],
                'manualProduct.taxes' => ['required', 'numeric', 'min:0'],
            ],
            [
                'manualProduct.product_name.required' => 'El campo es obligatorio.',
                'manualProduct.product_name.string' => 'El campo debe ser un texto válido.',
                'manualProduct.price.required' => 'El campo es obligatorio.',
                'manualProduct.price.numeric' => 'El campo debe ser un número válido.',
                'manualProduct.price.min' => 'El valor no puede ser menor que 0.',
                'manualProduct.quantity.required' => 'El campo es obligatorio.',
                'manualProduct.quantity.integer' => 'El campo debe ser un número entero.',
                'manualProduct.quantity.min' => 'El valor debe ser al menos 1.',
                'manualProduct.taxes.required' => 'El campo es obligatorio.',
                'manualProduct.taxes.numeric' => 'El campo debe ser un número válido.',
                'manualProduct.taxes.min' => 'El valor no puede ser menor que 0.',
            ]
        );
    }


    public function saveManualProduct(): void
    {
        $this->validateManualProduct();

        $id = Str::uuid()->toString();

        $this->selectedProducts[$id] = [
            "aleatory_id" => $id,
            "detail_id" => null,
            "item_id" => null,
            "item_name" => $this->manualProduct["product_name"],
            "item_type" => "manual_product",
            "price_unit" => $this->manualProduct["price"],
            "price" => $this->manualProduct["price"],
            "taxes" => $this->manualProduct["taxes"],
            "taxes_amount" => $this->manualProduct["taxes_amount"],
            "quantity" => $this->manualProduct["quantity"],
            "price_with_taxes" => $this->manualProduct["price_with_taxes"],
            "total" => $this->manualProduct["total"],
        ];

        $this->notify('Producto añadido correctamente', 'Producto añadido', 'success');
        $this->dispatch('close-modal', id: 'manual-product-modal');
        $this->resetManualProduct();
    }

    public function closeModalManual(): void
    {
        $this->dispatch('close-modal', id: 'manual-product-modal');
    }

    public function validateForm(): void
    {
        $this->validate(
            [
                'form.customer_id' => ['required'],
                'form.date' => ['required'],
            ],
            [
                'form.customer_id.required' => 'Este campo es obligatorio.',
                'form.date.required' => 'Este campo es obligatorio.',
            ]
        );
    }

    public function saveForm(int $action = 0): void
    {
        $this->validateForm();

        if (empty($this->selectedProducts)) {
            $this->notify('Debes seleccionar al menos 1 artículo', 'Error al guardar', 'danger');
            return;
        }

        // Guardar lógica del pedido pendiente

        $this->notify(
            $action ? 'Venta facturada correctamente' : 'Venta guardada correctamente',
            $action ? 'Facturada' : 'Guardada',
            'success'
        );
    }

    public function selectItem($itemId): void
    {
        $item = Item::find($itemId);

        if (!$item) return;

        if (isset($this->selectedProducts[$item->id])) {
            $this->notify('El producto ya ha sido añadido. Modifíquelo desde productos seleccionados.', 'Producto ya añadido', 'warning');
            return;
        }

        $quantity = $this->getQuantityItem($item->id);

        if ($quantity <= 0) {
            $this->notify('Debes seleccionar una cantidad válida mayor a 0.', 'Cantidad inválida', 'danger');
            return;
        }

        $subtotal = $quantity * $item->price;
        $taxes = round(($subtotal * $item->taxes) / 100, 2);

        $this->selectedProducts[$item->id] = [
            "aleatory_id" => null,
            "detail_id" => null,
            "item_id" => $item->id,
            "item_name" => $item->name,
            "item_type" => $item->type,
            "price_unit" => $item->price,
            "price" => $subtotal,
            "taxes" => $item->taxes,
            "taxes_amount" => $taxes,
            "price_with_taxes" => $subtotal + $taxes,
            "quantity" => $quantity,
            "total" => $subtotal + $taxes,
        ];

        $this->notify('Producto añadido correctamente', 'Producto añadido', 'success');
    }

    public function deleteItem($id): void
    {
        if (isset($this->selectedProducts[$id])) {
            unset($this->selectedProducts[$id]);
            $this->notify('Producto eliminado correctamente', 'Producto eliminado', 'success');
        }
    }

    private function getQuantityItem($id): float|int
    {
        return is_numeric($this->inputValues[$id] ?? 0) ? $this->inputValues[$id] + 0 : 0;
    }

    public function getConsultaItemsProperty()
    {
        return Item::active()
            ->when($this->searchProduct, fn($q) => $q->where('name', 'like', "%{$this->searchProduct}%"))
            ->when($this->searchType, fn($q) => $q->where('type', $this->searchType));
    }

    public function getGeneralTotal(): void
    {
        $this->getGeneralTotals = ['total' => 0, 'taxes_amount' => 0];

        foreach ($this->selectedProducts as $item) {
            $this->getGeneralTotals['total'] += $item['total'];
            $this->getGeneralTotals['taxes_amount'] += $item['taxes_amount'];
        }
    }

    private function notify(string $body, string $title, string $type): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->{$type}()
            ->duration(3000)
            ->send();
    }

    public function render()
    {
        $this->getGeneralTotal();

        return view('livewire.Sales.form', [
            'items' => $this->consultaItems->paginate($this->perPage),
            'customerList' => Customer::active()->get(),
        ]);
    }
}
