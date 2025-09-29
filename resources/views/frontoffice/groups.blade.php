@extends('frontoffice.layouts.app')

@section('title', 'Groupes - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Animated header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Groups
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                Discover communities of readers. Join, share, and discuss your favorite books!
            </p>
        </div>
        <br>

        <!-- Grid of groups -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12">
            @foreach($groups as $group)
                @php
                    $status = null;
                    $isMember = false;
                    $isPending = false;
                    if(auth()->check()) {
                        $pivot = $group->users->where('id', auth()->id())->first();
                        if($pivot) {
                            $status = $pivot->pivot->status;
                            $isMember = ($status === 'accepted');
                            $isPending = ($status === 'pending');
                        }
                    }
                    $memberCount = $group->users->where('pivot.status', 'accepted')->count();
                    $recentMembers = $group->users()->wherePivot('status', 'accepted')->orderBy('group_user.updated_at', 'desc')->take(3)->get();
                @endphp
                
                <div class="card relative p-4 border rounded-xl hover:shadow-lg transition-shadow bg-white dark:bg-[#161615] flex flex-col h-full">
                    <!-- Badge thème en haut à gauche -->
                    <div class="absolute top-3 left-3 z-10">
                        <span class="bg-primary py-1 px-3 text-xs text-white rounded-lg font-semibold shadow" style="letter-spacing:0.5px;">
                            {{ $group->theme }}
                        </span>
                    </div>
                    
                    <!-- Badge public/privé en haut à droite -->
                    <div class="absolute top-3 right-3 z-10">
                        <span class="bg-black/20 backdrop-blur-sm text-black px-2 py-1 rounded-full text-xs flex items-center gap-1">
                            @if($group->is_public)
                                <span>🌐</span> Public
                            @else
                                <span>🔒</span> Private
                            @endif
                        </span>
                    </div>

                    <!-- Image du groupe -->
                    <div class="flex justify-center items-center relative mt-2">
                        @if($group->image)
                            <img src="{{ asset('storage/' . $group->image) }}" style="width:128px; height:128px; object-fit:cover; border-radius:0.75rem; box-shadow:0 1px 4px #0001; border:2px solid #e3e3e0;" alt="{{ $group->name }}">
                        @else
                            <div style="width:128px; height:128px; display:flex; align-items:center; justify-content:center; background:#f3f4f6; border-radius:0.75rem; border:2px solid #e3e3e0;">
                                <span class="text-4xl text-primary">📚</span>
                            </div>
                        @endif
                    </div>

                    <!-- Contenu -->
                    <div class="mt-4 flex-1 flex flex-col">
                        <h6 class="mb-1 font-bold text-base text-center">
                            <a href="{{ $isMember ? route('frontoffice.group.wall', $group->id) : '#' }}" class="hover:text-primary underline">{{ $group->name }}</a>
                        </h6>
                        
                        <p class="text-xs text-gray-500 text-center mb-2 line-clamp-2">{{ $group->description }}</p>
                        
                        <div class="flex items-center justify-center gap-2 mb-2">
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <span>👥</span> {{ $memberCount }}
                            </span>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <span>📅</span> {{ $group->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-center mb-2">
                            @foreach($recentMembers as $member)
                                <div class="w-7 h-7 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            @endforeach
                            @if($memberCount > 3)
                                <span class="text-xs text-gray-400 ml-2">+{{ $memberCount - 3 }} more</span>
                            @endif
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="flex flex-col gap-2 mt-auto">
                            @if(!auth()->check())
                                <a href="{{ route('login') }}" class="w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white py-2 px-4 rounded-lg font-medium text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors inline-flex items-center justify-center gap-2">
                                    <span>🔒</span> Sign in to join
                                </a>
                            @elseif($isMember)
                                <a href="{{ route('frontoffice.group.wall', $group->id) }}" class="w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white py-2.5 px-4 rounded-lg font-medium text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors inline-flex items-center justify-center gap-2 border border-gray-300 dark:border-gray-600 text-center">
                                    <span>👥</span> <span class="w-full block text-center">View group</span>
                                </a>
                                <div class="text-center">
                                    <span class="inline-flex items-center text-xs text-green-600 dark:text-green-400 font-medium bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-full">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> You are a member
                                    </span>
                                </div>
                            @elseif($isPending)
                                <div class="w-full bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-300 py-2.5 px-4 rounded-lg font-medium text-sm border border-amber-200 dark:border-amber-800 flex items-center justify-center gap-2 text-center">
                                    <span>⏳</span> <span class="w-full block text-center">Request pending</span>
                                </div>
                                <div class="text-center">
                                    <span class="text-xs text-amber-600 dark:text-amber-400">Awaiting approval</span>
                                </div>
                            @else
                                <form action="{{ route('groups.join', $group->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-lg font-medium text-sm transition-colors inline-flex items-center justify-center gap-2">
                                        <span>+</span> Join group
                                    </button>
                                </form>
                                @if(!$group->is_public)
                                    <div class="text-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Admin approval required</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message if no groups -->
        @if($groups->count() == 0)
            <div class="text-center py-16 bg-white dark:bg-[#161615] rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl text-gray-400">👥</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No groups available</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Check back later to discover new communities.</p>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium text-sm">Refresh</button>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }
    
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    /* Style Facebook */
    .shadow-sm {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .hover\:shadow-md {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Avatar stack */
    .flex.items-center.justify-center > div:not(:first-child) {
        margin-left: -0.5rem;
    }

    /* Transition douce */
    .transition-colors {
        transition: all 0.2s ease-in-out;
    }

    /* Design cohérent avec Facebook */
    .bg-blue-600 {
        background-color: #1877f2;
    }

    .hover\:bg-blue-700 {
        background-color: #166fe5;
    }

    /* Bordures subtiles */
    .border-gray-200 {
        border-color: #dddfe2;
    }

    .dark .border-gray-700 {
        border-color: #3e4042;
    }

    /* Positionnement des badges */
    .absolute.top-3.left-3 {
        top: 0.75rem;
        left: 0.75rem;
    }

    .absolute.top-3.right-3 {
        top: 0.75rem;
        right: 0.75rem;
    }

    /* Z-index pour que les badges soient au-dessus */
    .z-10 {
        z-index: 10;
    }
</style>
@endpush