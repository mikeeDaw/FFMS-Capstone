@push('title')
    <title> Create Order - Checkout | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/checkout.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>  
    <script type="module" src= "{{ asset('/js/fireBase/orders/checkout.js') }}"> </script> 
@endpush

@php

    $consig = session('Consignee');
    $package = session('Package');
    $charges = session('Charges');

    function returnDict($arr) {
        $strDict = '';

        foreach($arr as $key => $value){
            $strDict .= " '$key' : '$value' ,";
        }
        return $strDict;
    }

    echo "<script type='text/javascript'>
        var consig = { ".returnDict($consig)." };
        var packOrd = {".returnDict($package)."};
        var charges = {".returnDict($charges)."};
        var currUser = \"".session('uid')."\";
        </script>"; 
    
    $vehicle = match($package['serv-typ']){
        'TrackHead' => 'Tractor Head',
        'Chassis20' => '20ft Chassis',
        'Chassis40' => '40ft Chassis',
        'Truck10W' => '10 Wheeler Truck',
        'WingVan' => 'Wing Van',
        'ClVan4' => '4 Wheeler Closed Van',
        'ClVan6' => '6 Wheeler Closed Van',
    };
    $packType = match($package['itm-pack']){
        'Box10' => '10kg Box',
        'Box25' => '25kg Box',
        'Envelope' => 'Envelope',
        'ReusePak' => 'Reusable Pak',
        'Tube' => 'Tube',
    };
@endphp

