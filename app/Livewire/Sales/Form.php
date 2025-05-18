<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Item;
use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class Form extends Component
{

    use WithPagination;
    public ?Order $order = null;
    public  $searchProduct = null;
    public  $searchType = null;
    public int | string $perPage = 10;
    public $inputValues = [];
    public $selectedProducts = [];
    public $manualProduct;
    public $getGeneralTotals = [
        "total" => 0,
    ];

    public $form = [
        'date' => '',
        'customer_id' => '',
        // Agrega aquí más campos si los necesitas
    ];

    public function mount(?Order $order = null)
    {
        $this->resertManualProduct();
        $this->order = $order;
        dd($this->order);
        if ($order) {
            $this->form = $order->only(array_keys($this->form));
        }

        if (empty($order->id)) {
            $this->form["date"] = Carbon::now()->format("Y-m-d");
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
    public function resertManualProduct()
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
    public function getGeneralTotal()
    {
        $this->getGeneralTotals = [
            "total" => 0,
            "taxes_amount" => 0,
        ];
        foreach ($this->selectedProducts as $value) {
            $this->getGeneralTotals["total"] += $value["total"];
            $this->getGeneralTotals["taxes_amount"] += $value["taxes_amount"];
        }
    }
    public function calculateManualProduct()
    {
        if (
            empty($this->manualProduct["quantity"]) ||
            empty($this->manualProduct["price"]) ||
            empty($this->manualProduct["taxes"])
        ) {
            $this->manualProduct["taxes_amount"] = null;
            $this->manualProduct["price_with_taxes"] = null;
            $this->manualProduct["total"] = null;
            return false;
        }

        $taxes_amount = round(
            (float)($this->manualProduct["quantity"] * $this->manualProduct["price"] * $this->manualProduct["taxes"]) / 100,
            2
        );
        $price  = ($this->manualProduct["quantity"] * $this->manualProduct["price"]);
        $this->manualProduct["taxes_amount"] = $taxes_amount;
        $this->manualProduct["price_with_taxes"] = $taxes_amount + $price;
        $this->manualProduct["total"] = $taxes_amount + $price;
    }

    public function validateManualProduct()
    {
        $this->validate(
            [
                'manualProduct.product_name' => ['required', 'string'],
                'manualProduct.price' => ['required', 'numeric', 'min:0'],
                'manualProduct.quantity' => ['required', 'integer', 'min:1'],
                'manualProduct.taxes' => ['required', 'numeric', 'min:0'],
            ],
            [
                'manualProduct.product_name.required' => 'Este campo es obligatorio.',
                'manualProduct.price.required' => 'Este campo es obligatorio.',
                'manualProduct.quantity.required' => 'Este campo es obligatorio.',
                'manualProduct.taxes.required' => 'Este campo es obligatorio.',
            ]
        );
    }

    public function updated($property)
    {
        if (str_starts_with($property, 'manualProduct.')) {
            $this->calculateManualProduct();
        }
    }
    public function saveManualProduct()
    {

        $this->validateManualProduct();
        $id_aleatory = Str::random(16);
        $this->selectedProducts[$id_aleatory] = [
            "aleatory_id" => $id_aleatory,
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
        Notification::make()
            ->title('Producto añadido')
            ->body('Producto añadido correctamente')
            ->success()
            ->duration(3000)
            ->send();

        $this->dispatch('close-modal', id: 'manual-product-modal');
        $this->resertManualProduct();
    }
    public function closeModalManual()
    {
        $this->dispatch('close-modal', id: 'manual-product-modal');
    }


    public function validateForm()
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

    public function saveForm($action = 0)
    {
        $this->validateForm();

        if (count($this->selectedProducts) == 0) {
            Notification::make()
                ->title('Error al guardar')
                ->body('Debes seleccionar almenos 1 artículo')
                ->danger()
                ->duration(3000)
                ->send();
            return false;
        }



        if (empty($this->order->id)) {
            //$this->
        }


        Notification::make()
            ->title($action ? "Facturada" : "Guardada")
            ->body($action ? "Venta facturada correctamente" : "Vente guardada correctamente")
            ->success()
            ->duration(3000)
            ->send();




        /*if ($this->order) {
            $this->order->update($this->form);
            session()->flash('success', 'Venta actualizada.');
        } else {
            Order::create($this->form);
            session()->flash('success', 'Venta creada.');
        }*/

        //return redirect()->to('admin/sales');
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
                "aleatory_id" => null,
                "detail_id" => null,
                "item_id" => $item->id,
                "item_name" => $item->name,
                "item_type" => $item->type,
                "price_unit" => $item->price,
                "price" => 0,
                "taxes" => $item->taxes,
                "taxes_amount" => 0,
                "price_with_taxes" => 0,
                "quantity" => 0,
                "total" => 0,
            ];
        }

        $taxes_amount = round((float)($quantity * $item->price * $item->taxes) / 100, 2);
        $price  = ($quantity * $item->price);
        $this->selectedProducts[$item->id]["price"] = $price;
        $this->selectedProducts[$item->id]["quantity"] = $quantity;
        $this->selectedProducts[$item->id]["total"] = $price + $taxes_amount;
        $this->selectedProducts[$item->id]["taxes_amount"] =  $taxes_amount;

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
        $this->getGeneralTotal();
        return view('livewire.Sales.form', [
            'items' => $this->consultaItems->paginate($this->perPage),
            'customerList' => Customer::active()->get(),
        ]);
    }
}
