@push('title')
    <title> Driver Profile | Lebria Transport</title>
@endpush

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/profile.css') }}">
@endpush

@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="{{ asset('/js/profile/profile.js') }}" defer></script>
@endpush

<x-dashLayout>
    @php
        $user = session('user');
        $canDrive = [$can_TrackHead];
    @endphp

    {{-- Header --}}
    <div class="row g-0 mb-4">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0 "> User Profile Information </p>
            </div>
        </div>
    </div>

    {{-- Pfp & Some Details--}}
    <div class="row g-0 mb-3 justify-content-between">

        {{-- Profile Box --}}
        <div class="col-12 col-md-3 boxStyle rounded-4">       
            <div class="d-flex flex-column align-items-center">
                <div class="border border-info text-info rounded-circle fs-2 poppins fw-medium mt-4" style="padding: 15px 30px; width: fit-content">
                    {{substr($Fname, 0, 1)}}
                </div>
                <div class="poppins fs-5 mt-3 text-center">
                    {{ $Fname." ".$Lname }}
                </div>
                <div class="poppins text-body-tertiary">
                    @if ($Userlevel == 'User')
                        {{ "Customer" }}
                    @else
                        {{ $Userlevel }} 
                    @endif
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="col-12 col-md-6 d-flex flex-column justify-content-end">
            <div class="d-flex flex-row align-items-center flex-wrap justify-content-between boxStyle py-0">

                <div class="d-flex flex-column flex-grow-1 p-3 justify-content-center align-items-center border-end">
                    <div class="poppins"> Deliveries Done </div>
                    <div class="fw-medium fs-3"> {{ array_sum($vehicles) }} </div>
                </div>

                <div class="d-flex flex-column flex-grow-1 p-3 justify-content-center align-items-center">
                    <div class="poppins"> Overall Rating </div>
                    @if ( $ratings )
                        <div class="fw-medium fs-3 d-flex align-items-center text-primary">
                            {{ round(array_sum($ratings)/count($ratings),2) }}
                            <ion-icon class=" ms-2 fs-3" name="star"></ion-icon>
                        </div>
                    @else
                        <i class="fw-medium fs-4 text-body-tertiary align-items-center poppins"> N/A </i>
                    @endif
                        
                    
                </div>

            </div>
        </div>

    </div>

    {{-- Profile Area --}}
    <div class="col-12 boxStyle mb-4 rounded-4">

        <div class="row gx-0 justify-content-evenly p-4 py-sm-0 px-sm-4">

                {{-- Profile Info --}}
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="border-bottom pb-2 fs-5 d-flex justify-content-between poppins">
                            <div class="text-primary"> User Information </div> 
                            @if ($user['Userlevel'] == "Admin")
                                @if (!$status)
                                <a href={{ "/dashboard/drivers/disable/".$uid }}>
                                <button class="btn btn-danger statusBtn"> Disable</button>
                                </a>
                                @else
                                <a href={{ "/dashboard/drivers/enable/".$uid }}>
                                <button class="btn btn-success statusBtn"> Enable </button>
                                </a>
                                @endif
                                
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex uidInfo">
                            <div> User ID: </div>
                            <div> {{ $uid }}</div>
                        </div>
                    </div>

                    <form action={{ "/dashboard/drivers/$uid/update" }} method="post">
                        @csrf
                    {{-- Name Info --}}
                    <div class="row mb-2 gx-5">
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class="me-3" type="text" id="Fname" name="Fname" value="{{ $Fname }}" autocomplete="off" readonly />
                                <label for="Fname" class="poppins"> First Name</label>

                                @error('Fname')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" type="text" id="Lname" name="Lname" value="{{ $Lname }}" autocomplete="off" readonly />
                                <label for="Lname" class="poppins"> Last Name</label>
                                
                                @error('Lname')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="row mb-2 gx-5">
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">

                                <input class="me-3" type="text" id="Email" name="Email" value="{{ $Email }}" autocomplete="off" readonly />
                                <label for="Email" class="poppins" disabled> Email</label>
                                
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" id="Contact" type="text" name="Contact" value="{{ $Contact }}" autocomplete="off" readonly />
                                <label for="Contact" class="poppins"> Contact No.</label>
                                
                                @error('Contact')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Personal Info --}}
                    <div class="row mb-2 gx-5">
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" type="date" id="DOB" name="DOB" value="{{ $DOB }}" autocomplete="off" readonly />
                                <label for="DOB" class="poppins"> Birthdate</label>
                                
                                @error('DOB')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Bottom buttons --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="infoDiv justify-content-end" style="gap:20px">
                                <button type="button" class="cancel" disabled> Cancel </button>
                                <button class="submit" disabled> Save </button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>

        </div>
    </div>


    <div class="row gx-0">
        <div class="col-12">

            <div class="row gx-0 justify-content-between gy-3">

                {{-- Driver Vehicles --}}
                <div class="col-12 col-md-6 col-xl-5 boxStyle p-3 pt-4 rounded-4">

                    <div class="border-bottom pb-2">  
                        <div class="poppins text-primary"> Vehicle Frequency </div>
                    </div>

                    <form action={{"/dashboard/drivers/".$uid."/vehicles"}} method="post">
                        @csrf
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th> </th>
                                <th class="text-center"> Able </th>
                                <th class="text-center"> Frequency </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($vehicles as $key => $value)
                        @php
                        $vehicle = match($key){
                           'TrackHead' => 'Tractor Head',
                           'Chassis20' => '20ft Chassis',
                           'Chassis40' => '40ft Chassis',
                           'Truck10W' => '10 Wheeler Truck',
                           'WingVan' => 'Wing Van',
                           'ClVan4' => '4 Wheeler Closed Van',
                           'ClVan6' => '6 Wheeler Closed Van',
                        }
                        @endphp

                        <tr>
                            <td class="ps-3" style="width: 60%">{{$vehicle}}</td>
                            {{-- Checkbox if driver can drive a vehicle type --}}
                            <td> 
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" value='1' name="ableDrive[{{ $key }}]" id={{"able_".$key}}
                                    style="border-color: #85aeff;"
                                    @if ( ${'can_'.$key}) checked @endif >
                                    <label class="form-check-label" for={{"able_".$key}}>
                                    
                                    </label>
                                </div>
                            </td>
                            {{-- Hidden input for unchecked checkboxes --}}
                            <input type="hidden" name="ableDrive[{{ $key }}]" value='0' disabled >
                            {{-- Vehicle Use Ferquency --}}
                            <td style="width: 40%">
                                <div class="d-flex vehiInfo position-relative">
                                    <ion-icon name="create-outline"></ion-icon>
                                    <input class="text-end" type="text" value="{{$value}}" name={{$key}} readonly>

                                </div>
                            </td>
                        </tr>


                        @endforeach
                        </tbody>
                    </table>

                    {{-- Bottom buttons --}}
                    <div class="col-12">
                        <div class="infoDiv justify-content-end pt-1" style="gap:20px">
                            <button type="button" class="cancel2" disabled> Cancel </button>
                            <button class="submit2" disabled> Save </button>
                        </div>
                    </div>

                    </form>
                </div>

                {{-- Driver Ratings --}}
                <div class="col-12 col-md-5">
                    <div class="row gx-0 gy-3">

                        @foreach ($ratings as $categ => $rate)
                        <div class="col-12 boxStyle px-4 py-3 rounded-pill position-relative">
                            <div class="d-flex">
                                <div class="rateSubt"> Category Rating </div>
                                <div class="mt-3 fs-5 poppins" style="color: #62bb1f;"> {{ $categ }} </div>
                                
                                <div class="ms-3 d-flex align-items-baseline rating">
                                    <div class=" d-flex align-items-center me-2" style="color:#4b9f73;">
                                    <div class=" fs-2 me-1"> {{round($rate,1)}}</div>
                                    <ion-icon class="fs-2" name="star"></ion-icon>
                                    </div>
                                    <div class="poppins">/ 5</div>   
                                </div>
                            </div>
                            
                        </div>

                        @endforeach

                    </div>
                </div>
            </div>
            
        </div>
    </div>

</x-dashLayout>