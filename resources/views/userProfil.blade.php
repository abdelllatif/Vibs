<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</head>
<body class="bg-gray-900">
    <!-- Navbar -->
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">Vibe</h1>
            <div>
                <a href="/" class="text-gray-300 hover:text-white px-4">Accueil</a>
                <a href="/profile" class="text-gray-300 hover:text-white px-4">Profile</a>
                <a href="/messages" class="text-gray-300 hover:text-white px-4">Messages</a>
                <a href="/logout" class="text-gray-300 hover:text-white px-4">Déconnexion</a>
            </div>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Cover Image -->
            <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600"></div>

            <!-- Profile Info -->
            <div class="relative px-6 py-4">
                <!-- Profile Picture -->
                <div class="absolute -top-16">
                    <img src="{{asset('storage/'.($user->photo_profil ?? 'uploads/user.jpg'))}}" alt="Profile Picture"
                         class="w-32 h-32 rounded-full border-4 border-gray-800">
                </div>

                <!-- Actions -->
                <div class="flex justify-end mb-4">
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Ajouter en ami
                    </button>
                </div>

                <!-- User Info -->
                <div class="mt-8">
                    <h1 class="text-2xl font-bold text-white">{{$user->nom." ".$user->prenom}}</h1>
                    <p class="text-gray-400">@ {{$user->pseudo}}</p>
                    <p class="mt-2 text-gray-300">{{$user->bio}}</p>

                    <!-- Stats -->
                    <div class="flex gap-6 mt-4">
                        <div class="text-center">
                            <span class="text-white font-bold text-lg">{{$posts->count()}}</span>
                            <p class="text-gray-400">Publications</p>
                        </div>
                        <div class="text-center">
                            <span class="text-white font-bold text-lg">{{ $friends->count() }}</span>
                            <p class="text-gray-400">Amis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Friends Section -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Amis</h2>
                <button onclick="toggleFriendsModal()" class="text-blue-500 hover:text-blue-400">
                    Afficher tous
                </button>
            </div>

            @if($friends->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach ($friends as $friend)
                <div class="bg-gray-800 rounded-lg p-4 text-center">
                    <img src="{{asset('storage/'.($user->photo_profil ?? 'uploads/user.jpg'))}}" alt="Friend {{ $friend->name }}"
                         class="w-20 h-20 rounded-full mx-auto mb-2">
                    <h3 class="text-white font-semibold">{{ $friend->nom." ".$friend->prenom}}</h3>
                    <p class="text-gray-400 text-sm">{{ $friend->email }}</p>
                </div>
            @endforeach
            @else
            <p class="text-gray-400 text-center py-8">Pas encore d'amis</p>
            @endif
        </div>




    <!-- Affichage des posts -->
<div class="max-w-2xl mx-auto mt-6">
    @foreach($posts as $post)
     <div class="bg-white p-6 rounded-lg shadow-md mb-4 text-left max-w-md mx-auto dark:bg-gray-800 dark:text-gray-200"> <!-- Changed text-center to text-left -->
         <div class="flex items-center justify-start mb-3">
             <img src="{{ asset('storage/' . ($post->user->photo_profil ?? 'uploads/user.jpg')) }}" alt="{{ $post->user->nom }}" class="rounded-full w-8 h-8">
             <div class="ml-2">
                 <p class="font-bold text-sm dark:text-white">{{ $post->user->nom }} {{ $post->user->prenom }}</p>
                 <p class="text-xs text-gray-500 flex items-center dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M3 10h18M4 20h16M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z" />
                     </svg>
                     @if ($post->created_at->diffInDays(now()) < 7)
                    {{ $post->created_at->diffForHumans() }}
                     @else
                    {{ $post->created_at->format('d/m/Y') }}
                     @endif
                 </p>
             </div>
         </div>
         @if($post->image)
         <div class="flex justify-center mt-3">
             <img class="w-full max-w-xs h-52 object-cover rounded-lg" src="{{ asset('storage/' . $post->image) }}" alt="Image du post">
         </div>
         @endif
        <div class="flex flex-col items-start mt-3">
             <p class="text-gray-700 text-base mb-3 dark:text-gray-300">{{ $post->contenu }}</p>
             <div class="flex justify-start items-center gap-4 mt-3 w-full">
                 <div class="flex items-center gap-2">
                     <button onclick="addlikes({{ $post->id }})" class="{{ $post->user_has_liked ? 'bg-blue-600' : 'bg-gray-400' }} text-white px-3 py-1.5 rounded-lg hover:opacity-90 transition-colors dark:bg-blue-500 dark:hover:bg-blue-400">
                         👍 {{ $post->user_has_liked ? 'Aimé' : 'J\'aime' }}
                     </button>
                     <span id="like-count-{{ $post->id }}" class="text-sm font-medium">{{ $post->like_count ?? 0 }}</span>
                 </div>
                 <button class="text-blue-600 font-medium dark:text-blue-400" onclick="document.getElementById('comment-form-{{ $post->id }}').classList.toggle('hidden')">💬 comments {{ $post->comments->count() }}</button>
             </div>
             <div id="comment-form-{{ $post->id }}" class="hidden mt-3 w-full">
                 <input type="text" placeholder="Ajouter un commentaire..." class="bg-gray-100 text-gray-800 px-3 py-2 rounded-lg w-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                 <button onclick="addcomment(event)" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg mt-2 w-full hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400"
                         data-post-id="{{ $post->id }}">Publier</button>
             <div id="comments-list-{{ $post->id }}" class="comments-list mt-3">
                 @foreach ($post->comments as $comment)
                <div class="comment p-3 mb-3 border rounded-lg shadow-sm w-full dark:bg-gray-700 dark:text-gray-200">
                    <div class="flex items-start justify-start">
                        <img src="{{ asset('storage/' . ($comment->user->photo_profil ?? 'uploads/user.jpg')) }}" alt="User  Profile" class="rounded-full" width="40" height="40">

                        <div class="ml-3 w-full">
                            <div class="flex items-center">
                            <p class="m-0 font-bold inline-block">{{ $comment->user->nom ?? 'Unknown' }}</p>
                            <span class="text-gray-500 ml-2 text-sm dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span> <!-- Display Created At -->
                            </div>
                            <p>{{ $comment->contenu }}</p>
                         </div>
                     </div>
                 </div>

             @endforeach

             </div>
             </div>
         </div>
     </div>
 @endforeach
 </div>
    <script>
        function toggleFriendsModal() {
            const modal = document.getElementById('friendsModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</body>
</html>
