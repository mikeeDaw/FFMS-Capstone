<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Login CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/logform.css') }}">
    
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poppins&display=swap" rel="stylesheet">

    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    rel="stylesheet"integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" 
    crossorigin="anonymous">

</head>
<body>
    
    <div class="contain">
        <form class="logForm" action= '/login' method="post">
            @csrf
            <span class="logo center"> <img src=""></span>
            <span class="title center"> Welcome Back!</span>
            <span class="l-sub center"> Log in to find new good deals today.</span>

            <div class="floater">
                <div class="icon"><img src=" {{ asset('/images/logform/envelope.png') }}" alt=""></div>
                <input class="email" name="email" placeholder="Email" type="text" value="{{old('email') }}">
                <label for="email"> Email</label>
                {{-- Validation Error Message --}}
                @error('email')
                    <p class="v-err"> {{ $message }}</p>
                @enderror
                @isset($errors)
                    <p class="v-err"> {{ $errors->first('emailErr') }}</p>
                 @endisset
            </div>

            <div class="floater">
                <div class="icon"><img src="{{ asset('/images/logform/padlock.png') }}" alt=""></div>
                <input class="email" name="pass" placeholder="Password" type="password" id="pass" style="padding-right: 45px;">
                <label for="pass"> Password </label>
                <ion-icon id="passEye" name="eye-outline"></ion-icon>
                {{-- Validation Error Message --}}
                @error('pass')
                    <p class="v-err"> {{ $message }}</p>
                @enderror
                @isset($errors)
                    <p class="v-err"> {{ $errors->first('passErr') }}</p>
                @endisset
            </div>

            <input class="enter" type="submit" value="Log in" name="submitter">

            <div class="d-flex justify-content-center">
                <a href="/forgotPassword" class="text-center poppins text-decoration-none mt-3 fs-14 forgot border-bottom border-white pb-1"> Forgot Password? </a>
            </div>

        
        <span class="signup center">Don't Have an account? 
        <a href="/register/create"> Sign Up </a> </span>
        </form>

    </div>


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

<script>

    var icon = document.getElementById("passEye");
    var input = document.getElementById("pass");

    icon.addEventListener('click', function(){
        if(input.type == 'password'){
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    })

</script>