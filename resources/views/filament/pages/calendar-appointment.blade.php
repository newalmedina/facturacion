<x-filament-panels::page>

    {{-- Leyenda de colores --}}
    <div class="flex flex-wrap space-x-4 mt-4 mb-4">
        @php
            $colors = [
                'available' => '#6c757d',
                'confirmed' => '#28a745',
                'pending_confirmation' => '#63b2f7ff',
                'cancelled' => '#dc3545',
            ];
        @endphp

        @foreach ($colors as $status => $color)
            @php
                $label = (new \App\Models\Appointment(['status' => $status]))->status_name_formatted;
            @endphp
            <div class="flex items-center space-x-1 mb-2">
                <span class="w-4 h-4 rounded-full" style="background-color: {{ $color }}; margin-right:10px"></span>
                <span class="text-sm" style="margin-right:20px">{{ $label }}</span>
            </div>
        @endforeach
    </div>

    {{-- Filtros: Estado y Trabajador --}}
    <div class="flex flex-wrap mb-4 -mx-2">
        <div class="w-1/4 px-2">
            <x-filament-forms::field-wrapper.label>Estado</x-filament-forms::field-wrapper.label>
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="selectedStatus">
                    <option value="">Seleccione estado</option>
                    @foreach ($statusList as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>

        <div class="w-1/4 px-2">
            <x-filament-forms::field-wrapper.label>Trabajador</x-filament-forms::field-wrapper.label>
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="selectedWorker">
                    <option value="">Seleccione trabajador</option>
                    @foreach ($workerList as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>

        <div class="w-1/4 px-2">
            {{-- Espacio para otro filtro si quieres --}}
        </div>
        <div class="w-1/4 px-2">
            {{-- Espacio para otro filtro si quieres --}}
        </div>
    </div>

    {{-- Widget del calendario --}}
    @livewire(\App\Filament\Widgets\CalendarWidget::class, [
        'selectedStatus' => $selectedStatus,
        'selectedWorker' => $selectedWorker,
    ], key(str()->random()))

</x-filament-panels::page>
