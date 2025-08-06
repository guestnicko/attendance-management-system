
<?php
// Ensure proper date handling
date_default_timezone_set('Asia/Manila');
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');
$generatedDateTime = date('Y-m-d - H:i:s');
$eventDate = date('F j, Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }
        
        .header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
        }
        
        .header-text {
            margin-left: 15px;
        }
        
        .college-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }
        
        .college-subname {
            font-size: 20px;
            margin: 0;
        }
        
        .tagline {
            font-style: italic;
            margin: 0;
        }
        
        .contact-info {
            margin-left: auto;
            text-align: right;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .main-title {
            text-align: center;
            font-size: 22px;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        
        .sub-title {
            text-align: center;
            font-size: 18px;
            margin-top: 5px;
            margin-bottom: 30px;
        }
        
        .report-details {
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        .time-column {
            width: 15%;
        }
        
        .name-column {
            width: 40%;
        }
        
        .fines-column, .total-column, .date-column {
            width: 15%;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://via.placeholder.com/80" alt="DNSC Logo" class="logo">
        <div class="header-text">
            <div class="college-name">DAVAO DEL NORTE</div>
            <div class="college-subname">STATE COLLEGE</div>
            <p class="tagline">"Inspiring Change, Creating Futures"</p>
        </div>
        <div class="contact-info">
            president@dnsc.edu.ph<br>
            dnsc.edu.ph<br>
            @officialdnsc<br>
            New Visayas, Panabo City, 8105
        </div>
    </div>

    <h1 class="main-title">Institute of Computing Student Association</h1>

    <h2 class="main-title">KALIBULUNG 2026 FINES</h2>
    <h3 class="sub-title">Bachelor of Science Information Technology – 3H</h3>

    <div class="report-details">
        <div>Event Date: <?php echo $eventDate; ?></div>
        <div>Generated on: <?php echo $generatedDateTime; ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO.</th>
                <th>NAME</th>
                <th>TIME OF IN AND OUT</th>
                <th>FINES AMOUNT</th>
                <th>TOTAL FINES</th>
                <th>DATE</th>
            </tr>
        </thead>
        <tbody>
    
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>