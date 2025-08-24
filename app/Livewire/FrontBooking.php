<?php

namespace App\Livewire;

use App\Mail\AppointmentNotifyWorkerMail;
use App\Mail\AppointmentRequestedMail;
use App\Models\Appointment;
use App\Models\Country;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

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
        $this->apppointment = new Appointment();
        $this->selectedDate = Carbon::now()->format("Y-m-d");

        $this->workerlist = User::canAppointment()->get();
        $this->showItems = Item::showBooking()->orderBy('price', 'asc')->get();
        $this->showItemsOthers = Item::showBookingOthers()->orderBy('price', 'asc')->get();
        $this->countries = Country::activos()
            ->select('id', 'name', 'phonecode')
            ->orderBy('name', 'asc')
            ->get();

        // $this->sendMail();
    }

    public function sendMail()
    {
        // Enviar mail al requester por cola
        $this->apppointment = Appointment::find(4);

        Mail::to($this->apppointment->requester_email)
            ->queue(new AppointmentRequestedMail($this->apppointment->id));
        // Enviar mail al worker por cola
        Mail::to($this->apppointment->worker->email)
            ->queue(new AppointmentNotifyWorkerMail($this->apppointment->id));


        //Mail::to($this->apppointment->requester_email)

        Mail::to("el.solitions@gmail.com")
            ->queue(new AppointmentNotifyWorkerMail($this->apppointment->id));
    }

    /**
     * Se dispara automáticamente cuando cambia $worker_id o $date
     */
    public function updatedWorkerId()
    {
        $this->loadAppointments();
    }

    public function updatedDate()
    {
        $this->loadAppointments();
    }

    private function loadAppointments()
    {
        $this->apppointmentList = Appointment::active()
            ->where("date", $this->selectedDate)
            ->when(!empty($this->worker_id), fn($query) => $query->where('worker_id', $this->worker_id))
            ->statusAvailable()
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        if (!empty($this->selectedAppointment)) {
            $found = false;

            foreach ($this->apppointmentList as $appointment) {
                if ($appointment->id == $this->selectedAppointment) {
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $this->selectedAppointment = null;
            }
        }
    }


    public function selectAppointment($id)
    {
        $this->selectedAppointment = $id;
        //$this->apppointment = Appointment::find($id);
    }


    public function submit()
    {

        $this->validate();

        $appointment = Appointment::find($this->selectedAppointment);

        if (! $appointment) {
            $this->addError('selectedAppointment', 'La cita seleccionada no existe.');
            return;
        }

        // Verificar si ya hay otra cita para este email en la misma fecha
        $existing = Appointment::where('requester_email', $this->form['requester_email'])
            ->where('date', $appointment->date)
            ->where('id', '!=', $appointment->id)
            ->first();

        if ($existing) {
            session()->flash(
                'error',
                "Ya existe una cita seleccionada para este día con este correo. 
                Para poder seleccionar esta debes cancelar la existente."
            );
            return;
        }

        $country = Country::find($this->phoneCode);
        // Actualizar cita
        $appointment->update([
            'item_id'  => $this->form['item_id'],
            'requester_name'  => $this->form['requester_name'],
            'requester_phone' => "+" . $country->phonecode . $this->form['requester_phone'],
            'requester_email' => $this->form['requester_email'],
            'comments'        => $this->form['comments'],
            'status'          => 'pending_confirmation',
        ]);

        // Mensaje dinámico
        $fecha = $appointment->date->format('d/m/Y');
        $horaInicio = $appointment->start_time->format('H:i');
        $horaFin = $appointment->end_time->format('H:i');

        session()->flash(
            'success',
            "Acabas de seleccionar una cita para el día {$fecha} de {$horaInicio} a {$horaFin}. 
        A continuación recibirás un correo electrónico con los datos de tu cita."
        );

        $this->showForm = false;
        $this->sendMail();
    }


    public function initCalendar()
    {
        $this->dispatchBrowserEvent('init-calendar');
    }

    public function render()
    {
        // Cargar citas inicialmente
        $this->loadAppointments();
        $this->selectedItem = Item::find($this->form["item_id"]);
        $this->selecteOtherdItem = Item::find($this->other_item_id);
        // dd($this->selectedItem);
        // dd($this->selectedAppointment);
        return view('livewire.front-booking');
    }
}
