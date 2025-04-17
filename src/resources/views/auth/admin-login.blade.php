<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面(管理者)</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/admin-login.css') }}">
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ">
    </header>

    <main class="main">
        <h2>管理者ログイン</h2>

        <form class="login-form" action="/admin/login" method="post">
            @csrf
            <label class="login-form_label" for="email">メールアドレス</label>
            <input type="text" name="email" class="login-form_label-input" value="{{ old('email') }}">
            <div class="login-form_error-message">
                @error('email')
                {{ $message }}
                @enderror
            </div>

            <label class="login-form_label" for="password">パスワード</label>
            <input type="text" name="password" class="login-form_label-input" value="{{ old('password') }}">
            <div class="login-form_error-message">
                @error('password')
                {{ $message }}
                @enderror
            </div>

            <button type="submit" class="admin-login">管理者ログインする</button>

        </form>

    </main>
</body>
</html>
