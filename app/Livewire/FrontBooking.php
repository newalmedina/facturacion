<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Country;
use App\Models\Item;
use Carbon\Carbon;
use Livewire\Component;

class FrontBooking extends Component
{
    public $name;
    public $email;
    public $phone;
    public $date;
    public  Appointment $apppointment;
    public $message;
    public $showItems = [];
    public $apppointmentList = [];
    public $showItemsOthers = [];
    public $countries = [];
    public $phoneCode = 207;
    public $generalSettings; // <--- Recibirá la configuración
    protected $rules = [
        'form.item_id' => 'required|integer|exists:items,id',
        'form.requester_name' => 'required|string|min:3',
        'form.requester_phone' => 'required|string|min:3',
        'form.email' => 'required|email',
        'date' => 'required|date|after_or_equal:today',
        'form.comments' => 'nullable|string',
    ];
    protected $messages = [
        'form.item_id.required' => 'Debes seleccionar un servicio.',
        'form.item_id.integer' => 'El valor seleccionado no es válido.',
        'form.item_id.exists' => 'El servicio seleccionado no existe.',

        'form.requester_name.required' => 'El nombre es obligatorio.',
        'form.requester_name.string' => 'El nombre debe ser texto.',
        'form.requester_name.min' => 'El nombre debe tener al menos 3 caracteres.',

        'form.requester_phone.required' => 'El teléfono es obligatorio.',
        'form.requester_phone.string' => 'El teléfono debe ser texto.',
        'form.requester_phone.min' => 'El teléfono debe tener al menos 3 caracteres.',

        'form.email.required' => 'El correo electrónico es obligatorio.',
        'form.email.email' => 'Debes ingresar un correo electrónico válido.',

        'date.required' => 'La fecha es obligatoria.',
        'date.date' => 'Debes ingresar una fecha válida.',
        'date.after_or_equal' => 'La fecha no puede ser anterior a hoy.',

        'form.comments.string' => 'Los comentarios deben ser texto.',
    ];


    public function mount()
    {
        $this->apppointment = new Appointment();

        $this->date =  Carbon::now()->format("Y-m-d");



        $this->showItems = Item::showBooking()->orderBy('price', 'asc')->get();
        $this->showItemsOthers = Item::showBookingOthers()->orderBy('price', 'asc')->get();
        $this->countries = Country::activos()
            ->select('id', 'name', 'phonecode')
            ->orderBy('name', 'asc')
            ->get();

        $this->phoneCode = 207;
    }

    public function selectAppointment($id)
    {
        $this->apppointment = Appointment::find($id);
        dd($this->apppointment);
    }
    public function submit()
    {
        $this->validate();

        // Aquí guardarías en BD, mandarías correo, etc.
        // Ejemplo: Appointment::create([...]);

        session()->flash('success', '¡Tu cita ha sido registrada correctamente!');

        // Limpiar campos 
        $this->reset(['name', 'email', 'phone', 'date', 'message']);
    }

    public function render()
    {


        $this->apppointmentList = Appointment::active()
            ->where("date", $this->date)
            ->statusAvailable()
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

            
        return view('livewire.front-booking');
    }
}
