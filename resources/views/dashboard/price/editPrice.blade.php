@push('title')
    <title> Edit Pricings | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashPrice.css') }}">
@endpush
@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

@endpush

<x-dashLayout>

        {{-- Header --}}
        <div class="row g-0 mb-4">
            <div class="d-flex flex-row justify-content-start align-items-center">
                <div class="ms-1 me-5">
                    <p class="fs-4 poppins m-0"> Edit Prices </p>
                </div>
            </div>
        </div>

        <div class="row gx-0">
            <div class="col-12 boxes mb-4 mb-md-0">
                <form action={{"/dash/prices/edit/".$category}} method="post">
                    @csrf
                <div class="cardtitle poppins mb-3 mt-2">
                    <div class="d-flex justify-content-between">
                        <div> SERVICE TYPE COST </div>
                    </div> 
                    
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                      <tr class="table-dark poppins fw-medium">
                        <td class="w-50 text-start">Service Type</th>
                        <td class="w-50 text-center">Cost</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($priceList as $key => $item)
                     {{-- @php
                         $prctype = match($key){
                            'TrackHead' => 'Tractor Head',
                            'Chassis20' => '20ft Chassis',
                            'Chassis40' => '40ft Chassis',
                            'Truck10W' => '10 Wheeler Truck',
                            'WingVan' => 'Wing Van',
                            'ClVan4' => '4 Wheeler Closed Van',
                            'ClVan6' => '6 Wheeler Closed Van',
                            'Box10' => '10kg Box',
                            'Box25' => '25kg Box',
                            'Envelope' => 'Envelope',
                            'ReusePak' => 'Reusable Pak',
                            'Tube' => 'Tube',
                            'Insurance' => 'Insurance',
                            'ServiceChg' => 'Service Charge',
                            'TotalCost' => 'Total Cost',
                         }
                     @endphp --}}
                        <tr>
                            <td class="text-start">{{$key}}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center prcInput">
                                    <div>
                                        @if ($category != 'charges')
                                            PHP
                                        @else
                                            %
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <input type="text" value= @if ($category != 'charges')
                                        {{$item}}
                                        @else
                                        {{$item*100}}
                                        @endif name={{$key}} autocomplete="off">
                                    </div>
                                </div>
                            </td> 
                        </tr>        
                    @endforeach
                    </tbody>
                  </table>
                {{-- Bottom buttons --}}
                <div class="row">
                    <div class="col-12">
                        <div class="infoDiv d-flex justify-content-end" style="gap:20px">
                            <button type="button" class="cancel" > Reset </button>
                            <button class="submit"> Save </button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>

</x-dashLayout>

<script>
    var values = new Array()

    $('input[type=text]').map(function(idx, elem){
        values.push($(elem).val());
    });

    $('.cancel').click( function(){

        $('input[type=text]').each(function(idx, elem){
        $(this).val(values[idx])
        });

    });

</script>