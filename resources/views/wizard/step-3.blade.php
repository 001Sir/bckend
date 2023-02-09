@extends('layouts.main')

@section('content')
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <a href="/">
            <img src="/img/logo.png" class="w-40 mx-auto mb-8" alt="logo" />
        </a>

        <div class="w-[1024px] mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h3 class="raleway text-lg font-bold text-center uppercase mt-3">{{ __('Admin Account') }}</h3>
            <div class="w-[800px] mx-auto my-4 bg-gray-200 rounded-full">
                <div
                    style="width: 600px"
                    class="
                p-1
                text-xs
                font-medium
                leading-none
                text-center text-blue-100
                bg-teal-400
                rounded-full
                mb-4
              ">
                    {{ __('Step 3') }}
                </div>
            </div>

            <form method="POST" action="{{ route('wizard.step3.form') }}" enctype="multipart/form-data">
                @csrf
   
                <div class="mb-4 text-gray-600 mt-8">
                    <p class="text-md text-black font-bold mb-2">{{ __('Profile Picture') }}
                    <p class="text-xs">
                        {{ __('Upload your admin profile picture here.') }}
                    </p>
                    <input class="absolute pin-x pin-y cursor-pointer mt-2" type="file" name="profilePic" />
                </div>

                <div class="mt-16">
                    <label for="name" class="block font-medium text-sm text-gray-700">
                        {{ __('Name') }}
                    </label>

                    <input id="name" name="name" type="text"
                        class="block mt-1 w-full p-2 rounded-md shadow-sm border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        value="" required autofocus></textarea>
                </div>

                <div class="mt-6">
                    <label for="email" class="block font-medium text-sm text-gray-700">
                        {{ __('Email') }}
                    </label>

                    <input id="email" name="email" type="email"
                        class="block mt-1 w-full p-2 rounded-md shadow-sm border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        value="" required autofocus></textarea>
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">
                        {{ __('Password') }}
                    </label>

                    <input id="password" name="password" type="password"
                        class="block mt-1 w-full p-2 rounded-md shadow-sm border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        value="" required autofocus></textarea>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a
                        href="{{ route('wizard.step2') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        &lt; {{ __('Previous') }}
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        {{ __('Submit & Next') }} &gt;
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
