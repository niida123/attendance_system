<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $meta['title'] }}</title>
    <style>
        @page { margin: 90px 30px 60px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1f36; }
        header { position: fixed; top: -70px; left: 0; right: 0; height: 70px; }
        footer { position: fixed; bottom: -40px; left: 0; right: 0; height: 30px; font-size: 9px; color: #9ca3af; text-align: center; }
        .logo-row { display: table; width: 100%; }
        .logo-cell { display: table-cell; width: 60px; vertical-align: middle; }
        .name-cell { display: table-cell; vertical-align: middle; padding-left: 10px; }
        .company-name { font-size: 16px; font-weight: bold; color: #1a1f36; }
        .report-title { font-size: 13px; font-weight: bold; color: #4f46e5; margin-top: 2px; }
        .meta-line { font-size: 10px; color: #6b7280; margin-top: 6px; }
        table.summary { width: 100%; border-collapse: collapse; margin: 14px 0 16px 0; }
        table.summary td {
            background: #eef2ff; border: 1px solid #e0e7ff; padding: 8px 10px;
            font-size: 10px; text-align: center;
        }
        table.summary td b { display: block; font-size: 14px; color: #1a1f36; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th {
            background: #4f46e5; color: #fff; padding: 6px 5px; font-size: 9.5px;
            text-align: left; border: 1px solid #4338ca;
        }
        table.data td { padding: 5px; font-size: 9.5px; border: 1px solid #e5e7eb; }
        table.data tr:nth-child(even) { background: #f8f9ff; }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 7px; border-radius: 10px; font-size: 8.5px; font-weight: bold;
        }
        .badge-present { background: #ecfdf5; color: #059669; }
        .badge-late { background: #fef3c7; color: #d97706; }
        .badge-absent { background: #fef2f2; color: #ef4444; }
        .badge-leave { background: #ede9fe; color: #7c3aed; }
        .badge-holiday { background: #fef9c3; color: #a16207; }
    </style>
</head>
<body>

<header>
    <div class="logo-row">
        <div class="logo-cell">
            @if(file_exists($meta['company_logo']))
                <img src="{{ $meta['company_logo'] }}" height="50">
            @endif
        </div>
        <div class="name-cell">
            <div class="company-name">{{ $meta['company_name'] }}</div>
            <div class="report-title">{{ $meta['title'] }}</div>
        </div>
    </div>
</header>

<footer>
    {{ $meta['company_name'] }} — Generated {{ $meta['generated_at'] }} &nbsp;|&nbsp;
    Page <span class="pagenum"></span>
</footer>

<div class="meta-line">
    <strong>{{ $meta['period'] }}</strong> &nbsp;|&nbsp;
    Office: {{ $meta['office'] }} &nbsp;|&nbsp;
    Department: {{ $meta['department'] }}
    @if(!empty($meta['employee']))
        &nbsp;|&nbsp; Employee: {{ $meta['employee'] }}
    @endif
    <br>Generated: {{ $meta['generated_at'] }} by {{ $meta['generated_by'] }}
</div>

<table class="summary">
    <tr>
        <td>Total Employees<b>{{ $summary['total_employees'] }}</b></td>
        <td>Present<b>{{ $summary['present'] }}</b></td>
        <td>Late<b>{{ $summary['late'] }}</b></td>
        <td>Absent<b>{{ $summary['absent'] }}</b></td>
        <td>Leave<b>{{ $summary['leave'] }}</b></td>
        <td>Holiday<b>{{ $summary['holiday'] }}</b></td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            <th>No.</th><th>Emp. Code</th><th>Employee Name</th><th>Department</th><th>Office</th>
            <th>Check In</th><th>Check Out</th><th>Working Hrs</th><th>Late (min)</th><th>OT (hr)</th><th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $r)
        <tr>
            <td class="text-center">{{ $r['no'] }}</td>
            <td>{{ $r['employee_code'] }}</td>
            <td>{{ $r['employee_name'] }}</td>
            <td>{{ $r['department'] }}</td>
            <td>{{ $r['office'] }}</td>
            <td class="text-center">{{ $r['check_in'] ?? '—' }}</td>
            <td class="text-center">{{ $r['check_out'] ?? '—' }}</td>
            <td class="text-center">{{ $r['working_hours'] ?? '—' }}</td>
            <td class="text-center">{{ $r['late_minutes'] ?? 0 }}</td>
            <td class="text-center">{{ $r['overtime_hours'] ?? 0 }}</td>
            <td class="text-center">
                @php $cls = ['Present'=>'badge-present','Late'=>'badge-late','Absent'=>'badge-absent','Leave'=>'badge-leave','Holiday'=>'badge-holiday'][$r['status']] ?? 'badge-absent'; @endphp
                <span class="badge {{ $cls }}">{{ $r['status'] }}</span>
            </td>
        </tr>
        @empty
        <tr><td colspan="11" class="text-center" style="padding:20px;color:#9ca3af;">No attendance records found.</td></tr>
        @endforelse
    </tbody>
</table>

<script type="text/php">
    if (isset($pdf)) {
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $size = 9;
        $width = $fontMetrics->get_text_width($text, $font, $size);
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size, array(0.6,0.6,0.6));
    }
</script>

</body>
</html>
