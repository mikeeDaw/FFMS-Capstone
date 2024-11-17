@push('title')
    <title> Driver Ratings | Lebria Transport</title>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/driverRating.css') }}">
@endpush

@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
@endpush

@php
    $top3 = array_slice($driverRate,0,3);

    foreach ($driverRate as $ratings) {
        $overall[] = $ratings['ratingAvg'];
        $timeli[] = $ratings['timelinessRating'];
        $handling[] = $ratings['handlingRating'];
        $profes[] = $ratings['professionalismRating'];
    }

    $overall = round(array_sum($overall) / count($overall),1);
    $timeli = round(array_sum($timeli) / count($timeli),1);
    $handling = round(array_sum($handling) / count($handling),1);
    $profes = round(array_sum($profes) / count($profes),1);

@endphp

<x-dashLayout>

    {{-- Header --}}
    <div class="row g-0 mb-3">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> Driver Ratings </p>
            </div>
        </div>
    </div>

    <div class="row g-0">
        {{-- Top 3 --}}
        <div class="col-12 col-md-8 pe-0 pe-md-3 mb-3 mb-md-0">
            <div class="bg-white rounded-4 p-4 d-flex flex-column" style=" box-shadow: 0 5px 25px -4px #adadad;">

            {{-- Top 3 Title --}}
            <div class="row g-0 justify-content-between">
                <div class="col-12 col-md-3 poppins fw-medium text-secondary mb-2">
                    TOP 3 DRIVERS
                </div>

                <div class="col-12 col-md-8 d-flex flex-column mb-2">
                    <div class="row g-0">
                        <div class="col-7 d-flex align-items-center">
                            <span class="bg-primary legend"> </span>
                            <span class="poppins fs-14"> Professionalism </span>
                        </div>
                        <div class="col-5 d-flex align-items-center">
                            <span class="bg-success legend"> </span>
                            <span class="poppins fs-14"> Handling </span>
                        </div>

                    </div>
                    <div class="row g-0">
                        <div class="col-7 d-flex align-items-center">
                            <span class="bg-info legend"> </span>
                            <span class="poppins fs-14"> Overall Rating</span>
                        </div>

                        <div class="col-5 d-flex align-items-center">
                            <span class="bg-danger legend"> </span>
                            <span class="poppins fs-14"> Timeliness </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Drivers --}}
            <div class="row g-0 justify-content-evenly mt-5 mt-sm-4 mb-3">

                @foreach ($top3 as $key => $person)
                    
                    @php
                        $profRate = $person['professionalismRating']*20;
                        $handRate = $person['handlingRating']*20;
                        $timeRate = $person['timelinessRating']*20;
                        $avgRate = $person['ratingAvg']*20;
                        $fname = explode(' ', $key)[0];
                        $lname = explode(' ', $key)[1]
                    @endphp

                    <div class="col-6 col-sm-4 d-flex flex-column mb-4 mb-sm-0">

                        {{-- Progress Bars --}}
                        <div class="d-flex justify-content-center" style="gap: 6px;">
                            {{-- Professionalism --}} 
                            <div class="d-flex flex-column align-items-center">
                                <div class="progress progress-bar-vertical">
                                    <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow={{$profRate}} aria-valuemin="0" aria-valuemax="100" style="height: {{$profRate}}%;">
                                    </div>
                                </div>

                                <div class="d-flex flex-column mt-2 align-items-center">
                                    <span class="text-warning-emphasis"> {{ round($person['professionalismRating'],1) }}</span>
                                </div>
                            </div>

                            {{-- Handling --}}
                            <div class="d-flex flex-column align-items-center">
                                <div class="progress progress-bar-vertical">
                                    <div class="progress-bar progress-bar-animated bg-success progress-bar-striped" role="progressbar" aria-valuenow={{$handRate}} aria-valuemin="0" aria-valuemax="100" style="height: {{$handRate}}%;">
                                    </div>
                                </div>

                                <div class="d-flex flex-column mt-2 align-items-center">
                                    <span class="text-warning-emphasis"> {{ round($person['handlingRating'],1) }}</span>
                                </div>
                            </div>

                            {{-- Timeliness --}}
                            <div class="d-flex flex-column align-items-center">
                                <div class="progress progress-bar-vertical">
                                    <div class="progress-bar progress-bar-animated bg-danger progress-bar-striped" role="progressbar" aria-valuenow={{$timeRate}} aria-valuemin="0" aria-valuemax="100" style="height: {{$timeRate}}%;">
                                    </div>
                                </div>

                                <div class="d-flex flex-column mt-2 align-items-center">
                                    <span class="text-warning-emphasis"> {{ round($person['timelinessRating'],1) }} </span>
                                </div>
                            </div>

                            {{-- Average --}}
                            <div class="d-flex flex-column align-items-center">
                                <div class="progress progress-bar-vertical">
                                    <div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow={{$avgRate}} aria-valuemin="0" aria-valuemax="100" style="height: {{$avgRate}}%;">
                                    </div>
                                </div>

                                <div class="d-flex flex-column mt-2 align-items-center">
                                    <span class="text-warning-emphasis"> {{ round($person['ratingAvg'],1) }} </span>
                                </div>
                            </div>

                        </div>
                        
                        {{-- Driver Name --}}
                        <div class="d-flex flex-column align-items-center mt-3">
                            {{-- Letter Thing --}} 
                            <div class="poppins rounded-circle border  border-info fs-5 text-info text-center mb-2" style="padding: 8px 0; width: 51px;"> 
                                {{ mb_substr($fname, 0, 1) }}
                            </div>
                            {{-- Last Name --}}
                            <div class="poppins">
                                {{ $lname }},
                            </div>
                            {{-- First Name --}}
                            <div class="poppins">
                                {{ $fname }}
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>

            </div>
        </div>

        {{-- All Drivers Ave --}}
        <div class="col-12 col-md-4 mt-4 mt-md-0">
            <div class="px-0 ps-md-3 pe-xl-4">
            
            <div class="row gy-3 gx-0 justify-content-center justify-content-sm-around">

                <div class="col-12 border-bottom border-dark-subtle border-1 pb-2 mb-3">
                    <span class="poppins fs-6"> All Drivers Average Rating</span>
                </div>

                {{-- Overall --}}
                <div class="col-10 col-sm-5 col-md-12 bg-white border-start d-flex py-3 px-4 align-items-center border-start border-info border-5">
                    <span class="poppins"> Overall Rating </span>

                    <div class="d-flex align-items-center {{ $overall >= 3 ? 'text-success' : 'text-danger'}} ms-4">
                        <span class="fs-4 me-2"> {{ $overall }}</span>
                        <ion-icon class="fs-4" name="star"></ion-icon>
                    </div>
                </div>

                {{-- Handling --}}
                <div class="col-10 col-sm-5 col-md-12 bg-white border-start d-flex py-3 px-4 align-items-center border-start border-success border-5">
                    <span class="poppins"> Handling Rating </span>

                    <div class="d-flex align-items-center {{ $handling >= 3 ? 'text-success' : 'text-danger'}} ms-4">
                        <span class="fs-4 me-2"> {{ $handling }} </span>
                        <ion-icon class="fs-4" name="star"></ion-icon>
                    </div>
                </div>

                {{-- Professionalism --}}
                <div class="col-10 col-sm-5 col-md-12 bg-white border-start d-flex py-3 px-4 align-items-center border-start border-primary border-5">
                    <span class="poppins text-break"> Professionalism </span>

                    <div class="d-flex align-items-center {{ $profes >= 3 ? 'text-success' : 'text-danger'}} ms-4">
                        <span class="fs-4 me-2"> {{ $profes }} </span>
                        <ion-icon class="fs-4" name="star"></ion-icon>
                    </div>
                </div>

                {{-- Timeliness --}}
                <div class="col-10 col-sm-5 col-md-12 bg-white border-start d-flex py-3 px-4 align-items-center border-start border-danger border-5">
                    <span class="poppins text-break"> Timeliness </span>

                    <div class="d-flex align-items-center {{ $timeli >= 3 ? 'text-success' : 'text-danger'}} ms-4">
                        <span class="fs-4 me-2"> {{ $timeli }} </span>
                        <ion-icon class="fs-4" name="star"></ion-icon>
                    </div>
                </div>

            </div>

            </div>
        </div>

    </div>

    <div class="row g-0">

        {{-- Table --}}
        <div class="row g-0 bg-white border p-4 tablewrapper bot-margin rounded-4 mt-5 shadow2 rounded-4">
            <div class="poppins fw-medium mb-3 text-secondary"> 
                INDIVIDUAL RATINGS
            </div>
            @if (count($driverRate) != 0)
            <div class="col-12">
                <div class="table-responsive tblHeight">

                    <table class="table table-hover table-striped table-bordered">
                        <thead class="">
                            <tr>
                                <th> </th>
                                <th class="poppins fw-medium"> Name </th>
                                <th class="text-center poppins fw-medium"> Handling </th>
                                <th class="text-center poppins fw-medium"> Professionalism </th>
                                <th class="text-center poppins fw-medium"> Timeliness </th>
                                <th class="text-center poppins fw-medium" style="white-space: nowrap;"> Overall Rating </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($driverRate as $name => $driver)

                                <tr class="clickRow" data-href={{'/dashboard/drivers/'.$driver['driverID']}} >
                                    <td class="text-center"> {{ $loop->iteration}} </td>
                                    <td class=""> {{ $name }} </td>
                                    <td class="text-center"> {{ round($driver['handlingRating'],1) }} </td>
                                    <td class="text-center"> {{ round($driver['professionalismRating'],1)  }} </td>
                                    <td class="text-center"> {{ round($driver['timelinessRating'],1) }} </td>
                                    <td class="text-center"> {{ round($driver['ratingAvg'],1) }} </td>
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

    </div>

</x-dashLayout>

<script>
    $(".clickRow").click(function() {
        window.location = $(this).data("href");
    });
</script>