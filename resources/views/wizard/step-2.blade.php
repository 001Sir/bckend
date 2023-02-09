@extends('layouts.main')

@section('content')
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <a href="/">
            <img src="/img/logo.png" class="w-40 mx-auto mb-8" alt="logo" />
        </a>

        <div class="w-[1024px] mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h3 class="raleway text-lg font-bold text-center uppercase mt-3">{{ __('Contact Information') }}</h3>
            <div class="w-[800px] mx-auto my-4 bg-gray-200 rounded-full">
                <div
                    style="width: 400px"
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
                    {{ __('Step 2') }}
                </div>
            </div>

            <form method="POST" action="{{ route('wizard.step2.form') }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-6">
                    <label for="address" class="block font-medium text-sm text-gray-700">
                        {{ __('Address') }}
                    </label>

                    <textarea id="address" name="address"
                        class="block mt-1 w-full h-[100px] p-2 rounded-md shadow-sm border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        value="" required autofocus></textarea>
                </div>

                <!-- App Min. Version (iOS & Android) -->
                <div class="grid grid-cols-2 gap-12 mt-6">
                    <div class="col-span-1">
                        <label for="contactEmail" class="block font-medium text-sm text-gray-700">
                            {{ __('Contact E-mail') }}
                        </label>
    
                        <input id="contactEmail" name="contactEmail" type="text"
                            class="block mt-1 w-full p-2 rounded-md shadow-sm border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            value="" required autofocus>
                    </div>
                    <div class="col-span-1">
                        <label for="phone" class="block font-medium text-sm text-gray-700">
                            {{ __('Phone') }}
                        </label>
    
                        <input id="phone" name="phone" type="text"
                            class="block mt-1 w-full p-2 rounded-md shadow-sm border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            value="" required autofocus>
                    </div>                
                </div>

                {{-- <div class="mt-6">
                    <label for="footerText" class="block font-medium text-sm text-gray-700">
                        {{ __('Footer Text') }}
                    </label>

                    <input id="footerText" name="footerText" type="text"
                        class="block mt-1 w-full p-2 rounded-md shadow-sm border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        value="" required autofocus>
                </div>                 --}}

                <div class="flex items-center justify-between mt-6">
                    <a
                        href="{{ route('wizard.step1') }}"
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
