<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>template</title>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
</head>

<body>

    <div class="container">
        <div class="row">
            @yield("content")
        </div>
    </div>

    <script src="{{asset('assets/bootstrap.min.js')}}"></script>
</body>

</html>