@push('css')
    <style>
        .curv::before{
            content: '';
            background: linear-gradient(181.2deg, rgb(181, 239, 249) 10.5%, rgb(254, 254, 254) 86.8%);
            width: 100%;
            display: block;
            height: 400px;
            position: absolute;
            top: 0px;
            z-index: -5;
        }

        .curv::after{
            content: '';
            display: block;
            z-index: -4;
            position: absolute;
            height: 427px;
            background: #FFF;
            width: 870px;
            left: -369px;
            border-radius: 100%;
            top: 39px;
            transform: rotate(356deg);
            opacity: 0.6;
        }
    </style>
@endpush

@push('title')
    <title> Forgot Password | Lebria Transport</title>
@endpush

@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
@endpush


<x-layout>

    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>

    <div class="row g-0 justify-content-center justify-content-md-start ms-0 ms-md-5" style="min-height: calc(100vh - 235px);">

        <div class="col-11 col-md-6 mt-4 ms-0 ms-md-5 pt-3">
            <div class="">
                
                <span class="poppins fs-5 fw-medium"> Reset Password </span>

                <form action="/forgotPassword/send" method="post">
                    @csrf

                    <div class="border bg-white rounded-4 p-3 my-4 d-flex flex-column" style="box-shadow: 0 0 14px -7px #484848;">
                        <span class="poppins mb-3"> Enter your email address </span>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text border-dark-subtle" id="basic-addon1">@</span>
                                <input type="text" name='email' class="form-control border-primary-subtle" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" required>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary py-1 px-3 fs-14">
                                Send Password Reset Link
                            </button>
                        </div>

                    </div>

                </form>
            </div>
        </div>

    </div>



</x-layout>