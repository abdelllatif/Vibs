@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            {{ __("You're logged in!") }}
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gray-800 mt-8">
        <div class="max-w-6xl mx-auto px-4 py-16">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-4 text-white">Connectez-vous avec vos amis sur Vibe</h1>
                    <p class="text-gray-300 mb-8">Créez votre profil, trouvez vos amis et commencez à partager vos vibes dans un espace sécurisé et convivial.</p>
                    <div class="space-x-4">
                        <a href="#" class="bg-purple-600 text-white px-8 py-3 rounded-full hover:bg-purple-700">Commencer maintenant</a>
                        <a href="#" class="text-purple-400 hover:text-purple-300">En savoir plus</a>
                    </div>
                </div>
                <div>
                    <img src="https://www.jandsvision.com/cdn-cgi/image/width=1200,height=628,fit=crop,quality=80,format=auto,onerror=redirect,metadata=none/wp-content/uploads/2022/05/Lei-Header-4.jpeg" alt="Vibe Platform" class="rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </div>
@endsection
