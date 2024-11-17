@push('title')
    <title> Resource Allocation | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashUser.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
    <script type="module" src= "{{ asset('/js/fireBase/shipments/allocate.js') }}" > </script>
@endpush

@php

    function returnDict($arr) {
        $strDict = '';

        foreach($arr as $bchID => $data){

            $strDict .= " '$bchID' : {";

            foreach($data as $key => $value){

                if($key == 'ordersList'){
                    $ordList = '';
                    foreach ($value as $shipID => $orderID) {
                        $ordList .= " '$shipID' : '$orderID' , ";
                    }
                    $strDict .= " '$key' : { $ordList } ,";
                } else if($key == 'cityGroup'){
                    $cityList = '';
                    foreach ($value as $city) {
                        $cityList .= " '$city', ";
                    }
                    $strDict .= " '$key' : [ $cityList ], ";
                }else {
                    $strDict .= " '$key' : '$value' ,";
                }

            }

            $strDict .= "} ,";


        }
        return $strDict;
    }

    function returnArr($arr) {
        $strDict = '';
        foreach($arr as $value){
            $strDict .= " '$value' ,";
        }
        return $strDict;
    }

    echo "<script type='text/javascript'>
        var batches = { ".returnDict($batches)." };
        var services = [ ".returnArr($serviceTyp)." ];
        </script>"; 

@endphp

