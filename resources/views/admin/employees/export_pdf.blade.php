<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Employee Registry Export</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #00ADC5;
            padding-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #00ADC5;
            margin-bottom: 5px;
        }

        .title {
            font-size: 18px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .meta {
            text-align: right;
            margin-bottom: 20px;
            color: #999;
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
            text-align: left;
            padding: 12px 8px;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 10px;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .status-active {
            color: #10b981;
            font-weight: bold;
        }

        .status-inactive {
            color: #ef4444;
            font-weight: bold;
        }

        .status-terminated {
            color: #64748b;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            color: #999;
            font-size: 10px;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">ATT-APP</div>
        <div class="title">Employee Registry Report</div>
    </div>

    <div class="meta">
        Generated on: {{ $date }}
    </div>

    <table>
        <thead>
            <tr>
                <th>EEC-ID</th>
                <th>Full Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Site</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->full_name }}</td>
                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                    <td>{{ $employee->position ?? 'N/A' }}</td>
                    <td>{{ $employee->site ?? 'N/A' }}</td>
                    <td>
                        <span class="status-{{ strtolower($employee->status) }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} ATT-APP Systems. Confidential Document. Page <span class="page-number"></span>
    </div>
</body>

</html>