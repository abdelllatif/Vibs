@extends('layouts.app') {{-- Use your main layout if available --}}

@section('content')
<div class="min-h-screen bg-gray-900 text-white flex flex-col">
    {{-- Header --}}
    <div class="bg-gray-800 p-4 flex justify-between items-center">
        <h2 class="text-xl font-bold">Messages</h2>
        <button class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600">New Message</button>
    </div>

    {{-- Messages Container --}}
    <div class="flex flex-col flex-grow p-4 space-y-4 overflow-y-auto">
        {{-- Message 1 (Sent by user) --}}
        <div class="flex justify-end">
            <div class="bg-blue-500 text-white p-3 rounded-lg max-w-sm shadow-md">
                Hey, how are you?
            </div>
        </div>

        {{-- Message 2 (Received from another user) --}}
        <div class="flex justify-start">
            <div class="bg-gray-700 p-3 rounded-lg max-w-sm shadow-md">
                I'm good! What about you?
            </div>
        </div>

        {{-- Message 3 (Sent by user) --}}
        <div class="flex justify-end">
            <div class="bg-blue-500 text-white p-3 rounded-lg max-w-sm shadow-md">
                Just working on my project!
            </div>
        </div>

        {{-- Message 4 (Received) --}}
        <div class="flex justify-start">
            <div class="bg-gray-700 p-3 rounded-lg max-w-sm shadow-md">
                That sounds great! Need any help?
            </div>
        </div>
    </div>

    {{-- Message Input Field --}}
    <div class="bg-gray-800 p-4 flex items-center">
        <input type="text" class="flex-grow bg-gray-700 text-white p-2 rounded-lg outline-none" placeholder="Type a message...">
        <button class="ml-2 bg-blue-500 p-2 rounded-lg hover:bg-blue-600">
            📩
        </button>
    </div>
</div>
@endsection
