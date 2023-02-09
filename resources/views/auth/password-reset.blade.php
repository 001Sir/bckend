@extends('layouts.main')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <a href="/">
        <img src="/img/logo.png" class="w-40 mx-auto mb-8" alt="logo" />
    </a>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        @if($status == 'success')
        <div class="card">
            <div class="text-teal-600 font-bold text-lg mb-4">{{ __('Please check your inbox!') }}</div>
            <p class="text-md font-normal">{{ __("Password Reset link sent.") }}</p>
        </div>
        @endif

        @if($status == 'fail')
        <div class="card">
            <div class="text-red-600 font-bold text-lg mb-4">{{ __('Whoops! Something went wrong.') }}</div>
            <p class="text-md font-normal">{{ __("We couldn't generate a password reset for that email, please check again.") }}</p>
        </div>
        @endif
    </div>
</div>
@endsection