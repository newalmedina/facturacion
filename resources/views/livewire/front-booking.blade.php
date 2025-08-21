<div>
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>

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
                <div id="calendar-container" class="flatpickr-container"></div>

            </div>

           
            <div class="col-12  mb-3 pt-5">
                <div class="mb-3">
                    <label class="form-label">Selecciona un trabajador</label>
                    <select wire:model.live="worker_id" class="form-control w-100">
                        <option value="">Todos</option>
                        @foreach($workerlist as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('worker_id') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                <div class="row g-3">
                    <div class="row g-3">
                        @foreach($apppointmentList as $appointment)
                            <div class="col-12 col-md-3 d-flex">
                                <label class="appointment-card d-block cursor-pointer flex-fill h-100"
                                    wire:click="selectAppointment({{ $appointment->id }})">
                                    
                                    <input type="radio" name="selectedAppointment" 
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
                @error('form.appointment_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
           
        </div>
        <div class="row mt-4">
            <div class="col-12 ">
                <!-- Nombre -->
                <div class="mb-3">
                    <input type="text" wire:model="form.requester_name" class="form-control w-100" placeholder="Nombre completo">
                    @error('form.requester_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <input type="email" wire:model="form.email" class="form-control w-100" placeholder="Correo electrónico">
                    @error('form.email') <small class="text-danger">{{ $message }}</small> @enderror
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
                    @error('form.requester_phone') <small class="text-danger">{{ $message }}</small> @enderror
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
                    @error('form.item_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <textarea wire:model="form.comments" class="form-control w-100" rows="3" placeholder="Mensaje opcional"></textarea>
                    @error('form.comments') <small class="text-danger">{{ $message }}</small> @enderror
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
    document.addEventListener("DOMContentLoaded", function () {
        document.addEventListener('livewire:load', function () {
             alert(123);
            //  initializeCalendar();
     
             // Re-inicializa después de cada actualización de Livewire
             Livewire.hook('message.processed', (message, component) => {
                //  initializeCalendar();
             });
     
             function initializeCalendar() {
                 const calendarEl = document.querySelector("#calendar-container");
                 
                 if (!calendarEl._flatpickr) { // Evita inicializarlo varias veces
                     flatpickr(calendarEl, {
                         inline: true,
                         defaultDate: "today",
                         minDate: "today",
                         locale: "es",
                         dateFormat: "Y-m-d",
                         monthSelectorType: "static",
                         onChange: function(selectedDates, dateStr, instance) {
                             // Emitimos el evento a Livewire
                             Livewire.emit('dateSelected', dateStr);
                         }
                     });
                 }
             }
         });
     
     });

</script>
@endpush
</div>
