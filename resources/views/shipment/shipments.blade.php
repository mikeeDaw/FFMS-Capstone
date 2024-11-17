@push('title')
    <title> Shipments | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashUser.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/carousel.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script type="module" src= "{{ asset('/js/fireBase/shipments/showShip.js') }}" > </script>
@endpush

@php

    $header = match($progress){
        'completed' => 'COMPLETED ORDERS',
        'outForDelivery' => 'DELIVERY IN PROGRESS',
        'forShipping' => 'PREPARING FOR SHIPMENT',
        'underExamine' => 'UNDER EXAMINATION',
        'arrived' => 'ARRIVED AT BRANCH',
        'payVerif' => 'PAYMENTS VERIFIED',
        'awaitPay' => 'WAITING FOR PAYMENT',
        'apprOrder' => 'ORDERS FOR APPROVAL',
    };

    $checkName = match($progress){
        'completed' => 'Co',
        'outForDelivery' => 'Ou',
        'forShipping' => 'Fo',
        'underExamine' => 'Un',
        'arrived' => 'Ar',
        'payVerif' => 'Pa',
        'awaitPay' => 'Aw',
        'apprOrder' => 'Ap',
    };

    echo "<script type='text/javascript'>
        var currProg = \"$progress\";
        var nextProg = \"$nextProg\";
        var checkNm = \"$checkName\";
        </script>"; 

@endphp

<x-dashLayout>

    <div id="loadOverlay"> </div>

    <div id="updLoad">
        <div id="boxLoad">
            <div class="custom-loader2"></div>
            <span class="poppins mt-4 text-center"> Updating Order/s... </span>
        </div>
    </div>

    {{-- Header --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> Shipments </p>
            </div>
        </div>
    </div>

    {{-- Order Counts --}}
    <div class=" g-0 row u-summ bot-margin">
        <div class="col-12 d-flex flex-column p-0">
            <div class="hrcol pb-3 ps-4 poppins"> Shipments Summary </div>

            {{-- Counts row 1 --}}
            <div class="row gx-0 align-items-center flex-wrap  justify-content-center bord-btm summ">
                
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 d-flex py-3 justify-content-center align-items-center bord-md-right item">
                    
                    <ion-icon class="me-3 text-danger" name="megaphone"></ion-icon>
                    
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Approve Order </small>
                        <h5>{{ $counts['apprOrder'] }}</h5>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 col-12 d-flex py-3 justify-content-center align-items-center bord-md-right item ">
                    
                    <ion-icon class="me-3 text-warning" name="hourglass"></ion-icon>
                    
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Awaiting Payment </small>
                        <h5>{{ $counts['awaitPay'] }}</h5>
                    </div>
                </div>

                <div class="d-flex col-lg-3 col-md-4 col-sm-6 col-12 py-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-info" name="wallet"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Payments Verified </small>
                        <h5>{{ $counts['payVerif'] }}</h5>
                    </div>
                    
                </div>

                <div class="d-flex col-lg-3 col-md-4 col-sm-6 col-12 py-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-danger" name="storefront"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Arrived Packages </small>
                        <h5>{{ $counts['arrived'] }}</h5>
                    </div>
                </div>




            </div>

            {{-- Counts row 2 --}}
            <div class="row gx-0 align-items-center flex-wrap  justify-content-center summ">

                <div class="d-flex col-lg-3 col-md-4 col-sm-6 col-12 py-3 justify-content-center align-items-center bord-md-right item">
                    
                    <ion-icon class="me-3 text-warning" name="eye"></ion-icon>
                    
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Under Examination </small>
                        <h5>{{ $counts['underExamine'] }}</h5>
                    </div>
                </div>

                <div class="d-flex col-lg-3 col-md-4 col-sm-6 col-12 py-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-primary" name="bus"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> For Shipment </small>
                        <h5>{{ $counts['forShipping'] }}</h5>
                    </div>
                    
                </div>

                <div class="d-flex col-lg-3 col-md-4 col-sm-6 col-12 py-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-success" name="map"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Delivery In Progress </small>
                        <h5>{{ $counts['outForDelivery'] }}</h5>
                    </div>
                </div>

                <div class="d-flex col-lg-3 col-md-4 col-sm-6 col-12 py-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-info" name="checkmark-done"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Shipments Completed </small>
                        <h5>{{ $counts['completed'] }}</h5>
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

            <select class="form-select shFilter poppins" aria-label="Default select example">
                <option value="apprOrder" {{ $progress == 'apprOrder' ? 'selected' : '' }}>
                    For Approval
                </option>
                <option value="awaitPay" {{ $progress == 'awaitPay' ? 'selected' : '' }}>
                    Awaiting Payment
                </option>
                <option value="payVerif" {{ $progress == 'payVerif' ? 'selected' : '' }}>
                    Payments Verified
                </option>
                <option value="arrived" {{ $progress == 'arrived' ? 'selected' : '' }}>
                    Items Arrived
                </option>
                <option value="underExamine" {{ $progress == 'underExamine' ? 'selected' : '' }}>
                    Under Examination
                </option>
                <option value="forShipping" {{ $progress == 'forShipping' ? 'selected' : '' }}>
                    For Shipping
                </option>
                <option value="outForDelivery" {{ $progress == 'outForDelivery' ? 'selected' : '' }}>
                    Out For Delivery
                </option>
                <option value="completed" {{ $progress == 'completed' ? 'selected' : '' }}>
                    Orders Completed
                </option>
            </select>



        </div>



    </div>

    <form action="/shipments/updateStatus/{{$progress}}" method="post" id="statForm">
        @csrf

    {{-- Table --}}
    <div class="row g-0 u-summ tablewrapper bot-margin rounded-4 shadow2 position-relative" style="height: calc(150px + 3rem);" id='tableWrapper'>
        <div class="poppins mb-3 cardtitle" style="display: none" id='headerTitle'> 
            {{$header}}
        </div>

        <div id="loadArea">
            <div class="custom-loader2"></div>
            <span class="mt-3 poppins"> Please Wait...</span>
        </div>

    </div>

    <input type="hidden" name="noData" id="noDataImg" value="{{ asset("/images/dashboard/nodata.png") }}">

    {{-- Submit Button --}}
    <div class="d-flex position-fixed align-items-center rounded-3 shUpd">
        <span class="poppins fw-medium" style="white-space: nowrap;"> Update Status </span>
        <button class="btn btn-primary ms-3" type='button' style="padding: 4px 15px;" id="updateBtn"> Save </button>
    </div>

    </form>

</x-dashLayout>


<script >
    $(function(){

        // For Filtering
        $('select').change(function(){
           var selValue = $(this).find(':selected').val();
            window.location = '/shipments/?progress=' + selValue;
        })
    })

</script>

