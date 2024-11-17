@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/formfields.css') }}">
@endpush

<div class="row gap">
    <div class="col-md-6 ps-0">
        <div class="float">   
        <input type="text" name='fname' placeholder=" " value="{{old('fname')}}" autocomplete="off">
        <label for="fname">First Name *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('fname')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>

    <div class="col-md-6 ps-0">
        <div class="float">   
        <input type="text" name='lname' placeholder=" " value="{{old('lname')}}" autocomplete="off">
        <label for="lname">Last Name *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('lname')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>
</div>

<span class="rowMargin"></span>

<div class="row gap">
    <div class="col-md-6 ps-0">
        <div class="float">   
        <input class="s-marg d-flex" type="text" name='conum' placeholder=" " autocomplete="off" value="{{old('conum')}}">
        <div class="imger">
            <img src="/images/logform/phflag.png" alt="">
            <span style="font-family: 'Roboto', sans-serif; margin-left:5px;
            font-size:15px;"> +63</span>
        </div>
        <label for="conum">Contact Number *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('conum')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>

    <div class="col-md-6 ps-0">
        <div class="float">   
            <input type="text" name='email' placeholder=" " value="{{old('email')}}" autocomplete="off">
            <label for="email">Email *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('email')
        <p class="v-err"> {{ $message }}</p>
        @enderror
</div>
</div>

<span class="rowMargin"></span>

<div class="row gap">
    <div class="col-md-6 ps-0">

        <div class="float" style="border:3px solid #00000000;">   
            <input type="date" name='dob' value="2000-04-13">
            <label for="dob" style="color:#000;">Date of Birth *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('dob')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>

    
    </div>

<span class="rowMargin"></span>

<div class="row gap">
    <div class="col-md-6 ps-0">
        <div class="float">   
        <input type="password" name='dr_pass' placeholder=" ">
        <label for="dr_pass">Password *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('dr_pass')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>

    <div class="col-md-6 ps-0">
        <div class="float">   
        <input type="password" name='dr_pass_confirmation' placeholder=" ">
        <label for="dr_pass_confirmation">Confirm Password *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('dr_pass_confirmation')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>

</div>

<span class="rowMargin"></span>

{{-- CheckBoxes --}}
<div class="row">
    <div class="col-12 d-flex flex-column justify-content-center checkField">
        <div class="d-flex mt-3">
            <label class="me-4"> Vehicles Able to Drive *</label>

            <button type="button" class="btn btn-outline-primary" style="padding: 1px 8px; font-size: 0.75rem;" id="checkAll">
                Check All
            </button>
        </div>
        


    <div class="d-flex flex-row justify-content-start py-2">

        {{-- Checkboxes Row 1 --}}
        <div class="d-flex flex-column flex-grow-1 gap">
            <div class="form-check ">
                <input class="form-check-input driverForm" type="checkbox" value="Tractor Head" name="VE[]" id="V1">
                <label class="form-check-label" for="V1">
                Tractor Head
                </label>
            </div>
            
            <div class="form-check ">
                <input class="form-check-input driverForm" type="checkbox" value="40ft Chassis" name="VE[]" id="V2">
                <label class="form-check-label" for="V2">
                40ft Chassis
                </label>
            </div>

            <div class="form-check ">
                <input class="form-check-input driverForm" type="checkbox" value="20ft Chassis" name="VE[]" id="V3">
                <label class="form-check-label" for="V3">
                20ft Chassis
                </label>
            </div>
        </div>

        {{-- Checkboxes Row 2 --}}
        <div class="d-flex flex-column flex-grow-1 gap">
            <div class="form-check ">
                <input class="form-check-input driverForm" type="checkbox" value="10 Wheeler Truck" name="VE[]" id="V4">
                <label class="form-check-label" for="V4">
                10 Wheeler Truck
                </label>
            </div>
            
            <div class="form-check ">
                <input class="form-check-input driverForm" type="checkbox" value="4 Wheeler Closed Van" name="VE[]" id="V5">
                <label class="form-check-label" for="V5">
                4 Wheeler Closed Van
                </label>
            </div>

            <div class="form-check ">
                <input class="form-check-input driverForm" type="checkbox" value="6 Wheeler Closed Van" name="VE[]" id="V6">
                <label class="form-check-label" for="V6">
                6 Wheeler Closed Van
                </label>
            </div>
        </div>

        {{-- Checkboxes Row 3 --}}
        <div class="d-flex flex-column flex-grow-1 gap"> 
            <div class="form-check ">
                <input class="form-check-input driverForm" type="checkbox" value="Wing Van" name="VE[]" id="V7">
                <label class="form-check-label" for="V7">
                Wing Van
                </label>
            </div>
        </div>
    </div>

    </div>
    @error('VE')
    <p class="v-err"> {{ $message }}</p>
    @enderror
</div>
