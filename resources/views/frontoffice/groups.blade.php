@extends('frontoffice.layouts.app')

@section('title', 'Groupes - Bookly')

@section('content')
    <div class="min-h-screen bg-white dark:bg-black py-8">
        <!-- Header amélioré avec votre style -->
        <div class="container mx-auto px-4 mb-12">
            <div class="text-center">
                <h1 class="text-5xl lg:text-6xl font-extrabold mb-4 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-500 bg-clip-text text-transparent drop-shadow-lg tracking-tight" style="line-height:1.1;">
                    Groupes de Lecture
                </h1>
                <p class="text-lg text-black dark:text-white max-w-2xl mx-auto">
                    Découvrez et rejoignez des communautés de lecteurs passionnés
                </p>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="container mx-auto px-4">
            <!-- Grid des groupes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                    @endphp

                    <div class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden flex flex-col min-h-[340px]">
                        <!-- Badge thème à gauche -->
                        <div class="absolute top-4 left-4 bg-primary py-1 px-3 text-xs text-white rounded-lg z-10">
                            {{ $group->theme }}
                        </div>

                        <!-- Image/Icone du groupe -->
                        <div class="flex items-center justify-center pt-6 pb-2 bg-gradient-to-br from-blue-400 to-purple-500">
                            @if($group->image)
                                <img src="{{ asset('storage/'.$group->image) }}" alt="Image du groupe" class="w-20 h-20 object-cover rounded-full shadow border-4 border-white -mt-8 z-10">
                            @else
                                <div class="w-20 h-20 flex items-center justify-center rounded-full bg-white shadow border-4 border-white -mt-8 z-10">
                                    <span class="text-4xl text-blue-500">👥</span>
                                </div>
                            @endif
                        </div>

                        <!-- Contenu -->
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <!-- En-tête -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $group->name }}</h3>
                            <!-- Description -->
                            <p class="text-gray-600 mb-2 line-clamp-2 leading-relaxed text-sm">
                                {{ $group->description ?: 'Découvrez et rejoignez cette communauté de lecteurs passionnés.' }}
                            </p>
                            <!-- Statistiques -->
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <div class="flex items-center space-x-1">
                                    <span class="text-blue-500">👥</span>
                                    <span>{{ $memberCount }} membre(s)</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span class="text-purple-500">📅</span>
                                    <span>{{ $group->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <!-- Boutons d'action -->
                            <div class="space-y-2 mt-2">
                                @if(!auth()->check())
                                    <a href="{{ route('login') }}" class="w-full bg-primary text-white py-2 px-3 rounded-lg font-bold text-sm shadow-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition inline-flex items-center justify-center">
                                        <span class="mr-1">🔒</span> Connexion requise
                                    </a>
                                @elseif($isMember)
                                    <a href="{{ route('frontoffice.group.wall', $group->id) }}" class="w-full bg-gradient-to-r from-blue-600 to-purple-700 text-black py-2 px-3 rounded-lg font-bold text-sm shadow-lg hover:from-purple-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition inline-flex items-center justify-center">
                                        <span class="mr-1">🚀</span> <span style="color:#000; text-shadow:0 1px 2px #fff;">Accéder au groupe</span>
                                    </a>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-success-light text-success-dark border border-success mt-1">
                                        <span class="mr-1">✅</span> Membre
                                    </span>
                                @elseif($isPending)
                                    <span class="w-full bg-warning text-black py-2 px-3 rounded-lg font-bold text-sm shadow-lg focus:outline-none focus:ring-2 focus:ring-warning focus:ring-offset-2 transition inline-flex items-center justify-center cursor-default">
                                        <span class="mr-1">⏳</span> Demande en attente
                                    </span>
                                    <span class="text-xs text-warning-dark font-medium text-black">Validation en cours</span>
                                @else
                                    <form action="{{ route('groups.join', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-3 rounded-lg font-bold text-sm shadow-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition inline-flex items-center justify-center">
                                            <span class="mr-1">🤝</span> Rejoindre
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message si aucun groupe -->
            @if($groups->count() == 0)
                <div class="text-center py-16">
                    <div class="text-6xl mb-4 text-black dark:text-white">📚</div>
                    <h3 class="text-2xl font-bold text-black dark:text-white mb-2">Aucun groupe disponible</h3>
                    <p class="text-black dark:text-white">Revenez plus tard pour découvrir de nouvelles communautés.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
