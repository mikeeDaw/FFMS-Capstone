@push('title')
    <title> Sales Report | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/reports.css') }}">
@endpush

@push('script')
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endpush

@php
    $dates = array_keys($weekly);
    $values = array_values($weekly);
    echo "<script>";
    echo "var datesLbl = ['".implode("','",$dates)."'];";
    echo "var dataVals = ['".implode("','",$values)."'];";
    echo "</script>";

    $weekOpt = ['1','2','3','4','5'];
    $monthOpt = ['January', 'February', 'March', 'April', 'May', 'June', 'July','August', 'September',
                'October', 'November', 'December'];

@endphp
<x-dashLayout>


    {{-- Header --}}
    <div class="row g-0">
        <div class="col-12 bg-white bordered px-4 py-3 shadow2 rounded-4 mb-4">

            <div class="row g-0">
                {{-- Date Range --}}
                <div class="col-12 col-sm-4 col-lg-5 mb-4 mb-sm-0">
                    <div class="d-flex flex-column">
                        <span class="text-secondary fs-14"> 
                            Reports Range 
                        </span>
                        <div>
                            <span class="fs-4 poppins">
                                {{ reset($dates)." - ".explode(' ', end($dates))[1] }} 
                        </span>
                        <span class="fs-14 poppins fw-medium text-secondary">
                                {{ $year }}
                        </span>
                        </div>

                    </div>
                </div>

                {{-- Total Sales, Daily Ave, & Orders --}}
                <div class="col-12 col-sm-8 col-lg-7 d-flex justify-content-center flex-wrap">
                        {{-- Total Sales--}}
                        <div class="d-flex align-items-center justify-content-center flex-grow-1 p-2 p-md-0">
                            <div class="d-flex p-2 rounded-circle bg-primary-subtle me-3">
                                <ion-icon class="headerIcon text-primary" name="wallet"></ion-icon>
                            </div>
        
                            <div class="d-flex flex-column">
                                <span> ₱ {{ number_format(array_sum($values),2) }} </span>
                                <span class="fs-13 poppins text-secondary"> Total Sales</span>
                            </div>
                        </div>
                        {{-- Daily Average --}}
                        <div class="d-flex align-items-center justify-content-center flex-grow-1 p-2 p-md-0">
                            <div class="d-flex p-2 rounded-circle bg-info-subtle me-3">
                                <ion-icon class="headerIcon text-info" name="golf"></ion-icon>
                            </div>
        
                            <div class="d-flex flex-column">
                                <span> ₱ {{ number_format(array_sum($values) / count($values) ,2) }}</span>
                                <span class="fs-13 poppins text-secondary"> Daily Average</span>
                            </div>
                        </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Filter Section --}}
    <div class="g-0 row pt-3">

        {{-- Week --}}
        <div class="d-flex flex-row mb-3 align-items-center bg-white w-auto ps-3 me-3 rounded-2 filterBox">

            <div class="poppins me-3 d-flex align-items-center">
                <ion-icon name="filter-outline"></ion-icon>
                <span class="ms-2 poppins"> Week </span>
            </div>

            <select class="form-select shFilter poppins" id="week" aria-label="Default select example">
                @foreach ($weekOpt as $opt)
                    <option value={{"Week$opt"}} {{ $week == "Week$opt" ? 'selected' : ''}} >
                        Week {{$opt}}
                    </option>
                @endforeach
            </select>



        </div>
        {{-- Month --}}
        <div class="d-flex flex-row mb-3 align-items-center bg-white w-auto ps-3 me-3 rounded-2 filterBox">

            <div class="poppins me-3 d-flex align-items-center">
                <ion-icon name="filter-outline"></ion-icon>
                <span class="ms-2 poppins"> Month </span>
            </div>

            <select class="form-select shFilter poppins" id='month' aria-label="Default select example">
                @foreach ($monthOpt as $opt)
                    <option value={{$opt}} {{ $month == $opt ? 'selected' : ''}}>
                        {{$opt}}
                    </option>
                @endforeach
            </select>

        </div>
        {{-- Year --}}
        <div class="d-flex flex-row mb-3 align-items-center bg-white w-auto ps-3 me-3 rounded-2 filterBox">

            <div class="poppins me-3 d-flex align-items-center">
                <ion-icon name="filter-outline"></ion-icon>
                <span class="ms-2 poppins"> Year </span>
            </div>

            <select class="form-select shFilter poppins" id="year" aria-label="Default select example">
                @foreach ($yearOpt as $opt)
                    <option value={{$opt}} {{ $year == $opt ? 'selected' : ''}}>
                        {{$opt}}
                    </option>
                @endforeach
            </select>

        </div>

        {{-- Submitter --}}
        <div class="d-flex mb-3 align-items-center me-3 w-auto">
            <button class="btn btn-primary ms-2 py-1 poppins fs-14 text-light" id='filterGo'> Submit </button>
        </div>


    </div>

    {{-- Line Chart --}}
    <div class="row g-0">
        <div class="col-12 col-md-10 col-xl-8 bg-white p-4 rounded-4 mb-4 shadow2">

            <div class="poppins">
                Sales Chart
            </div>

            <div>
                <canvas id='lineChart' style="min-height: 400px;"></canvas>
            </div>

        </div>
    </div>


</x-dashLayout>

<script src="{{ asset('/js/charts/lineChart.js') }}"></script>

<script>

    $(function() {

        $('#filterGo').on('click', function() {
            var weekVal = $('#week').val()
            var monVal = $('#month').val()
            var yearVal = $('#year').val()

            if(yearVal && monVal){
                window.location = '/reports/sales/?week=' + weekVal + '&month=' + monVal + '&year=' + yearVal
            } else {
                alert('Month and Year Required.')
            }
        })

    })

</script>