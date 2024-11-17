@push('title')
    <title> Order Information | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/profileOrder.css') }}">
@endpush

@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
    <script type="module" src= "{{ asset('/js/fireBase/orderInfo/dashOrd.js') }}" defer> </script>
@endpush

@php                        
    $vehicle = match($order['serv-typ']){
        'TrackHead' => 'Tractor Head',
        'Chassis20' => '20ft Chassis',
        'Chassis40' => '40ft Chassis',
        'Truck10W' => '10 Wheeler Truck',
        'WingVan' => 'Wing Van',
        'ClVan4' => '4 Wheeler Closed Van',
        'ClVan6' => '6 Wheeler Closed Van',
        default => $order['serv-typ']
    };

    $packaging = match($order['itm-pack']){
        'Box10' => '10kg Box',
        'Box25' => '25kg Box',
        'Envelope' => 'Envelope',
        'ReusePak' => 'Reusable Pak',
        'Tube' => 'Tube',
        default => $order['itm-pack']
    };

    $payMeth = match($order['pay']){
        'Online' => 'Online Banking',
        'COD' => 'Cash On Delivery',
    };

    $progress = null;

@endphp

<x-dashLayout>

    @php
        $time_input = strtotime($order['CreatedAt']); 
        $date = getDate($time_input); 

        echo "<script type='text/javascript'>
        var orderID = \"${order['orderID']}\";
        </script>"; 
    @endphp

    {{-- Header --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> Order Details </p>
            </div>
        </div>
    </div>

    <div class="container-md py-3">
        <div class="row gx-0 gx-md-4 gx-xl-0 gy-5 justify-content-evenly">

            {{-- Order #, Price, Progress --}}
            <div class="col-12 col-md-5 col-xl-4">
                <div>

                    {{-- Order # and Price --}}
                    <div class="row gx-0">
                        <div class="col-12">

                        {{-- Order ID & Status --}}
                        <div class="orderBox position-relative p-3 d-flex flex-column justify-content-center align-items-center rounded-top-4 border border-bottom-0">
                            <ion-icon class="boxIcon mb-2" name="cube-outline"></ion-icon>
                            <div class="poppins fs-5 fw-medium"> Order ID </div>
                            <div class=""> {{$order['orderID']}} </div>

                             {{-- Status Indicator --}}
                             <div class=" d-flex status rounded-4 align-items-center" @if ($order['status'] == 2)
                             style="color: rgb(146, 19, 19) !important" @endif >
                                <ion-icon name="ellipse"></ion-icon>
                                <div class="ms-2 fst-italic poppins">
                                    @switch($order['status'])
                                        @case(0)
                                            In Progress
                                            @break
                                        @case(1)
                                            Completed
                                            @break
                                        @case(2)
                                            Cancelled
                                            @break
                                        @default
                                            In Progress
                                    @endswitch
                                </div>
                            </div>

                        </div>
                        
                        {{-- Price Breakdown & Upload Button --}}
                        <div class="px-2 pt-4 border rounded-bottom-4 bg-white">
                            <table class="table table-borderless mb-2">
                                <tbody>
                                    <td class="text-secondary poppins">Freight Charge</td>
                                    <td class="text-end poppins">₱ {{ number_format($order['freightChrg'],2) }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-secondary poppins">Service Charge</td>
                                    <td class="text-end poppins">₱ {{ number_format($order['servChrg'],2) }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-secondary poppins">Insurance Fee</td>
                                    <td class="text-end poppins">₱ {{ number_format($order['insurFee'],2) }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-secondary poppins">Subtotal</td>
                                    <td class="text-end poppins">₱ {{ number_format($order['subtotal'],2) }}</td>
                                  </tr>
                                  <tr class="position-relative">
                                    <td class="text-secondary poppins">
                                        <div class="position-relative">
                                            <span> TOTAL </span>
                                            @if (floor($order['balance']) <= 0 )
                                            <div class="paidTagTotal poppins fs-11 fw-medium rounded-4 text-success"> PAID </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end poppins">₱ {{ number_format($order['total'],2) }}</td>
                                    
                                  </tr>
                                </tbody>
                            </table>

                            @if ($order['pay'] == 'COD')
                                <div class="d-flex flex-row align-items-center px-3 py-2 border-top position-relative">
                                    <div class="flex-grow-1 text-start poppins"> Downpayment </div>
                                    <div class="flex-grow-1 text-end poppins" style="z-index: 1;"> ₱ {{ number_format(round($order['total'] / 2,2),2) }}</div>
                                    @if (floor($order['balance']) <= round($order['total'] / 2,2) )
                                    <div class="paidTag poppins fs-11 fw-medium rounded-4 text-success" style='z-index:0;'> PAID </div>
                                    @endif
                                </div>
                                
                                <div class="d-flex flex-row align-items-center px-3 py-2 border-top position-relative">
                                    <div class="flex-grow-1 text-start poppins"> On Delivery </div>
                                    <div class="flex-grow-1 text-end poppins" style="z-index: 1;"> ₱ {{ number_format(round($order['total'] / 2,2),2) }}</div>
                                    @if (floor($order['balance']) <= 0 )
                                    <div class="paidTag poppins fs-11 fw-medium rounded-4 text-success" style='z-index:0;'> PAID </div>
                                    @endif
                                </div>
                            @endif
                            <div class="d-flex flex-row align-items-center px-3 py-2 border-top bg-body-secondary">
                                <div class="flex-grow-1 text-start poppins" > Balance </div>
                                <div class="flex-grow-1 text-end poppins"> ₱ {{ number_format($order['balance'],2) }}</div>
                            </div>

                            <div class="d-flex flex-column border-top p-3  justify-content-end position-relative">
                                <div class="d-flex flex-column mb-3 payment">
                                    <div class="text-secondary poppins fs-14"> Payment Method: </div>
                                    <div class="poppins"> {{ $payMeth }}</div>
                                </div>                           

                            </div>
                        </div>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="row gx-0 mt-4">
                        <div class="col-12">
                        
                        <div class=" border rounded-4 p-3 bg-white">
                            Progress

                            <div class="progWrapper d-flex flex-column mt-2 position-relative" id="progWrapper">

                                {{-- * JavaScript na maglalagay * --}}
                                <div class="position-absolute justify-content-center align-items-center" style="height: 100px; width: 100%; display: flex;" id='loaderShip'>
                                    <div class="load-container">
                                        <div class="custom-loader position-absolute"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- Order Info --}}
            <div class="col-12 col-md-7 col-xl-6 p-0 p-sm-3 p-md-0 ">
                <div class="">

                    <div class="row gx-0">
                 
                    <div class="p-3 pt-2 border rounded-4 bg-white">
                        <div class="border-bottom pb-2 fs-6 poppins mb-3 d-flex align-items-center text-secondary justify-content-between"> 
                            <div>
                                <div class="poppins me-2 fs-14"> Date Created </div>
                                <div class="fs-5"> {{ $date['mday']." ".$date['month']." ".$date['year'] }} </div>
                            </div>
                            {{-- Dismiss Button--}}
                            <a href="#" id="dismiss" style="display:none">
                                <button class="btn btn-danger py-1 fs-14 poppins" reject-open> Dismiss </button>
                            </a>

                        </div>

                        {{-- Order Desciption Area --}}
                        <div class="row">
                            <div class="col-12 col-md-7 d-flex flex-column">
                            <div class="d-flex align-items-baseline mb-1">
                            <div class="poppins me-2 fs-14 text-secondary"> Item Description: </div>
                            <div class="poppins"> {{$order['itm-desc']}}</div>
                            </div>

                            <div class="d-flex align-items-baseline">
                            <div class="poppins me-2 fs-14 text-secondary"> Item Category: </div>
                            <div class="poppins"> {{$order['itm-categ']}}</div>
                            </div>
                            </div>

                            <div class="col-12 col-md-5 d-flex flex-column pt-1 pt-md-0">
                            <div class="d-flex align-items-baseline mb-1">
                            <div class="poppins me-2 fs-14 text-secondary"> Item Quantity: </div>
                            <div class="poppins"> {{$order['itm-quan']}}</div>
                            </div>

                            </div>
                        </div>

                        {{-- Contact Person --}}
                        <div class="row gx-0 mt-4">
                            <div class="poppins fw-medium titleBlue mb-1"> Contact Person</div>
                            {{-- Box --}}
                            <div class="col-12 border rounded-3">

                                <div class="d-flex align-items-baseline px-3 py-2 border-bottom">
                                    <div class="poppins fs-14 text-secondary me-2 "> Name: </div>
                                    <div class="poppins"> {{ $order['fname']." ".$order['lname'] }}</div>
                                </div>
                                
                                <div class="row gx-0">

                                    <div class="col-12 col-lg-5">
                                        <div class="py-2 px-3 border-lg-right border-md-bottom d-flex align-items-center">
                                            <ion-icon class="text-secondary" name="call-outline"></ion-icon>
                                            <div class=" ms-2 fs-14 poppins">
                                                {{ $order['cnum'] }}
                                            </div>
                                            
                                        </div>
                                        
                                    </div>

                                    <div class="col-12 col-lg-7">
                                        <div class="py-2 px-3 d-flex align-items-center">
                                            <ion-icon class="text-secondary" name="mail-outline"></ion-icon>
                                            <div class=" ms-2 fs-14 poppins">
                                                {{ $order['email'] }}
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        {{-- Address Info --}}
                        <div class="row gx-0 mt-4">
                            <div class="poppins fw-medium titleBlue mb-1"> Destination Address </div>
                            {{-- Box --}}
                            <div class="col-12 border rounded-3">
                              
                                {{-- Province and City --}}
                                <div class="row gx-0">

                                    <div class="col-12 col-lg-6">
                                        <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                            <div class="poppins fs-14 text-secondary me-2 "> Province: </div>
                                            <div class="poppins">
                                                {{ $order['province'] }}
                                            </div>          
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                            <div class="poppins fs-14 text-secondary me-2 "> City: </div>
                                            <div class="poppins">
                                                {{ $order['city'] }}
                                            </div>
                                        </div>     
                                    </div>

                                </div>

                                {{-- Baranggay and Zipcode --}}
                                <div class="row gx-0">

                                    <div class="col-12 col-lg-6">
                                        <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                            <div class="poppins fs-14 text-secondary me-2 "> Baranggay: </div>
                                            <div class="poppins">
                                                {{ $order['barang'] }}
                                            </div>          
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                            <div class="poppins fs-14 text-secondary me-2 "> Zipcode: </div>
                                            <div class="poppins">
                                                {{ $order['zipcode'] }}
                                            </div>
                                        </div>     
                                    </div>

                                </div>

                                {{-- Street Address --}}
                                <div class="row gx-0">

                                    <div class="col-12">
                                        <div class="py-2 px-3 border-lg-right d-flex align-items-center">
                                            <div class="poppins fs-14 text-secondary me-2 "> Street Address: </div>
                                            <div class="poppins">
                                                {{ $order['street'] }}
                                            </div>          
                                        </div>
                                    </div>

                                </div>

                            </div>

                            
                        </div>

                        {{-- Package Info --}}
                        <div class="row gx-0 mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="poppins fw-medium titleBlue mb-1"> Package Details </div>
                                {{-- Edit Button --}}
                                <a href={{"/order/edit/".$order['orderID']}} id="editOrd" style="display:none">
                                    <button type='button' class="btn btn-primary py-1 fs-14 poppins"> Edit </button>
                                </a>

                            </div>

                            {{-- Box --}}
                            <div class="col-12 border rounded-3">

                            {{-- Weight and Package Quantity --}}
                            <div class="row gx-0">

                                <div class="col-12 col-lg-5">
                                    <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                        <div class="poppins fs-14 text-secondary me-2 "> Weight: </div>
                                        <div class="poppins">
                                            {{ $order['weight']." kg" }}
                                        </div>          
                                    </div>
                                </div>

                                <div class="col-12 col-lg-7">
                                    <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                        <div class="poppins fs-14 text-secondary me-2 "> Package Quantity: </div>
                                        <div class="poppins">
                                            {{ $order['quant'] }}
                                        </div>
                                    </div>     
                                </div>

                            </div>

                            {{-- Dimensions --}}
                            <div class="row gx-0">

                                <div class="col-12 col-lg-6 col-xl-4">
                                    <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                        <div class="poppins fs-14 text-secondary me-2 "> Width: </div>
                                        <div class="poppins">
                                            {{ $order['wd']." cm" }}
                                        </div>          
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6 col-xl-4">
                                    <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                        <div class="poppins fs-14 text-secondary me-2 "> Height: </div>
                                        <div class="poppins">
                                            {{ $order['hg']." cm" }}
                                        </div>          
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4">
                                    <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                        <div class="poppins fs-14 text-secondary me-2 "> Length: </div>
                                        <div class="poppins">
                                            {{ $order['lg']." cm" }}
                                        </div>
                                    </div>     
                                </div>

                            </div>

                            {{-- Value --}}
                            <div class="row gx-0">

                                <div class="col-12">
                                    <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                        <div class="poppins fs-14 text-secondary me-2 "> Declared Value: </div>
                                        <div class="poppins">
                                            {{ "₱ ".number_format($order['itm-value'],0) }}
                                        </div>
                                    </div>     
                                </div>

                            </div>

                            {{-- Packaging Type --}}
                            <div class="row gx-0">
                                <div class="d-flex align-items-baseline px-3 py-2 border-bottom">
                                    <div class="poppins fs-14 text-secondary me-2 "> Packaging Type: </div>
                                    <div class="poppins"> {{ $packaging }}</div>
                                </div>
                            </div>

                            {{-- Service Type --}}
                            <div class="row gx-0">
                                <div class="d-flex align-items-baseline px-3 py-2 ">
                                    <div class="poppins fs-14 text-secondary me-2 "> Service Type: </div>
                                    <div class="poppins"> {{ $vehicle }}</div>
                                </div>
                            </div>

                            </div>
                            
                        </div>

                    </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Overlay --}}
    <div class="overlay" data-overlay> </div>
    
    {{-- Reject Dialog --}}
    <div class="col-9 col-sm-6 col-lg-4 col-xl-3 payView" id="rejectDialog" style="display:none;" rejectDia>
        <div class="row gx-0 mb-2 pb-1 border-bottom border-dark-subtle">
            <div class="position-relative">
                <div class="poppins fs-5 fw-medium"> Dismiss Order </div>
                <div class="closeBtn position-absolute" reject-close>        
                    <ion-icon name="close" class="d-block fs-4"></ion-icon> 
                </div>
            </div>
        </div>

        
        <div class="row gx-0">
            <span class="poppins mt-3 mb-4"> Continue to dismiss the order? </span>
        </div>

        {{-- Bottom Buttons --}}
        <div class="d-flex flex-wrap justify-content-end w-75 float-end ">
            <div class="d-flex flex-grow-1 justify-content-end">
                <button class="close rounded-3 fs-14 me-3" reject-close2> Cancel </button>
                <a href='#' id="dismissConfirm">
                    <button class="btn btn-danger py-1 fs-14 px-3 rounded-3"> Yes </button>
                </a>
            </div>
        </div>
    </div>



</x-dashLayout>

<script>
    $(function() {

        const overlay = document.querySelector("[data-overlay]");

        const rejopen = document.querySelector("[reject-open]");
        const rejclose = document.querySelector("[reject-close]");
        const rejclose2 = document.querySelector("[reject-close2]");
        const rejectDialog = document.querySelector("[rejectDia]");

        const rejecter = [rejopen, rejclose, rejclose2];


        for (let i = 0; i < rejecter.length; i++) {
            rejecter[i].addEventListener("click", function () {
            overlay.classList.toggle("active");
            rejectDialog.classList.toggle("active");
            });
        }


    })
</script>