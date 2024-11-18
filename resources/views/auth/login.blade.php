@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}" class="auth">
    {{ csrf_field() }}

    <h1>Sign in</h1>

    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete='off' spellcheck='false'>
    @if ($errors->has('email'))
    <span class="error">
        {{ $errors->first('email') }}
    </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required autocomplete='off' spellcheck='false'>
    @if ($errors->has('password'))
    <span class="error">
        {{ $errors->first('password') }}
    </span>
    @endif

    <label id="remember">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button type="submit">
        Login
    </button>
    <div id='gotoregister'>
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left"
      viewBox="0 0 16 16">
      <path fill-rule="evenodd"
        d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
      <path fill-rule="evenodd"
        d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
    </svg>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
  </div>
    @if (session('success'))
    <p class="success">
        {{ session('success') }}
    </p>
    @endif
</form>
@endsection