@push('title')
    <title> Verify Account | Lebria Transport</title>
@endpush

<x-layout>

        <div class="row g-0">
            <div class="col-12 bg-primary-subtle d-flex justify-content-center align-items-center" style="height: calc(100vh - 189px)">
                
                <div class="col-9 col-md-6 col-lg-5 col-xl-4 d-flex flex-column justify-content-center align-items-center">

                    <img class="my-3" src={{asset('/images/logform/emailSent.png')}} alt="" style="max-height: 180px;">
                    <span class="poppins fs-5 fw-medium text mb-3"> Verify Your Account! </span>
                    <span class="poppins text-secondary text-center px-2"> Verification email sent to <span class="fw-medium fst-italic">{{ session('user')['Email']}} </span>. Please <span class="fw-medium"> Refresh </span> the page once your email address has been verified.  </span>
                </div>
                    
            </div>
        </div>

        

</x-layout>