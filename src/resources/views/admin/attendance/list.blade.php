<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠一覧画面(管理者)</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/list.css') }}">
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
        <h2><span class="line"></span>{{ \Carbon\Carbon::parse($date)->isoFormat('YYYY年M月D日') }}の勤怠</h2>


        <div class="date-navigation">

            <div class="nav-left">
            <img src="{{ asset('images/arrow.png') }}" alt="" class="left-arrow">
            <a href="?date={{ \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') }}" class="previous-date">前日</a>
            </div>

            <div class="center-content">
            <img src="{{ asset('images/date.png') }}" class="image" alt="">
            <span class="time">{{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}</span>
            </div>

            <div class="nav-right">
            <a href="?date={{ \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') }}" class="next-date">翌日</a>
            <img src="{{ asset('images/arrow.png') }}" alt="" class="right-arrow">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>

                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>
                @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</td>
                    <td>
                        @php
                            $breakTotal = $attendance->breaks->reduce(function($carry, $break) {
                                $start = \Carbon\Carbon::parse($break->break_start);
                                $end = $break->break_end ? \Carbon\Carbon::parse($break->break_end) : now();
                                return $carry + $start->diffInMinutes($end);
                            }, 0);
                        @endphp
                        {{ $breakTotal > 0 ? floor($breakTotal / 60) . ':' . str_pad($breakTotal % 60, 2, '0', STR_PAD_LEFT) : '0:00' }}
                    </td>

                    <td>
                        @php
                            $workTotal = $attendance->end_time ? \Carbon\Carbon::parse($attendance->start_time)->diffInMinutes(\Carbon\Carbon::parse($attendance->end_time)) : 0;
                            $actualWorkTime = $workTotal - $breakTotal;
                        @endphp
                        {{ $actualWorkTime > 0 ? floor($actualWorkTime / 60) . ':' . str_pad($actualWorkTime % 60, 2, '0', STR_PAD_LEFT) : '0:00' }}
                    </td>
                    <td>
                        <a href="{{ url('/admin/attendance/' . $attendance->id) }}" class="detail">詳細</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>