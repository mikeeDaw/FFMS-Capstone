@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/editOrder.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
    <script type="module" src= "{{ asset('/js/fireBase/orderInfo/editOrd.js') }}" defer> </script>
@endpush

<x-dashLayout>
    @php
        echo "<script type='text/javascript'>
        var orderID = \"${order['orderID']}\";
        </script>"; 
    @endphp

    {{-- Header --}}
    <div class="row g-0 bot-margin mb-4">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> Edit Package / Service Details </p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-0 ms-0 ms-md-4">

            {{-- Order Info --}}
            <div class="col-12 col-md-9 col-xl-7 p-0 p-sm-3 p-md-0 ">
                <div class="">

                    <div class="row gx-0">
                        
                    <div class="p-3 pt-2 border rounded-4 bg-white">
                        <div class="border-bottom py-2 fs-6 poppins mb-3 d-flex align-items-baseline text-secondary justify-content-start"> 
                            
                            <div class="poppins me-2 fs-14"> Order ID:  </div>
                            <div class="poppins fs-14"> {{ $order['orderID'] }} </div>
                            

                        </div>

                        

                        {{-- Order Desciption Area --}}
                        <div class="row">
                            <div class="col-12 col-md-7 d-flex flex-column">
                            <div class="d-flex align-items-baseline mb-1">
                            <div class="poppins me-2 fs-14 text-secondary"> Item Description: </div>
                            <div class="poppins"> {{ $order['itm-desc']}}</div>
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

                        <form action='' method="post" id="saveForm">
                            @csrf
                        {{-- Package Info --}}
                        <div class="row gx-0 mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="poppins fw-medium titleBlue mb-1"> Package Details </div>
                            </div>

                            {{-- Box --}}
                            <div class="col-12 border rounded-3">

                                {{-- Weight and Package Quantity --}}
                                <div class="row gx-0">

                                    <div class="col-12 col-lg-5">
                                        <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                            <div class="poppins fs-14 text-secondary me-2 "> Weight (kg): </div>
                                            <input type="text" name="weight" value={{$order['weight']}} class="inputOrd num-input">          
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-7">
                                        <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                            <div class="poppins fs-14 text-secondary me-2 "> Package Quantity: </div>
                                            <input type="text" name="packQuant" value={{$order['quant']}} class="inputOrd num-input">
                                        </div>     
                                    </div>

                                </div>

                                {{-- Dimensions --}}
                                <div class="row gx-0">

                                    <div class="col-12 col-lg-6 col-xl-4">
                                        <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                            <div class="poppins fs-14 text-secondary me-2 "> Width (cm): </div>
                                            <input type="text" name="width" value={{$order['wd']}} class="inputOrd num-input">
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6 col-xl-4">
                                        <div class="py-2 px-3 border-lg-right border-bottom d-flex align-items-center">
                                            <div class="poppins fs-14 text-secondary me-2 "> Height (cm): </div>
                                            <input type="text" name="height" value={{$order['hg']}} class="inputOrd num-input">        
                                        </div>
                                    </div>

                                    <div class="col-12 col-xl-4">
                                        <div class="py-2 px-3 d-flex align-items-center border-bottom">
                                            <div class="poppins fs-14 text-secondary me-2 "> Length (cm): </div>
                                            <input type="text" name="length" value={{$order['lg']}} class="inputOrd num-input">
                                        </div>     
                                    </div>

                                </div>

                                {{-- Packaging Type --}}
                                <div class="row gx-0">
                                    <div class="row align-items-baseline px-3 py-2 border-bottom">
                                        <div class="poppins fs-14 text-secondary me-2 col-12 col-sm-4"> Packaging Type: </div>
                                        <select class="form-select w-auto poppins col-12 col-sm-8" aria-label="Default select example" name="updPack">
                                            <option value="Box10" {{ $order['itm-pack'] == "Box10" ? 'selected' : '' }}>10kg Box</option>
                                            <option value="Box25" {{ $order['itm-pack'] == "Box25" ? 'selected' : '' }}>25kg Box</option>
                                            <option value="Envelope" {{ $order['itm-pack'] == "Envelope" ? 'selected' : '' }}>Envelope</option>
                                            <option value="ReusePak" {{ $order['itm-pack'] == "ReusePak" ? 'selected' : '' }}>Reusable Pak</option>
                                            <option value="Tube" {{ $order['itm-pack'] == "Tube" ? 'selected' : '' }}>Tube</option>
                                            </select>
                                    </div>
                                </div>

                                {{-- Service Type --}}
                                <div class="row gx-0">
                                    <div class="row align-items-baseline px-3 py-2 ">
                                        <div class="col-12 col-sm-4 poppins fs-14 text-secondary me-2 "> Service Type: </div>

                                        <select class="form-select w-auto poppins col-12 col-sm-8" aria-label="Default select example" name="updServ">
                                        <option value="TrackHead" {{ $order['serv-typ'] == "TrackHead" ? 'selected' : '' }}>Tractor Head</option>
                                        <option value="Chassis20" {{ $order['serv-typ'] == "Chassis20" ? 'selected' : '' }} >20ft Chassis</option>
                                        <option value="Chassis40" {{ $order['serv-typ'] == "Chassis40" ? 'selected' : '' }}>40ft Chassis</option>
                                        <option value="Truck10W" {{ $order['serv-typ'] == "Truck10W" ? 'selected' : '' }}>10 Wheeler Truck</option>
                                        <option value="WingVan" {{ $order['serv-typ'] == "WingVan" ? 'selected' : '' }}>Wing Van</option>
                                        <option value="ClVan4" {{ $order['serv-typ'] == "ClVan4" ? 'selected' : '' }}>4 Wheeler Closed Van</option>
                                        <option value="ClVan6" {{ $order['serv-typ'] == "ClVan6" ? 'selected' : '' }} >6 Wheeler Closed Van</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-primary py-1 fs-14">
                                    Save
                                </button>
                            </div>
                            
                        </div>

                        </form>
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

                    </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-dashLayout>