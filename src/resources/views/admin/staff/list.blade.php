<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタッフ一覧画面(管理人)</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/staff/list.css') }}">
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
        <h2><span class="line"></span>スタッフ一覧</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>月次勤怠</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ url('/admin/attendance/staff/' . $user->id) }}" class="detail">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </main>
</body>
</html>