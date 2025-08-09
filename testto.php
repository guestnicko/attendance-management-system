<?php
// Set timezone
date_default_timezone_set('Asia/Manila');

// Event date using current timestamp
$event_date = date('F j, Y');

// Current timestamp for generation
$generated_on = date('Y-m-d') . ' – ' . date('H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f8f8f8;
        }
        .receipt {
            width: 90%;
            max-width: 850px;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 25px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo-header {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 15px;
        }
        .logo {
            max-width: 100px;
            max-height: 100px;
            margin-right: 15px;
        }
        .college-name {
            text-align: left;
            flex-grow: 1;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .college-name h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
            text-transform: uppercase;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .college-name h2 {
            font-size: 20px;
            margin: 0;
            line-height: 1.2;
            text-transform: uppercase;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .college-address {
            font-size: 14px;
            margin: 5px 0 0;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .contact-info {
            text-align: right;
            font-size: 14px;
            line-height: 1.5;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .institute-header {
            font-size: 22px;
            font-weight: bold;
            font-style: italic;
            margin: 20px 0;
            text-align: center;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .event-title {
            font-size: 28px;
            font-weight: bold;
            margin: 30px 0 10px;
            text-align: center;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .program-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0 30px;
            text-align: center;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .event-details {
            margin: 40px 0;
            font-size: 16px;
            line-height: 1.8;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .event-details div {
            margin: 8px 0;
        }
        .event-details strong {
            font-weight: bold;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            width: 100%;
            text-align: center;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .col-6 {
            flex: 0 0 50%;
        }
        .text-end {
            text-align: right;
        }
        .label {
            font-weight: bold;
        }
        .barcode {
            text-align: center;
            margin: 15px 0;
            font-family: monospace;
            font-size: 16px;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        
    
        .vision-mission-footer {
            margin-top: 30px;
            margin-left: -25px;
            margin-right: -25px;
            padding-left: 25px; 
            display: flex;
            background-color: #006400;
            color: white;
            font-family: 'Century Gothic', 'CenturyGothic', Arial, sans-serif;
            font-size: 11px;
            position: relative;
            width: calc(100% + 50px);
            overflow: hidden;
            min-height: 90px;
            box-sizing: border-box;
        }
        
        .vision-section {
            flex: 1;
            padding: 10px 8px;
            text-align: left;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .mission-section {
            flex: 1.5;
            padding: 10px 8px;
            text-align: left;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .values-section {
            flex: 0.8;
            padding: 10px 8px;
            text-align: left;
            padding-right: 150px; 
        }
        
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-size: 13px;
            text-align: center;
        }
        
        .section-content {
            line-height: 1.3;
        }
        
        .logo-container {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 70%;
            background-color: white;
            padding: 0 15px;
            max-width: 140px;
            border-radius: 10px 0 0 10px;
        }
        
        .footer-logo {
            height: 50px;
            margin: 0 5px;
            background-color: white;
            padding: 5px;
        }
        
        @media print {
            .buttons {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
                background-color: white;
            }
            .receipt {
                width: 100%;
                max-width: none;
                border: none;
                margin: 0;
                padding: 15px 25px;
                box-shadow: none;
                page-break-after: always;
            }
            .vision-mission-footer {
                margin-left: 0;
                margin-right: 0;
                width: 100%;
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="logo-header">
                <img src="/public/images/logos/dnsc-logo.png" alt="DNSC Logo" class="logo">
                <div class="college-name">
                    <h2>DAVAO DEL NORTE</h2>
                    <h1>STATE COLLEGE</h1>
                    <p class="college-address">"Inspiring Change, Creating Futures"</p>
                    <p class="college-address">New Visayas, Panabo City, 8105</p>
                </div>
                <div class="contact-info">
                    <div>president@dnsc.edu.ph </div>
                    <div>dnsc.edu.ph </div>
                    <div>@officialdnsc </div>
                    <div>New Visayas, Panabo City, 8105 </div>
                </div>
            </div>
        </div>

        <div class="institute-header">
            Institute of Computing Student Association
        </div>

        <div class="event-title">
            KALIBULUNG 2026 FINES
        </div>

        <div class="program-title">
            Bachelor of Science Information Technology – 3H
        </div>

        <div class="event-details">
            <div><strong>Event Date:</strong> <?php echo htmlspecialchars($event_date); ?></div>
            <div><strong>Generated on:</strong> <?php echo $generated_on; ?></div>
        </div>
           
        <div class="vision-mission-footer">
            <div class="vision-section">
                <div class="section-title">VISION</div>
                <div class="section-content">
                    A premier Higher Institution in Agri-Fisheries and Socio-cultural Development in the ASEAN Region.
                </div>
            </div>
            
            <div class="mission-section">
                <div class="section-title">MISSION</div>
                <div class="section-content">
                    DNSC strives to produce competent human resource, generate, and utilize knowledge and technology, uphold good governance and quality management system for sustainable resources and resilient communities.
                </div>
            </div>
            
            <div class="values-section">
                <div class="section-title">CORE VALUES</div>
                <div class="section-content">
                    Stewardship<br>
                    Adaptability and Excellence<br>
                    Integrity and Innovativeness<br>
                    Love of God and Country
                </div>
            </div>
            
            <div class="logo-container">
                <img src="/public/images/logos/bagong-pilipinas-logo.png" alt="Bagong Pilipinas Logo" class="footer-logo">
                <img src="/public/images/logos/dnsc-seal.png" alt="DNSC Seal" class="footer-logo">
            </div>
        </div>
        
</body>
</html>
