<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pengingat Jadwal</title>
</head>

<body>
    <h2>Halo {{ $schedule->user->name }},</h2>
    <p>Ini adalah pengingat untuk jadwal rutin Anda.</p>
    <p><strong>Tipe:</strong> {{ ucfirst($schedule->type) }}</p>
    <p><strong>Mulai:</strong> {{ $schedule->start_date }}</p>
    <p><strong>Selesai:</strong> {{ $schedule->end_date ?? '-' }}</p>
    @if (!empty($schedule->rules))
        <p><strong>Aturan:</strong></p>
        <ul>
            @foreach ($schedule->rules as $type => $rule)
                <li>{{ ucfirst($type) }}: {{ is_array($rule) ? implode(', ', $rule) : $rule }}</li>
            @endforeach
        </ul>
    @endif

    <br>
    <p>Terima kasih,<br>Sistem Penjadwalan</p>
</body>

</html>
