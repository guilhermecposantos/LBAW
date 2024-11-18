@extends('layouts.app')

@section('content')
<form method="POST" action="{{ url('/profile') }}" enctype="multipart/form-data" id='edit-profile-form'>
    @csrf
    <h1>Edit profile</h1>
    <label for="first-name">First Name</label>
    <input type="text" placeholder="Your first name" id="first-name" name="first-name"
        value="{{ old('firstname', $old['firstname']) }}" required autofocus spellcheck='false' autocomplete='off'>
    <label for="last-name">Last Name</label>
    <input type="text" placeholder="Your last name" id="last-name" name="last-name"
        value="{{ old('lastname', $old['lastname']) }}" required autofocus spellcheck='false' autocomplete='off'>
    <label for="username">Username</label>
    <input type="text" placeholder="Your username" id="username" name="username"
        value="{{ old('username', $old['username']) }}" required autofocus spellcheck='false' autocomplete='off'>
    <label for="email">Email</label>
    <input type="text" id="email" name="email" placeholder="Your email" value="{{ old('email', $old['email']) }}"
        required autofocus spellcheck='false' autocomplete='off'>
    <button type="submit">
        Save changes
    </button>
</form>
</div>
@endsection