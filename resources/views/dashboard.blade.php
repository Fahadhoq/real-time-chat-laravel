<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class='text-center my-4'>
        <hr class='my-4'>
        <span class='w-3/5 mx-auto mt-4'>
            <div>
                <h1>Event</h1>
                <a href="{{ route('order.ship') }}">
                    <button class="ms-3">
                    Click or order ship
                    </button>
                </a>
            </div>
        </span>
    </div>
</x-app-layout>
