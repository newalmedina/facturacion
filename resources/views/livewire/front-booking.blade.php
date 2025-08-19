<div>
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
    #calendar-container {
      width: 100%;
      padding: 15px;       /* más padding alrededor */
      box-sizing: border-box;
    }
    .flatpickr-calendar {
      width: 100% !important;
    }
    input.form-control, select.form-control, textarea.form-control {
      width: 100% !important;
    }
    
    /* Color del día seleccionado */
    .flatpickr-day.selected, 
    .flatpickr-day.selected:hover {
      background: #581177 !important;
      border-color: #581177 !important;
      color: #fff !important;
    }
    </style>

@endpush

    <h3 class="mb-4 text-center text-primary" style="color:#b462e2 !important">Reserva tu cita</h3>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="row g-3 mt-5">

        <div class="row">
            <!-- Calendario -->
            <div class="col-12 col-md-4 mb-3">
                <div id="calendar-container"></div>
            </div>

            <div class="col-12 col-md-8">
                <!-- Nombre -->
                <div class="mb-3">
                    <input type="text" wire:model="Apppointment.requester_name" class="form-control w-100" placeholder="Nombre completo">
                    @error('Apppointment.requester_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <input type="email" wire:model="Apppointment.email" class="form-control w-100" placeholder="Correo electrónico">
                    @error('Apppointment.email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <input type="text" wire:model="Apppointment.requester_phone" class="form-control w-100" placeholder="Teléfono">
                    @error('Apppointment.requester_phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Selección de items -->
                <div class="mb-3">
                    <label class="form-label">Selecciona un Servicio</label>
                    <select wire:model="Apppointment.item_id" class="form-control w-100">
                        <option value="">-- Seleccionar --</option>
                        @foreach($showItems as $item)
                            <option value="{{ $item->id }}">{{ $item->name . ' -- '.$item->total_price. '€' }}</option>
                        @endforeach
                    </select>
                    @error('Apppointment.item_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="mb-3">
            <textarea wire:model="Apppointment.comments" class="form-control w-100" rows="3" placeholder="Mensaje opcional"></textarea>
            @error('Apppointment.comments') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="text-center">
            <button type="submit" class="btn btn-cita px-4 py-2 rounded-pill">Reservar cita</button>
        </div>
            </div>
        </div>

        <!-- Mensaje -->
        

        <hr>

        <!-- Mensaje superior llamativo -->
        <div class="mb-2">
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2 fa-2x"></i>
                <div>
                    También tenemos <strong>otros servicios</strong> que pueden interesarte. ¡Échales un vistazo y elige el que más te convenga!
                </div>
            </div>
        </div>

        <!-- Selección de otros items -->
        <div class="mb-3">
            <label class="form-label">Otros servicios</label>
            <select wire:model="Apppointment.item_id" class="form-control w-100">
                <option value="">-- Seleccionar --</option>
                @foreach($showItemsOthers as $item)
                    <option value="{{ $item->id }}">{{ $item->name . ' -- ' . $item->total_price . '€' }}</option>
                @endforeach
            </select>

            <!-- Mensaje debajo del input -->
            <div class="mt-2 alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2 fa-2x"></i>
                <div>
                    Para concertar cita con uno de estos servicios, por favor contacta al teléfono <strong>
                        <a style="color:blue" href="https://wa.me/{{ preg_replace('/\D/', '', trim($generalSettings->phone, '"')) }}" target="_blank">
                            {{ trim($generalSettings->phone, '"') }}
                        </a>
                    </strong> (llamada o WhatsApp).
                </div>
            </div>
        </div>

        <!-- Botón -->
        
    </form>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
  flatpickr("#calendar-container", {
    inline: true,
    defaultDate: "today",
    minDate: "today",  // solo fechas desde hoy en adelante
    locale: "es",      // español
    onChange: function(selectedDates, dateStr, instance) {
        @this.set('date', dateStr); // Livewire
    }
  });
</script>
@endpush


</div>
