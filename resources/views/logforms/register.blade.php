@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/register.css') }}">
@endpush
@push('script')  
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>   
@endpush


<x-layout>
    
    {{-- Temporary Spacing --}}
    <span class="curv" style="display:block;padding-top:50px;"> </span>

    {{-- Overlay --}}
    <div class="m-0" id='privOverlay' data-overlay2> </div>
    
    <!-- Sign Up Form -->
    <div class="container" style="padding-top:20px;">
        <div class="row justify-content-evenly align-items-center">
            <div class="col-11 col-md-10 col-lg-8 contain bg-white mb-3" style="--bs-bg-opacity: 0.8;">
                <form class="logForm" action= '/register' method="post">
                   @csrf

                    <!-- Form Header -->
                    {{-- <span class="logo center"> <img src=""></span> --}}
                    <div>
                    <span class="title left d-block"> Join Us!</span>
                    <span class="r-sub left d-block"> Experience our high quality service.</span>
                    </div>

                    <span class="d-block" style="margin-top: 40px;"></span>

                    <!-- Form Fields -->
                    <div class="container-fluid">
                        <x-userForm />
                    </div>

                    <div class="d-flex align-items-center justify-content-center flex-column">
                        <div class="d-flex justify-content-center align-items-center mt-5">
                            <input type="checkbox" name="dataCheck" id="dataCheck">
                            <span class="ms-3 poppins fs-14 "> I have read the <span id='privState'> Data Privacy Statement </span> </span>
                        </div>
                        @error('dataCheck')
                        <p class="v-err"> Please Read the Privacy Statement</p>
                        @enderror
                    </div>

                    <span class="d-block" style="margin-top: 10px;"></span>

                    <!-- Bottom Buttons -->
                    <input class="reg w-100" type="submit" value="Sign Up" name="submitter">
                    <span style=" display:block; margin-bottom:20px;"></span>
                    <span class="center">
                        <a href="/"> 
                        Cancel 
                        </a> 
                    </span>

                </form>
            </div>
        </div>
    </div>

    {{-- Data Privacy Dialog --}}
    <div class="col-11 col-md-8 col-lg-6 col-xl-5 m-0" id="privDialog">
        {{-- Top Section --}}
        <div class="row gx-0 mb-3">
            <div class="position-relative">
                <span class="poppins fw-medium" style="font-size: 18px;"> Data Privacy Statement </span>
                <div class="position-absolute" id='closeDialog' style="top: -3px; right: -1px; cursor: pointer;">
                    <ion-icon name="close" class="fs-4 d-block"> </ion-icon>
                </div>
            </div>
        </div>
        {{-- Content --}}
        <div class="row gx-0">
            <div class="d-flex position-relative" style="height: 520px; overflow: auto;" >
                <div class="d-flex justify-content-center flex-column position-absolute" >
                    <span class="fs-14 poppins"> We collect and Use the Following information about you: </span>
                    <span class="fs-14 poppins mt-2"> <span class="fw-medium"> Your Profile Information. </span> The data you give us when you register on the platform, Your name, phone number, date of birth (when applicable), email, and address.  </span>
                    <span class="fs-14 poppins mt-3 mb-2"> How we Use your Information: </span>
                    <table style="border-collapse: separate; border-spacing:0 10px;">
                        <thead></thead>
                        <tbody>
                            <tr>
                                <td class="fs-14 poppins fw-medium"> Account Registration: </td>
                                <td class="fs-14 poppins">
                                    To create and maintain your account on our platform.
                                </td>
                            </tr>
                            <tr>
                                <td class="fs-14 poppins fw-medium"> Communication: </td>
                                <td class="fs-14 poppins"> To send important updates, notifications, and account-related information.  </td>
                            </tr>
                            <tr>
                                <td class="fs-14 poppins fw-medium"> Customer <br> Support: </td>
                                <td class="fs-14 poppins"> To assist you with any inquiries, requests, or issues you may encounter. </td>
                            </tr>
                            <tr>
                                <td class="fs-14 poppins fw-medium"> Compliance: </td>
                                <td class="fs-14 poppins"> To ensure adherence to legal and regulatory requirements. </td>
                            </tr>
                        </tbody>
                    </table>

                    <span class="fs-14 poppins mt-2">
                        Each person shall be guided by the principles of transparency, legitimate purpose, and proportionality in processing personal data of students, parents, employees, external parties, and other stakeholders. These principles shall guide the university in the acquisition, use and dissemination of the cited personal data.
                    </span>

                    <span class="fs-14 poppins mt-2">
                        <span class="fw-medium"> Transparency. </span>  Data subjects must be aware of the nature, purpose, and extent of the processing of his or her personal data, including the risks and safeguards involved, the identity of the personal information controller, his or her rights as a data subject, and how these can be exercised.
                    </span>

                    <span class="fs-14 poppins mt-2">
                        <span class="fw-medium"> Legitimate Purpose. </span>  Personal data collected shall be processed based on declared and specified purpose and shall not be contrary to law, morals, or public policy.
                    </span>

                    <span class="fs-14 poppins mt-2">
                        <span class="fw-medium"> Proportionality. </span>  Processing of personal data shall be adequate, relevant, suitable, necessary, and not excessive in relation to the functions of the Institution.
                    </span>

                    <span class="fs-14 poppins mt-3">
                        We shall adhere to all provisions of Republic Act No. 10173 or the Data Privacy Act of 2012, its Implementing Rules and Regulations, relevant policies and issuance of the National Privacy Commission, and all other requirements and standards for continuous improvement and effectiveness of personal data security management systems.
                    </span>

                </div>
            </div>
        </div>

        {{-- Close --}}
        <div class="row gx-0 mt-3">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger fs-14 py-1" id="closeDia2">Close</button>
            </div>
        </div>

    </div>

</x-layout>

<script>

$(function() {

    var prOverlay = $('#privOverlay')
    var prDialog = $('#privDialog')
    var closeDial = $('#closeDialog')
    var prOpen = $('#privState')
    var close2 = $('#closeDia2')

    var bindEvent = [prOpen, closeDial, close2];

    bindEvent.forEach( (elem) => {

        elem.on('click', function() {
            prOverlay.toggleClass('active')
            prDialog.toggleClass('active')

        })
    })


})

</script>