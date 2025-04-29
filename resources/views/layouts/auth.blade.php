<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Auth Page')</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 350px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #aaa;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            background: #3490dc;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .link {
            margin-top: 15px;
            text-align: center;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<body>

    <div class="container">
        @yield('content')
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

</body>
</html>
