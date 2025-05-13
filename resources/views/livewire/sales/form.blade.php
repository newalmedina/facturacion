<div class="grid grid-cols-10 gap-4">

    <div class="col-span-9 lg:col-span-6">
        <x-filament::section collapsible >
            <x-slot name="heading">
               Productos Seleccionados
            </x-slot>

            {{-- pRODUCTOS SELECCIONADOS --}}
             <div class="overflow-x-auto">
                <table  class="mt-5 min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-white dark:bg-gray-800">
                        <tr>
                            <th class="px-1 py-2 text-left text-black dark:text-white">Nombre</th>
                            <th class="px-1 py-2 text-left text-black dark:text-white">Precio Unidad</th>
                            <th class="px-1 py-2 text-left text-black dark:text-white">Cantidad</th>
                            <th class="px-1 py-2 text-left text-black dark:text-white">Precio</th>
                            <th class="px-1 py-2 text-left text-black dark:text-white">IVA%</th>
                            <th class="px-1 py-2 text-left text-black dark:text-white">IVA €</th>
                            <th class="px-1 py-2 text-left text-black dark:text-white">Total €</th>
                            <th class="px-1 py-2 text-left text-black dark:text-white"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($selectedProducts as $product)
                        {{-- @dd($product) --}}
                            <tr>
                                <td class="px-2 py-2 text-black dark:text-white">
                                    <div class="flex items-center ">
                                        @if ($product["item_type"] == "service")
                                            <div class="w-4 h-4 rounded bg-yellow-400 border border-yellow-600 mr-2"></div>
                                        @elseif ($product["item_type"] == "product")
                                            <div class="w-4 h-4 rounded bg-blue-500 border border-blue-700 mr-2"></div>
                                        @endif
                                        <span>{{ $product["item_name"] }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-2 text-black dark:text-white">{{ $product["price_unit"] }}</td>
                                <td class="px-2 py-2 text-black dark:text-white"  style="width: 120px !important;">
                                    <x-filament::input.wrapper >
                                        <x-filament::input
                                        value="{{$product['quantity']}}"
                                        wire:target="selectItem"
                                        type="number"
                                        min="1"
                                        />
                                    </x-filament::input.wrapper>

                                </td>
                                <td class="px-2 py-2 text-black dark:text-white">{{ $product["price"] }}</td>
                                <td class="px-2 py-2 text-black dark:text-white">{{ $product["taxes"] }}</td>
                                <td class="px-2 py-2 text-black dark:text-white">{{ $product["taxesAmount"] }}</td>
                                <td class="px-2 py-2 text-black dark:text-white">{{ $product["total"] }}</td>
                                <td class="px-2 py-2 text-black dark:text-white">
                                    <button
                                        wire:loading.attr="disabled"
                                        wire:click="deleteItem({{ $product['item_id'] }})"
                                        class="w-12 h-12 flex items-center justify-center focus:outline-none transition-all duration-300 ease-in-out transform hover:scale-110">
                                        <x-heroicon-s-trash class="w-6 h-6 text-red-500 hover:text-red-600" />
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- pRODUCTOS SELECCIONADOS --}}
        </x-filament::section>
    </div>
    <div class="col-span-9 lg:col-span-4">
        <x-filament::section collapsible   class="mb-5">
            <x-slot name="heading">
              Info general
            </x-slot>

            <div class="grid grid-cols-1 gap-2">
                <div class="col-span-1">
                    <x-filament-forms::field-wrapper.label>
                        Fecha
                    </x-filament-forms::field-wrapper.label>
                    <x-filament::input.wrapper  :valid="! $errors->has('form.date')">
                        <x-filament::input
                            type="date"
                            wire:model="form.date"

                        />
                    </x-filament::input.wrapper>
                </div>
                <div class="col-span-1">
                    <x-filament-forms::field-wrapper.label>
                        Cliente
                    </x-filament-forms::field-wrapper.label>
                    <x-filament::input.wrapper :valid="! $errors->has('form.customer')">
                        <x-filament::input.select wire:model="form.customer" searchable>
                            <option value="">Seleccione cliente</option>
                            @foreach ($customerList as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>

                </div>
            </div>
            {{-- INFO GENERAL --}}
            {{-- INFO GENERAL --}}
        </x-filament::section>

        <x-filament::section collapsible >
            <x-slot name="heading">
             Listado de productos
              <span class="inline-block bg-blue-500 text-white px-2 py-1 rounded-full text-sm ml-2">
                {{ $items->total() }}
            </span>
            </x-slot>

            {{-- lISTADO PRODUCTO --}}
           <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <!-- Primer input: Buscar Producto -->
                <div class="lg:col-span-3">
                    <x-filament::input.wrapper >
                        <x-filament::input
                            type="text"
                            wire:model="searchProduct"
                            wire:keyup="buscarProducto"
                            placeholder="Buscar producto"
                        />
                    </x-filament::input.wrapper>
                </div>

                <!-- Segundo input: Selección de Servicios/Productos -->
                <div class="lg:col-span-2">
                    <x-filament::input.wrapper >
                        <x-filament::input.select wire:model="searchType" searchable  wire:change="buscarProducto">
                            <option value="">Servicios / Productos</option>
                            <option value="service">Servicios</option>
                            <option value="product">Productos</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>
            </div>

            <div class="flex items-center mr-4  mt-5">
                <div class="flex items-center mr-5">
                    <div class="w-4 h-4 rounded bg-blue-500 border border-blue-700 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Productos</span>
                </div>
                <div class="flex items-center mr-5">
                    <div class="w-4 h-4 rounded bg-yellow-400 border border-yellow-600 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Servicios</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                    <table  class="mt-5 min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-white dark:bg-gray-800">
                            <tr>
                                <th class="px-1 py-2 text-left text-black dark:text-white">Nombre</th>
                                <th class="px-1 py-2 text-left text-black dark:text-white">Disp.</th>
                                <th class="px-1 py-2 text-left text-black dark:text-white">Precio</th>
                                <th class="px-1 py-2 text-left text-black dark:text-white">IVA%</th>
                                <th class="px-1 py-2 text-left text-black dark:text-white">Total</th>
                                <th class="px-1 py-2 text-left text-black dark:text-white">Cantidad</th>
                                <th class="px-1 py-2 text-left text-black dark:text-white"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($items as $item)
                                <tr>
                                    <td class="px-2 py-2 text-black dark:text-white">
                                        <div class="flex items-center ">
                                            @if ($item->type == "service")
                                                <div class="w-4 h-4 rounded bg-yellow-400 border border-yellow-600 mr-2"></div>
                                            @elseif ($item->type == "product")
                                                <div class="w-4 h-4 rounded bg-blue-500 border border-blue-700 mr-2"></div>
                                            @endif
                                            <span>{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 text-black dark:text-white">{{ $item->amount }}</td>
                                    <td class="px-2 py-2 text-black dark:text-white">{{ $item->price }}</td>
                                    <td class="px-2 py-2 text-black dark:text-white">{{ $item->taxes }}</td>
                                    <td class="px-2 py-2 text-black dark:text-white">{{ $item->totalPrice }}</td>
                                    <td class="px-2 py-2 text-black dark:text-white" style="width: 120px !important;">
                                        <x-filament::input.wrapper >
                                            <x-filament::input

                                                wire:model.defer="inputValues.{{ $item->id }}"
                                                wire:loading.attr="disabled"
                                                wire:target="selectItem"
                                                type="number"
                                                min="1"
                                            />
                                        </x-filament::input.wrapper>
                                    </td>
                                    <td class="px-2 py-2 text-black dark:text-white">
                                         <button
                                            wire:loading.attr="disabled"
                                            wire:click="selectItem({{ $item->id }}, {{ $item->id }})"
                                            class="w-12 h-12 flex items-center justify-center rounded-full bg-green-500 border-2 border-green-500 text-white hover:bg-green-600 hover:border-green-600 focus:outline-none transition-all duration-300 ease-in-out transform hover:scale-105 shadow-md">
                                            <x-heroicon-s-plus class="w-6 h-6 text-white hover:text-green-200" />
                                        </button>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                    <x-filament::pagination
                                :paginator="$items"
                            />
                    </div>
            </div>


            {{-- lISTADO PRODUCTO --}}
        </x-filament::section>
    </div>
  </div>
