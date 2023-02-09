@extends('layouts.main')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <a href="/">
        <img src="/img/logo.png" class="w-40 mx-auto mb-8" alt="logo" />
    </a>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-4 text-gray-600">
            <p class="text-md text-black font-bold mb-2">{{ __('Forgot your password?') }} 
            <p class="text-xs">{{ __('No problem. Just let us know your email address and we will email send you a password reset link that will allow you to choose a new one.') }}</p>
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

                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.token') }}">
        @csrf

        <!-- Email Address -->
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">
                    {{ __('Email') }}
                </label>

                <input id="email" name="email" type="email" class="block mt-1 w-full p-2 rounded-md shadow-sm border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-teal-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400 active:bg-green-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection