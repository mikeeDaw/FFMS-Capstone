@push('title')
    <title> Create Order - Step 1 | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/orderform.css') }}">
@endpush

@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    {{-- <script src="{{ asset('/js/distanceCalc/lrm-graphhopper.js') }}" defer></script> --}}
    {{-- <script src="{{ asset('/js/distanceCalc/distance.js') }}" defer></script> --}}
    <script type="module" src= "{{ asset('/js/fireBase/orders/orderCreate.js') }}" defer> </script>
    
@endpush

@php
    $customer = session('user');
@endphp

<x-layout>

    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>

    <div id="loadOverlay"> </div>

    <div id="loadArea">
        <div id="boxLoad">
            <div class="custom-loader2"></div>
            <span class="poppins mt-4 text-center"> Verifying Address...</span>
        </div>
    </div>

    <div class="container-md pb-5">

        <div class="row gx-0 justify-content-around align-items-start">

            <!-- Progress Bar -->
            <div class="col-lg-2 col-sm-12">
                {{-- Vert Progress --}}
                <div class="d-flex flex-column mt-3 mt-lg-5 align-items-center align-items-lg-start vertProg">
                    <div class="col-4 col-lg-6 d-flex flex-column align-items-center">
                        <span class="d-block w-100 Vline" style="height: 0.5px;"></span>
                        <span class="d-block position-relative Hline">
                            {{-- What --}}
                            <span class="d-flex align-items-center blockingDesc active">
                                <span class="circlePg active"> 
                                    <ion-icon class="iconPG active" name="compass-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc active">
                                    Where
                                </span>
                            </span>

                            {{-- Where --}}
                            <span class="d-flex align-items-center blockingDesc">
                                <span class="circlePg"> 
                                    <ion-icon class="iconPG" name="cube-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc">
                                    What
                                </span>
                            </span>

                            {{-- Checkout --}}
                            <span class="d-flex align-items-center blockingDesc">
                                <span class="circlePg"> 
                                    <ion-icon class="iconPG" name="card-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc">
                                    Checkout
                                </span>
                            </span>
                        </span>
                        <span class="d-block w-100 rounded-pill Vline"></span>
                    </div>
                </div>

                {{-- Horiz Progress --}}
                <div class="d-flex w-100 justify-content-center align-items-center my-4 horizProg">

                    <div class="d-flex align-items-center">
                        <span class="circlePg active"> 
                            <ion-icon class="iconPG active" name="compass-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc active">
                            Where
                        </span>
                    </div>

                    <span class="d-block vertProgLine"> </span>

                    
                    <div class="d-flex align-items-center">
                        <span class="circlePg"> 
                            <ion-icon class="iconPG" name="cube-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc">
                            What
                        </span>
                    </div>

                    <span class="d-block vertProgLine"> </span>

                    
                    <div class="d-flex align-items-center">
                        <span class="circlePg"> 
                            <ion-icon class="iconPG" name="card-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc">
                            Checkout
                        </span>
                    </div>

                </div>
            </div>

            <!-- Fill Up Form -->
            <div class="col-xl-6 col-lg-7 col-md-10 col-sm-9 pt-4">
            <form class="formBox" action="/order" method="post">
                @csrf
            <!-- Header -->
            <div class="row f-head g-0">
                <div class="col-md-12 pb-1 px-5 pt-3 d-flex align-items-center" style="gap:20px;">
                    <a href="#"> <ion-icon name="location-outline" class="bk-i"></ion-icon> </a>
                    <h5> Receipient Details</h5>
                </div>

            </div>

            <!-- Form Fields -->
            <div class="fillUp pad">

                <!-- Contact Person -->
                <div class="contact">

                    <div class="row justify-content-evenly">
                        <div class=" col-md-5 col-sm-6">
                            <label for="fname"> First Name</label>
                            <input type="text" name="fname" autocomplete="off" value={{ $customer['Fname'] }} readonly>
                            @error('fname')
                                <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-5 col-sm-6">
                            <label for="lname"> Last Name</label>
                            <input type="text" name="lname" autocomplete="off" value={{ $customer['Lname'] }} readonly>
                            @error('lname')
                                <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="row justify-content-evenly">
                        <div class="col-md-5 col-sm-6">
                            <label for="cnum"> Contact Number</label>
                            <input type="text" name="cnum" autocomplete="off" value={{ $customer['Contact'] }} readonly>
                            @error('cnum')
                                <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-5 col-sm-6">
                            <label for="email"> Email Address</label>
                            <input type="text" name="email" value={{ $customer['Email'] }} readonly>
                            @error('email')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Recipient Address -->
                <div class="addr">

                    <div class="row g-0">
                        <span class="col-12 col-md-10 col-xl-8 poppins text-secondary fs-14 px-2 px-md-4 mx-1 mt-0 mb-1 text-danger opacity-75">
                            *The system only accepts orders to locations within Metro Manila.
                        </span>
                    </div>

                    <div class="row justify-content-evenly">

                        <div class="col-md-5 col-sm-6">
                            <label for="province"> Province </label>
                            <input type="text" name="province" id="province" value='Metro Manila' readonly>
                            @error('province')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-5 col-sm-6">
                            <label for="city"> City</label>
                            <select class="form-select" aria-label="Default select example" name="city" id="city" required style='border-color: #696969;'>
                                <option value='' disabled selected hidden> -- Choose a City --</option>
                                <option value='' disabled> - Please Wait - </option>

                            </select>
                            @error('city')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    
                    <div class="row justify-content-evenly">

                        <div class="col-md-5 col-sm-6">
                            <label for="barang"> Barangay</label>
                            <select class="form-select" aria-label="chooseBarang" name="barang" id="barang" required style='border-color: #696969;'>
                                <option value='' disabled selected hidden> -- Choose Barangay --</option>
                                <option value='' disabled> - Select a City - </option>

                            </select>
                            @error('barang')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-5 col-sm-6">
                            <label for="zipcode"> Zip Code</label>
                            <input type="text" name="zipcode" id="zipcode" readonly>
                            @error('zipcode')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="row justify-content-evenly">

                        <div class=" col-sm-12 col-md-11">
                            <label for="street"> Detailed Address</label>
                            <input type="text" name="street" id='street' value="{{session('Consignee')['street'] ?? old('street')}}">
                            @error('street')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>

            </div>

            <hr style="width:90%; margin:auto">

            <!-- Bottom Buttons -->
            <div style="padding: 13px 25px 10px 25px;">

                <div class="row justify-content-between m-auto">

                    <div class="col-4 col-sm-3">
                    <a href="/" class="d-flex align-items-center bck-btn" style="gap:10px;">
                        <ion-icon name="arrow-back-circle-outline" class="bk-i"></ion-icon>
                        <h5 class="m-0 back"> Back</h5>
                    </a>
                    </div>
                    {{-- <input type="hidden" name="distance" value=''/>
                    <input type="hidden" name="route" value=''/> --}}
                    
                    <div class="col-3 d-flex justify-content-end">

                        <button class="d-flex align-items-center cont-btn "> 
                            <span> Next </span>
                            <ion-icon name="chevron-forward-outline"style="transition: .2s linear;">

                            </ion-icon>
                        </button>

                    </div>
                </div>
            </div>
            
            </form>

            @if($errors->first('addrErr'))

                <div class="col-12 mt-4">
                    <div class="row">
        
                        <div class="col-12 p-4 errBox">
                            <div class="row">
                                <h6 class="text-muted poppins prc-title m-0"> ERROR ENCOUNTERED</h6>
                            </div>
                            <div class="row px-3 mt-4">
                                <div class="d-flex align-items-center">
                                    <ion-icon class="fs-1 me-3 reddish" name="telescope-outline"></ion-icon>
                                    <span class="fs-5 poppins fw-medium ms-3 reddish"> Destination not found. Check your Address.</span>
                                </div>
                                
                            </div>
                        </div>
                                    
                    </div>
                </div>
    
            @endif

            </div>

        </div>
    </div>
    
    <div id="map" class="d-none"></div>


</x-layout>

{{-- <script>

    var xswitch = $('#switchBtn').on('change', function() {
        if($(this).is(':checked')){
            $('input[name=fname]').val(fname);
            $('input[name=lname]').val(lname);
            $('input[name=cnum]').val(contact);
            $('input[name=email]').val(email);
        } else{
            $('input[name=fname]').val("");
            $('input[name=lname]').val("");
            $('input[name=cnum]').val("");
            $('input[name=email]').val("");
        }
    });

</script> --}}

