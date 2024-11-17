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
        <input type="password" name='ur_pass' placeholder=" ">
        <label for="ur_pass">Password *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('ur_pass')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>

    <div class="col-md-6 ps-0">
        <div class="float">   
        <input type="password" name='ur_pass_confirmation' placeholder=" ">
        <label for="ur_pass_confirmation">Confirm Password *</label>
        </div>
        {{-- Validation Error Message --}}
        @error('ur_pass_confirmation')
        <p class="v-err"> {{ $message }}</p>
        @enderror
    </div>
</div>