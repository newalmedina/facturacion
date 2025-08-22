<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Country;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

use Illuminate\Support\Facades\Cache;

class FrontBooking extends Component
{
    public $name;
    public $email;
    public $phone;
    public $selectedDate;
    public Appointment $apppointment;
    public $message;
    public $showItems = [];
    public $showItemsOthers = [];
    public $apppointmentList = [];
    public $workerlist = [];
    public $countries = [];
    public $phoneCode = 207;
    public $worker_id = null;
    public $showForm = true;
    public $generalSettings;
    public $selectedAppointment = null;
    public $selectedItem = null;
    public $selecteOtherdItem = null;
    public $other_item_id = null;
    public $form = [
        'item_id' => null,
        'requester_name' => null,
        'requester_phone' => null,
        'requester_email' => null,
        'comments' => null,
    ];

    protected $rules = [
        'form.item_id' => 'required|',
        'selectedAppointment' => 'required|integer|exists:appointments,id',
        'form.requester_name' => 'required|string|min:3',
        'form.requester_phone' => 'required|string|min:3',
        'form.requester_email' => 'required|email',
        'selectedDate' => 'required|date|after_or_equal:today',
        'phoneCode' => 'required',
        'form.comments' => 'nullable|string',
    ];

    protected $messages = [
        'phoneCode.required' => 'Debes seleccionar código teléfono.',
        'selectedAppointment.required' => 'Debes seleccionar una cita.',
        'form.item_id.required' => 'Debes seleccionar un servicio.',
        'form.item_id.integer' => 'El valor seleccionado no es válido.',
        'form.item_id.exists' => 'El servicio seleccionado no existe.',
        'form.requester_name.required' => 'El nombre es obligatorio.',
        'form.requester_name.string' => 'El nombre debe ser texto.',
        'form.requester_name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'form.requester_phone.required' => 'El teléfono es obligatorio.',
        'form.requester_phone.string' => 'El teléfono debe ser texto.',
        'form.requester_phone.min' => 'El teléfono debe tener al menos 3 caracteres.',
        'form.requester_email.required' => 'El correo electrónico es obligatorio.',
        'form.requester_email.email' => 'Debes ingresar un correo electrónico válido.',
        'date.required' => 'La fecha es obligatoria.',
        'date.date' => 'Debes ingresar una fecha válida.',
        'date.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
        'form.comments.string' => 'Los comentarios deben ser texto.',
    ];




    public function mount()
    {
        $this->selectedDate = Carbon::now()->format("Y-m-d");

        // Caching para producción
        $this->workerlist = Cache::remember('workers', 3600, fn() => User::canAppointment()->get());
        $this->showItems = Cache::remember('booking_items', 3600, fn() => Item::showBooking()->orderBy('price', 'asc')->get());
        $this->showItemsOthers = Cache::remember('booking_items_others', 3600, fn() => Item::showBookingOthers()->orderBy('price', 'asc')->get());
        $this->countries = Cache::remember('countries', 3600, fn() => Country::activos()
            ->select('id', 'name', 'phonecode')
            ->orderBy('name', 'asc')
            ->get());

        $this->loadAppointments();
    }

    /**
     * Se dispara automáticamente cuando cambia $worker_id o $selectedDate
     */
    public function updatedWorkerId()
    {
        $this->loadAppointments();
    }

    public function updatedSelectedDate()
    {
        $this->loadAppointments();
    }

    private function loadAppointments()
    {
        $query = Appointment::active()
            ->where("date", $this->selectedDate)
            ->statusAvailable()
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc');

        if (!empty($this->worker_id)) {
            $query->where('worker_id', $this->worker_id);
        }

        $this->apppointmentList = $query->get();

        if (!empty($this->selectedAppointment) && !$this->apppointmentList->contains('id', $this->selectedAppointment)) {
            $this->selectedAppointment = null;
        }
    }

    public function selectAppointment($id)
    {
        $this->selectedAppointment = $id;
    }

    public function submit()
    {
        $this->validate();

        $appointment = Appointment::find($this->selectedAppointment);
        if (!$appointment) {
            $this->addError('selectedAppointment', 'La cita seleccionada no existe.');
            return;
        }

        $existing = Appointment::where('requester_email', $this->form['requester_email'])
            ->where('date', $appointment->date)
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($existing) {
            session()->flash(
                'error',
                "Ya existe una cita seleccionada para este día con este correo. 
            Para poder seleccionar esta debes cancelar la existente."
            );
            return;
        }

        $country = $this->countries->firstWhere('id', $this->phoneCode);

        $appointment->update([
            'item_id' => $this->form['item_id'],
            'requester_name' => $this->form['requester_name'],
            'requester_phone' => "+" . $country->phonecode . $this->form['requester_phone'],
            'requester_email' => $this->form['requester_email'],
            'comments' => $this->form['comments'],
            'status' => 'pending_confirmation',
        ]);

        $fecha = $appointment->date->format('d/m/Y');
        $horaInicio = $appointment->start_time->format('H:i');
        $horaFin = $appointment->end_time->format('H:i');

        session()->flash(
            'success',
            "Acabas de seleccionar una cita para el día {$fecha} de {$horaInicio} a {$horaFin}. 
        A continuación recibirás un correo electrónico con los datos de tu cita."
        );

        $this->showForm = false;
    }

    public function initCalendar()
    {
        $this->dispatchBrowserEvent('init-calendar');
    }

    public function render()
    {
        $this->selectedItem = $this->showItems->firstWhere('id', $this->form["item_id"]);
        $this->selecteOtherdItem = $this->showItemsOthers->firstWhere('id', $this->other_item_id);

        return view('livewire.front-booking');
    }
}
