<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vibe - Connectez-vous avec vos amis</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100">
    <!-- Navigation -->
    <nav class="bg-gray-800 shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-purple-400">Vibe</a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="text-gray-300 text-xl hover:text-purple-400">Accueil</a>
                    <a href="{{ url('/users') }}" class="text-gray-300 text-xl hover:text-purple-400">Explorer</a>
                    <div class="relative">
                        <input type="text" placeholder="Rechercher un utilisateur..."
                            class="py-2 px-4 rounded-full bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 w-60 focus:ring-purple-400">
                        <i class="fas fa-search absolute right-4 top-3 text-gray-400"></i>
                    </div>

                    @auth
                        <div class="flex items-center space-x-4">
                            <img src="{{ Auth::user()->profile_photo_url ?? asset('storage/user.jpg') }}" alt="Profile" class="w-8 h-8 rounded-full">
                            <a href="{{ route('profile') }}" class="text-gray-300 hover:text-purple-400">Mon Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-400 hover:text-red-300">Déconnexion</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-purple-600 text-white px-6 py-2 rounded-full hover:bg-purple-700">Connexion</a>
                        <a href="{{ route('register') }}" class="border border-purple-400 text-purple-400 px-6 py-2 rounded-full hover:bg-purple-600 hover:text-white">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center">
                <div class="text-gray-400">© 2025 Vibe. Tous droits réservés.</div>
                <div class="space-x-4">
                    <a href="#" class="text-gray-400 hover:text-purple-400">À propos</a>
                    <a href="#" class="text-gray-400 hover:text-purple-400">Confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-purple-400">Conditions</a>
                    <a href="#" class="text-gray-400 hover:text-purple-400">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
