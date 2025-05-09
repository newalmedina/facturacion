<div class="grid grid-cols-5 gap-4">
    <div class="col-span-3">
        <x-filament::section collapsible >
            <x-slot name="heading">
               Productos Seleccionados
            </x-slot>

            {{-- pRODUCTOS SELECCIONADOS --}}
            {{-- pRODUCTOS SELECCIONADOS --}}
        </x-filament::section>
    </div>
    <div class="col-span-2">
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
                    <x-filament::input.wrapper  :valid="! $errors->has('form.customer')">
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model="form.customer">
                                <option value="">Seleccione cliente</option>
                                @foreach ($customerList as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>

                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
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
            {{-- lISTADO PRODUCTO --}}
        </x-filament::section>
    </div>
  </div>
