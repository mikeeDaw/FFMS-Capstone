<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @stack('title')
    <link rel="icon" type="image/x-icon" href={{asset('/favicon.ico')}}>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    rel="stylesheet"integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" 
    crossorigin="anonymous">

    <!-- Dashboard Nav CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dashlayout.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    
    @stack('css')
    @stack('script')
        
    <!-- Notif Script -->
    <script type="module" src= "{{ asset('/js/fireBase/notif/notif.js') }}" defer> </script>

    <!-- Alpine JS -->
    <script src="//unpkg.com/alpinejs" defer></script>

</head>
<body>

    @php
        
    echo "<script language='javascript'>";
    if(session()->has('uid')){
        echo "var userID = '".session('uid')."'
        var userLvl = '".session('user')['Userlevel']."'";
    } else {
        echo "var userID = null";
    }
    echo "</script>";
    
    @endphp
    
    <div class="container-scroller">

        {{-- Nav Bar --}}
        <nav class="col-lg-12 col-12 p-0 d-flex flex-row navbar">

            {{-- NavBar Logo Area --}}
            <div class="navbrand-wrapper d-flex justify-content-center">
                <div class="navbrand-inner-wrapper mx-lg-4 d-flex justify-content-center justify-content-lg-between align-items-center w-100">
                    <a href="#" class="navbrand-logo brand-logo">
                        <img src="{{asset('images/home/lebria_logo.png')}}" alt="">
                    </a>
                    <a href="#" class="navbrand-logo brand-logo-mini">
                        <img src={{asset('favicon.ico')}} alt="">
                    </a>
                    <button class="navbrand-toggle align-self-center">
                        <span class="min-icon">
                            <ion-icon name="filter-outline"></ion-icon>
                        </span>
                    </button>
                </div>
            </div>

            {{-- NavBar Menu Area --}}
            <div class="nav-menu-wrapper d-flex align-items-center justify-content-end">
                
                {{-- Profile, Messages, and Notifs Menu Bar --}}
                <ul class="nav-menu-bar menu-right align-self-stretch">
                    <li class="nav-item me-0">
                        <a href="/">
                            <button class="btn goHome"> Go to Homepage</button>
                        </a>
                    </li>

                    {{-- Notifications --}}
                    <li class="nav-item dropdown me-1" id="notifArea" style="width: fit-content; border-bottom: none;">
                        <a href="#" class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" data-bs-toggle="dropdown" aria-expanded="false" id="notifBtn">
                            <div class="position-relative d-flex">

                                <ion-icon name="notifications" class="msgIcon"></ion-icon>
                                {{-- Red Dot if merong notif --}}
                                <span class="reddot" id='reddot'></span>
                            </div>
                        </a>
                        
                        {{-- Dropdown --}}
                        <div class="dropdown-menu dropdown-menu-right nav-menu-dropdown"
                        aria-labelledby="messageDropdown" id="notifDrop">
                            <p class="mb-0 fw-medium float-left dropdown-header" id='notifHead'> Notifications </p>
                            {{-- Dropdown Options --}}


                            {{-- Loading --}}
                            <div class="loaderSpace" id="loader" style="height: 100px; width:100%; position:relative">
                                <div class="loaderCont">
                                    <div class="custom-loader position-absolute"></div>
                                </div>
                            </div>

                            {{-- if No Notifs --}}
                            <div id="emptySpace">
                                <img src={{asset('images/dashboard/nodata.png')}} alt="">
                                <span class="ms-3 poppins fs-14 text-secondary"> Nothing Here..</span>
                            </div>
                        </div>
                    </li>
                    

                    {{-- Profile Menu --}}
                    <li class="nav-item nav-profile dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="letPic fs-6 text-dark"> {{substr(session('user')['Fname'], 0, 1)}} </span>
                            <span class="nav-profile-name"> {{ session('user')['Fname']." ".session('user')['Lname']}}</span>
                        </a>
                        {{-- Dropdown Content --}}
                        <div class="dropdown-menu dropdown-menu-right nav-menu-dropdown" aria-labelledby="profileDropdown">
                            <a href="/profile" class="dropdown-item">
                                <ion-icon name="person-outline"></ion-icon>
                                <p class="fw-normal mb-0"> Profile </p>
                            </a>
                            <a href="/logout" class="dropdown-item">
                                <ion-icon name="log-out-outline"></ion-icon>
                                <p class="fw-normal mb-0"> Log Out </p>
                            </a>
                        </div>
                    </li>
                </ul>

                {{-- Sidebar Toggler --}}
                <div class="navbar-toggler navbar-toggler-right align-self-center d-lg-none" type="button" offcanvas>
                    <ion-icon name="menu"></ion-icon>
                </div>
                
                </div>

        </nav>

        {{-- Page Body --}}
        <section class="container-fluid page-body-wrapper pt-0">
            {{-- Body Sidebar --}}
            <nav class="sidebar sidebar-offcanvas" id="sidebar" page-sidebar>
                <ul class="nav">
                    {{-- Dashboard --}}
                    <div class="nav-item active">
                        <a href="/dashboard" class="nav-link">
                            <ion-icon name="grid-outline"></ion-icon>
                            <span class="option-title">
                                Dashboard
                            </span>
                        </a>
                    </div>

                    {{-- Accounts With Dropdown --}}
                    <div class="nav-item">
                        <a href="#dropdowner" class="nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="dropdowner">
                            <ion-icon name="file-tray-stacked-outline"></ion-icon>
                            <span class="option-title"> Accounts </span>
                            <ion-icon class="menu-arrow" name="chevron-down-outline" style="color: #FFF !important"></ion-icon>
                        </a>
                        <div class="collapse" id="dropdowner">
                            <ul class="sub-menu flex-column">
                                @if (session('user')['Userlevel'] == 'Admin')
                                    <li class="drop-item">
                                        <ion-icon name="key-outline"></ion-icon>
                                        <a href="/dashboard/admin" class="nav-link">
                                            Admin
                                        </a>
                                    </li>
                                @endif
                                <li class="drop-item">
                                    <ion-icon name="person-outline"></ion-icon>
                                    <a href="/dashboard/users" class="nav-link">
                                        Users
                                    </a>
                                </li>
                                <li class="drop-item">
                                    <ion-icon name="car-outline"></ion-icon>
                                    <a href="/dashboard/drivers" class="nav-link">
                                        Drivers
                                    </a>
                                </li> 
                                <li class="drop-item">
                                    <ion-icon name="megaphone-outline"></ion-icon>
                                    <a href="/dashboard/staffs" class="nav-link">
                                        Staff
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Shipments With Dropdown --}}
                    <div class="nav-item">
                        <a href="#dropdowner2" class="nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="dropdowner2">
                            <ion-icon name="trail-sign-outline"></ion-icon>
                            <span class="option-title"> Shipments </span>
                            <ion-icon class="menu-arrow" name="chevron-down-outline" style="color: #FFF !important"></ion-icon>
                        </a>
                        <div class="collapse" id="dropdowner2">
                            <ul class="sub-menu flex-column">
                                <li class="drop-item">
                                    <ion-icon name="speedometer-outline"></ion-icon>
                                    <a href="/shipments" class="nav-link">
                                        Shipment Status
                                    </a>
                                </li>
                                <li class="drop-item">
                                    <ion-icon name="layers-outline"></ion-icon>
                                    <a href="/shipments/allocate" class="nav-link">
                                        Resource Allocation
                                    </a>
                                </li>
                                <li class="drop-item">
                                    <ion-icon name="trash-bin-outline"></ion-icon>
                                    <a href="/shipments/cancels" class="nav-link">
                                        Order Cancellations
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Reports With Dropdown --}}
                    <div class="nav-item">
                        <a href="#dropdowner3" class="nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="dropdowner2">
                            <ion-icon name="pie-chart-outline"></ion-icon>
                            <span class="option-title"> Reports </span>
                            <ion-icon class="menu-arrow" name="chevron-down-outline" style="color: #FFF !important"></ion-icon>
                        </a>
                        <div class="collapse" id="dropdowner3">
                            <ul class="sub-menu flex-column">
                                <li class="drop-item">
                                    <ion-icon name="star-outline"></ion-icon>
                                    <a href="/reports/ratings" class="nav-link">
                                        Driver Ratings
                                    </a>
                                </li>
                                @if (session('user')['Userlevel'] == 'Admin')
                                    <li class="drop-item">
                                        <ion-icon name="bar-chart-outline"></ion-icon>
                                        <a href="/reports/sales" class="nav-link">
                                            Sales
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- Prices Item--}}
                    <div class="nav-item active">
                        <a href="/dash/prices" class="nav-link">
                            <ion-icon name="pricetag-outline"></ion-icon>
                            <span class="option-title">
                                Prices
                            </span>
                        </a>
                    </div>

                    {{-- Vehicles Item--}}
                    <div class="nav-item active">
                        <a href="/vehicles" class="nav-link">
                            <ion-icon name="car-outline"></ion-icon>
                            <span class="option-title">
                                Vehicles
                            </span>
                        </a>
                    </div>

                </ul>
            </nav>

            {{-- Main Content Area --}}
            <div class="main-panel">
                <div class="main-wrapper position-relative">

                    {{-- Insert Content Here --}}
                    {{ $slot }}

                </div>

            </div>
        </section>

    </div>

    <input type="hidden" name="newOrd" id="newOrdImg" value="{{ asset('images/notif/box.png') }}">
    <input type="hidden" name="notice" id="noticeImg" value="{{ asset('images/notif/notice.png') }}">

    {{-- Flash Message --}}
    <x-flashMsg />
    <x-errFlashMsg />
    
    <!-- Page Sidebar Offcanvas Script -->
    <script src="{{ asset('/js/pageSidebar/off-canvas.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" 
    integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" 
    crossorigin="anonymous"></script>

    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>
</html>