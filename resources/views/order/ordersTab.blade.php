@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/orderTab.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
@endpush

<x-dashLayout>

    {{-- Header --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> Customer Orders </p>
            </div>
        </div>
    </div>

    {{-- Counts and New Orders --}}
    <div class="row gx-0 mt-3 justify-content-evenly">

        {{-- 2 Boxes --}}
        <div class="col-12 col-sm-3">
            <div class="row gx-0">

                <div class="col-12 border rounded-4 bg-white mb-3">
                    <div class="d-flex flex-column">
                        <div class="row gx-0 border-bottom pt-2 pb-1 text-center bg-info-subtle rounded-top-4">
                            <div class="poppins fs-14"> Orders Today </div>
                        </div>
                        <div class="row text-center ">
                            <div class="fs-2 fw-medium py-3 text-info"> {{ $newCount }} </div>
                        </div>

                    </div>
                </div>

                <div class="col-12 border rounded-4 bg-white">
                    <div class="d-flex flex-column">
                        <div class="row gx-0 border-bottom pt-2 pb-1 text-center bg-info-subtle rounded-top-4">
                            <div class="poppins fs-14"> Payments to Verify </div>
                        </div>
                        <div class="row text-center ">
                            <div class="fs-2 fw-medium py-3"> 0 </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- New Orders Table --}}
        <div class="col-12 col-sm-8 bg-white border rounded-4 newOrd">
            <div class=""> 
                <div class="poppins px-3 pt-3 pb-2 fs-14 fw-medium headcol"> 
                    NEW ORDERS
                </div>  
            </div>
            @if(!$newOrders)
                <x-table-nodata />
            @else
            <div class="col-12">
                <div class="px-3 py-2 table-responsive newOrdHT">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th></th>
                            <th>Item Description</th>
                            <th>Goods Category</th>
                            <th>Time Created</th>
                            <th>Status</th>
                          </tr>
                        </thead>

                        <tbody>
                        @foreach ($newOrders as $item)
                            <tr class="clickRow" data-href={{'/dash/orders/'.$item['orderID']}}>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['itm-desc'] }}</td>
                                <td>{{ $item['itm-categ'] }}</td>
                                <td>{{ $item['timeCreated'] }}</td>
                                
                                @if ( $item['payStatus'])
                                    <td class="text-success fst-italic"> {{ "Paid "}} </td>  
                                    @else
                                    <td class="text-danger-emphasis fst-italic"> {{ "Unpaid "}} </td>
                                @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                      </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-11 bg-white border rounded-4 ordList">

            <div class="poppins px-3 pt-3 pb-2 fs-14 fw-medium headcol"> 
                ORDERS LIST
            </div>  

            @if(!$orderList)
            <x-table-nodata />
            @else
            <div class="col-12">
                <div class="px-3 py-2 table-responsive ordList">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Date Created</th>
                            <th>Item</th>
                            <th>Goods Category</th>
                            <th>Pay Method</th>
                            <th>Status</th>
                            <th>Payment</th>
                        </tr>
                        </thead>
                        {{-- {{dd($orderList)}} --}}
                        <tbody>
                        @foreach ($orderList as $item)
                            <tr class="clickRow" data-href={{'/dash/orders/'.$item['orderID']}}>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['CreatedAt'] }}</td>
                                <td>{{ $item['itm-desc'] }}</td>
                                <td>{{ $item['itm-categ'] }}</td>
                                <td>{{ $item['pay'] }}</td>

                                @switch($item['status'])
                                    @case(1)
                                        <td class="text-success"> {{'Completed'}} </td>
                                        @break
                                    @case(2)
                                        <td class="text-warning"> {{'Cancelled'}} </td>
                                        @break
                                    @default
                                        <td> {{'In Progress'}} </td>
                                        
                                @endswitch

                                @if ( $item['payStatus'])
                                    <td class="text-success fst-italic"> {{ "Paid "}} </td>  
                                    @else
                                    <td class="text-danger-emphasis fst-italic"> {{ "Unpaid "}} </td>
                                @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

</x-dashLayout>

<script>
    $(".clickRow").click(function() {
        window.location = $(this).data("href");
    });
</script>