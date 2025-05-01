<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #eef2f7;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 500px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #aaa;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #38c172;
        }
        p {
            font-size: 18px;
            margin-top: 10px;
        }
        form {
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            background: #e3342f;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Student Dashboard</h2>
    <p>Welcome, {{ auth()->user()->name }}!</p>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" onclick="confirmLogout()">Logout</button>
    </form>
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

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        })
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let logoutTimeout;
    let sessionLifetimeMinutes = {{ config('session.lifetime') }};
    let logoutAfter = sessionLifetimeMinutes * 60 * 1000; // in ms

    function resetTimer() {
        clearTimeout(logoutTimeout);
        logoutTimeout = setTimeout(() => {
            Swal.fire({
                icon: 'info',
                title: 'Logged Out',
                text: 'You were logged out due to inactivity.',
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.location.href = "/logout";
            });
        }, logoutAfter);
    }

    // Reset timer on activity
    ['click', 'mousemove', 'keypress', 'scroll'].forEach(event => {
        window.addEventListener(event, resetTimer);
    });

    // Start the timer
    resetTimer();
</script>

</body>
</html>
