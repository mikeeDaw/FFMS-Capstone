@push('title')
    <title> Profile | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/profile.css') }}">
@endpush
@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="{{ asset('/js/profile/profile.js') }}" defer></script>
@endpush

<x-layout>
    @php
        $userDoc = session('user');
    @endphp

    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>
    

    {{-- Profile Area --}}
    <div class="container-md pt-4">

        <div class="col-12 pt-4">
      
            <div class="row gy-5 gy-md-0 justify-content-center">
                {{-- Profile Box --}}
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-column align-items-center py-3 px-4 bg-white rounded-4 profBox" style="--bs-bg-opacity: 0.7;">
                        <div class="border border-info text-info rounded-circle fs-2 poppins fw-medium mt-4" style="padding: 15px 30px; width: fit-content">
                            {{substr($userDoc['Fname'], 0, 1)}}
                        </div>
                        <div class="poppins fs-5 mt-3">
                            {{ $userDoc['Fname']." ".$userDoc['Lname']}}
                        </div>
                        <div class="poppins text-body-tertiary">
                            @if ($userDoc['Userlevel'] == "User")
                                Customer
                            @else
                                {{ $userDoc['Userlevel'] }}
                            @endif
                        </div>
                        {{-- Orders --}}
                        <div class="d-flex border-top mt-3 w-100">
                            <div class="flex-grow-1 text-center border-end pt-2"> 
                                <div class="fs-2 fw-medium"> {{count($orders)}} </div>
                                <div class="subinfo poppins"> ORDERS MADE </div>
                            </div>
                            <div class="flex-grow-1 text-center pt-2">
                                <div class="fs-2 fw-medium"> {{$pending}} </div>
                                <div class="subinfo poppins"> ORDERS PENDING</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile Info --}}
                <div class="col-12 col-md-8 col-lg-7 p-3 rounded-4 bg-white" style="--bs-bg-opacity: 0.7;">
                    <div class="row mb-3">
                        <div class="border-bottom pb-2 fs-5 poppins"> User Information </div>
                    </div>
                    <div class="row">
                        <div class="d-flex uidInfo">
                            <div> User ID: </div>
                            <div> {{ session('uid') }}</div>
                        </div>
                    </div>

                    <form action="/profile/update" method="post">
                        @csrf
                    {{-- Name Info --}}
                    <div class="row mb-2 gx-5">
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class="me-3" type="text" name="Fname" value="{{ $userDoc['Fname']}}" autocomplete="off" readonly />
                                <label for="Fname" class="poppins"> First Name</label>

                                @error('Fname')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" type="text" name="Lname" value="{{ $userDoc['Lname'] }}" autocomplete="off" readonly />
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
                                <input class="me-3" type="text" name="Email" value="{{ $userDoc['Email']}}" disabled />
                                <label for="Email" class="poppins"> Email</label>
                                
                                @error('Email')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" type="text" name="Contact" value="{{ $userDoc['Contact'] }}" autocomplete="off" readonly />
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
                                <input class=" me-3" type="date" name="DOB" value="{{ $userDoc['DOB']}}" autocomplete="off" readonly />
                                <label for="DOB" class="poppins"> Birthdate</label>
                                
                                @error('DOB')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="infoDiv justify-content-end" style="padding-right: 35px;">
                                <a href={{ "/profile/".$userDoc['Email']."/resetPassword" }} class="text-primary poppins text-decoration-none border-bottom border-primary"> Change Password </a>
                                
                                <ion-icon class="text-primary helpIcon" name="help-circle-outline"></ion-icon>
                                <span class="helpPopup"> The password reset link will be sent to your email address.</span>
                                
                                
                            </div>
                        </div>

                        {{-- <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" type="text" name="contact" value="{{ $userDoc['Contact'] }}" disabled />
                                <label for="contact" class="poppins"> Contact No.</label>
                                
                            </div>
                        </div> --}}
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
 
    </div>

    {{-- Orders Area --}}
    <section class="mt-4 py-5 linearBG">
        <div class="container-md">
            <div class="row justify-content-evenly">

                <div class="col-12 col-md-11">
                    <div class="ordWrapper">
                        <div class="fs-5 py-2 mb-3 poppins"> My Orders </div>

                        @if (empty($orders))
                        <x-table-nodata />
                        @else
                        
                        <div class="d-flex flex-column w-100" style="gap: 15px;">
                            @foreach ($orders as $order)

                            @php
                            $time_input = strtotime($order['CreatedAt']); 
                            $date = getDate($time_input); 
                            @endphp

                            <a href={{"/profile/orders/".$order['orderID']}}>
                            <div class="orderBoxes d-flex align-items-center">
                                {{-- Date Created --}}
                                <div class="d-flex flex-column align-items-center">
                                    <div class="poppins text-primary" style="font-size: 13px;">{{$date['month']}}</div>
                                    <div class="fs-4 fw-medium text-primary">{{$date['mday']}}</div>
                                </div>

                                {{-- ID and Description --}}
                                <div class="ms-3 d-flex flex-column">
                                    <div class="d-flex ordID poppins">
                                        <div> Order ID: {{$order['orderID']}} </div>
                                    </div>
                                    <div class="poppins fs-5">
                                        {{$order['itm-desc']}} 
                                        <span class="fs-6 ms-2 text-body-tertiary"> {{$order['itm-categ']}} </span>
                                    </div>
                                </div>



                            </div>
                            </a>
                            @endforeach

                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div style="height: 100px;"></div>

</x-layout>