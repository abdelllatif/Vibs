<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vibe - Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</head>
<body class="bg-gray-900 text-white">
    <!-- Navbar -->
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Vibe</h1>
            <div>
                <a href="/" class="text-gray-300 hover:text-white px-4">Accueil</a>
                <a href="{{ route('profile') }}" class="text-gray-300 hover:text-white px-4">Profil</a>
                <a href="{{ route('users.index') }}" class="text-gray-300 hover:text-white px-4">users</a>
                <a href="{{ route('messages') }}" class="text-gray-300 hover:text-white px-4">messages</a>
                <a href="{{ route('posts.affichage') }}" class="text-gray-300 hover:text-white px-4">posts</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-gray-300 hover:text-white px-4 bg-transparent border-none cursor-pointer">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Profil principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Bannière -->
            <div class="h-48 bg-gradient-to-r from-blue-600 to-purple-600 relative">
                <button onclick="document.getElementById('bannerUpload').click()"
                        class="absolute bottom-4 right-4 bg-black bg-opacity-50 p-2 rounded-full hover:bg-opacity-70">
                    <i class="fas fa-camera text-white"></i>
                </button>
                <input type="file" id="bannerUpload" class="hidden" accept="image/*">
            </div>
            @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if (session('status'))
            <div class="bg-green-500 text-white p-4 rounded">
                {{ session('status') }}
            </div>
            @endif
            <!-- Info profil -->
            <div class="relative px-6 pb-6">
                <div class="absolute -top-16 group">
                    <img src="{{ asset('storage/' . ($user->photo_profil ?? 'uploads/user.jpg')) }}" alt="Photo de profil" class="w-32 h-32 rounded-full border-4 border-gray-800 shadow-lg">
                    <button onclick="document.getElementById('profileUpload').click()"
                            class="absolute bottom-0 right-0 bg-blue-600 p-2 rounded-full hover:bg-blue-700 transition-all">
                        <i class="fas fa-camera text-white"></i>
                    </button>
                    <form action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
                        @csrf
                        <input type="file" id="profileUpload" class="hidden" accept="image/*" name="profile_image" onchange="document.getElementById('imageUploadForm').submit();">
                    </form>
                </div>

                <!-- Actions -->
                <div class="flex justify-end mt-4">
                    <button onclick="openEditModal()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        Modifier le profil
                    </button>
                    <a href="{{ route('invitations.index') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Inviter un ami
                    </a>
                </div>

                <!-- Informations -->
                <div class="mt-16">
                    <h1 class="text-2xl font-bold">{{ $user->nom . " " . $user->prenom }}</h1>
                    <p class="text-gray-400">@ {{$user->pseudo}}</p>
                    <p class="mt-2 text-gray-300">{{ $user->bio ?? 'no bio yet' }}</p>
                </div>

                <!-- Statistiques -->
                <div class="flex gap-6 mt-4">
                    <div class="text-center cursor-pointer" onclick="openFriendsModal()">
                        <span class="font-bold text-lg">{{ $friends->count() }}</span>
                        <p class="text-gray-400">Amis</p>
                    </div>
                </div>

                <!-- Friends Section -->
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Amis</h2>
                        <button onclick="openFriendsModal()" class="text-blue-500 hover:text-blue-400">Voir tous</button>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($friends->take(6) as $friend)
                        <div class="friend-card bg-white rounded-lg shadow-md p-4 flex flex-col items-center border border-gray-200" data-friend-id="{{ $friend->id }}">
                            <div class="relative group">
                                <img src="{{ asset('storage/' . ($friend->photo_profil ?? 'uploads/user.jpg')) }}"
                                     alt="{{  $friend->nom }}"
                                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-800 shadow-md">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-full flex items-center justify-center">
                                    <button onclick="removeFriend({{ $friend->id }})" class="text-white hover:text-red-500">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-3 text-center text-base font-semibold text-gray-800">
                                {{ $friend->nom . " " . $friend->prenom }}
                            </p>
                            <button onclick="messageFriend({{ $friend->id }})" class="mt-2 px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition">
                                Message
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

        </div>
    </div>

    <!-- Modal de modification -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center">
        <div class="bg-gray-800 rounded-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Modifier le profil</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="space-y-6" action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="space-y-6 bg-gray-700 p-6 rounded-lg">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                        <input type="text" value="{{ $user->pseudo }}" placeholder="Pseudo"
                        class="pl-10 block w-full rounded-lg bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" name="pseudo">
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" value="{{ $user->nom }}" placeholder="Nom"
                        class="pl-10 block w-full rounded-lg bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" name="nom">
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" value="{{ $user->prenom }}" placeholder="Prénom"
                        class="pl-10 block w-full rounded-lg bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" name="prenom">
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" value="{{ $user->email }}" placeholder="Email"
                               class="pl-10 block w-full rounded-lg bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" name="email">
                    </div>

                    <div class="relative">
                        <div class="absolute top-3 left-3">
                            <i class="fas fa-comment text-gray-400"></i>
                        </div>
                        <textarea placeholder="Bio" rows="4"
                        class="pl-10 block w-full rounded-lg bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" name="bio">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeEditModal()"
                            class="px-6 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for All Friends -->
    <div id="friendsModal" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Tous les amis</h2>
                <button onclick="closeFriendsModal()" class="text-gray-400 hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="friendsContainer" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4">

            </div>
        </div>
    </div>
    @if (session('success'))
