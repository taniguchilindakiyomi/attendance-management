<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ">
    </header>

    <main class="main">

        <h2>会員登録</h2>

        <form class="register-form" action="/register" method="post">
            @csrf
            <label class="register-form_label" for="name">名前</label>
            <input type="text" name="name" class="register-form_label-input" value="{{ old('name') }}">
            <div class="register-form_error-message">
                @error('name')
                {{ $message }}
                @enderror
            </div>

            <label class="register-form_label" for="email">メールアドレス</label>
            <input type="text" name="email" class="register-form_label-input" value="{{ old('email') }}">
            <div class="register-form_error-message">
                @error('email')
                {{ $message }}
                @enderror
            </div>

            <label class="register-form_label" for="password">パスワード</label>
            <input type="password" name="password" class="register-form_label-input" value="{{ old('password') }}">
            <div class="register-form_error-message">
                @error('password')
                {{ $message }}
                @enderror
            </div>

            <label class="register-form_label" for="password_confirmation">パスワード確認</label>
            <input type="password" name="password_confirmation" class="register-form_label-input" value="{{ old('password_confirmation') }}">
            <div class="register-form_error-message">
                @error('password_confirmation')
                {{ $message }}
                @enderror
            </div>

            <button type="submit" class="register-button">登録する</button>

        </form>

        <a href="/login" class="login-link">ログインはこちら</a>

    </main>
</body>
</html>