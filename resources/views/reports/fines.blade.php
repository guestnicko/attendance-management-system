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
            border-collapse: collapse;
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


    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Program</th>
                <th>Set</th>
                <th>Level</th>
                <th>Event</th>
                <th>Fines Amount</th>
                <th>Total Fines</th>
                <th>Date</th>
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

                    <td>{{ $event->event_name }}</td>
                    <td>{{ $log->fines_amount }}</td>
                    <td>{{ $log->total_fines }}</td>
                    <td>{{ $event->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
