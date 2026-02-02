<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Attendance History Report - {{ $date }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #334155;
            font-size: 10px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #00ADC5;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #0f172a;
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header p {
            color: #64748b;
            margin: 5px 0 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 1px;
            padding: 10px 5px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        td {
            padding: 8px 5px;
            border-bottom: 1px solid #f1f5f9;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            padding: 10px 0;
            border-top: 1px solid #f1f5f9;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-approved {
            background-color: #ecfdf5;
            color: #059669;
        }

        .status-pending {
            background-color: #fffbeb;
            color: #d97706;
        }

        .status-rejected {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .presence-p {
            font-weight: bold;
            color: #0284c7;
        }

        .presence-a {
            font-weight: bold;
            color: #dc2626;
        }

        .employee-info {
            font-weight: bold;
            color: #0f172a;
        }

        .dept-title {
            color: #00ADC5;
            font-weight: 900;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Workforce Attendance Registry</h1>
        <p>Comprehensive Audit Report | Generated on {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Week</th>
                <th>Dept</th>
                <th>Employee</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
                <tr>
                    <td style="white-space: nowrap;">{{ $entry->week_start_date->format('d M') }}</td>
                    <td class="dept-title">{{ $entry->department_name }}</td>
                    <td>
                        <div class="employee-info">{{ $entry->employee->full_name ?? 'N/A' }}</div>
                        <div style="font-size: 7px; color: #94a3b8;">{{ $entry->employee->employee_id ?? '' }}</div>
                    </td>
                    <td><span class="presence-{{ strtolower($entry->mon_m) }}">{{ $entry->mon_m }}</span>/<span
                            class="presence-{{ strtolower($entry->mon_a) }}">{{ $entry->mon_a }}</span></td>
                    <td><span class="presence-{{ strtolower($entry->tue_m) }}">{{ $entry->tue_m }}</span>/<span
                            class="presence-{{ strtolower($entry->tue_a) }}">{{ $entry->tue_a }}</span></td>
                    <td><span class="presence-{{ strtolower($entry->wed_m) }}">{{ $entry->wed_m }}</span>/<span
                            class="presence-{{ strtolower($entry->wed_a) }}">{{ $entry->wed_a }}</span></td>
                    <td><span class="presence-{{ strtolower($entry->thu_m) }}">{{ $entry->thu_m }}</span>/<span
                            class="presence-{{ strtolower($entry->thu_a) }}">{{ $entry->thu_a }}</span></td>
                    <td><span class="presence-{{ strtolower($entry->fri_m) }}">{{ $entry->fri_m }}</span>/<span
                            class="presence-{{ strtolower($entry->fri_a) }}">{{ $entry->fri_a }}</span></td>
                    <td><span class="presence-{{ strtolower($entry->sat_m) }}">{{ $entry->sat_m }}</span>/<span
                            class="presence-{{ strtolower($entry->sat_a) }}">{{ $entry->sat_a }}</span></td>
                    <td>
                        <span class="badge status-{{ strtolower($entry->weekly_status) }}">
                            {{ $entry->weekly_status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Confidential Document | Property of Core HR Systems | Page
        <script type="text/php">echo $PAGE_NUM . " of " . $PAGE_COUNT;</script>
    </div>
</body>

</html>