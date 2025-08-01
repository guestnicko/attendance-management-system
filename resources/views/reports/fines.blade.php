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
