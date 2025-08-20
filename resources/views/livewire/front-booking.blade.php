<div>
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>

.appointment-card {
    
    border: 2px solid #ccc; /* borde por defecto */
    border-radius: 0.5rem;
    transition: border-color 0.3s;
    width: 100%; /* todas las cards ocupan el mismo ancho */
}

.appointment-card input:checked + .card {
    border-color: #581177; 
    background: #dd93ec;
    color: #fff; 
    font-weight: bold;
}

.appointment-card input:checked + .card .card-body,
.appointment-card input:checked + .card .card-body label,
.appointment-card input:checked + .card .card-body p {
    color: #fff !important;
    font-weight: bold;
}

.cursor-pointer {
    cursor: pointer;
}

    #calendar-container {
      width: 100%;
      padding: 15px;
      box-sizing: border-box;
    }

    /* Forzar que el calendario muestre los 7 días completos */
    #calendar-container .flatpickr-calendar {
      min-width: 320px;   /* ancho mínimo para que entren todos los días */
      max-width: 100%;
      margin: 0 auto;
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

    /* Ajustes para pantallas pequeñas */
    @media (max-width: 768px) {
      #calendar-container .flatpickr-calendar {
        width: 100% !important;
        min-width: auto;
        font-size: 14px;
      }
      .flatpickr-day {
        max-width: 34px;
        height: 34px;
        line-height: 34px;
      }
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
            <div class="col-12 mb-3">
                <div id="calendar-container"></div>
            </div>

           
            <div class="col-12  mb-3 pt-5">
                @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                <div class="row g-3">
                    <div class="row g-3">
                        @foreach($apppointmentList as $appointment)
                        <div class="col-12 col-md-3 d-flex">
                            <label class="appointment-card d-block cursor-pointer flex-fill h-100"
                                   wire:click="selectAppointment({{ $appointment->id }})">
                                
                                <input type="radio" name="selectedAppointment" 
                                       wire:model="selectedAppointment" 
                                       value="{{ $appointment->id }}" class="d-none">
                                
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body text-center d-flex flex-column justify-content-center">
                                        <span style="font-size: 15px">{{ $appointment->worker->name }}</span>
                                        <p style="font-size: 15px">{{ $appointment->start_time->format('H:i') }} - {{ $appointment->end_time->format('H:i') }}</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                    
                    </div>
                    
                </div>
            </div>
           
        </div>
        <div class="row mt-4">
            <div class="col-12 ">
                <!-- Nombre -->
                <div class="mb-3">
                    <input type="text" wire:model="form.requester_name" class="form-control w-100" placeholder="Nombre completo">
                    @error('Apppointment.requester_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <input type="email" wire:model="form.email" class="form-control w-100" placeholder="Correo electrónico">
                    @error('Apppointment.email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label class="form-label">Código país</label>
                    <select wire:model="phoneCode" class="form-control w-100">
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">
                                {{ $country->name }} ( +{{ $country->phonecode }} )
                            </option>
                        @endforeach
                    </select>

                    @error('appointment.phone_code') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror

                    <small class="form-text text-muted">
                        📌 Importante: selecciona el código correcto para poder contactar por WhatsApp.
                    </small>
                </div>

                <div class="mb-3">
                    <input type="text" wire:model="form.requester_phone" class="form-control w-100" placeholder="Teléfono">
                    @error('Apppointment.requester_phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Selección de items -->
                <div class="mb-3">
                    <label class="form-label">Selecciona un Servicio</label>
                    <select wire:model="form.item_id" class="form-control w-100">
                        <option value="">-- Seleccionar --</option>
                        @foreach($showItems as $item)
                            <option value="{{ $item->id }}">{{ $item->name . ' -- '.$item->total_price. '€' }}</option>
                        @endforeach
                    </select>
                    @error('Apppointment.item_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <textarea wire:model="form.comments" class="form-control w-100" rows="3" placeholder="Mensaje opcional"></textarea>
                    @error('Apppointment.comments') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-12">
                <div class="text-center">
                    <button type="submit" class="btn btn-cita px-4 py-2 rounded-pill">Reservar cita</button>
                </div>
            </div>
        </div>

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
            <select wire:model="form.item_id" class="form-control w-100">
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
                        {{ trim($generalSettings->phone, '"') }}
                    </strong> 
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
      flatpickr("#calendar-container", {
        inline: true,
        defaultDate: "today",
        minDate: "today",  
        locale: "es",
        dateFormat: "Y-m-d",   // <-- ESTE formato es el que espera tu backend
        monthSelectorType: "static",
        onChange: function(selectedDates, dateStr, instance) {
            @this.set('date', dateStr);
        }
        });
    </script>
    @endpush
</div>
