@push('title')
    <title> Create Order - Step 2 | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/orderform.css') }}">
@endpush
@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <!-- Leaflet -->
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script type="module" src= "{{ asset('/js/fireBase/orders/orderS2.js') }}"> </script>
@endpush
<x-layout>

    @php
        $reciever = session('Consignee');

        function returnDict($arr) {
            $strDict = '';

            foreach($arr as $key => $value){
                $strDict .= " '$key' : '$value' ,";
            }
            return $strDict;
        }  

        echo "<script type='text/javascript'>
        var reciever = { ".returnDict($reciever)." };
        var currUser = \"".session('uid')."\";
        </script>"; 
    @endphp

    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>

    <div id="loadOverlay"> </div>

    <div id="loadArea">
        <div id="boxLoad">
            <div class="custom-loader2"></div>
            <span class="poppins mt-4 text-center"> Calculating Cost...</span>
        </div>
    </div>


    <!-- Fill Up Fields -->
    <div class="container-md ">

        <div class="row gx-0 justify-content-around align-items-start">

             <!-- Progress Bar -->
             <div class="col-lg-2 col-sm-12">
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
                            <span class="d-flex align-items-center blockingDesc active">
                                <span class="circlePg active"> 
                                    <ion-icon class="iconPG active" name="cube-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc active">
                                    What
                                </span>
                            </span>

                            {{-- Checkout --}}
                            <span class="d-flex align-items-center blockingDesc" >
                                <span class="circlePg"> 
                                    <ion-icon class="iconPG" name="card-outline"></ion-icon> 
                                </span>
                                <span class="poppins fw-medium progDesc">
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
                        <span class="circlePg active"> 
                            <ion-icon class="iconPG active" name="cube-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc active">
                            What
                        </span>
                    </div>

                    <span class="d-block vertProgLine"> </span>

                    
                    <div class="d-flex align-items-center">
                        <span class="circlePg"> 
                            <ion-icon class="iconPG" name="card-outline"></ion-icon> 
                        </span>
                        <span class="poppins fw-medium progDesc">
                            Checkout
                        </span>
                    </div>

                </div>
            </div>

            {{-- FillUp Form --}}
            <div class="col-xl-6 col-lg-7 col-md-10 col-sm-9 pt-4">
            <form class="formBox" action="/step2/order" method="post" id='s2form'>
                @csrf
            {{-- Header --}}
            <div class="row f-head g-0">
                <div class="col-md-12 pb-2 px-5 pt-4 d-flex align-items-center" style="gap:20px;">
                    <a href="#"> <ion-icon name="briefcase-outline" class="bk-i"></ion-icon> </a>
                    <h5> Package Details</h5>
                </div>

            </div>

            <div class="fillUp">
            <div class="row justify-content-between">
                <div class="col-md-8">
                    <label for="itm-desc"> Item Description</label>
                    <input type="text" name="itm-desc" value="{{session('Package')['itm-desc'] ?? old('itm-desc')}}" autocomplete="off" id='itm-desc'>
                    @error('itm-desc')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="itm-quan"> Item Quantity</label>
                    <input type="number" name="itm-quan" value="{{session('Package')['itm-quan'] ?? old('itm-quan')}}" autocomplete="off" id='itm-quan'>
                    @error('itm-quan')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="itm-pack"> Packaging </label>
                    <select class="form-select f-sel" aria-label="Default select example" name="itm-pack" id='itm-pack' required>
                    <option value='' disabled selected>Select Packaging</option>                  
                    <option value="wait" disabled> Please Wait...</option>
                    </select>
                    
                </div>
                <div class="col-md-4">
                    <label for="itm-categ"> Goods Category </label>
                    <select class="form-select f-sel" aria-label="Default select example" name="itm-categ" id='itm-categ' required>
                    <option value='' disabled selected>Select Category</option>
                    <option value="Perishable">Perishable</option>
                    <option value="Fragile">Fragile</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Industrial">Industrial</option>
                    <option value="Consumer">Consumer</option>
                    </select>

                </div>
                <div class="col-md-4">
                    <label for="itm-value"> Item Declared Value </label>
                    <input type="text" name="itm-value" value="{{ session('Package')['itm-value'] ?? old('itm-value')}}" autocomplete="off" id='itm-value'>
                    @error('itm-value')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="quant"> Package Quantity</label>
                    <input type="number" name="quant" value="{{session('Package')['quant'] ?? old('quant')}}" autocomplete="off" id='quant'>
                    @error('quant')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name='serv-typ' id='serv-typ' value="">
                <input type="hidden" name='packVol' id='packVol' value="">

                <div class="col-md-4">
                    <label for="weight"> Weight <span class="unit"> kg </span> </label>
                    <input type="text" name="weight" value="{{session('Package')['weight'] ??  old('weight')}}" autocomplete="off" id='weight'>
                    @error('weight')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
  
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="lg"> Length <span class="unit"> cm </span></label>
                    <input type="text" name="lg" value="{{session('Package')['lg'] ??  old('lg')}}" autocomplete="off" id='lg'>
                    @error('lg')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="wd"> Width <span class="unit"> cm </span></label>
                    <input type="text" name="wd" value="{{session('Package')['wd'] ?? old('wd')}}" autocomplete="off" id='wd'>
                    @error('wd')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="hg"> Height <span class="unit"> cm </span></label>
                    <input type="text" name="hg" value="{{session('Package')['hg'] ??  old('hg')}}" autocomplete="off" id='hg'>
                    @error('hg')
                    <p class="v-err"> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <label for="note"> Notes </label>
                    <textarea class="form-control nt" name="note" rows="3"> {{session('Package')['note'] ?? old('note')}} </textarea>
                </div>
            </div>

            <input type="hidden" name="distance" value=''/>
            <input type="hidden" name="route" value=''/>

            </div>

            <hr style="width:90%; margin:auto">

            
        <!-- Bottom Buttons -->
            <div style="padding: 20px 25px 10px 25px;">
                <div class="row justify-content-between m-auto">
                    <div class="col-4 col-sm-3">
                    <a href="/order/step1" class="d-flex align-items-center" style="gap:10px;">
                        <ion-icon name="arrow-back-circle-outline" class="bk-i"></ion-icon>
                        <h5 class="m-0 back"> Back</h5>
                    </a>
                    </div>

                    <div class="col-3 d-flex justify-content-end">

                        <button class="d-flex align-items-center cont-btn" id='nextBtn'> 
                            <span> Next </span>
                            <ion-icon name="chevron-forward-outline"style="transition: .2s linear;">
                            </ion-icon>
                        </button>

                    </div>
                </div>
            </div>

            </form>
            </div>

        </div>
    </div>

    <div id="map" class="d-none"></div>

</x-layout>