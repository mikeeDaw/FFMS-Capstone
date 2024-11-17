<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href={{asset('/favicon.ico')}}>
    @stack('title')

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    rel="stylesheet"integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" 
    crossorigin="anonymous">

    <!-- Nav & Footer CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/navstyle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/footstyle.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Notif Script -->
    <script type="module" src= "{{ asset('/js/fireBase/notif/notif.js') }}" defer> </script>

    <!-- Component-specific CSS -->
    @stack('font')
    @stack('css')
    @stack('csrf')
    @stack('script')

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

    <!-- Navigation -->
    <header class='heading rounded-4' data-header >
        <div class="box">
            <a href="/" class="company">
                <img src="{{ asset('/images/home/lebria_logo.png') }}" width='150px'
                height="50px" alt="">
            </a>
            <button class="nav-hamb-open" data-nav-open-btn >
            <img src="{{ asset('/images/home/hamb.png') }}" width='30px'
                height="30px" alt="">
            </button>

            <div class="overlayHome" id='overlayHome' data-overlay> </div>

            <nav class="navi" data-navbar>
                <button class="nav-hamb-close" data-nav-close-btn> X </button>
                <a href="#" class="logo" > 
                <img src=" {{ asset('/images/home/lebria_logo.png') }}" width='190px'
                height="60px" alt="">
                </a>
                <ul class="nav-list">
                <li class="nav-item">
                        <a href="/" class="nav-link" > Home</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="#" class="nav-link"> About</a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="/order/step1" class="nav-link"> Shipping</a>
                    </li>
                    <li class="nav-item">
                        <a href="/quote" class="nav-link"> Quote</a>
                    </li>
        
                </ul>
        
                <ul class="nav-list2">
        
                    @if (session()->has('uid'))
                        @if(session('user')['Userlevel'] != 'User')
                        <li>
                            <a href="/dashboard" class="nav2-btn">
                            <button class="btn goDash"> Dashboard </button>
                            </a>
                        </li>
                    @endif
                        
                    {{-- Notifications --}}
                    <li class="nav-item dropdown me-1" id="notifArea" style="width: fit-content; border-bottom: none;">
                        <a href="#" class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" data-bs-toggle="dropdown" aria-expanded="false" id="notifBtn">
                            <div class="position-relative d-flex">

                                <ion-icon name="notifications" class="msgIcon"></ion-icon>
                                {{-- Red Dot if merong notif --}}
                                <span class="reddot" id='reddot'></span>
                            </div>

                            <span class="notifLbl ms-2 poppins"> Notifications </span>
                        </a>
                        
                        {{-- Dropdown --}}
                        <div class="dropdown-menu dropdown-menu-right nav-menu-dropdown"
                        aria-labelledby="messageDropdown" id="notifDrop">
                            <p class="mb-0 fw-semibold float-left dropdown-header" id='notifHead'> Notifications </p>
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


                    {{-- Profile --}}
                    <li class="nav-item nav-profile dropdown" pf-dropdown>
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" style="gap:10px;" data-bs-toggle="dropdown" aria-expanded="false">
        
                            <span class="letPic fs-6"> {{substr(session('user')['Fname'], 0, 1)}} </span>
                            <span class="nav-profile-name"> {{ session('user')['Fname']." ".session('user')['Lname']}}</span>
                        </a>
                        {{-- Dropdown Content --}}
                        <div class="dropdown-menu dropdown-menu-right nav-menu-dropdown" aria-labelledby="profileDropdown">
        
                            <a href="/profile" class="dropdown-item d-flex align-items-center">
                                <ion-icon class="fs-5 me-2" name="person-outline"></ion-icon>
                                <p class="fw-normal mb-0"> Profile </p>
                            </a>
                            <a href="/logout" class="dropdown-item d-flex align-items-center">
                                <ion-icon class="fs-5 me-2" name="log-out-outline"></ion-icon>
                                <p class="fw-normal mb-0"> Log Out </p>
                            </a>
        
                        </div>
                    </li>
                    @else
        
                    <li class="loginCont">
                        <a href="/login/attempt" class="nav2-btn">
                        <button class='l-out poppins'> Log In </button>
                        </a>
                    </li>
                    <li class="signCont">
                        <a href="/register/create" class="nav2-btn">
                        <button class='sign poppins'> Sign Up </button>
                        </a>
                    </li>             
                    @endif
        
        
                </ul>
            </nav>
        </div>
    </header>    

    {{-- Contact --}}
    <section class="blackTop">
        <div class="col-12 d-flex justify-content-center" style="height: 40px;">
            <div class="d-flex align-items-center text-light" style='width: 90%;'>
                <div class="d-flex align-items-center overflow-hidden" style="white-space:nowrap;">
                    <ion-icon name="call"></ion-icon>
                    <span class="mx-3 pe-3 fs-14 poppins border-2 border-end"> (632) 521 3466 </span>
                </div>
                <div class="d-flex align-items-center overflow-hidden" style="white-space:nowrap;">
                    <ion-icon name="time-outline"></ion-icon>
                    <span class=" ms-2 poppins fs-14"> Mon-Sat 8:00 - 19:00</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="position-relative overflow-hidden">
    {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="foot-p"></div>
            <div class='container pb-3'>
                <div class="row align-items-center">
                    <div class="col-md-6  col-sm-12">
                        {{-- <img src="{{ asset('images/home/lebria_logo.png') }}" width='250px'
                        height="70px" alt=""> --}}
                        <span class="poppins text-white-50"> Copyright <span class="fs-5"> © </span> 2023 
                        All Rights Reserved. Permavirg </span>
                    </div>
                    <div class="col-md-6 col-sm-12">
 
                    </div>
                </div>

            </div>

        {{-- <div class="foot-p"></div> --}}

    </footer>

    <input type="hidden" name="newOrd" id="newOrdImg" value="{{ asset('images/notif/box.png') }}">
    <input type="hidden" name="notice" id="noticeImg" value="{{ asset('images/notif/notice.png') }}">

    {{-- Flash Message --}}
    <x-flashMsg />
    <x-errFlashMsg />
    
    <!-- NavBar Script -->
    <script src="{{ asset('/js/nav/navscript.js') }}"></script>

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