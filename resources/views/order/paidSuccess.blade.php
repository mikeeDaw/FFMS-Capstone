@push('title')
    <title> Payment Success | Lebria Transport</title>
@endpush

@push('css')
    <style>
        .curv::before{
            content: '';
            background: linear-gradient(181.2deg, rgb(181, 239, 249) 10.5%, rgb(254, 254, 254) 86.8%);
            width: 100%;
            display: block;
            height: 400px;
            position: absolute;
            top: 0px;
            z-index: -5;
        }

        .curv::after{
            content: '';
            display: block;
            z-index: -4;
            position: absolute;
            height: 507px;
            background: #ffffffad;
            width: 870px;
            left: -369px;
            border-radius: 100%;
            top: 39px;
            transform: rotate(356deg);
            opacity: 0.6;
        }
    </style>
@endpush

@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script type="module" src= "{{ asset('/js/fireBase/orders/payOk.js') }}" defer> </script>
@endpush

@php

    function returnDict($arr) {
        $strDict = '';

        foreach($arr as $key => $value){
            $strDict .= " '$key' : '$value' ,";
        }
        return $strDict;
    }

    echo "<script type='text/javascript'>
        var orderData = { ".returnDict($orderData)." };
        var orderID = \"".$orderID."\";
        var monthW = \"".$monthWord."\";
        var monthN = \"".$monthNum."\";
        var year = \"".$year."\";
        var day = \"".$day."\";
        var weekNum = \"".$weekNum."\";
        var weekYr = \"".$weekYr."\";
        var shipID = \"".$shipID."\";
        var paid = \"".$paid."\";
        var datePaid = \"".$datePay."\";
        </script>"; 
@endphp

<x-layout>

    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>

    <div class="row g-0 justify-content-center ms-0" style="min-height: calc(100vh - 235px);">

        <div class="col-9 col-md-6 col-lg-5 col-xl-4 d-flex flex-column justify-content-center align-items-center">

            <img class="my-3" src={{asset('/images/logform/payImg.png')}} alt="" style="max-height: 150px; border-radius: 100%;">
            <span class="poppins fs-5 fw-medium text mb-3"> Payment Successful! </span>
            <span class="poppins text-secondary text-center px-2"> Electronic Receipt sent to <span class="fw-medium fst-italic">{{ session('user')['Email']}} </span>. Thank you for trusting our service.  </span>
        </div>

    </div>



</x-layout>