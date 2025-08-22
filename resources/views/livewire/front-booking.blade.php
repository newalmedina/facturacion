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
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

        @if ($showForm)
            <form wire:submit.prevent="submit" class="row g-3 mt-5">

                    <div class="row">
                        <!-- Calendario -->
                        <div class="col-12 mb-3">
                           <div id="calendar-container" wire:ignore>
                                <!-- calendario -->
                            </div>
                            
                        </div>
                        
                        
                        <div class="col-12  mb-3 pt-5">
                            <input class="form-control w-100" type="date" id="date" wire:model.live="selectedDate" class="form-control d-none">
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
                                <div class="col-12 text-center">
                                        <!-- Overlay de carga -->
                                        <div wire:loading wire:target="selectedDate, worker_id" class="loading-overlay">
                                            <div class="spinner-border loading-overlay-color" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </div>
                                </div>
                            @forelse($apppointmentList as $appointment)
                                <div class="col-12 col-md-3 d-flex">
                                    <label class="appointment-card d-block cursor-pointer flex-fill h-100"
                                        wire:click="selectAppointment({{ $appointment->id }})">

                                        <div class="card h-100 shadow-sm {{ $appointment->id == $selectedAppointment ? 'selected' : '' }}">
                                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                                <span style="font-size: 15px">{{ $appointment->worker->name }}</span>
                                                <p style="font-size: 15px">
                                                    {{ $appointment->start_time->format('H:i') }} - {{ $appointment->end_time->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-danger fw-bold text-center mt-3">
                                        ❌ No hay citas disponibles para este día.
                                    </p>
                                </div>
                            @endforelse



                                    <div class="col-12">
                                        @error('selectedAppointment') 
                                            <small class="text-danger">{{ $message }}</small> 
                                        @enderror
                                    </div>
                                
                                </div>
                                
                            </div>
                            @error('form.appointment_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    
                    </div>
                    <div class="row mt-4">

                        <!-- Nombre -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="requester_name" class="form-label">Nombre completo</label>
                            <input type="text" id="requester_name"  wire:model.defer="form.requester_name" class="form-control" placeholder="Ej: Juan Pérez">
                            @error('form.requester_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email"  wire:model.defer="form.requester_email" class="form-control" placeholder="Ej: juan@mail.com">
                            @error('form.requester_email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Código país -->
                        <div class="col-12 col-md-3 mb-3">
                            <label for="phoneCode" class="form-label">Código país</label>
                            <select id="phoneCode"  wire:model.defer="phoneCode" class="form-control">
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->name }} (+{{ $country->phonecode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment.phone_code') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                            <small class="form-text text-muted">
                                📌 Importante: selecciona el código correcto para WhatsApp.
                            </small>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-12 col-md-9 mb-3">
                            <label for="requester_phone" class="form-label">Teléfono</label>
                            <input type="text" id="requester_phone"  wire:model.defer="form.requester_phone" class="form-control" placeholder="Ej: 600123456">
                            @error('form.requester_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Selección de items -->
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-12  mb-3">
                                <label for="item_id" class="form-label">Selecciona un Servicio</label>
                                    <select id="item_id" wire:model.live="form.item_id" class="form-control">
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($showItems as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} -- {{ $item->total_price }}€</option>
                                        @endforeach
                                    </select>
                                    @error('form.item_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                @if ($selectedItem?->image)
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="services-thumb mt-4">
                                            <img src="{{ asset('storage/' . $selectedItem->image) }}" 
                                                class="services-image img-fluid" 
                                                style="max-height: 667px" 
                                                alt="{{ $selectedItem->name . ' ' . $selectedItem->description }}">
                                        </div>                       
                                    </div>
                                @endif


                            </div>
                        </div>
                    

                        <!-- Comentarios -->
                        <div class="col-12 mb-3">
                            <label for="comments" class="form-label">Mensaje opcional</label>
                            <textarea id="comments" wire:model.defer="form.comments" class="form-control" rows="3" placeholder="Ej: Quisiera reservar para la mañana"></textarea>
                            @error('form.comments') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>



                    <div class="row ">
                        <div class="col-12">
                            <div class="text-center">
                                <button type="submit" class="btn btn-cita px-4 py-2 rounded-pill">Reservar cita</button>
                            </div>
                        </div>
                    </div>
            </form>
            
        @else
            <div class="row ">
                <div class="col-12">
                    <div class="text-center mt-5">
                        <a href="{{ route('booking') }}"  class="btn btn-cita px-4 py-2 rounded-pill">Regresar</a>
                    </div>
                </div>
            </div>
        @endif
        <hr>

       <div class="row">
        <div class="col-12">
             <!-- Mensaje superior llamativo -->
            <div class="mb-2">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2 fa-2x"></i>
                    <div>
                        También tenemos <strong>otros servicios</strong> que pueden interesarte. ¡Échales un vistazo y elige el que más te convenga!
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <!-- Selección de otros items -->
            <label class="form-label">Otros servicios</label>
                <select wire:model.live="other_item_id" class="form-control w-100">
                    <option value="">-- Seleccionar --</option>
                    @foreach($showItemsOthers as $item)
                        <option value="{{ $item->id }}">{{ $item->name . ' -- ' . $item->total_price . '€' }}</option>
                    @endforeach
                </select>
               
        </div>
            @if ($selecteOtherdItem?->image)
            <div class="col-12 col-md-6 mb-3">                              
                <div class="services-thumb mt-4">
                    <img src="{{ asset('storage/' . $selecteOtherdItem->image) }}" 
                        class="services-image img-fluid" 
                        style="max-height: 667px" 
                        alt="{{ $selecteOtherdItem->name . ' ' . $selecteOtherdItem->description }}">
                </div>                       
            </div>
        @endif

        <div class="col-12  mb-3">                              

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
       </div>
  

@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Inicializa en el primer render
        initializeCalendar(@this.get('selectedDate'));

        // Reinicializa después de cada render del componente
        Livewire.hook('morphed', ({ el, component }) => {
            if (el.querySelector('#calendar-container')) {
                initializeCalendar(@this.get('selectedDate'));
            }
        });
    });

    function initializeCalendar(selectedDate) {
        let calendarEl = document.querySelector("#calendar-container");

        if (!calendarEl) return;

        // Destruir instancia previa si existe
        if (calendarEl._flatpickr) {
            calendarEl._flatpickr.destroy();
        }

        flatpickr(calendarEl, {
            inline: true,
            defaultDate: selectedDate || "today", // 👉 si hay fecha seleccionada la usamos
            minDate: "today",
            locale: "es",
            dateFormat: "Y-m-d",
            monthSelectorType: "static",
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('selectedDate', dateStr);
            }
        });
    }
</script> --}}
@endpush




</div>
