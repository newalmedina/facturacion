<div class="grid grid-cols-9 gap-4">
    <div class="col-span-5">
        <x-filament::section collapsible >
            <x-slot name="heading">
               Productos Seleccionados
            </x-slot>

            {{-- pRODUCTOS SELECCIONADOS --}}
            {{-- pRODUCTOS SELECCIONADOS --}}
        </x-filament::section>
    </div>
    <div class="col-span-4">
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
            </x-slot>

            {{-- lISTADO PRODUCTO --}}
            <x-filament::input.wrapper  :valid="! $errors->has('form.date')">
                <x-filament::input
                    type="text"
                    wire:model="searchProduct"
                    placeholder="Buscar producto"

                />
            </x-filament::input.wrapper>
            <table style="width: 100%" class="mt-5 min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-white dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-black dark:text-white">Nombre</th>
                        <th class="px-4 py-2 text-left text-black dark:text-white">Cantidad</th>
                        <th class="px-4 py-2 text-left text-black dark:text-white">Precio</th>
                        <th class="px-4 py-2 text-left text-black dark:text-white">IVA%</th>
                        <th class="px-4 py-2 text-left text-black dark:text-white">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-2 text-black dark:text-white">{{ $item->name }}</td>
                            <td class="px-4 py-2 text-black dark:text-white">{{ $item->amount }}</td>
                            <td class="px-4 py-2 text-black dark:text-white">{{ $item->price }}</td>
                            <td class="px-4 py-2 text-black dark:text-white">{{ $item->taxes }}</td>
                            <td class="px-4 py-2 text-black dark:text-white">{{ $item->totalPrice }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <div class="mt-4">
               <x-filament::pagination
                        :paginator="$items"
                        :page-options="[5, 10, 20, 50, 100, 'all']"
                        :current-page-option-property="$perPage"
                    />
            </div>
            {{-- lISTADO PRODUCTO --}}
        </x-filament::section>
    </div>
  </div>
