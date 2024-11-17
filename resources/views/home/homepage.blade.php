@push('title')
    <title> Home | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/homestyle.css') }}">
@endpush
@push('font')
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@900&display=swap" rel="stylesheet">
@endpush
@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
    <script type="module" src= "{{ asset('/js/fireBase/trial.js') }}" defer> </script>
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

                
                <div class="col-12 col-sm-9 col-md-4 col-lg-3 card d-flex align-items-center homeCard clickCard" data-href={{'/order/step1'}}>

                    <ion-icon name="calendar" class="gp-icon mt-4"></ion-icon>
                    <h4 class="text-center mt-3 poppins" > New Shipment </h4>
                    <div class="card-body">
                      <p class="card-text text-center"> Book an Order</p>
                    </div>
                </div>


                <div class="col-12 col-sm-9 col-md-4 col-lg-3 card d-flex align-items-center homeCard clickCard" data-href={{'/quote'}}>

                    <ion-icon name="cash" class="gp-icon mt-4"></ion-icon>
                    <h4 class="text-center mt-3 poppins" > Get a Quote </h4>
                    <div class="card-body">
                      <p class="card-text text-center"> Get an Accurate Pricing </p>
                    </div>
                </div>

                <div class="col-12 col-sm-9 col-md-4 col-lg-3 card d-flex align-items-center homeCard clickCard"
                data-href={{'/profile'}}>

                    <ion-icon name="receipt" class="gp-icon mt-4"></ion-icon>
                    <h4 class="text-center mt-3 poppins" > Monitor Orders </h4>
                    <div class="card-body">
                      <p class="card-text text-center"> Keep Track of your Shipments </p>
                    </div>
                </div>

                </div>
                  
            </div>
        </div>
    </section>

    <div style="height: 250px;"></div>

    <div class="spacingCards"></div>

</x-layout>

<script>
    $(function() {

        // For clicking each order row
        $(".clickCard").click(function() {
            window.location = $(this).data("href");
        });

    })
</script>
