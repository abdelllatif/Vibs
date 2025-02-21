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
                <a href="{{ route('logout') }}" class="text-gray-300 hover:text-white px-4">Déconnexion</a>
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
                                <img src="{{ asset('storage/' . ($friend->friend->photo_profil ?? 'uploads/user.jpg')) }}"
                                     alt="{{  $friend->friend->nom }}"
                                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-800 shadow-md">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-full flex items-center justify-center">
                                    <button onclick="removeFriend({{ $friend->id }})" class="text-white hover:text-red-500">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-3 text-center text-base font-semibold text-gray-800">
                                {{ $friend->friend->nom . " " . $friend->friend->prenom }}
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

        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openFriendsModal() {
            showFriends(); // Load friends when opening the modal
            document.getElementById('friendsModal').classList.remove('hidden');
        }

        function closeFriendsModal() {
            document.getElementById('friendsModal').classList.add('hidden');
        }

        function removeFriend(friendId) {
            // Implement the logic to remove a friend
            console.log(`Remove friend with ID: ${friendId}`);
        }
    </script>
</body>
</html>
