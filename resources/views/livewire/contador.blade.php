<div class="flex flex-col items-center justify-center min-h-screen">
    <h1 class="text-4xl font-bold mb-6">
        {{ $count }}
    </h1>

    <div class="space-x-4">
        <button wire:click="decrementar" class="px-4 py-2 bg-red-500 text-white rounded-lg">-</button>
        <button wire:click="incrementar" class="px-4 py-2 bg-green-500 text-white rounded-lg">+</button>
    </div>
</div>
