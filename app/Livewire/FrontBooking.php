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
    public $selectedDate;
    public $apppointment;
    public $showItems = [];
    public $showItemsOthers = [];
    public $apppointmentList = [];
    public $highlightedDates = [];
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

    public function mount()
    {
        $this->apppointment = new Appointment();
        $this->selectedDate = Carbon::now()->format("Y-m-d");

        // Datos estáticos: solo se cargan una vez
        $this->workerlist = User::canAppointment()->get();
        $this->showItems = Item::showBooking()->orderBy('price')->get();
        $this->showItemsOthers = Item::showBookingOthers()->orderBy('price')->get();
        $this->countries = Country::activos()
            ->select('id', 'name', 'phonecode')
            ->orderBy('name')
            ->get();
        $this->highlightedDates = Appointment::active()
            ->where("date", ">=", now()->format('Y-m-d'))
            ->statusAvailable()
            ->distinct("date")
            ->pluck("date")
            ->map(fn($date) => Carbon::parse($date)->format("Y-m-d"))
            ->toArray();

        // Primera carga de citas
        $this->loadAppointments();
    }

    private function loadAppointments()
    {
        $this->apppointmentList = Appointment::active()
            ->with('worker') // 🔥 evita N+1
            ->where("date", $this->selectedDate)
            ->when($this->worker_id, fn($q) => $q->where('worker_id', $this->worker_id))
            ->statusAvailable()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    // ⚡️ Solo cuando cambie worker_id o fecha
    public function updatedWorkerId()
    {
        $this->loadAppointments();
    }
    public function updatedSelectedDate()
    {
        $this->loadAppointments();
    }

    // ⚡️ Solo cuando cambien los items
    public function updatedFormItemId($id)
    {
        $this->selectedItem = Item::find($id);
    }
    public function updatedOtherItemId($id)
    {
        $this->selecteOtherdItem = Item::find($id);
    }

    public function selectAppointment($id)
    {
        $this->selectedAppointment = $id;
    }

    public function submit()
    {
        $this->validate();

        $appointment = Appointment::find($this->selectedAppointment);

        if (! $appointment) {
            $this->addError('selectedAppointment', 'La cita seleccionada no existe.');
            return;
        }

        $existing = Appointment::where('requester_email', $this->form['requester_email'])
            ->where('date', $appointment->date)
            ->where('id', '!=', $appointment->id)
            ->first();

        if ($existing) {
            session()->flash(
                'error',
                "Ya existe una cita para este día con este correo."
            );
            return;
        }

        $country = Country::find($this->phoneCode);

        $appointment->update([
            'item_id'        => $this->form['item_id'],
            'requester_name' => $this->form['requester_name'],
            'requester_phone' => "+" . $country->phonecode . $this->form['requester_phone'],
            'requester_email' => $this->form['requester_email'],
            'comments'       => $this->form['comments'],
            'status'         => 'pending_confirmation',
        ]);

        session()->flash(
            'success',
            "Has reservado la cita correctamente."
        );

        $this->showForm = false;
        $this->sendMail();
    }

    public function sendMail()
    {
        // Aquí deberías pasar el $appointment actualizado,
        // no uno fijo con id=4 👇
        // Mail::to(...)->queue(...);
    }

    public function render()
    {
        return view('livewire.front-booking');
    }
}
