@push('title')
    <title> User Profile | Lebria Transport</title>
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
    @endphp

    {{-- Header --}}
    <div class="row g-0 mb-4">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> User Profile Information </p>
            </div>
        </div>
    </div>

    {{-- Profile Area --}}
    <div class="col-12 bg-white px-3 py-4" style="border: 1px solid #e3e3e3; ">

        <div class="row gx-0 justify-content-evenly">

                {{-- Profile Box --}}
                <div class="col-12 col-md-3">
                    <div class="d-flex flex-column align-items-center py-3 px-4 mb-4 mb-md-0">
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

                {{-- Profile Info --}}
                <div class="col-12 col-md-8 col-lg-7">
                    <div class="row mb-3">
                        <div class="border-bottom pb-2 fs-5 d-flex justify-content-between poppins">
                            <div> User Information </div> 
                            @if ($user['Userlevel'] == "Admin")
                                @if (!$status)
                                <a href={{ "/dashboard/users/disable/".$uid }}>
                                <button class="btn btn-danger statusBtn"> Deactivate </button>
                                </a>
                                @else
                                <a href={{ "/dashboard/users/enable/".$uid }}>
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
                    
                    <form action={{ "/dashboard/".lcfirst($Userlevel)."s/$uid/update" }} method="post">
                        @csrf
                    {{-- Name Info --}}
                    <div class="row mb-2 gx-5">
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class="me-3" type="text" name="Fname" value="{{ $Fname }}" autocomplete="off" readonly />
                                <label for="Fname" class="poppins"> First Name</label>

                                @error('Fname')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" type="text" name="Lname" value="{{ $Lname }}" autocomplete="off" readonly />
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
                                <input class="me-3" type="text" name="Email" value="{{ $Email }}" autocomplete="off" readonly />
                                <label for="Email" class="poppins" disabled > Email</label>
                                
                                @error('Email')
                                <p class="v-err"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="infoDiv">
                                <ion-icon name="create-outline"></ion-icon>
                                <input class=" me-3" type="text" name="Contact" value="{{ $Contact }}" autocomplete="off" readonly />
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
                                <input class=" me-3" type="date" name="DOB" value="{{ $DOB }}" autocomplete="off" readonly />
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

</x-dashLayout>