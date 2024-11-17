@push('title')
    <title> Vehicles | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashUser.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/formfields.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script> 
@endpush

@php

@endphp
<x-dashLayout>

    {{-- Header --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-4">
                <p class="fs-4 poppins m-0"> Vehicles </p>
            </div>
            <button type="button" class="btn btn-primary addUsr" data-add-user>
                +Add Vehicle
            </button>
        </div>
    </div>
    
    {{-- Vehicle Counts --}}
    <div class=" g-0 row u-summ bot-margin rounded-4 shadow">
        <div class="col-12 d-flex flex-column p-0">
            <div class="hrcol pb-3 ps-4 poppins"> {{ $vehiNames[$servTyp]['name']." Summary" }} </div>
            <div class="d-flex flex-row align-items-center flex-wrap  justify-content-between summ">

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    
                    <ion-icon class="me-3 text-info" name="speedometer"></ion-icon>
                    
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Available Vehicles</small>
                        <h5>{{ count($AvVehicles) }}</h5>
                    </div>
                </div>

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-warning" name="timer"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Unavailable Vehicles</small>
                        <h5>{{ count($UnVehicles) }}</h5>
                    </div>
                    
                </div>

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-success" name="layers"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Total Vehicles</small>
                        <h5>{{ count($AvVehicles) + count($UnVehicles) }}</h5>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="g-0 row pt-3">
        <div class="d-flex flex-row mb-3 align-items-center bg-white w-auto ps-3 rounded-2 filterBox">

            <div class="poppins me-3 d-flex align-items-center">
                <ion-icon name="filter-outline"></ion-icon>
                <span class="ms-2 poppins"> Filter </span>
            </div>

            <select class="form-select shFilter poppins" id="filterer" aria-label="Default select example">
                @foreach ($vehiNames as $key => $car)
                    <option value="{{ $key }}" {{ $servTyp == $key ? 'selected' : '' }}>
                        {{ $car['name'] }}
                    </option>
                @endforeach
                {{-- <option value="TractorHead" {{ $servTyp == 'TrackHead' ? 'selected' : '' }}>
                    Tractor Head
                </option>
                <option value="40ftChassis" {{ $servTyp == 'Chassis40' ? 'selected' : '' }}>
                    40ft Chassis
                </option>
                <option value="20ftChassis" {{ $servTyp == 'Chassis20' ? 'selected' : '' }}>
                    20ft Chassis
                </option>
                <option value="10WTruck" {{ $servTyp == 'Truck10W' ? 'selected' : '' }}>
                    10-Wheeler Truck
                </option>
                <option value="ClosedVan6" {{ $servTyp == 'ClVan6' ? 'selected' : '' }}>
                    6-Wheeler Closed Van
                </option>
                <option value="ClosedVan4" {{ $servTyp == 'ClVan4' ? 'selected' : '' }}>
                    4-Wheeler Closed Van
                </option>
                <option value="WingVan" {{ $servTyp == 'WingVan' ? 'selected' : '' }}>
                    Wing Van
                </option> --}}
            </select>

        </div>



    </div>

    <form action="/vehicles/updAvail/{{$servTyp}}" method="post">
        @csrf

    {{-- Available Table --}}
    <div class="row g-0 u-summ tablewrapper bot-margin shadow rounded-4">
        <div class="poppins mb-3 cardtitle"> 
            {{ 'AVAILABLE VEHICLES' }}
        </div>
        @if (count($AvVehicles) != 0)
        <div class="col-12">
            <div class="table-responsive tblHeight">

                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> </th>
                            <th> Plate No.</th>
                            <th> Last Used </th>
                            <th class="text-center"> Change Availability </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($AvVehicles as $vehicle)
                            <tr>
                                <td> {{ $loop->iteration}} </td>
                                <td> {{ $vehicle['vehicleID'] }} </td>
                                <td> {!! $vehicle['last_used'] ?? "<i class='text-secondary'> none </i>" !!} </td>
                                <td class="d-flex justify-content-center"> 
                                    <div class="form-check">
                                        <input class="form-check-input shChecker" type="checkbox" name='toUnav[]' id={{"toUnav".$loop->iteration}}  value= {{ $vehicle['vehicleID'] }} >
                                        <label class="form-check-label shChckLbl" for={{"toUnav".$loop->iteration}}>
                                            Unavailable
                                        </label>
                                    </div>    
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            <x-table-nodata />
        @endif
    </div>

    {{-- Unavailable Table --}}
    <div class="row g-0 u-summ tablewrapper bot-margin shadow rounded-4">
        <div class="poppins mb-3 cardtitle"> 
            {{ 'UNAVAILABLE VEHICLES' }}
        </div>
        @if (count($UnVehicles) != 0)
        <div class="col-12">
            <div class="table-responsive tblHeight">

                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> </th>
                            <th> Plate No.</th>
                            <th> Last Used </th>
                            <th class="text-center"> Change Availability </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($UnVehicles as $vehicle)
                            <tr>
                                <td> {{ $loop->iteration}} </td>
                                <td> {{ $vehicle['vehicleID'] }} </td>
                                <td> {!! $vehicle['last_used'] ?? "<i class='text-secondary'> none </i>" !!} </td>
                                <td class="d-flex justify-content-center"> 
                                    <div class="form-check">
                                        <input class="form-check-input shChecker" type="checkbox" name='toAvail[]' id={{"toAvail".$loop->iteration}}  value= {{ $vehicle['vehicleID'] }} >
                                        <label class="form-check-label shChckLbl" for={{"toAvail".$loop->iteration}}>
                                            Available
                                        </label>
                                    </div>    
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            <x-table-nodata />
        @endif
    </div>

    {{-- Submit Button --}}
    <div class="d-flex position-fixed align-items-center rounded-3 shUpd">
        <span class="poppins fw-medium" style="white-space: nowrap;"> Update Availability </span>
        <button class="btn btn-primary ms-3" style="padding: 4px 15px;"> Save </button>
    </div>

    </form>

    <div class="overlay" data-overlay> </div>


    {{-- Add vehicle form --}}
    <div class="col-lg-5 col-9 col-sm-6 col-md-5  formWrapper" data-form-add>
        <div class="mb-4 position-relative">
            <div>
            <span class="d-block text-start fs-5 fw-medium poppins "> New Vehicle </span>
            <span class="d-block text-start" style="font-size:0.85rem;"> * Indicates a Required Field.</span>
            </div>
            <div class="closeBtn position-absolute" data-close-btn2 > <ion-icon name="close" class="d-block fs-4"></ion-icon> </div>
        </div>
        <form action="/vehicles/create" method="post">
            @csrf
            {{-- Form Fields --}}
            <div class="row g-0 mb-3">
                <select class="form-select poppins py-3" name='vehiType' aria-label="Default select example">
                    <option disabled selected>Select Vehicle Type *</option>
                    <option value="TrackHead">Tractor Head</option>
                    <option value="Chassis40">40ft Chassis</option>
                    <option value="Chassis20">20ft Chassis</option>
                    <option value="Truck10W">10 Wheeler Truck</option>
                    <option value="ClVan6">6 Wheeler Closed Van</option>
                    <option value="ClVan4">4 Wheeler Closed Van</option>
                    <option value="WingVan">Wing Van</option>
                  </select>
                  @error('vehiType')
                  <p class="v-err"> {{ $message }}</p>
                  @enderror
            </div>

            <div class="row g-0">
                <div class="col-12 ps-0">
                    <div class="float">   
                    <input type="text" name='plate' id='plate' placeholder=" " value="{{old('plate')}}" autocomplete="off">
                    <label for="plate">Plate No. *</label>
                    </div>
                    {{-- Validation Error Message --}}
                    @error('plate')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex flex-row justify-content-end mt-5 gap13">
                <input type="submit" class="btn btn-primary w-25 bot-btn">
                <button type="button" class="btn btn-danger bot-btn" data-close-btn > Cancel </button>
            </div>
            
        </form>
    </div>


</x-dashLayout>

<!-- Script -->
<script src="{{ asset('/js/overlay/overlay.js') }}"></script>

<script>
    $(function() {

        // For Filtering
        $('#filterer').change(function(){
            var selValue = $(this).find(':selected').val();
            window.location = '/vehicles/?type=' + selValue;
        })

        // Script for Update Status button
        $(':checkbox').change(function(){

            if($(':checkbox:checked').length == 0){
                $('.shUpd').css('top', '-65px');
            } else {
                $('.shUpd').css('top', '60px');
            }
        })
    })
</script>