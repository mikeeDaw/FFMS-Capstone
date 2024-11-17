<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if(session('user') !== null )
        {{ session('user')->uid }}
    @endif
    @if(session('redirUser') !== null)
        {{ session('redirUser')->email }}
    @endif
</body>
</html>