<!DOCTYPE html>
<html>

<head>
    <title>Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            color: #333;
            text-align: center;
        }


        .college-info {
            display: flex;
            flex-direction: column;
        }

        .college-name {
            font-weight: bold;
            font-size: 20px;
            line-height: 1.2;
        }

        .college-subname {
            font-size: 16px;
            text-transform: uppercase;
        }

        .logo {
            max-width: 70px;
            max-height: 70px;
        }

        footer {}
    </style>
</head>

<body>
    <header class="header">
        <div class="college-info">
            <img src="{{ public_path('images/logos/IC.svg') }}" alt="Logo 1" class="logo">
            <img src="{{ public_path('images/logos/ICSA.svg') }}" alt="Logo 2" class="logo">
        </div>
        <hr>
        <p>
        <h1>Attendance Report</h1>
        Event Name: {{ $event->event_name }} <br>
        Event Date: {{ date('M d, Y', strtotime($event->created_at)) }} <br>
        Generated on: {{ date('Y-m-d H:i:s') }} <br>
        </p>
    </header>
    <hr>

    <table class="" style="min-height: 500px; border-collapse: collapse;
">
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Program</th>
                <th>Set</th>
                <th>Level</th>
                <th>Time In</th>
                <th>Time Out</th>

            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $log->s_fname . ' ' . $log->s_lname }}</td>
                    <td>{{ $log->s_program }}</td>
                    <td>{{ $log->s_set }}</td>
                    <td>{{ $log->s_lvl }}</td>
                    <td>{{ $log->attend_checkIn ? date('h:i A', strtotime($log->attend_checkIn)) : '-' }}</td>
                    <td>{{ $log->attend_checkOut ? date('h:i A', strtotime($log->attend_checkOut)) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <footer>
        Prepared By:
        <p>{{ $request->prepared_by }}</p>
    </footer>

</body>

</html>
