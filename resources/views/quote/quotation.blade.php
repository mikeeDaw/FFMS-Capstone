@push('title')
    <title> Quotation | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/quotestyle.css') }}">
@endpush

@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    {{-- <script src="{{ asset('/js/distanceCalc/lrm-graphhopper.js') }}" defer></script> --}}
    {{-- <script src="{{ asset('/js/distanceCalc/distance.js') }}" defer></script> --}}
    <script type="module" src= "{{ asset('/js/fireBase/orders/quotation.js') }}"> </script>
    
@endpush

@push('csrf')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush


<x-layout>

    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>

    <div id="loadOverlay"> </div>

    <div id="loadArea">
        <div id="boxLoad">
            <div class="custom-loader2"></div>
            <span class="poppins mt-4 text-center"> Calculating Cost...</span>
        </div>
    </div>

    <div id="map" class="d-none"></div>

    <div class="container mt-3 pb-5 mb-5">
        <div class="col-12 px-4 pe-lg-0 ms-md-3 ms-lg-5">
            <div class="row justify-content-start">
                {{-- Header --}}
                <div class="col-lg-7 col-12 my-3">
                    <h3> Get a Quote </h3>
                </div>
                {{-- Form Fields --}}
                <div class="col-lg-8 col-xl-7 col-12 quoteBox p-4">
                    <form action="/quote/calc" method="post" id='quoteForm'>
                        @csrf
                        <span class="d-block poppins fst-italic fs-14 text-danger opacity-50 mb-3">
                            Required Field *
                        </span>
                        <span class="formLbl ps-1 my-0">Address</span>
                        <span class="d-block text-secondary fs-14 poppins mb-3"> *The system only accepts orders to locations within Metro Manila. </span>

                        <div class="row gx-3 mb-1">
                            <div class="col-md-6 col-12 position-relative mb-1">
                                <input class="w-100 text-secondary" type="text" name="province" placeholder=" " value='Metro Manila' readonly>
                                <label for="city"> Province </label>
                            </div>
                            
                            <div class="col-md-6 col-12 position-relative mb-1">
                                <input class='w-100' type="text" name='zipcode' placeholder=" " style="background:#b3b3b347; pointer-events: none;" value="" id="zipcode" readonly>
                                <label for="zipcode"> Zipcode </label>
                                @error('zipcode')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row gx-3 mb-1">
                            <div class="col-md-6 col-12 position-relative mb-1">
                                <select class="form-select form-select qt-select" aria-label=".form-select example" name='city' id='city' required>
                                    <option selected disabled value=""> City * </option>
                                    <option value="" disabled> Please Wait... </option>
                                  </select>
                                @error('city')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12 position-relative mb-1">
                                <select class="form-select form-select qt-select" aria-label=".form-select example" name='barang' id='barang' required>
                                    <option selected disabled value=""> Barangay * </option>
                                    <option value="" disabled> Select a City </option>
                                  </select>
                                @error('barang')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row gx-3 mb-1">
                            <div class="col-12 position-relative mb-1">
                                <input class="w-100" type="text" value="{{old('street') }}"name="street" placeholder=" " id='street' autocomplete="off">
                                <label for="street"> Street Address </Address> </label>
                            </div>
                        </div>

                        <span class="formLbl ps-1">Package Details</span>

                        <div class="row gx-3 mb-1">
                            <div class="col-md-3 col-6 position-relative mb-1">
                                <input class="w-100" type="text" name="weight" id='weight' placeholder=" " value="{{old('weight') }}"autocomplete="off">
                                <label for="weight"> Weight(kg)* </label>
                                @error('weight')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-3 col-6 position-relative mb-1">
                                <input class="w-100" type="text" name="length" id="length" placeholder=" " value="{{old('length') }}"autocomplete="off">
                                <label for="length"> Length(cm) </label>
                                @error('length')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-3 col-6 position-relative mb-1">
                                <input class="w-100" type="text" name="width" id='width' placeholder=" " value="{{old('width') }}"autocomplete="off">
                                <label for="width"> Width(cm) </label>
                                @error('width')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-3 col-6 position-relative mb-1">
                                <input class="w-100" type="text" name="height" id='height' placeholder=" " value="{{old('height') }}"autocomplete="off">
                                <label for="height"> Height(cm) </label>
                                @error('height')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        
                        </div>

                        <div class="row gx-3 mb-1">
                            <div class="col-md-6 col-12 position-relative mb-1">
                                <input class="w-100" type="text" name="qty" placeholder=" " value="{{old('qty') }}"autocomplete="off">
                                <label for="city"> Package Quantity *</label>
                                @error('qty')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6 col-12 position-relative mb-1">
                                <input class='w-100' type="text" name='value' placeholder=" " value="{{old('value') }}" id='value' autocomplete="off">
                                <label for="value"> Value of Goods (Php) * </label>
                                @error('value')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row gx-3 mb-1 mt-2">
                            <input type="hidden" name="service" value="" id='service'>
                            {{-- <div class="col-md-6 col-12 position-relative mb-1">
                                <select class="form-select form-select qt-select" aria-label=".form-select example" name='service' required>
                                    <option selected disabled value=""> Service Type * </option>
                                    <option value="TrackHead">Tractor Head</option>
                                    <option value="Chassis20">20ft Chassis</option>
                                    <option value="Chassis40">40ft Chassis</option>
                                    <option value="Truck10W">10 Wheeler Truck</option>
                                    <option value="WingVan">Wing Van</option>
                                    <option value="ClVan4">4 Wheeler Closed Van</option>
                                    <option value="ClVan6">6 Wheeler Closed Van</option>
                                  </select>
                                @error('service')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div> --}}
                            <div class="col-12 position-relative mb-1">
                                <select class="form-select form-select qt-select" aria-label=".form-select example"
                                name='package' required>
                                    <option selected disabled value=""> Packaging Type *</option>
                                    <option value="Box10">10kg Box</option>
                                    <option value="Box25">25kg Box</option>
                                    <option value="Envelope">Envelope</option>
                                    <option value="ReusePak">Reusable Pak</option>
                                    <option value="Tube">Tube</option>
                                </select>
                                @error('package')
                                    <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name='distance' value=''>
                        <input type="hidden" name="route" value=''/>

                            <button id="getQT" {{--type="button"--}}class="mt-4 d-flex flex-row align-items-center gap-1 quote-btn"> 
                                <p class="m-0" style="font-size: 13px;"> Get Quote </p>
                                <ion-icon name="chevron-forward"></ion-icon>
                            </button>
                            
                    </form>
                </div>
            </div>
        </div>

        {{-- Quotation Price --}}
        @if (session('total'))

        <div class="col-12 px-4 ps-md-4 pe-md-0 ms-md-3 ms-lg-5 mt-4">
            <div class="row">

                <div class="col-lg-8 col-xl-7 col-12 p-4 priceBox">
                    <div class="row">
                        <h6 class="text-muted poppins prc-title m-0"> Your Quotation</h6>
                    </div>
                    <div class="row px-3 mt-4">
                        <table class="table">
                            <thead>
                              <tr class="table-warning">
                                <th scope="col" class="poppins fw-medium pe-5 w-50 text-end">Charges</th>
                                <th scope="col" class="poppins fw-medium text-center">Cost</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="pe-5 text-end">Freight Charge</td>
                                <td class="text-center">
                                    {{ "Php ".number_format(session('freightChrg'))}}
                                </td>
                              </tr>
                              <tr>
                                <td class="pe-5 text-end">Insurance Fee</td>
                                <td class="text-center">
                                    {{ "Php ".number_format(session('insurFee'))}}
                                </td>
                              </tr>
                              <tr>
                                <td class="pe-5 text-end">Service Charge</td>
                                <td class="text-center">
                                    {{ "Php ".number_format(session('servChrg'))}}
                                </td>
                              </tr>
                              <tr>
                                <td class="pe-5 text-end">Subtotal</td>
                                <td class="text-center">
                                    {{ "Php ".number_format(session('subtotal'))}}
                                </td>
                              </tr>
                              <tr class="table-warning">
                                <td class="pe-5 text-end poppins fw-medium ">Total</td>
                                <td class="text-center fw-medium">
                                    {{ "Php ".number_format(session('total'))}}
                                </td>
                              </tr>
                            </tbody>
                          </table>
                    </div>
                </div>
                            
            </div>
        </div>
        @endif

        {{-- If address not Found --}}
        @if($errors->first('addrErr'))

        <div class="col-12 ps-4 ms-md-3 ms-lg-5 mt-4">
            <div class="row">

                <div class="col-lg-8 col-xl-7 col-12 p-4 errBox">
                    <div class="row">
                        <h6 class="text-muted poppins prc-title m-0"> ERROR ENCOUNTERED</h6>
                    </div>
                    <div class="row px-3 mt-4">
                        <div class="d-flex align-items-center">
                            <ion-icon class="fs-1 me-3" name="telescope-outline"></ion-icon>
                            <span class="fs-5 poppins fw-medium ms-3 reddish"> Destination not found. Check your Address.</span>
                        </div>
                        
                    </div>
                </div>
                            
            </div>
        </div>

        @endif
    </div>



</x-layout>