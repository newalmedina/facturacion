<x-filament::page>
    <form wire:submit="save" class="space-y-4">
        {{ $this->form }}
        <x-filament::button type="submit">Actualizar Perfil</x-filament::button>
    </form>
</x-filament::page>
