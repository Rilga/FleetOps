<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #0056b3; margin-bottom: 20px; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .status-critical { color: #dc3545; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 9px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Vessel Identity: <strong>{{ $ship->name }}</strong> | Generated on: {{ $date }}</p>
        <p>Period: <strong>{{ $period }}</strong></p>
    </div>

    @foreach($ship->machineries as $machinery)
        <div style="background: #eee; padding: 5px; margin-top: 10px;">
            <strong>UNIT: {{ strtoupper($machinery->name) }} ({{ $machinery->model }})</strong>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Job Details</th>
                    <th>Interval</th>
                    <th>Completion Date</th>
                    <th>Done at RH</th>
                    <th>Verification</th>
                </tr>
            </thead>
            <tbody>
                @forelse($machinery->maintenanceTasks as $task)
                    @foreach($task->histories as $history)
                <tr>
                    <td>{{ $task->job_details }}</td>
                    <td>{{ $task->interval }} hrs</td>
                    <td>{{ $history->completion_date }}</td>
                    <td>{{ number_format($history->done_at_rh, 0) }}</td>
                    <td>{{ $history->is_verified ? 'VERIFIED' : 'PENDING' }}</td>
                </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #777;">No maintenance history for the selected period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        Gitera Fleet Management System - Confidential Report
    </div>
</body>
</html>