<x-layout>

    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>

    <div id="loadOverlay"> </div>

    <div id="loadArea">
        <div id="boxLoad">
            <div class="custom-loader2"></div>
            <span class="poppins mt-4 text-center"> Creating Order... </span>
        </div>
    </div>

    <div class="container-md">
        <div class="row gx-0 justify-content-around align-items-start">

            <!-- Progress Bar -->
            <div class="col-lg-2 col-sm-12">
                {{-- *Progress Bar Here* --}}

                {{-- Vert Progress --}}
                <div class="d-flex flex-column mt-3 mt-lg-5 align-items-center align-items-lg-start vertProg">
                    <div class="col-4 col-lg-6 d-flex flex-column align-items-center">
                        <span class="d-block w-100 Vline" style="height: 0.5px;"></span>
                        <span class="d-block position-relative Hline">
                            {{-- What --}}
                            <span class="d-flex align-items-center blockingDesc" >
                                <span class="circlePg"> 
                                    <ion-icon class="iconPG " name="compass-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc">
                                    Where
                                </span>
                            </span>

                            {{-- Where --}}
                            <span class="d-flex align-items-center blockingDesc " >
                                <span class="circlePg "> 
                                    <ion-icon class="iconPG " name="cube-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc">
                                    What
                                </span>
                            </span>

                            {{-- Checkout --}}
                            <span class="d-flex align-items-center blockingDesc active">
                                <span class="circlePg active"> 
                                    <ion-icon class="iconPG active" name="card-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc active">
                                    Checkout
                                </span>
                            </span>
                        </span>
                        <span class="d-block w-100 rounded-pill Vline"></span>
                    </div>
                </div>

                {{-- Horiz Progress --}}
                <div class="d-flex w-100 justify-content-center align-items-center my-4 horizProg">

                    <div class="d-flex align-items-center">
                        <span class="circlePg"> 
                            <ion-icon class="iconPG" name="compass-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc">
                            Where
                        </span>
                    </div>

                    <span class="d-block vertProgLine"> </span>

                    
                    <div class="d-flex align-items-center">
                        <span class="circlePg"> 
                            <ion-icon class="iconPG" name="cube-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc">
                            What
                        </span>
                    </div>

                    <span class="d-block vertProgLine"> </span>

                    
                    <div class="d-flex align-items-center">
                        <span class="circlePg active"> 
                            <ion-icon class="iconPG active" name="card-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc active">
                            Checkout
                        </span>
                    </div>

                </div>
            </div>

            {{-- Form Field --}}
            <div class="col-xl-6 col-lg-7 col-md-10 col-sm-9 pt-4">
                <form class="checkBox" action="/step3/order" method="post" id='checkoutForm'>
                    @csrf
                    {{-- Header --}}
                    <div class="row f-head g-0">
                        <div class="col-md-12 pb-1 px-5 pt-3 d-flex align-items-center" style="gap:20px;">
                            <a href="#"> <ion-icon name="pricetags-outline" class="bk-i"></ion-icon> </a>
                            <h5> Checkout Order </h5>
                        </div>
        
                    </div>

                    {{-- Content --}}
                    <div class="contentWrapper">
                        <div class="d-flex mb-2" style="gap:15px">
                            <div class="tags"> {{ $vehicle }} </div>
                            <div class="tags"> {{ $packType }} </div>
                        </div>
                        {{-- Reciever Info --}}
                        <div class="row gx-0">
                            <div class="d-flex mt-2">
                                <div class="poppins me-2">Deliver To: </div>
                                <div class="fw-medium">{{ $consig['fname']." ".$consig['lname']}}</div>
                            </div>
                            <div class="d-flex">
                                <div class="poppins me-2">Contact No: </div>
                                <div class="fw-medium">{{ $consig['cnum'] }}</div>
                            </div>
                        </div>
                        {{-- Address and Route --}}
                        <div class="row gx-0 my-3">
                            <div class="d-flex flex-column col-12 col-sm-6">
                                <div class="poppins"> Address </div>
                                <div class="fw-medium pe-3"> {{ $consig['street'].", ".$consig['barang'].", ".$consig['city'].", ".$consig['zipcode'].", ".$consig['province'] }}</div>
                            </div>
                            <div class="d-flex flex-column col-12 col-sm-6">
                                <div class="poppins"> Route </div>
                                <div class="fw-medium"> {{ $package['route'] }}</div>
                            </div>
                        </div>

                        {{-- Charges --}}
                        <div class="row gx-0">
                        <table class="table mt-2">
                            <thead>
                              <tr class="lightBl fw-medium">
                                <td scope="col" class="poppins w-50 text-end pe-5">Charges</th>
                                <td scope="col" class="poppins w-50 text-center">Value</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="text-end pe-5">Freight Charge</td>
                                <td class="text-center align-middle">{{ "PHP ".number_format($charges['freightChrg'],2)}}</td>
                              </tr>
                              <tr>
                                <td class="text-end pe-5">Service Charge</td>
                                <td class="text-center align-middle"> {{ "PHP ".number_format($charges['servChrg'],2)}}</td>
                              </tr>
                              <tr>
                                <td class="text-end pe-5">Insurance Charge</td>
                                <td class="text-center align-middle">{{ "PHP ".number_format($charges['insurFee'],2)}}</td>
                              </tr>
                              <tr>
                                <td class="text-end pe-5">Subtotal</td>
                                <td class="text-center align-middle">{{ "PHP ".number_format($charges['subtotal'],2)}}</td>
                              </tr>
                              <tr class="ligherBl">
                                <td class="text-end pe-5 poppins fw-medium">Total Amount</td>
                                <td class="text-center poppins fw-medium align-middle">{{ "PHP ".number_format($charges['total'],2)}}</td>
                              </tr>
                            </tbody>
                          </table>
                          <span class="text-end poppins fs-12 text-secondary">* Additional Charges may be applied</span>
                        </div>

                        {{-- Payment Method --}}
                        <div class="row gx-0">

                            <div class="poppins mt-3 mb-4">
                                Choose a Payment Method
                            </div>
                            {{-- Radio Buttons --}}
                            <div class="d-flex flex-column flex-sm-row justify-content-evenly" style="gap:20px;">
                            <label class="radioWrapper">
                                <input type="radio" name="pay" value="COD" checked>
                                    <div class="rad-btn d-flex flex-column align-items-center">
                                        <ion-icon name="cash-outline"></ion-icon>
                                        <p class="m-0 text-center mt-2 poppins"> Cash On <br> Delivery</p>
                                    </div>
                            </label>

                            <label class="radioWrapper">
                                <input type="radio" name="pay" value="Online">
                                    <div class="rad-btn d-flex flex-column align-items-center">
                                        <ion-icon name="card-outline"></ion-icon>
                                        <p class="m-0 text-center mt-2 poppins"> Online <br> Payment</p>
                                    </div>
                            </label>
                            </div>
                        </div>

                        <div class="row gx-0">
                            <div class="px-3 py-4 pb-2">
                                <span class="poppins text-secondary fs-14" id='CODdesc' style="display:block;"> * COD: Pay 50% of total price through online banking & the remaining 50% on the arrival of delivery.</span>
                                <span class="poppins text-secondary fs-14" id='OLdesc' style="display:none;"> * Online Payment: Pay 100% of price through online banking before processing the order. </span>
                            </div>
                        </div>

                        <div class="row gx-0 mt-3">
                            <div class="px-4 py-2 d-flex flex-column border border-dark-subtle rounded-4">
                                <span class="poppins fw-medium text-secondary"> Cancellation Policy:</span>
                                <span class="poppins px-2 fs-14 text-dark"> Order cancellations are allowed until the payment for the order is verified. </span>
                            </div>
                            
                        </div>
                        
                    </div>

                    <hr style="width:90%; margin:auto">
                    
                    <!-- Bottom Buttons -->
                    <div style="padding: 20px 25px 10px 25px;">
                        <div class="row justify-content-between m-auto">
                            <div class="col-4 col-sm-3 my-2">
                            <a href={{ url()->previous() }} class="d-flex align-items-center h-100" style="gap:10px;">
                                <ion-icon name="arrow-back-circle-outline" class="bk-i"></ion-icon>
                                <h5 class="m-0 back"> Back</h5>
                            </a>
                            </div>
        
                            <div class="col-7 col-md-5 d-flex justify-content-end my-2">
                                <button class="d-flex align-items-center cont-btn "> 
                                    <span> Place Order </span>
                                    <ion-icon name="chevron-forward-outline" style="transition: .2s linear;">
                                    </ion-icon>
                                </button>
        
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


</x-layout>

<script>
    $(function() {

        $("input[name='pay']").on('click', function() {
            var val = $(this).val();

            if(val == 'COD'){
                $('#CODdesc').show();
                $('#OLdesc').hide();
            } else {
                $('#CODdesc').hide();
                $('#OLdesc').show();
            }

        })
    })
</script>