<x-dashLayout>

    {{-- Header --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> Available Resources and Shipments </p>
            </div>
        </div>
    </div>

    {{-- Order Counts --}}
    <div class=" g-0 row u-summ bot-margin shadow2 rounded-4">
        <div class="col-12 d-flex flex-column p-0">
            <div class="hrcol pb-3 ps-4 poppins"> Resources Summary </div>

            <div class="d-flex flex-row align-items-center flex-wrap  justify-content-between bord-btm summ">
    

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    
                    <ion-icon class="me-3 text-warning" name="boat"></ion-icon>
                    
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Shipments to Allocate </small>
                        <h5> {{ count($batches) }} </h5>
                    </div>
                    
                </div>


            </div>
        </div>
    </div>

    <form action="/shipments/allocate/save" method="post" id='saveAlloc'>
        @csrf

    {{-- For Shipment Table --}}
    <div class="row g-0 u-summ tablewrapper bot-margin shadow2 rounded-4">
        <div class="poppins mb-3 cardtitle"> 
            FOR SHIPMENT
        </div>
        @if (count($batches) != 0)
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> Date Created</th>
                            <th class="text-center"> Category </th>
                            <th class="text-center"> Orders </th>
                            <th class="text-center"> Service Type</th>
                            <th class="text-center"> Driver </th>
                            <th class="text-center"> Vehicle </th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody id="allocTbl">
                        @foreach ($batches as $batchID => $batch)
                            <tr id="{{ $batchID }}">
                                <td class="align-middle"> {{ explode(' ',$batch['dateCreated'])[0] }} </td>
                                <td class="align-middle batchCateg"> 
                                    {{ $batch['category'] }} 
                                    <input type="hidden" name="batchID" value={{ $batchID }}>
                                </td>
                                <td class="align-middle"> 
                                    <div class="d-flex flex-column orderList">
                                        {{-- Foreach dito ng ORDERS --}}
                                        @foreach ($batch['ordersList'] as $shipID => $orderID)
                                        <span class="clickRow" data-href={{'/dash/orders/'.$orderID}}>
                                        <span class="fs-12 text-secondary poppins me-2">OID {{$loop->iteration}} :</span> {{ $orderID }} 
                                        </span>
                                        @endforeach
                                    </div>
                                </td>
                                
                                <td class="align-middle servType">
                                     {{ $batch['servType'] }}
                                     <input type="hidden" name="servType" value={{$batch['servType']}}>
                                </td>

                                {{-- Manual Allocation of Driver --}}
                                @if ( !$batch['driverID'])
                                <td class="align-middle"> 
                                    <select class="form-select drivSel" aria-label="Default select example"
                                    style="width: unset; border-color: #696969;" name='manualAllo[{{$batchID}}][driver]'>
                                        <option value='' disabled selected >- Select Driver -</option>
                                        <option value="XX" disabled> Please Wait...</option>
                                    </select>
                                    <input type="hidden" name="drivEmail" value=''>
                                </td>
                                @else
                                <td class="align-middle"> {{ $batch['driverID'] }}</td>
                                @endif

                                <input type="hidden" name='manualAllo[{{$batchID}}][serv-typ]' value= {{ $batch['servType'] }} disabled>

                                {{-- Manual Allocation of Vehicle --}}
                                @if (!$batch['vehicleID'])
                                <td class="align-middle"> 
                                    <select class="form-select vehiSel" aria-label="Default select example"
                                    style="width: unset; border-color: #696969;" name='manualAllo[{{$batchID}}][vehicle]'>
                                        <option value='' disabled selected>- Select Vehicle -</option>
                                        <option value="XX" disabled> Please Wait...</option>
                                    </select>
                                </td>
                                @else
                                <td class="align-middle"> {{ $batch['vehicleID'] }}</td>
                                @endif


                                {{-- Automatic Checkbox --}}
                                <td class="align-middle"> 
                                        <button type='button' class="btn btn-outline-success fs-14 poppins autoBtn"> Auto </button>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        @else
            <x-table-nodata />
        @endif
    </div>

    <div class="d-flex position-fixed align-items-center rounded-3 shUpd">
        <span class="poppins fw-medium" style="white-space: nowrap;"> Save Allocation </span>
        <button class="btn btn-primary ms-3" style="padding: 4px 15px;"> Save </button>
    </div>
    
    <input type="hidden" name="timeCanAllo" value='true' id='timeCanAllo'>

    </form>

</x-dashLayout>

<script>
    
    function checkDrops(selectName){
        var selected = $(selectName).find(':selected');
        // Show all options muna bago mag hide
        $(selectName).find('option').show();

        $.each($(selectName), function(){
            var self = this;
            var selectValue = $(this).val();

            $.each(selected, function() {
                if (selectValue !== $(this).val() ){
                    $(self).find('option[value="'+$(this).val()+'"]').hide()
                    $(self).find('option:disabled').show()
                } else{
                    $(self).find('option[value='+$(this).val()+']').show()
                }
            })

        })
    }

    $(function(){

        $('select[name$="[driver]"]').on('change', function(){

            checkDrops('select[name$="[driver]"]')
            $(this).css( ($(this).val() == '') ? {'color' : '#737272'} : {'color' : '#000'})

            // Enable hidden input
            var hidden = $(this).closest('tr').find('input[type=hidden]');
            hidden.prop('disabled', false);
            
            // Remove check on 'auto' if user entered input in manual allocation
            // var aCheck = $(this).closest('tr').find('td .form-check input')
            //     if(aCheck.is(':checked')){
            //         aCheck.prop('checked', false);
            //     }

            // Show submit button if theres item selected
            if($('select option:not(:disabled):selected').length == 0){
                $('.shUpd').css('top', '-65px');
            } else{
                $('.shUpd').css('top', '60px');
            }
        });

        $('select[name$="[vehicle]"]').on('change', function(){

                checkDrops('select[name$="[vehicle]"]')
                $(this).css( ($(this).val() == '') ? {'color' : '#737272'} : {'color' : '#000'})

                // Enable hidden input
                var hidden = $(this).closest('tr').find('input[type=hidden]');
                hidden.prop('disabled', false);

                // Show submit button if theres item selected
                if($('select option:not(:disabled):selected').length == 0){
                    $('.shUpd').css('top', '-65px');
                } else{
                    $('.shUpd').css('top', '60px');
                }
        });

        // Checkbox functions
        $(':checkbox').change(function() {
            // If 'auto' checkbox is checked, remove values in manual allocation
            
            var hidden = $(this).closest('tr').find('input[type=hidden]');

            if($(this).is(':checked')){
                var dSel = $(this).closest('tr').find('td select[name$="[driver]"]');
                var vSel = $(this).closest('tr').find('td select[name$="[vehicle]"]');
                $(dSel).val('');
                $(vSel).val('');

                checkDrops('select[name$="[driver]"]')
                checkDrops('select[name$="[vehicle]"]')
            } else{
                hidden.prop('disabled', true);
            }
            // Show submit button if 'auto' is checked
            if($(':checkbox:checked').length == 0 && $('select option:not(:disabled):selected').length == 0 ){     
                $('.shUpd').css('top', '-65px');
            }else{
                $('.shUpd').css('top', '60px');
            }

        })

        // For clicking each order row
        $(".clickRow").click(function() {
            window.location = $(this).data("href");
        });

    })
</script>