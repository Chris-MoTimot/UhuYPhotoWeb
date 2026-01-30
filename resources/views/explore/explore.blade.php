@extends('layouts.app')

@section('title', 'Jelajahi Inspirasi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Explore</h1>
        <p class="text-gray-600">Temukan inspirasi dari seluruh komunitas.</p>
    </div>

    <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
        @foreach($pins as $pin)
            <div class="break-inside-avoid shadow-sm hover:shadow-xl transition-shadow duration-300 rounded-2xl bg-white border border-gray-100 overflow-hidden group">
                <a href="{{ route('pins.show', $pin->id) }}">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $pin->image_url) }}" 
                             alt="{{ $pin->title }}" 
                             class="w-full h-auto group-hover:brightness-90 transition-all duration-300">
                    </div>
                    
                    <div class="p-4">
                        <h2 class="font-semibold text-gray-800 text-sm truncate">{{ $pin->title }}</h2>
                        
                        <div class="flex items-center mt-3 space-x-2">
                            @if($pin->user->profile_picture)
                                <img src="{{ asset('storage/' . $pin->user->profile_picture) }}" 
                                     class="w-6 h-6 rounded-full object-cover">
                            @else
                                <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-[10px]"></i>
                                </div>
                            @endif
                            <span class="text-xs text-gray-500">{{ $pin->user->name }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-10">
        {{ $pins->links() }}
    </div>
</div>
@endsection