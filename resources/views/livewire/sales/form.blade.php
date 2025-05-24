<div class="grid grid-cols-10 gap-4">
    <div class="col-span-10 flex justify-between w-full">

       <div>
            <x-filament::button color="primary"   class="mr-5" wire:click="saveForm(0)">
                Guardar
            </x-filament::button>
            <x-filament::button color="success"  class="" wire:click="saveForm(1)">
                Guardar y facturar
            </x-filament::button>
       </div>
       <div>
            <x-filament::button color="gray"  onclick="cancelBtnAction()"  class="mr-5" >
                Cancelar
            </x-filament::button>
       </div>
      </div>
    <div class="col-span-10 flex justify-between w-full">

        <div style="display: flex; justify-content: center; align-items: center;">
            <x-filament::badge
                color="success"
                style="font-size: 28px; font-weight: bold; padding: 20px 32px; width: 220px; text-align: center; line-height: 1.5; height: 70px; display: flex; align-items: center; justify-content: center;"
            >
                {{ number_format($getGeneralTotals['total'], 2) }} €
            </x-filament::badge>
        </div>
      </div>
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
                        @foreach ($selectedProducts as $key => $product)
                        {{-- @dd($product) --}}
                            <tr>
                                <td class="px-2 py-2 text-black dark:text-white">
                                    <div class="flex items-center ">
                                        @if ($product["item_type"] == "service")
                                            <div class="w-4 h-4 rounded bg-yellow-400 border border-yellow-600 mr-2"></div>
                                        @elseif ($product["item_type"] == "product")
                                            <div class="w-4 h-4 rounded bg-blue-500 border border-blue-700 mr-2"></div>
                                        @elseif ($product["item_type"] =="manual_product")
                                            <div class="w-4 h-4 rounded bg-fuchsia-500 border border-fuchsia-700 mr-2"></div>
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
                                <td class="px-2 py-2 text-black dark:text-white">{{ $product["taxes_amount"] }}</td>
                                <td class="px-2 py-2 text-black dark:text-white">{{ $product["total"] }}</td>
                                <td class="px-2 py-2 text-black dark:text-white">
                                    <button
                                        wire:loading.attr="disabled"
                                        wire:click="deleteItem('{{ $key }}')"
                                        class="w-12 h-12 flex items-center justify-center focus:outline-none transition-all duration-300 ease-in-out transform hover:scale-110">
                                        <x-heroicon-s-trash class="w-6 h-6 text-red-500 hover:text-red-600" />
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    @if($getGeneralTotals['taxes_amount']<>0)
                    <tfoot>
                        <tr>
                            <th colspan="5"></th>
                            <th class="px-1 py-2 text-left text-black dark:text-white"  colspan="1">{{ number_format($getGeneralTotals['taxes_amount'], 2)}}</th>
                            <th  class="px-1 py-2 text-left text-black dark:text-white" colspan="1">{{ number_format($getGeneralTotals['total'], 2)}}</th>
                            <th colspan="1"></th>
                        </tr>
                    </tbody>
                    @endif
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
                    @error('form.date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-1">
                    <x-filament-forms::field-wrapper.label>
                        Cliente
                    </x-filament-forms::field-wrapper.label>
                    <x-filament::input.wrapper :valid="! $errors->has('form.customer_id')">
                        <x-filament::input.select wire:model="form.customer_id" searchable>
                            <option value="">Seleccione cliente</option>
                            @foreach ($customerList as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                    @error('form.customer_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
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
            <div class="lg:col-span-5 flex items-center mr-4 ">
                <div class="flex items-center mr-5 p-2">
                    <div class="w-4 h-4 rounded bg-blue-500 border border-blue-700 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Productos</span>
                </div>
                <div class="flex items-center mr-5 p-2">
                    <div class="w-4 h-4 rounded bg-yellow-400 border border-yellow-600 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Servicios</span>
                </div>
                <div class="flex items-center mr-5 p-2">
                    <div class="w-4 h-4 rounded bg-fuchsia-500 border border-fuchsia-700 mr-2"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Producto manual</span>
                </div>
            </div>
               <div class="lg:col-span-5" style="text-align: right">
                  <x-filament::modal  id="manual-product-modal" width="5xl" :close-by-clicking-away="false">
                    <x-slot name="trigger">
                        <x-filament::button size="sm" color="info">
                             Añadir producto manual
                        </x-filament::button>
                    </x-slot>
                    <x-slot name="header">
                        Añadir producto manual
                    </x-slot>
                    <hr>
                    <div class="mb-5 mt-5" style="text-align: left !important">
                        <div class="grid grid-cols-1 lg:grid-cols-7 gap-2">
                            <div class="col-span-1 lg:col-span-2">
                                <x-filament-forms::field-wrapper.label>
                                    Nombre Producto
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper
                                :valid="! $errors->has('manualProduct.product_name')"
                                >
                                    <x-filament::input type="text"
                                         wire:model="manualProduct.product_name"  />
                                </x-filament::input.wrapper>
                                @error('manualProduct.product_name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1 lg:col-span-1">
                                <x-filament-forms::field-wrapper.label>
                                    Precio
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper :valid="! $errors->has('manualProduct.price')">
                                    <x-filament::input type="number"
                                          wire:model.live.debounce.500ms="manualProduct.price" min="1" />
                                </x-filament::input.wrapper>
                                @error('manualProduct.price')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1 lg:col-span-1">
                                <x-filament-forms::field-wrapper.label>
                                    Cantidad
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper :valid="! $errors->has('manualProduct.quantity')">
                                    <x-filament::input
                                          wire:model.live.debounce.500ms="manualProduct.quantity" type="number" min="1"   />

                                </x-filament::input.wrapper>
                                @error('manualProduct.quantity')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1 lg:col-span-1">
                                <x-filament-forms::field-wrapper.label>
                                    Iva %
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper :valid="! $errors->has('manualProduct.taxes')" >
                                    <x-filament::input
                                          wire:model.live.debounce.500ms="manualProduct.taxes" type="number" min="1"  />
                                </x-filament::input.wrapper>
                                @error('manualProduct.taxes')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1 lg:col-span-1">
                                <x-filament-forms::field-wrapper.label>
                                    Iva €
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper>
                                    <x-filament::input style="background: #e9e9e9f"
                                         wire:model.defer="manualProduct.taxes_amount" disabled type="number" min="1" />
                                </x-filament::input.wrapper>
                            </div>
                            <div class="col-span-1 lg:col-span-1">
                                <x-filament-forms::field-wrapper.label>
                                    Total €
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper>
                                    <x-filament::input disabled style="background: #e9e9e9f"
                                         wire:model.defer="manualProduct.total" type="number" min="1" />
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <x-slot name="footerActions">

                        <div class="flex justify-between w-full">
                            <x-filament::button color="gray" wire:click="closeModalManual" size="sm" class="">
                              Cerrar
                            </x-filament::button>
                            <x-filament::button wire:click='saveManualProduct' size="sm" class="">
                              Guardar
                            </x-filament::button>
                          </div>


                    </x-slot>
                    {{-- Modal content --}}
                </x-filament::modal>
               </div>
                <!-- Primer input: Buscar Producto -->
                <div class="lg:col-span-3">
                    <x-filament::input.wrapper >
                        <x-filament::input
                            type="text"
                            wire:model.live.debounce.500ms="searchProduct"

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

                {{-- <div class="lg:col-span-5 flex items-center justify-center gap-2 w-full" wire:loading wire:target="buscarProducto"   >
                    <x-filament::loading-indicator class="h-5 w-5"  />
                    <span>Cargando tabla</span>
                </div> --}}
                <div class="lg:col-span-5 w-full flex justify-center" wire:loading wire:target="buscarProducto" >
                    <div class="flex items-center justify-center gap-2">
                        <x-filament::loading-indicator class="h-5 w-5" />
                        <span>Cargando tabla</span>
                    </div>
                </div>

            </div>


            <div class="overflow-auto h-full">
                    <table  class="mt-5 min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700" >
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

                                                @else
                                                <div class="w-4 h-4 rounded bg-fuchsia-500 border border-fuchsia-700 mr-2"></div>
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
                                                wire:target="selectItem"
                                                type="number"
                                                min="1"
                                            />
                                        </x-filament::input.wrapper>
                                    </td>
                                    <td class="px-2 py-2 text-black dark:text-white">
                                         <button
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
    <script>
         function cancelBtnAction() {
                window.location.href = "{{ url('/admin/sales') }}";
            }
    </script>
  </div>
