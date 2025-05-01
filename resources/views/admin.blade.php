<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #eef2f7;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 90%;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #aaa;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #3490dc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #3490dc;
            color: white;
        }
        button {
            padding: 5px 10px;
            background: #38c172;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout {
            margin-top: 20px;
            text-align: center;
        }
        .logout button {
            background: #e3342f;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Dashboard</h2>

    <h3>Users Management</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Failed Attempts</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->failed_attempts }}</td>
                <td>
                    @if ($user->is_locked)
                        Locked
                    @else
                        Active
                    @endif
                </td>
                <td>
                    @if ($user->is_locked)
                    <form method="POST" action="{{ url('/admin/unlock/'.$user->id) }}">
                        @csrf
                        <button type="submit">Unlock</button>
                    </form>
                    @else
                        ---
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Login Attempt Logs</h3>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>IP Address</th>
                <th>Status</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->email }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>
                    @if ($log->status)
                        <span style="color: green;">Success</span>
                    @else
                        <span style="color: red;">Failed</span>
                    @endif
                </td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()">Logout</button>
        </form>
    </div>
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
