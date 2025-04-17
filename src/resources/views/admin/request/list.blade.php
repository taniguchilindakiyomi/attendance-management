<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請一覧画面(管理者)</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/request/list.css') }}">
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
        <h2><span class="line"></span>申請一覧</h2>

        <div class="status">

            <a href="{{ url('/stamp_correction_request/list?status=pending') }}"  class="status-button {{ $status === 'pending' ? 'active' : 'inactive' }}">承認待ち</a>

            <a href="{{ url('/stamp_correction_request/list?status=approved') }}"  class="status-button {{ $status === 'approved' ? 'active' : 'inactive' }}">承認済み</a>
        </div>

        <span class="under-line"></span>

        <table class="table">
            <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
                <tr>
                    <td>{{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->attendance->start_time)->format('Y/m/d') }}</td>
                    <td>{{ $request->remarks }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}</td>
                    <td>
                        <a href="{{ url('/stamp_correction_request/approve/' . ($request->id ?? '')) }}" class="detail">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </main>
</body>
</html>