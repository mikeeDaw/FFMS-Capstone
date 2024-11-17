@push('title')
    <title> Order Details | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/profileOrder.css') }}">
@endpush

@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script type="module" src= "{{ asset('/js/fireBase/orderInfo/profileOrd.js') }}" defer> </script>

@endpush

@php        

    $vehicle = match($singleOrder['serv-typ']){
        'TrackHead' => 'Tractor Head',
        'Chassis20' => '20ft Chassis',
        'Chassis40' => '40ft Chassis',
        'Truck10W' => '10 Wheeler Truck',
        'WingVan' => 'Wing Van',
        'ClVan4' => '4 Wheeler Closed Van',
        'ClVan6' => '6 Wheeler Closed Van',
    };

    $packaging = match($singleOrder['itm-pack']){
        'Box10' => '10kg Box',
        'Box25' => '25kg Box',
        'Envelope' => 'Envelope',
        'ReusePak' => 'Reusable Pak',
        'Tube' => 'Tube',
    };

    $payMeth = match($singleOrder['pay']){
        'Online' => 'Online Banking',
        'COD' => 'Cash On Delivery',
    };

@endphp


<x-layout>

    @php
        echo "<script type='text/javascript'>
        var orderID = \"${singleOrder['orderID']}\";
        </script>"; 

        $progress = null;
    @endphp



    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>
    

    <div class="container-md pt-3 pb-5">
        <div class="row gx-0 gy-5 justify-content-evenly">

            {{-- Order #, Price, Progress --}}
            <div class="col-12 col-md-5 col-lg-4">
                <div>
                    {{-- Order # and Price --}}
                    <div class="row gx-0">
                        <div class="col-12">

                        <div class="orderBox position-relative p-3 d-flex flex-column justify-content-center align-items-center rounded-top-4 border border-bottom-0">
                            <ion-icon class="boxIcon mb-2" name="cube-outline"></ion-icon>
                            <div class="poppins fs-5 fw-medium"> Order ID </div>
                            <div class=""> {{$singleOrder['orderID']}} </div>

                            {{-- Status Indicator --}}
                            <div class=" d-flex status rounded-4 align-items-center" @if ($singleOrder['status'] == 2)
                                style="color: rgb(146, 19, 19) !important" @endif >
                                <ion-icon name="ellipse"></ion-icon>
                                <div class="ms-2 fst-italic poppins">
                                    @switch($singleOrder['status'])
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
                        <div class="px-2 pt-4 border rounded-bottom-4">

                            <table class="table table-borderless mb-2">
                                <tbody>
                                    <td class="text-secondary poppins">Freight Charge</td>
                                    <td class="text-end poppins">₱ {{ number_format($singleOrder['freightChrg'],2) }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-secondary poppins">Service Charge</td>
                                    <td class="text-end poppins">₱ {{ number_format($singleOrder['servChrg'],2) }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-secondary poppins">Insurance Fee</td>
                                    <td class="text-end poppins">₱ {{ number_format($singleOrder['insurFee'],2) }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-secondary poppins">Subtotal</td>
                                    <td class="text-end poppins">₱ {{ number_format($singleOrder['subtotal'],2) }}</td>
                                  </tr>
                                  <tr>
                                    <td class="text-secondary poppins">
                                        <div class="position-relative">
                                            <span> TOTAL </span>
                                            @if (floor($singleOrder['balance']) <= 0 )
                                            <div class="paidTagTotal poppins fs-11 fw-medium rounded-4 text-success"> PAID </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end poppins">₱ {{ number_format($singleOrder['total'],2) }}</td>
                                  </tr>
                                </tbody>
                            </table>
                        
                            @if ($singleOrder['pay'] == 'COD')
                                <div class="d-flex flex-row align-items-center px-3 py-2 border-top position-relative">
                                    <div class="flex-grow-1 text-start poppins"> Downpayment </div>
                                    <div class="flex-grow-1 text-end poppins"> ₱ {{ number_format(round($singleOrder['total'] / 2,2),2) }}</div>
                                    @if (floor($singleOrder['balance']) <= round($singleOrder['total'] / 2,2) )
                                    <div class="paidTag poppins fs-11 fw-medium rounded-4 text-success">
                                         PAID </div>
                                    @endif
                                
                                </div>
                                <div class="d-flex flex-row align-items-center px-3 py-2 border-top position-relative">
                                    <div class="flex-grow-1 text-start poppins"> On Delivery </div>
                                    <div class="flex-grow-1 text-end poppins"> ₱ {{ number_format(round($singleOrder['total'] / 2,2),2) }}</div>
                                    @if (floor($singleOrder['balance']) <= 0 )
                                    <div class="paidTag poppins fs-11 fw-medium rounded-4 text-success">
                                         PAID </div>
                                    @endif
                                </div>
                                
                            @endif

                            <div class="d-flex flex-row align-items-center px-3 py-2 border-top bg-body-secondary position-relative">
                                <div class="flex-grow-1 text-start poppins"> Balance </div>
                                <div class="flex-grow-1 text-end poppins"> ₱ {{ $singleOrder['balance'] <= 0 ? 0 : number_format($singleOrder['balance'],2) }}</div>
                                @if ($singleOrder['balance'] <= 0 )
                                    <div class="paidTag poppins fs-11 fw-medium rounded-4 text-success">
                                         PAID </div>
                                @endif
                            </div>

                            <div class="d-flex flex-column border-top p-3  justify-content-end position-relative">
                                <div class="d-flex flex-column mb-2 payment">
                                    <div class="text-secondary poppins fs-14"> Payment Method: </div>
                                    <div class="poppins"> {{ $payMeth }}</div>
                                </div>

                                {{-- <form action={{ "/dash/payment/".$singleOrder['orderID'] }} method="post" enctype="multipart/form-data" id="uploadForm" class="mb-0">
                                    @csrf
                                    
                                    <div class="position-relative uploadRec d-flex justify-content-end">
                                        <input type="file" name='receipt' id="receipt" disabled >
                                        <label for="receipt" id="recLbl" class="rounded-3" style="pointer-events:none; opacity:0.6;"> 
                                            <ion-icon class="align-middle" name="cloud-upload-outline"></ion-icon>
                                            <span class="ms-2"id="upText"> Upload Reciept </span> 
                                        </label>
                                    </div>
                                    @error('receipt')
                                        <p class="v-err"> {{ $message }}</p>
                                    @enderror

                                </form> --}}

                                <a href="{{$checkoutURL}}" id="payRedirect" >
                                 <button class="payBTN mt-1 poppins"> Proceed to Payment </button>
                                </a>

                                <span class=" mt-2 fs-12 text-center text-primary" style="display:none;" id="payDiscl"> You can proceed to payment once your order is approved.</span>


                                {{-- @if ($singleOrder['rpt_img_name'])
                                <div>
                                    <button class="mt-2 d-flex justify-content-center align-items-center" id="viewReciept" data-add-user> 
                                        <ion-icon name="eye" style="font-size:20px;"></ion-icon>
                                        <span class="ms-2"> View Reciept </span>  
                                    </button>
                                </div>
                                @endif --}}

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
            <div class="col-12 col-md-6 p-3 p-md-0 ">
                <div class="">

                    <div class="row gx-0">

                    <div class="">
                        <div class="border-bottom pb-2 mb-3 d-flex align-items-center justify-content-between"> 
                            <span class="fs-5 poppins"> Order Details  </span> 

                                <button class="btn btn-danger" id="cancelBtn" style=" font-size: 12px; padding: 4px 10px;" cancel-open disabled> Cancel Order </button>                          
                            
                        </div>

                        {{-- Order Desciption Area --}}
                        <div class="row">
                            <div class="col-8 col-md-7 d-flex flex-column">
                            <div class="d-flex align-items-baseline mb-1">
                            <div class="poppins me-2 fs-14 text-secondary"> Item Description: </div>
                            <div class="poppins"> {{$singleOrder['itm-desc']}}</div>
                            </div>

                            <div class="d-flex align-items-baseline">
                            <div class="poppins me-2 fs-14 text-secondary"> Item Category: </div>
                            <div class="poppins"> {{$singleOrder['itm-categ']}}</div>
                            </div>
                            </div>

                            <div class="col-12 col-md-5 d-flex flex-column pt-1 pt-md-0">
                            <div class="d-flex align-items-baseline mb-1">
                            <div class="poppins me-2 fs-14 text-secondary"> Item Quantity: </div>
                            <div class="poppins"> {{$singleOrder['itm-quan']}}</div>
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
                                    <div class="poppins"> {{ $singleOrder['fname']." ".$singleOrder['lname'] }}</div>
                                </div>
                                
                                <div class="row gx-0">

                                    <div class="col-12 col-lg-5">
                                        <div class="py-2 px-3 border-lg-right border-md-bottom d-flex align-items-center">
                                            <ion-icon class="text-secondary" name="call-outline"></ion-icon>
                                            <div class=" ms-2 fs-14 poppins">
                                                {{ $singleOrder['cnum'] }}
                                            </div>
                                            
                                        </div>
                                        
                                    </div>

                                    <div class="col-12 col-lg-7">
                                        <div class="py-2 px-3 d-flex align-items-center">
                                            <ion-icon class="text-secondary" name="mail-outline"></ion-icon>
                                            <div class=" ms-2 fs-14 poppins">
                                                {{ $singleOrder['email'] }}
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
                                                {{ $singleOrder['province'] }}
                                            </div>          
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                            <div class="poppins fs-14 text-secondary me-2 "> City: </div>
                                            <div class="poppins">
                                                {{ $singleOrder['city'] }}
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
                                                {{ $singleOrder['barang'] }}
                                            </div>          
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                            <div class="poppins fs-14 text-secondary me-2 "> Zipcode: </div>
                                            <div class="poppins">
                                                {{ $singleOrder['zipcode'] }}
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
                                                {{ $singleOrder['street'] }}
                                            </div>          
                                        </div>
                                    </div>

                                </div>

                            </div>

                            
                        </div>

                        {{-- Package Info --}}
                        <div class="row gx-0 mt-4">
                            <div class="poppins fw-medium titleBlue mb-1"> Package Details </div>
                            {{-- Box --}}
                            <div class="col-12 border rounded-3">

                            {{-- Weight and Package Quantity --}}
                            <div class="row gx-0">

                                <div class="col-12 col-lg-5">
                                    <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                        <div class="poppins fs-14 text-secondary me-2 "> Weight: </div>
                                        <div class="poppins">
                                            {{ $singleOrder['weight']." kg" }}
                                        </div>          
                                    </div>
                                </div>

                                <div class="col-12 col-lg-7">
                                    <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                        <div class="poppins fs-14 text-secondary me-2 "> Package Quantity: </div>
                                        <div class="poppins">
                                            {{ $singleOrder['quant'] }}
                                        </div>
                                    </div>     
                                </div>

                            </div>

                            {{-- Dimensions --}}
                            <div class="row gx-0">

                                <div class="col-12 col-lg-4">
                                    <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                        <div class="poppins fs-14 text-secondary me-2 "> Width: </div>
                                        <div class="poppins">
                                            {{ $singleOrder['wd']." cm" }}
                                        </div>          
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                        <div class="poppins fs-14 text-secondary me-2 "> Height: </div>
                                        <div class="poppins">
                                            {{ $singleOrder['hg']." cm" }}
                                        </div>          
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                        <div class="poppins fs-14 text-secondary me-2 "> Length: </div>
                                        <div class="poppins">
                                            {{ $singleOrder['lg']." cm" }}
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
                                            {{ "₱ ".number_format($singleOrder['itm-value'],0) }}
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

            {{-- Overlay --}}
            <div class="overlay m-0" style="z-index: 10" data-overlay2> </div>

            {{-- Cancel Dialog --}}
            <div class="col-9 col-sm-7 col-md-5 payView" style= "z-index: 11;" cancel-dialog>
                <form action="" method="post" id="canceller">
                    @csrf

                <div class="row g-0 mb-5">
                    <div class="position-relative">
                        <span class="poppins fw-medium border-bottom pb-1 border-dark-subtle d-block"> Proceed Cancellation </span>
                        <div class="closeBtn position-absolute" close-cancel>        
                            <ion-icon name="close" class="d-block fs-4"></ion-icon> 
                        </div>
                    </div>
                </div>

                <div class="row g-0">
                    <div class="position-relative">
                        <textarea name="reason" id="reason" cols="30" rows="4" class="w-100 rounded-4 poppins" placeholder="Type here..."></textarea>
                        <label for="reason" class="fs-14 poppins pe-2"> State the Reason </label>
                    </div>
                </div>

                <div class="row g-0 mt-3">
                    <div class="d-flex justify-content-end">
                        <button class="close rounded-3 me-3 fs-14" type="button" cancel-close2> Close </button>
                        <button class="btn btn-danger py-1 fs-14"> Cancel Order</button>
                    </div>
                </div>

                </form>
            </div>
            
        </div>
    </div>


</x-layout>

<script>

    $(function() {
   
        const overlay2 = document.querySelector("[data-overlay2]");

        // Cancel Pop-up

        const cancelClose = document.querySelector('[close-cancel]');
        const cancelOpen = document.querySelector('[cancel-open]');
        const cancelDia = document.querySelector('[cancel-dialog]');
        const cancelClose2 = document.querySelector('[cancel-close2]')

        const cancel = [cancelOpen, cancelClose, cancelClose2];
        for (let i = 0; i < cancel.length; i++) {
            cancel[i].addEventListener("click", function () {
            overlay2.classList.toggle("active");
            cancelDia.classList.toggle("active");
            });
        }
    


    })

</script>