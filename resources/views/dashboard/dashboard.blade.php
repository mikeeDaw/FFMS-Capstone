@push('title')
    <title> Dashboard | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/reports.css') }}">
@endpush
@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src= "{{ asset('/js/fireBase/dashboard/dash.js') }}" defer> </script>
@endpush

@php
    $now = new \DateTime();
    $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));
    $time = $newGMT->format('H:i');
    $date = $newGMT->format('F d Y');
    $day = $newGMT->format('D');

@endphp

<x-dashLayout>

    {{-- <div id="cover">
        <div id="loaderBox">
            <div class="custom-loader2"></div>
            <span class="poppins fs-3 text-white mt-5"> Loading...</span>
        </div>
    </div> --}}
    
    {{-- Header --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-column justify-content-start align-items-start ms-2">
            <div class="me-5">
                <p class="fs-4 poppins m-0"> 
                    {{ $time <= "12:00" ? 'Good Morning' : ($time >= "18:00" ? 'Good Evening' : "Good Afternoon")}},
                    {{session('user')['Fname']}} 
                </p>
            </div>
            <div class="d-flex align-items-center">
                <ion-icon class="text-secondary" name="calendar-clear-outline"></ion-icon>
                <span class="ms-1 poppins text-secondary"> {{ $day.", ".$date }}</span>
            </div>
        </div>
    </div>
    
    {{-- Order Counts --}}
    <div class="row g-0 justify-content-evenly mt-3">
        {{-- For Approve --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 rounded-end-4 d-flex align-items-center justify-content-center border-start border-danger border-3 box-shad clickTab" data-href={{'/shipments/?progress=apprOrder'}} >
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-danger" name="megaphone-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> Approve Orders </span>
                    <span class="poppins fw-medium" id="approve"> {{$statuses['apprOrder']}} </span>
                </div>
            </div>
        </div>
        {{-- Await Payment --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 d-flex align-items-center justify-content-center border-start border-warning border-3 rounded-end-4 box-shad clickTab" data-href={{'/shipments/?progress=awaitPay'}}>
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-warning" name="hourglass-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> Await Payment </span>
                    <span class="poppins fw-medium" id="awaitPay"> {{$statuses['awaitPay']}} </span>
                </div>
            </div>
        </div>
        {{-- Pay Verified --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 d-flex align-items-center justify-content-center border-start border-info border-3 rounded-end-4 box-shad clickTab" data-href={{'/shipments/?progress=payVerif'}} >
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-info" name="wallet-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> Payment Verified </span>
                    <span class="poppins fw-medium" id="paid"> {{$statuses['payVerif']}} </span>
                </div>
            </div>
        </div>
        {{-- Arrived --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 d-flex align-items-center justify-content-center border-start border-danger border-3 rounded-end-4 box-shad clickTab" data-href={{'/shipments/?progress=arrived'}} >
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-danger" name="storefront-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> Items Arrived</span>
                    <span class="poppins fw-medium" id="arrived"> {{$statuses['arrived']}} </span>
                </div>
            </div>
        </div>
        {{-- Under Examination --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 d-flex align-items-center justify-content-center border-start border-warning border-3 rounded-end-4 box-shad clickTab" data-href={{'/shipments/?progress=underExamine'}} >
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-warning" name="eye-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> In Examination</span>
                    <span class="poppins fw-medium" id="examine"> {{$statuses['underExamine']}}</span>
                </div>
            </div>
        </div>
        {{-- For Shipping --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 d-flex align-items-center justify-content-center border-start border-primary border-3 rounded-end-4 box-shad clickTab" data-href={{'/shipments/?progress=forShipping'}} >
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-primary" name="bus-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> For Shipment</span>
                    <span class="poppins fw-medium" id="forShip"> {{$statuses['forShipping']}}</span>
                </div>
            </div>
        </div>
        {{-- Delivery Ongoing --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 d-flex align-items-center justify-content-center border-start border-success border-3 rounded-end-4 box-shad clickTab" data-href={{'/shipments/?progress=outForDelivery'}}>
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-success" name="map-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> In Delivery </span>
                    <span class="poppins fw-medium" id="inDeliv"> {{$statuses['outForDelivery']}} </span>
                </div>
            </div>
        </div>
        {{-- Review Cancel --}}
        <div class="col-6 col-sm-4 col-md-3 p-2">
            <div class="bg-white p-2 d-flex align-items-center justify-content-center border-start border-info border-3 rounded-end-4 box-shad clickTab" data-href={{'/shipments/cancels'}} >
                <div class="iconWrapper">
                    <ion-icon class="iconInside text-info" name="remove-circle-outline"></ion-icon>
                </div>

                <div class="d-flex flex-column ms-2 ms-sm-3 align-items-center">
                    <span class="fs-13 poppins text-secondary text-center"> Cancelled </span>
                    <span class="poppins fw-medium" id="forCancel"> {{$statuses['cancelled']}} </span>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-0 mt-5">

        {{-- Pie Chart Area --}}
        <div class="col-12 col-md-6 pe-0 pe-md-3 mb-3 mb-md-0">

            <div class="bg-white p-4 rounded-4 p-4" style="box-shadow: 0px 0px 11px -6px #717171;">

                <div class="poppins text-secondary mb-3">
                    Service Type Selected
                </div>
    
                <div class="d-flex justify-content-center position-relative" style="height: 385px;">
                    <div class="loaderBox">
                        <div class="custom-loader2"></div>
                        <span class="poppins text-dark mt-3"> Loading...</span>
                    </div>
                    <canvas id='pieChart' style="max-height: 400px;"></canvas>
                </div>

            </div>

        </div>

        {{-- Bar Graph Area --}}
        <div class="col-12 col-md-6 ps-0 ps-md-3 mt-3 mt-md-0">

            <div class="bg-white p-4 rounded-4 p-4" style="box-shadow: 0px 0px 11px -6px #717171;">

                <div class="poppins text-secondary mb-3">
                    Goods Category Frequency
                </div>
    
                <div class="d-flex justify-content-center position-relative" style="height: 385px;">

                    <div class="loaderBox">
                        <div class="custom-loader2"></div>
                        <span class="poppins text-dark mt-3"> Loading...</span>
                    </div>
                    <canvas id='barChart' style="max-height: 400px;"></canvas>
                </div>

            </div>

        </div>

    </div>

    <div class="row g-0 mt-4 justify-content-center">
        
        {{-- Bar Package Graph Area --}}
        <div class="col-12 col-md-6 ps-0 ps-md-3 mt-3 mt-md-0">

            <div class="bg-white p-4 rounded-4 p-4" style="box-shadow: 0px 0px 11px -6px #717171;">

                <div class="poppins text-secondary mb-3">
                    Packaging Type Frequency
                </div>
    
                <div class="d-flex justify-content-center position-relative" style="height: 385px;">
                    <div class="loaderBox">
                        <div class="custom-loader2"></div>
                        <span class="poppins text-dark mt-3"> Loading...</span>
                    </div>
                    <canvas id='packBar' style="max-height: 400px;"></canvas>
                </div>

            </div>

        </div>
    </div>
    
</x-dashLayout>


<script>

    $(function() {
        // For clicking each order row
        $(".clickTab").click(function() {
            window.location = $(this).data("href");
        });
    })

</script>