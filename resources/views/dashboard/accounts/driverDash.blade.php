@push('title')
    <title> Drivers | Lebria Transport</title>
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashUser.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
    <!-- User Script -->
    <script src="{{ asset('/js/profile/dashprofile.js') }}" defer></script>
@endpush

<x-dashLayout>

    {{-- Header --}}
    <div class="row g-0 bot-margin">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="ms-1 me-5">
                <p class="fs-4 poppins m-0"> Driver Accounts</p>
            </div>
            @if (session('user')['Userlevel'] == 'Admin')
            <button type="button" class="btn btn-primary addUsr" data-add-user>
                +Add Driver
            </button>
            @endif
        </div>
    </div>

    {{-- Users Counts --}}
    <div class=" g-0 row u-summ bot-margin shadow2 rounded-4">
        <div class="col-12 d-flex flex-column p-0">
            <div class="hrcol pb-3 ps-4 poppins"> Drivers Summary </div>
            <div class="d-flex flex-row align-items-center flex-wrap  justify-content-between summ">

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    
                    <ion-icon class="me-3 text-warning" name="cafe"></ion-icon>
                    
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Available Drivers</small>
                        <h5>{{ $ina_ct }}</h5>
                    </div>
                </div>

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-info" name="notifications-off"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Unavailable Drivers</small>
                        <h5>{{ $act_ct }}</h5>
                    </div>
                    
                </div>

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-danger" name="skull"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Deactivated Drivers</small>
                        <h5> {{ $dis_ct }}</h5>
                    </div>
                </div>

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-success" name="planet"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Total Drivers </small>
                        <h5>{{ $tot_ct }}</h5>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <form action="/dashboard/drivers/availability" method="post">
        @csrf

    {{-- Available Driver Tables --}}
    <div class=" g-0 row u-summ tablewrapper bot-margin shadow2 rounded-4">
        <div class="poppins mb-3 cardtitle ">
            AVAILABLE DRIVERS
        </div>
        @if (!$ina_ct == 0)
            <div class="col-12">
                <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> </th>
                            <th> First Name</th>
                            <th> Last Name</th>
                            <th> Contact No. </th>
                            <th> Last Delivery</th>
                            <th class="text-center"> Change Availability </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($ina_u as $user)
                            <tr>
                                <td> {{ $loop->iteration}} </td>
                                <td class="clickRow" data-href={{'/dashboard/drivers/'.$user[0]}} > {{ $user[1] }} </td>
                                <td class="clickRow" data-href={{'/dashboard/drivers/'.$user[0]}}> {{ $user[2] }} </td>
                                <td> {{ $user[3] }} </td>
                                <td>
                                    @if (!$user[4])
                                        <i class='text-muted'> none </i>
                                    @else
                                        {{ $user[4] }}
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center"> 
                                    <div class="form-check">
                                        <input class="form-check-input shChecker" type="checkbox" name='toUnav[]' id={{"toUnav".$loop->iteration}}  value= {{ $user[0] }} >
                                        <label class="form-check-label shChckLbl" for={{"toUnav".$loop->iteration}}>
                                            Unavailable
                                        </label>
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

    {{-- Unavailable Driver Tables --}}
    <div class=" g-0 row u-summ tablewrapper bot-margin shadow2 rounded-4">
        <div class="poppins mb-3 cardtitle ">
            UNAVAILABLE DRIVERS
        </div>
        @if (!$act_ct == 0)
            <div class="col-12">
                <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> </th>
                            <th> First Name</th>
                            <th> Last Name </th>
                            <th> Contact No. </th>
                            <th> Last Delivery </th>
                            <th class="text-center"> Change Availability </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($act_u as $user)
                            <tr>
                                <td> {{ $loop->iteration}} </td>
                                <td class="clickRow" data-href={{'/dashboard/drivers/'.$user[0]}}>
                                     {{ $user[1] }}
                                </td>
                                <td class="clickRow" data-href={{'/dashboard/drivers/'.$user[0]}}> 
                                    {{ $user[2] }} 
                                </td>
                                <td> {{ $user[3] }} </td>
                                <td>
                                    @if (!$user[4])
                                        <i class='text-muted'> none </i>
                                    @else
                                        {{ $user[4] }}
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center"> 
                                    <div class="form-check">
                                        <input class="form-check-input shChecker" type="checkbox" name='toAvail[]' id={{"toUnav".$loop->iteration}}  value= {{ $user[0] }} >
                                        <label class="form-check-label shChckLbl" for={{"toAvail".$loop->iteration}}>
                                            Available
                                        </label>
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

    {{-- Submit Button --}}
    <div class="d-flex position-fixed align-items-center rounded-3 shUpd">
        <span class="poppins fw-medium" style="white-space: nowrap;"> Update Availability </span>
        <button class="btn btn-primary ms-3" style="padding: 4px 15px;"> Save </button>
    </div>

    </form>

    {{-- Deactivated User Tables --}}
    <div class=" g-0 row u-summ tablewrapper bot-margin shadow2 rounded-4">
        <div class="poppins mb-3 cardtitle ">
            DEACTIVATED USERS
        </div>

        @if (!$dis_ct == 0)
            <div class="col-12">
                <div class="table-responsive tblHeight">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> </th>
                            <th> First</th>
                            <th> Last </th>
                            <th> Contact No. </th>
                            <th> Last Delivery</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($dis_u as $user)
                            <tr class="clickRow" data-href={{'/dashboard/drivers/'.$user[0]}}>
                                <td> {{ $loop->iteration}} </td>
                                <td> {{ $user[1] }} </td>
                                <td> {{ $user[2] }} </td>
                                <td> {{ $user[3] }} </td>
                                <td>
                                    @if (!$user[4])
                                        <i class='text-muted'> none </i>
                                    @else
                                        {{ $user[4] }}
                                    @endif
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


    <div class="overlay" data-overlay> </div>

    {{-- Add driver form --}}
    <div class="col-lg-7 col-10 formWrapper" style="max-height: 620px; overflow: auto;" data-form-add>
        <div class="mb-4 position-relative">
            <div>
            <span class="d-block text-start fs-5 fw-medium poppins "> Create Driver </span>
            <span class="d-block text-start" style="font-size:0.85rem;"> * Indicates a Required Field.</span>
            </div>
            <div class="closeBtn position-absolute" data-close-btn2 > <ion-icon name="close" class="d-block fs-4"></ion-icon> </div>
        </div>
        <form action="/dashboard/drivers/create" class="driverForm" method="post">
            @csrf
            {{-- Form Fields --}}
            <x-driverForm />

            {{-- Buttons --}}
            <div class="d-flex flex-row justify-content-end mt-5 gap13">
                <input type="submit" class="btn btn-primary w-25 bot-btn">
                <button type="button" class="btn btn-danger bot-btn" data-close-btn > Cancel </button>
            </div>
            
        </form>
    </div>

    <!-- Script -->
    <script src="{{ asset('/js/overlay/overlay.js') }}"></script>
</x-dashLayout>


<script>
    $(function() {

        // Script for Update Status button
        $(':checkbox:not(.driverForm)').change(function(){

            if($(':checkbox:not(.driverForm):checked').length == 0){
                $('.shUpd').css('top', '-65px');
            } else {
                $('.shUpd').css('top', '60px');
            }
        })
    })
</script>