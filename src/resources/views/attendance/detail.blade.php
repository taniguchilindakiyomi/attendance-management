<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠詳細画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css//attendance/detail.css') }}">
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
        <h2><span class="line"></span>勤怠詳細</h2>

@if(!$stampRequest)
        <form action="/attendance/{{ $attendance->id }}" method="post">
            @csrf
            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
            <table class="table">
            <tr>
                <th>名前</th>
                <td>{{ Auth::user()->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('Y年n月j日') }}</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    <input class="input" type="time" name="requested_start_time" value="{{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '' }}">
                    <span class="separator">〜</span>
                    <input class="input" type="time" name="requested_end_time" value="{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '' }}">

                    <div class="form-error">
                    @error('requested_end_time')
                    {{ $message }}
                    @enderror
                    </div>
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    @foreach($attendance->breaks as $break)
                <input class="input" type="time" name="requested_break_start"
                    value="{{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}">
                <span class="separator">〜</span>
                <input class="input" type="time" name="requested_break_end"
                    value="{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}">
                    <div class="form-error">
                @error("requested_break_start")
                    {{ $message }}
                @enderror
                @error("requested_break_end")
                    {{ $message }}
                @enderror
            </div>
            @endforeach


        @if($attendance->breaks->count() >= 2)
            <div>
                <input class="input" type="time" name="requested_break_start" value="{{ old('requested_breaks.new.start') }}">
                <span class="separator">〜</span>
                <input class="input" type="time" name="requested_break_end" value="{{ old('requested_breaks.new.end') }}">

                <div class="form-error">
                @error('requested_break_start')
                    {{ $message }}
                @enderror
                @error('requested_break_end')
                    {{ $message }}
                @enderror
                </div>
            </div>
        @endif
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>
                    <textarea name="remarks" class="textarea">{{ old('remarks') }}</textarea>
                    <div class="form-error">
                    @error('remarks')
                    {{ $message }}
                    @enderror
                    </div>
                </td>
            </tr>
        </table>

        <div class="mt-4">
            <button type="submit" class="button">修正</button>
        </div>
        </form>
@endif

@if($stampRequest)
        <table class="table">
            <tr>
                <th>名前</th>
                <td>{{ Auth::user()->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{ \Carbon\Carbon::parse($attendance->start_time)->isoFormat('YYYY年M月D日') }}</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    {{ $stampRequest->requested_start_time ? \Carbon\Carbon::parse($stampRequest->requested_start_time)->format('H:i') : '' }}
                    <span class="separator">〜</span>
                    {{ $stampRequest->requested_end_time ? \Carbon\Carbon::parse($stampRequest->requested_end_time)->format('H:i') : '' }}
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    {{ $stampRequest->requested_break_start ? \Carbon\Carbon::parse($stampRequest->requested_break_start)->format('H:i') : '' }}
                    <span class="separator">〜</span>
                    {{ $stampRequest->requested_break_end ? \Carbon\Carbon::parse($stampRequest->requested_break_end)->format('H:i') : '' }}
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>{{ $stampRequest->remarks }}</td>
            </tr>
        </table>
        <p class="alert-warning">*承認待ちのため修正はできません。</p>
        @endif
    </main>
</body>
</html>