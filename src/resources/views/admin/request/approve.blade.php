<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修正申請承認画面(管理者)</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/request/approve.css') }}">
</head>
<body class="body">
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ">

        <div class="header-nav">
        <a href="/admin/attendance/list">勤怠一覧</a>
        <a href="/admin/staff/list">スタッフ一覧</a>
        <a href="/stamp_correction_request/list">申請一覧</a>
        <form class="logout-form" action="/admin/logout" method="post">
            @csrf
            <button class="logout-button" type="submit">ログアウト</button>
        </form>
        </div>
    </header>

    <main class="main">
        <h2><span class="line"></span>勤怠詳細</h2>

        <table class="table">
            <tr>
                <th>名前</th>
                <td>{{ $attendanceRequest->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{ \Carbon\Carbon::parse($attendanceRequest->attendance->start_time)->isoFormat('YYYY年MM月DD日') }}</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>{{ \Carbon\Carbon::parse($attendanceRequest->attendance->start_time)->format('H:i') }}
                <span class="separator">〜</span>
                {{ \Carbon\Carbon::parse($attendanceRequest->attendance->end_time)->format('H:i') }}</td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    @foreach($attendanceRequest->attendance->breaks as $break)
                    {{ \Carbon\Carbon::parse($break->break_start)->format('H:i') }}
                    <span class="separator">〜</span>
                    {{ \Carbon\Carbon::parse($break->break_end)->format('H:i') }}<br>
                    @endforeach
                </td>
            </tr>

            <tr>
                <th>備考</th>
                <td>{{ $attendanceRequest->remarks }}</td>
            </tr>
        </table>

        @if ($attendanceRequest->status === 'approved')
        <button disabled class="approved-button">承認済み</button>
        @else

        <form method="POST" action="{{ url('/stamp_correction_request/approve/' . $attendanceRequest->id) }}">
        @csrf
        <button type="submit" class="button">承認</button>
        </form>
        @endif

    </main>
</body>
</html>