<div class="bg-green-500 text-white p-4 rounded">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="bg-red-500 text-white p-4 rounded">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <div class="max-w-2xl mx-auto mt-6">
        @if($posts->isEmpty())
        <div class="bg-yellow-500 text-white p-4  rounded">
            Aucune publication trouvée.
        </div>
    @else
        @foreach($posts as $post)
        <div class="bg-white p-6 rounded-lg shadow-md mb-4 text-left max-w-md mx-auto dark:bg-gray-800 dark:text-gray-200"> <!-- Changed text-center to text-left -->
            <div class="flex items-center justify-between ">
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
            <div class="relative">
                <button onclick="document.getElementById('menu-{{ $post->id }}').classList.toggle('hidden')"
                    class="p-4 text-white hover:text-gray-400">
                    ⋮
                </button>
                <div id="menu-{{ $post->id }}"
                    class="absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-lg hidden z-10 p-2">
                    <!-- Close button -->
                    <button onclick="document.getElementById('menu-{{ $post->id }}').classList.add('hidden')"
                        class="text-gray-500 text-sm font-bold float-right">
                        ✖
                    </button>

                    <button onclick="openEditPostModal({{ $post->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        ✏️ Modifier
                    </button>
                    <form action="{{ route('post.delete', ['postId' => $post->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                            🗑️ Supprimer
                        </button>
                    </form>
                </div>
            </div>


        </div>

            @if($post->image)
            <div class="flex justify-center mt-3">
                <img  id="post-image-{{ $post->id }}" class="w-full max-w-xs h-52 object-cover rounded-lg" src="{{ asset('storage/' . $post->image) }}" alt="Image du post">
            </div>
            @endif
           <div class="flex flex-col items-start mt-3">
                <p id="post-content-{{ $post->id }}" class="text-gray-700 text-base mb-3 dark:text-gray-300">{{ $post->contenu }}</p>
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
                    <button onclick="addcomment(event)" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg mt-2 w-full hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400"  data-post-id="{{ $post->id }}">Publier</button>
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

<!-- Modal for editing post -->
<div id="editPostModal" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-50">
    <div class="bg-white w-96 p-6 rounded-lg shadow-lg relative dark:bg-gray-800 dark:text-gray-200">
        <button onclick="closeEditPostModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl dark:text-gray-300">✖</button>
        <h2 class="text-xl font-bold mb-3 text-center dark:text-white">Modifier le post</h2>
        <form id="editPostForm" method="POST" action="{{ route('post.edit', ['postId' => $post->id]) }}" enctype="multipart/form-data">
            @csrf
            <textarea id="editPostContent" name="contenu" placeholder="Exprimez-vous..." class="w-full h-20 p-2 border rounded-lg resize-none overflow-auto focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"></textarea>
            <div id="currentImagePreview" class="mt-3"></div>

            <div class="flex mt-3 space-x-2">
                <label class="flex-1 flex items-center justify-center cursor-pointer border border-gray-300 rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700">
                    📷 Changer l'image
                    <input type="file" id="editImageInput" name="image" accept="image/*" class="hidden" onchange="previewEditImage(event)">
                </label>
            </div>
            <input type="hidden" id="deleteImageFlag" name="delete_image" value="0">
            <button type="submit" class="bg-blue-600 text-white w-full mt-4 py-2 rounded-lg shadow-md hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400">Enregistrer</button>
        </form>
    </div>
</div>
</div>
@endif

    </div>
    <script>
        function showFriends() {
            fetch("{{ route('friends.index') }}")
            .then(response => response.json())
            .then(data => {
                let container = document.getElementById('friendsContainer');
                container.innerHTML = '';
                data.forEach(friend => {
                    container.innerHTML += `
                       <div class="friend-card flex flex-col items-center justify-center p-4 bg-white shadow-md rounded-lg transition-transform duration-300 hover:scale-105">
                        <img src="/storage/${friend.friend.photo_profil || 'uploads/user.jpg'}" class="w-20 h-20 rounded-full border-4 border-gray-200 mb-2">
                        <p class="text-lg font-semibold text-gray-800 mb-2">${friend.friend.nom}</p>
                        <button onclick="removeFriend(${friend.friend.id})"
                                class="text-red-500 hover:text-red-700 transition-colors duration-300">
                            <i class="fas fa-user-minus text-xl"></i>
                        </button>
                    </div>
                    `;
                });
                document.getElementById('friendsModal').classList.remove('hidden');
            });
        }
function openEditPostModal(postId) {
    const modal = document.getElementById('editPostModal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    const postContent = document.getElementById(`post-content-${postId}`).innerText;
    const postImage = document.getElementById(`post-image-${postId}`).src;
    document.getElementById('editPostContent').value = postContent;
    document.getElementById('currentImagePreview').innerHTML = `
        <img src="${postImage}" alt="Current Image" class="w-full max-w-xs h-52 object-cover rounded-lg mt-2">
    `;
    const form = document.getElementById('editPostForm');
    // Set the action to include /edit
    form.action = `/posts/${postId}/edit`;
}
function closeEditPostModal() {
    const modal = document.getElementById('editPostModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}
function previewEditImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        const imagePreview = document.getElementById('currentImagePreview');
        imagePreview.innerHTML = `<img src="${e.target.result}" alt="New Image" class="w-full max-w-xs h-52 object-cover rounded-lg mt-2">`;
    };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
