@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Dashboard
                </h3>
            </div>
            <div class="p-6">
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <p class="text-sm text-gray-600">
                    You are logged in!
                </p>

                <!-- Display message capsules -->
                <div class="mt-4">
                    <h4 class="text-md text-gray-900">Your Message Capsules</h4>
                    @if($messageCapsules->isEmpty())
                        <p class="text-sm text-gray-600">You have no message capsules.</p>
                    @else
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($messageCapsules as $capsule)
                                <li class="text-sm text-gray-800">{{ $capsule->title }} - Opens
                                    on: {{ $capsule->open_date->format('M d, Y') }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


