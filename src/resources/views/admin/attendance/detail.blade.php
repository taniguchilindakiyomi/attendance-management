<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠詳細画面(管理者)</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/detail.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>

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

        <form class="form" action="/admin/attendance/{{ $attendance->id }}" method="post">
            @csrf
            @method('PUT')

        <table class="table">
            <tr>
                <th>名前</th>
                <td>{{ $attendance->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>
                    <input class="input-date" type="text" name="start_date" value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('  Y年                               n月j日') }}">
                </td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td><input  class="input" type="time" name="start_time" value="{{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '' }}">
                    <span class="separator">〜</span>
                    <input class="input" type="time" name="end_time" value="{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '' }}">
                    <div class="form-error">
                    @error('end_time')
                    {{ $message }}
                    @enderror
                    </div>
                </td>
            </tr>
            @foreach ($attendance->breaks as $index => $break)
            <tr>
                <th>休憩{{ $index + 1 }}</th>
                <td>
                    <input class="input" type="time" name="breaks[{{ $index }}][break_start]" value="{{ \Carbon\Carbon::parse($break->break_start)->format('H:i') }}">
        <span class="separator">〜</span>
        <input class="input" type="time" name="breaks[{{ $index }}][break_end]" value="{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}">
                    <div class="form-error">
                    @error('breaks.' . $index . '.break_start')
                    {{ $message }}
                    @enderror

                    @error('breaks.' . $index . '.break_end')
                    {{ $message }}
                    @enderror
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <th>備考</th>
                <td>
                    <textarea class="textarea" name="remarks">{{ old('remarks') }}</textarea>
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
    </main>
    <script>
    flatpickr("#datepicker", {
        locale: "ja",
        dateFormat: "Y年n月j日",
        altInput: true,
        altFormat: "Y-m-d",
        defaultDate: "{{ \Carbon\Carbon::parse($attendance->start_time)->format('Y-m-d') }}"
    });
</script>
</body>
</html>