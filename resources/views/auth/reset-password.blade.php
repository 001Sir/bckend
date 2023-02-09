@extends('layouts.main')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <a href="/">
        <img src="/img/logo.png" class="w-40 mx-auto mb-8" alt="logo" />
    </a>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-4 text-gray-600">
            <p class="text-md text-black font-bold mb-2">{{ __('Reset Password') }} 
        </div>

        <!-- Session Status -->
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4">
                <div class="font-medium text-red-600">
                    {{ __('Whoops! Something went wrong.') }}
                </div>
            </div>
        @endif

        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ request()->token }}">

                <div class="mb-3">
                    <label for="email" class="py-10 col-md-4 col-form-label text-md">{{ __('Email') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="w-full p-2 @error('email') is-invalid border-red-400 @enderror border" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password">{{ __('Password') }}</label>

                    <div>
                        <input id="password" type="password" class="w-full p-2 @error('password') is-invalid border-red-400 @enderror border" name="password" required autocomplete="new-password">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password-confirm">{{ __('Confirm Password') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="w-full p-2 border mt-2" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="
                        w-40
                        px-2
                        py-2
                        border border-gray-300
                        bg-teal-400
                        text-white
                        font-bold
                        text-sm text-center
                        rounded-lg
                        hover:bg-green-400
                        mt-4">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection