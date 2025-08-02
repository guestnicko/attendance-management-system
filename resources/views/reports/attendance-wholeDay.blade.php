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
            min-height: 700px;

        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 9px;
        }

        th {
            background-color: #f2f2f2;
            font-size: 12px;

        }

        h1 {
            color: #333;
            text-align: center;
        }

        .header {
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Attendance Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Program</th>
                <th>Set</th>
                <th>Level</th>
                <th>Morning Time In</th>
                <th>Morning Time Out</th>
                <th>Afternoon Time In</th>
                <th>Afternoon Time Out</th>
                <th>Event</th>
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
                    <td>{{ $log->attend_checkIn ? date('h:i A', strtotime($log->attend_checkIn)) : '-' }}</td>
                    <td>{{ $log->attend_checkOut ? date('h:i A', strtotime($log->attend_checkOut)) : '-' }}</td>
                    <td>{{ $log->attend_afternoon_checkIn ? date('h:i A', strtotime($log->attend_afternoon_checkIn)) : '-' }}
                    </td>
                    <td>{{ $log->attend_afternoon_checkOut ? date('h:i A', strtotime($log->attend_afternoon_checkOut)) : '-' }}
                    </td>

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
