<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Country;
use App\Models\Item;
use Livewire\Component;

class FrontBooking extends Component
{
    public $name;
    public $email;
    public $phone;
    public $date;
    public  Appointment $Apppointment;
    public $message;
    public $showItems = [];
    public $showItemsOthers = [];
    public $countries = [];
    public $phoneCode = 207;
    public $generalSettings; // <--- Recibirá la configuración
    protected $rules = [
        'Apppointment.item_id' => 'required|integer|exists:items,id',
        'Apppointment.requester_name' => 'required|string|min:3',
        'Apppointment.requester_phone' => 'required|string|min:3',
        'Apppointment.email' => 'required|email',
        'date' => 'required|date|after_or_equal:today',
        'Apppointment.comments' => 'nullable|string',
    ];
    protected $messages = [
        'Apppointment.item_id.required' => 'Debes seleccionar un servicio.',
        'Apppointment.item_id.integer' => 'El valor seleccionado no es válido.',
        'Apppointment.item_id.exists' => 'El servicio seleccionado no existe.',

        'Apppointment.requester_name.required' => 'El nombre es obligatorio.',
        'Apppointment.requester_name.string' => 'El nombre debe ser texto.',
        'Apppointment.requester_name.min' => 'El nombre debe tener al menos 3 caracteres.',

        'Apppointment.requester_phone.required' => 'El teléfono es obligatorio.',
        'Apppointment.requester_phone.string' => 'El teléfono debe ser texto.',
        'Apppointment.requester_phone.min' => 'El teléfono debe tener al menos 3 caracteres.',

        'Apppointment.email.required' => 'El correo electrónico es obligatorio.',
        'Apppointment.email.email' => 'Debes ingresar un correo electrónico válido.',

        'date.required' => 'La fecha es obligatoria.',
        'date.date' => 'Debes ingresar una fecha válida.',
        'date.after_or_equal' => 'La fecha no puede ser anterior a hoy.',

        'Apppointment.comments.string' => 'Los comentarios deben ser texto.',
    ];


    public function mount()
    {
        $this->Apppointment = new Appointment();
        $this->showItems = Item::showBooking()->orderBy('price', 'asc')->get();
        $this->showItemsOthers = Item::showBookingOthers()->orderBy('price', 'asc')->get();
        $this->countries = Country::activos()
            ->select('id', 'name', 'phonecode')
            ->orderBy('name', 'asc')
            ->get();

        $this->phoneCode = 207;
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
        return view('livewire.front-booking');
    }
}
