{{-- @dd($generalSettings) --}}
@if($generalSettings->allow_appointment)
    <section class="booking-section section-padding bg-dark" id="booking_section" >
    <div class="container text-center text-white">
        <h2 class="mb-3" style="color:white">¿Quieres reservar tu cita?</h2>
        <p class="mb-4 text-cita">
            Hazlo de forma rápida y sencilla. Solo pulsa el botón y concierta tu cita en unos segundos.
            </p>

            <button class="btn btn-cita px-4 py-2 rounded-pill">
            Concertar cita
            </button>
    </div>
    </section>
    
@endif


{{-- <section class="booking-section section-padding" id="booking-section">
    <div class="container">
        <div class="row">

            <div class="col-lg-10 col-12 mx-auto">
                <form action="#" method="post" class="custom-form booking-form" id="bb-booking-form" role="form">
                    <div class="text-center mb-5">
                        <h2 class="mb-1">Book a seat</h2>

                        <p>Please fill out the form and we get back to you</p>
                    </div>

                    <div class="booking-form-body">
                        <div class="row">

                            <div class="col-lg-6 col-12">
                                <input type="text" name="bb-name" id="bb-name" class="form-control" placeholder="Full name" required="">
                            </div>

                            <div class="col-lg-6 col-12">
                                <input type="tel" class="form-control" name="bb-phone" placeholder="Mobile 010-020-0340" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required="">
                            </div>
                        
                            <div class="col-lg-6 col-12">
                                <input class="form-control" type="time" name="bb-time" value="18:30">
                            </div>

                            <div class="col-lg-6 col-12">
                                <select class="form-select form-control" name="bb-branch" id="bb-branch" aria-label="Default select example">
                                    <option selected="">Select Branches</option>
                                    <option value="Grünberger">Grünberger</option>
                                    <option value="Behrenstraße">Behrenstraße</option>
                                    <option value="Weinbergsweg">Weinbergsweg</option>
                                </select>

                            </div>
                            <div class="col-lg-6 col-12">
                                <input type="date" name="bb-date" id="bb-date" class="form-control" placeholder="Date" required="">
                            </div>

                            <div class="col-lg-6 col-12">
                                <input type="number" name="bb-number" id="bb-number" class="form-control" placeholder="Number of People" required="">
                            </div>
                        </div>

                        <textarea name="bb-message" rows="3" class="form-control" id="bb-message" placeholder="Comment (Optionals)"></textarea>

                        <div class="col-lg-4 col-md-10 col-8 mx-auto">
                            <button type="submit" class="form-control">Submit</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
    </div>
</section> --}}