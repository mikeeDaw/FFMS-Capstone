

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/homestyle2.css') }}">
@endpush
@push('font')
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@900&display=swap" rel="stylesheet">
@endpush

<x-layout>


    <!-- Hero -->
    <section class="landing pt-5" style="background-image: url({{ asset('/images/home/darker.jpg') }})">

        {{-- The Title thing --}}
        <div class="container-md pt-3">
            <div class='row mx-3 d-flex justify-content-center'>

                <div class='col-12 col-sm-10 col-md-9 d-flex justify-content-center flex-column align-items-center'>

                {{-- Truck SVG--}}
                <div>
                    <img src="{{asset('/images/home/Semi-Truck.svg')}}" alt="" style="width:120px">
                </div>

                <span class="poppins fs-14 text-secondary mb-3"> TRANSPORT WITH US </span>

                {{-- Title --}}
                <h1 class="merri text-center col-10 col-xl-8"> 
                    Fast & Secure Delivery for Small and Big Packages                    
                </h2>

                {{-- Book/Signup Button --}}
                <a href="@if(session()->has('user')) /order/step1  @else /register/create  @endif">
                    <button class="signup">
                        <span>
                            @if (!session()->has('user'))
                                Sign Up
                            @else
                                Book Now
                            @endif
                        </span>
                        <ion-icon name="chevron-forward-outline" class="arrow"></ion-icon>
                    </button>
                </a>

                </div>
            </div>      
        </div>

        {{-- Cards --}}
        <div class="row g-0 justify-content-center">
            <div class="col-10 d-flex justify-content-center align-items-center cardGroup">

                <div class="row g-0 w-100 justify-content-evenly">

                <div class="col-12 col-sm-9 col-md-4 col-lg-3 card d-flex align-items-center homeCard">

                    <ion-icon name="calendar" class="gp-icon mt-4"></ion-icon>
                    <h4 class="text-center mt-3 poppins" > New Shipment </h4>
                    <div class="card-body">
                      <p class="card-text"> Book an Order</p>
                    </div>
                  </div>

                  <div class="col-12 col-sm-9 col-md-4 col-lg-3 card d-flex align-items-center homeCard">

                    <ion-icon name="cash" class="gp-icon mt-4"></ion-icon>
                    <h4 class="text-center mt-3 poppins" > Get a Quote </h4>
                    <div class="card-body">
                      <p class="card-text"> Get an Accurate Pricing </p>
                    </div>
                  </div>

                  <div class="col-12 col-sm-9 col-md-4 col-lg-3 card d-flex align-items-center homeCard">

                    <ion-icon name="receipt" class="gp-icon mt-4"></ion-icon>
                    <h4 class="text-center mt-3 poppins" > Shipping Rates </h4>
                    <div class="card-body">
                      <p class="card-text"> Find the Best Offer </p>
                    </div>
                  </div>

                </div>
                  
            </div>
        </div>
    </section>

    <!-- Below Hero -->
    {{-- <div class="container-md my-5 outer">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-10 col-8 inner">
                <div class="card-group cg">
                    <a href= "@if(session()->has('uid')) /order/step1 @else /login/attempt
                         @endif" class="card justify-content-center align-items-center p-3">

                        <ion-icon name="calendar-outline" class="gp-icon mt-4"></ion-icon>

                        <div class="card-body">
                        <h5 class="card-title text-center mb-3" > Shipping </h5>
                        <p class="card-text text-center mb-4 "> Book a shipment that suits your needs </p>
                        </div>
                    </a>
                    <a href="#" class="card justify-content-center align-items-center p-3">
                    <ion-icon name="cash-outline" class="gp-icon mt-4"></ion-icon>
                        <div class="card-body">
                        <h5 class="card-title text-center mb-3" > Payment </h5>
                        <p class="card-text text-center mb-4"> Manage your current or past transactions</p>
                        </div>
                    </a>
                    <a href="#" class="card justify-content-center align-items-center p-3">
                        <ion-icon name="receipt-outline" class="gp-icon mt-4"></ion-icon>
                        <div class="card-body">
                        <h5 class="card-title text-center mb-3">Shipping Rates</h5>
                        <p class="card-text text-center mb-4"> Compare and view pricings and find the best offer for you.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="spacingCards"></div>

</x-layout>

