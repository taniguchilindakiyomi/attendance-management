<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出勤登録画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/attendance/create.css') }}">
</head>
<body class="body">
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ">
        <div class="header-nav">
        <a href="/attendance">勤怠</a>
        <a href="/attendance/list">勤怠一覧</a>
        <a href="/stamp_correction_request/list">申請</a>
        <form class="logout-form" action="/logout" method="post">
            @csrf
            <button class="logout-button" type="submit">ログアウト</button>
        </form>
        </div>
    </header>

    <main class="main">
        <div class="status">{{ $status }}</div>
        <p class="date">{{ \Carbon\Carbon::now()->isoFormat('YYYY年M月D日（ddd）') }}
        </p>
        <p class="time"><strong><span id="current-time">{{ now()->format('H:i') }}</span></strong>
        </p>

        @if ($status === '勤務外')
        <form action="/attendance/start" method="post">
            @csrf
            <button type="submit" class="button">出勤</button>
        </form>

        @elseif ($status === '出勤中')

        <div class="button-group">
        <form action="/attendance/end" method="post">
            @csrf
            <button type="submit" class="button">退勤</button>
        </form>
        <form action="/attendance/break/start" method="post">
            @csrf
            <button type="submit" class="break">休憩入</button>
        </form>
        </div>

        @elseif ($status === '休憩中')
        <form action="/attendance/break/end" method="post">
            @csrf
            <button type="submit" class="break">休憩戻</button>
        </form>
        @elseif ($status === '退勤済')
        <p class="comment">お疲れ様でした。</p>
        @endif

    </main>
    <script>
    function updateTime() {
        const currentTimeElement = document.getElementById('current-time');
        const now = new Date();

        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        currentTimeElement.textContent = `${hours}:${minutes}`;
    }

    updateTime();

    setInterval(updateTime, 1000);
</script>
</body>
</html>