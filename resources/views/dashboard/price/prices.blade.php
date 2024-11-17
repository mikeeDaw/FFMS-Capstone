@push('title')
    <title> Pricings | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashPrice.css') }}">
@endpush

@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script type="module" src= "{{ asset('/js/fireBase/dashboard/showPrice.js') }}" > </script>   
@endpush


<x-dashLayout>

    <div id="cover">
        <div id="loaderBox">
            <div class="custom-loader2"></div>
            <span class="poppins fs-4 text-white mt-5"> Please Wait...</span>
        </div>
    </div>

    {{-- Header --}}
    <div class="row g-0 mb-4">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-4">
                <p class="fs-4 poppins m-0"> Quotation Prices </p>
            </div>
            <button type="button" class="btn btn-primary fs-12 poppins" id="addVehicle"> + Add Vehicle Type </button>
            <button type="button" class="ms-3 btn btn-primary fs-12 poppins" id="addPackaging"> + Add Packaging Type </button>
        </div>
    </div>

    <div id="prcOverlay" class="m-0"> </div>

    {{-- Service and Packaging --}}
    <div class="row g-0 justify-content-around mb-4">

        <div class="col-12 col-md-6 boxes mb-4 mb-md-0 shadow2 rounded-4">
            <div class="cardtitle poppins mb-3 mt-2">
                <div class="d-flex justify-content-between">
                    <div> SERVICE TYPE COST </div>
                    <a href="/dash/prices/service"> <button class="btn btn-primary editor"> Edit </button> </a>
                </div> 
                
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                  <tr class="table-dark poppins fw-medium">
                    <td class="w-50 text-start">Service Type</th>
                    <td class="w-50 text-center">Cost</th>
                  </tr>
                </thead>
                <tbody id='servTbl'>
       
                </tbody>
              </table>
        </div>

        <div class="col-12 col-md-5 boxes shadow2 rounded-4">
            <div class="cardtitle poppins mb-3 mt-2"> 
                <div class="d-flex justify-content-between">
                    <div> PACKAGING TYPE </div>
                    <a href="/dash/prices/package"> <button class="btn btn-primary editor"> Edit </button> </a>
                </div>  
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                  <tr class="table-dark poppins fw-medium">
                    <td class="w-50 text-start">Service Type</th>
                    <td class="w-50 text-center">Cost</th>
                  </tr>
                </thead>
                <tbody id="packTbl">

                </tbody>
              </table>
        </div>

    </div>

    {{-- Gas and Charges --}}
    <div class="row g-0 justify-content-around mb-4">

        <div class="col-12 col-md-6 boxes mb-4 mb-md-0 shadow2 rounded-4">
            <div class="cardtitle poppins mb-3 mt-2">
                <div class="d-flex justify-content-between">
                    <div> SERVICE PER KM COST </div>
                    <a href="/dash/prices/gas"> <button class="btn btn-primary editor"> Edit </button> </a>
                </div>   
            </div>

            <table class="table table-striped table-bordered ">
                <thead>
                  <tr class="table-dark poppins fw-medium">
                    <td class="w-50 text-start">Service Type</th>
                    <td class="w-50 text-center">Cost</th>
                  </tr>
                </thead>
                <tbody id="perKmTbl">

                </tbody>
              </table>
        </div>

        <div class="col-12 col-md-5 boxes shadow2 rounded-4">
            <div class="cardtitle poppins mb-3 mt-2">
                <div class="d-flex justify-content-between">
                    <div> CHARGES </div>
                    <a href="/dash/prices/charges"> <button class="btn btn-primary editor"> Edit </button> </a>
                </div>  
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                  <tr class="table-dark poppins fw-medium">
                    <td class="w-50 text-start">Charge</th>
                    <td class="w-50 text-center">Percentage</th>
                  </tr>
                </thead>
                <tbody id='chrgTbl'>

                </tbody>
              </table>
        </div>

    </div>

    {{-- Add Vehicles Dialog --}}
    <div class="col-10 col-md-8 col-lg-6 col-xl-5 m-0" id="prcServDialog">
        {{-- Top Section --}}
        <div class="row gx-0 mb-4">
            <div class="position-relative">
                <span class="poppins fw-medium" style="font-size: 18px;"> Add a Service Type </span>
                <div class="position-absolute" id='closeServ' style="top: -3px; right: -1px; cursor: pointer;">
                    <ion-icon name="close" class="fs-4 d-block"> </ion-icon>
                </div>
            </div>
        </div>
        {{-- Content --}}
        <div class="row gx-0">
            <div class="d-flex position-relative" style="height: 320px; overflow: auto;" >
                <form action="/dash/addService" method="post" class="w-100">
                    @csrf

                    {{-- Fields Row 1 --}}
                    <div class="row gx-0 prcGap">
                        <div class="col-md-6 pe-2">
                            <div class="floats">   
                                <input type="text" name='servType' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servType">
                                <label for="servType">Service Type *</label>
                            </div>
                            @error('servType')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6 pe-2">
                            <div class="floats">   
                                <input type="text" name='servCode' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servCode">
                                <label for="servCode">Service Type Code *</label>
                            </div>
                            @error('servCode')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Fields Row 2 --}}
                    <div class="row gx-0 mt-2 prcGap">
                        <div class="col-md-6 pe-2">
                            <div class="floats">   
                                <input type="text" name='servChrg' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servChrg">
                                <label for="servChrg">Service Charge *</label>
                            </div>
                            @error('servChrg')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6 pe-2">
                            <div class="floats">   
                                <input type="text" name='servKM' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servKM">
                                <label for="servKM">Per KM Cost *</label>
                            </div>
                            @error('servKM')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Cargo Details Header --}}
                    <div class="row gx-0 mt-4">
                        <span class="poppins text-secondary"> Cargo Space Dimensions & Weight</span>
                    </div>
                    {{-- Cargo Dimensions --}}
                    <div class="row gx-0 mt-2 prcGap">
                        <div class="col-md-4 pe-2">
                            <div class="floats">   
                                <input type="text" name='servLg' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servLg">
                                <label for="servLg">Length (m) *</label>
                            </div>
                            @error('servLg')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-4 pe-2">
                            <div class="floats">   
                                <input type="text" name='servWd' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servWd">
                                <label for="servWd">Width (m) *</label>
                            </div>
                            @error('servWd')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-4 pe-2">
                            <div class="floats">   
                                <input type="text" name='servHg' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servHg">
                                <label for="servHg">Height (m) *</label>
                            </div>
                            @error('servHg')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Cargo Max Weight --}}
                    <div class="row gx-0 mt-2 prcGap">
                        <div class="col-md-12 pe-2">
                            <div class="floats">   
                                <input type="text" name='servWeight' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="servWeight">
                                <label for="servWeight"> Max Weight (kg) *</label>
                            </div>
                            @error('servWeight')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                
            </div>
        </div>

        {{-- Bottom Buttons --}}
        <div class="row gx-0 mt-3">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger fs-14 py-1 me-3" id="closeServ2">Close</button>
                <button class="btn btn-primary fs-14 py-1" id="closeServ2">Create</button>
            </div>
        </div>
        </form>
    </div>

    {{-- Add Packaging Dialog --}}
    <div class="col-10 col-md-8 col-lg-6 col-xl-5 m-0" id="prcPackDialog">
        {{-- Top Section --}}
        <div class="row gx-0 mb-3">
            <div class="position-relative">
                <span class="poppins fw-medium" style="font-size: 18px;"> Add a Packaging Type </span>
                <div class="position-absolute" id='closePack' style="top: -3px; right: -1px; cursor: pointer;">
                    <ion-icon name="close" class="fs-4 d-block"> </ion-icon>
                </div>
            </div>
        </div>
        {{-- Content --}}
        <div class="row gx-0">
            <div class="d-flex position-relative" style="height: 320px; overflow: auto;" >
                <form action="/dash/addPackage" method="post" class="w-100">
                    @csrf

                    {{-- Fields Row 1 --}}
                    <div class="row gx-0 prcGap">
                        <div class="col-md-12 pe-2">
                            <div class="floats">   
                                <input type="text" name='pckType' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="pckType">
                                <label for="pckType">Packaging Type *</label>
                            </div>
                            @error('pckType')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Fields Row 2 --}}
                    <div class="row gx-0 mt-2 prcGap">
                        <div class="col-md-12 pe-2">
                            <div class="floats">   
                                <input type="text" name='pckCode' placeholder=" " value="{{old('fname')}}" autocomplete="off" id="pckCode">
                                <label for="pckCode">Packaging Type Code *</label>
                            </div>
                            @error('pckCode')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Packaging Cost --}}
                    <div class="row gx-0 mt-2 prcGap">
                        <div class="col-md-12 pe-2">
                            <div class="floats">   
                                <input type="text" name='pckCost' placeholder=" " value="{{old('fname')}}" autocomplete="off" id='pckCost'>
                                <label for="pckCost"> Cost *</label>
                            </div>
                            @error('pckCost')
                            <p class="v-err"> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                
            </div>
        </div>

        {{-- Bottom Buttons --}}
        <div class="row gx-0 mt-3">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger fs-14 py-1 me-3" id="closePack2">Close</button>
                <button class="btn btn-primary fs-14 py-1" id="closeServ2">Create</button>
            </div>
        </div>
        </form>
    </div>

</x-dashLayout>

<script>

    $(function() {

        var prcOverlay = $('#prcOverlay');

        var servDialog = $('#prcServDialog');
        var closeServ = $('#closeServ');
        var servOpen = $('#addVehicle');
        var closeServ2 = $('#closeServ2');

        var forServTyp = [servOpen, closeServ, closeServ2];

        forServTyp.forEach( (elem) => {

            elem.on('click', function() {
                prcOverlay.toggleClass('active');
                servDialog.toggleClass('active');

            })
        })

        var packDialog = $('#prcPackDialog');
        var closePack = $('#closePack');
        var packOpen = $('#addPackaging');
        var closePack2 = $('#closePack2');

        var forPackTyp = [packOpen, closePack, closePack2];

        forPackTyp.forEach( (elem) => {

            elem.on('click', function() {
                prcOverlay.toggleClass('active');
                packDialog.toggleClass('active');

            })
        })


    })

</script>