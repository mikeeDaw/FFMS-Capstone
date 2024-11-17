@push('title')
    <title> Staff | Lebria Transport</title>
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
                <p class="fs-4 poppins m-0"> Staff Accounts</p>
            </div>
            @if (session('user')['Userlevel'] == 'Admin')
            <button type="button" class="btn btn-primary addUsr" data-add-user>
              +Add Staff
            </button>
            @endif
        </div>
    </div>

    {{-- Users Counts --}}
    <div class=" g-0 row u-summ bot-margin shadow2 rounded-4" >
        <div class="col-12 d-flex flex-column p-0">
            <div class="hrcol pb-3 ps-4 poppins"> Staff Summary </div>
            <div class="d-flex flex-row align-items-center flex-wrap  justify-content-between summ">

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    
                    <ion-icon class="me-3 text-warning" name="footsteps"></ion-icon>
                    
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Active Accounts </small>
                        <h5>{{ $act_ct }}</h5>
                    </div>
                </div>

                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-info" name="rainy"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Deactivated Accounts </small>
                        <h5>{{ $dis_ct }}</h5>
                    </div>
                    
                </div>


                <div class="d-flex flex-grow-1 p-3 justify-content-center align-items-center bord-md-right item">
                    <ion-icon class="me-3 text-success" name="planet"></ion-icon>
                    <div class="d-flex flex-column align-items-center">
                        <small class="text-secondary"> Total Staff </small>
                        <h5>{{ $tot_ct }}</h5>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Active Staff Table --}}
    <div class=" g-0 row u-summ tablewrapper bot-margin shadow2 rounded-4">
        <div class="poppins mb-3 cardtitle ">
            ACTIVE ACCOUNTS
        </div>
        @if (!$act_ct == 0)
            <div class="col-12">
                <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th> </th>
                            <th> First</th>
                            <th> Last </th>
                            <th> Contact No. </th>
                            <th> Email </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($act_u as $user)
                        <tr class="clickRow" data-href={{'/dashboard/staffs/'.$user[0]}}>
                                <td> {{ $loop->iteration}} </td>
                                <td> {{ $user[1] }} </td>
                                <td> {{ $user[2] }} </td>
                                <td> {{ $user[3] }} </td>
                                <td>
                                    {{ $user[4]}}
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

    {{-- Deactivated User Tables --}}
    <div class=" g-0 row u-summ tablewrapper bot-margin shadow2 rounded-4">
        <div class="poppins mb-3 cardtitle ">
            DEACTIVATED ACCOUNTS
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
                            <th> Email</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($dis_u as $user)
                            <tr class="clickRow" data-href={{'/dashboard/staffs/'.$user[0]}}>
                                <td> {{ $loop->iteration}} </td>
                                <td> {{ $user[1] }} </td>
                                <td> {{ $user[2] }} </td>
                                <td> {{ $user[3] }} </td>
                                <td> {{ $user[4] }} </td>
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

    {{-- Add Staff form --}}
    <div class="col-lg-7 col-9 formWrapper" data-form-add>
        <div class="mb-4 position-relative">
            <div>
            <span class="d-block text-start fs-5 fw-medium poppins "> Create Staff </span>
            <span class="d-block text-start" style="font-size:0.85rem;"> * Indicates a Required Field.</span>
            </div>
            <div class="closeBtn position-absolute" data-close-btn2 > <ion-icon name="close" class="d-block fs-4"></ion-icon> </div>
        </div>
        <form action="/dashboard/staffs/create" method="post">
            @csrf
            {{-- Form Fields --}}
            <x-userForm />

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