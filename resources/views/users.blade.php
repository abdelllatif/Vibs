<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vibe - Découvrez les membres</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold text-purple-600">Vibe</a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <form action="{{ route('users.index') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un utilisateur..."
                                   class="w-64 py-2 px-4 rounded-full bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-600">
                            <button type="submit" class="absolute right-3 top-3 text-gray-400">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <a href="#" class="text-gray-600 hover:text-purple-600">
                        <i class="fas fa-bell text-xl"></i>
                    </a>
                   <a href="{{ route('profile') }}"><img src="{{ asset('storage/' . ($user->photo_profil ?? 'uploads/user.jpg')) }}" alt="Profile" class="w-10 h-10 rounded-full"></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Découvrez les membres</h1>
            <div class="flex space-x-4">
                <form action="{{ route('users.index') }}" method="GET">
                    <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-purple-600 focus:border-purple-600">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Trier par date d'inscription</option>
                        <option value="nom" {{ request('sort') == 'nom' ? 'selected' : '' }}>Trier par nom</option>
                        <option value="pseudo" {{ request('sort') == 'pseudo' ? 'selected' : '' }}>Trier par pseudo</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Users Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($users as $user)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <img src="{{ asset('storage/' . ($user->photo_profil ?? 'uploads/user.jpg'))  }}" alt="User  Avatar" class="w-20 h-20 rounded-full">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg">{{ $user->nom . ' ' . $user->prenom }}</h3>
                            <p class="text-gray-600">@ {{ $user->pseudo }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                Membre depuis: {{ $user->created_at->format('F Y') }}
                            </p>
                        </div>
                        @if(auth()->user()->friends()->where('friend_id', $user->id)->exists())
                        <button class="bg-gray-400 text-white px-4 py-2 rounded-full cursor-default">
                            <i class="fas fa-user-check mr-1"></i> Ami
                        </button>
                        @else
                        <button onclick="addFriend({{ $user->id }})" class="bg-purple-600 text-white px-4 py-2 rounded-full hover:bg-purple-700">
                            <i class="fas fa-user-plus mr-1"></i> Suivre
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

<!-- Pagination -->
<div class="mt-8 flex justify-center">
    {{ $users->onEachSide(1)->links('pagination::tailwind') }}
</div>


 </div>
 <script>
function addFriend(userId) {
    fetch(`/friends/add/${userId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.error || 'Operation failed');
    });
}
   </script>

</body>
</html>
