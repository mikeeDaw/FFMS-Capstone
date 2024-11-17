@push('title')
    <title> Cancellations | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashUser.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
@endpush


<x-dashLayout>

    {{-- Order Cancellations --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-4">
                <p class="fs-4 poppins m-0"> Order Cancellations </p>
            </div>
        </div>
    </div>

    {{-- Order Counts --}}
    {{-- <div class=" g-0 row u-summ bot-margin shadow2 rounded-4">
        <div class="col-12 d-flex flex-column p-0">
            <div class="hrcol pb-3 ps-4 poppins"> Cancellations Summary </div>
            <div class="d-flex flex-row align-items-center flex-wrap  justify-content-between summ">

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-danger" name="trash-bin"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Cancelled Orders </small>
                        <h5>{{ $canCount }}</h5>
                    </div>
                    
                </div>

            </div>
        </div>
    </div> --}}

    {{-- Filter Section --}}
    {{-- <div class="g-0 row pt-3">
        <div class="d-flex flex-row mb-3 align-items-center bg-white w-auto ps-3 rounded-2 filterBox">

            <div class="poppins me-3 d-flex align-items-center">
                <ion-icon name="filter-outline"></ion-icon>
                <span class="ms-2 poppins"> Filter </span>
            </div>

            <select class="form-select shFilter poppins" aria-label="Default select example">
                <option value="ReviewCancel" {{$progress == 'cancelNotice' ? 'selected' : '' }} >
                    To Review
                </option>
                <option value="CancelledOrders" {{$progress == 'cancelled' ? 'selected' : '' }} >
                    Cancelled Orders
                </option>
            </select>

        </div>
    </div> --}}

    <form action="/shipments/cancels/update" method="post">
        @csrf
    {{-- Table --}}
    <div class="row g-0 u-summ tablewrapper bot-margin shadow2 rounded-4">
        <div class="poppins mb-3 cardtitle"> 
            
        </div>
        @if (count($shipments) != 0)
        <div class="col-12">
            <div class="table-responsive tblHeight">

                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> </th>
                            <th> Date Cancelled </th>
                            <th> Order ID </th>
                            <th> Customer </th>
                            <th> Item Description </th>
                            <th> Reason </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td> {{ $loop->iteration}} </td>
                                <td> {{ explode(' ',$shipment['dateUpd'])[0] }} </td>
                                <td class="clickRow" data-href={{'/dash/orders/'.$shipment['orderID']}}>
                                        {{ $shipment['orderID'] }} 
                                </td>
                                <td> {{ $shipment['customerName'] }} </td>
                                <td> {{ $shipment['itemDesc'] }} </td>
                                <td> {{ $shipment['reason'] }}</td>
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

    {{-- Submit Button --}}
    <div class="d-flex position-fixed align-items-center rounded-3 shUpd">
        <span class="poppins fw-medium" style="white-space: nowrap;"> Update Status </span>
        <button class="btn btn-primary ms-3" style="padding: 4px 15px;"> Save </button>
    </div>

    </form>

    
</x-dashLayout>

<script>
    $(function(){

        // Script for Update Status button
        $(':checkbox').change(function(){

            if($(':checkbox:checked').length == 0){
                
                $('.shUpd').css('top', '-65px');
            }else{
                $('.shUpd').css('top', '60px');
            }
        })

        // For clicking each order row
        $(".clickRow").click(function() {
            window.location = $(this).data("href");
        });

        // For Filtering
        // $('select').change(function(){
        //    var selValue = $(this).find(':selected').val();
        //     window.location = '/shipments/cancels/?progress=' + selValue;
        // })
    })


</script>