<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Accueil - Réseau Social</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openPopup() {
            document.getElementById('postPopup').classList.remove('hidden');
        }
        function closePopup() {
            document.getElementById('postPopup').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">

    <!-- Navbar -->
    <nav class="bg-white shadow-md p-4 flex justify-between items-center dark:bg-gray-800">
        <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">Réseau Social</h1>
        <input type="text" placeholder="Rechercher..." class="bg-gray-200 text-gray-800 px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
    </nav>

    <!-- Section pour créer un post -->
    <div class="max-w-2xl mx-auto mt-6 p-4">
        <div class="bg-white p-4 rounded-lg shadow-md dark:bg-gray-800 dark:text-gray-200">
            <!-- Profile Section -->
            <div class="relative flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full overflow-hidden">
                    <img src="{{ asset('storage/' . ($user->photo_profil ?? 'uploads/user.jpg')) }}" alt="Profile Image" class="w-full h-full object-cover">
                </div>
                <div class="text-black font-bold text-xl dark:text-white">
                    <p>{{ $user->nom . ' ' . $user->prenom }}</p>
                </div>
            </div>

            <!-- Post Textarea -->
            <div class="bg-white p-4 mt-4 rounded-lg shadow-md dark:bg-gray-800 dark:text-gray-200">
                <textarea onclick="openPopup()" placeholder="Quoi de neuf ?" class="w-full bg-gray-100 text-gray-800 p-2 rounded resize-none border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"></textarea>
            </div>
        </div>
    </div>

<!-- Pop-up pour créer un post -->
<div id="postPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white w-96 p-6 rounded-lg shadow-lg relative dark:bg-gray-800 dark:text-gray-200">
        <button onclick="closePopup()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl dark:text-gray-300">✖</button>
        <h2 class="text-xl font-bold mb-3 text-center dark:text-white">Créer un post</h2>
        <form id="postForm">
            <textarea id="postContent" placeholder="Exprimez-vous..." class="w-full h-20 p-2 border rounded-lg resize-none overflow-auto focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"></textarea>
            <label class="mt-3 flex items-center justify-center cursor-pointer border border-gray-300 rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700">
                📷 Ajouter une image
                <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(event)">
            </label>
            <div id="imagePreview" class="mt-3 hidden">
                <img id="previewImg" class="w-24 h-24 object-cover rounded-lg mx-auto">
            </div>
            <button type="button" onclick="post()" class="bg-blue-600 text-white w-full mt-4 py-2 rounded-lg shadow-md hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400">Publier</button>
        </form>
    </div>
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
        function openPopup() {
            document.getElementById('postPopup').classList.remove('hidden');
        }

        function closePopup() {
            document.getElementById('postPopup').classList.add('hidden');
        }

        function post() {
        const btn = event.target;
        btn.disabled = true;
        const formData = new FormData();
        formData.append('postContent', document.getElementById('postContent').value);
        const imageInput = document.getElementById('imageInput');
        if (imageInput.files.length > 0) {
            formData.append('imageInput', imageInput.files[0]);
        }
        formData.append('_token', '{{ csrf_token() }}');
        fetch("{{ route('createPost') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closePopup();
                location.reload();
            } else {
                if (data.errors) {
                    alert("Erreur lors de la création du post: " + JSON.stringify(data.errors));
                } else {
                    alert("Erreur lors de la création du post");
                }
                btn.disabled = false;
            }
        })
        .catch(() => {
            alert("Une erreur s'est produite");
            btn.disabled = false;
        });
    }

    function addlikes(postId) {
    // Store the button element
    const button = event.target;

    // Get the CSRF token from the meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        alert("Erreur: Token CSRF manquant");
        return;
    }
    const formData = new FormData();
    formData.append('_token', csrfToken);

    fetch(`/posts/${postId}/reactions`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeCountElement = document.getElementById(`like-count-${postId}`);
            if (likeCountElement) {
                likeCountElement.textContent = data.like_count;
            }
            const dislikeCountElement = document.getElementById(`dislike-count-${postId}`);
            if (dislikeCountElement) {
                dislikeCountElement.textContent = data.dislike_count;
            }
            const isCurrentlyLiked = button.classList.contains('liked');
            if (isCurrentlyLiked) {
                button.classList.remove('liked');
                button.classList.remove('bg-blue-700');
                button.classList.add('bg-blue-600');
                button.innerHTML = '👍 J\'aime';
            } else {
                button.classList.add('liked');
                button.classList.remove('bg-blue-600');
                button.classList.add('bg-blue-700');
                button.innerHTML = '👍 J\'ai aimé';
            }
        } else {
            alert(data.message || "Une erreur s'est produite lors de l'ajout du like.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Une erreur s'est produite lors de la communication avec le serveur.");
    });
}
function addcomment(event) {
    if (event.target.matches('button[data-post-id]')) {
        const postId = event.target.dataset.postId;
        const commentInput = event.target.previousElementSibling;
        const comment = commentInput.value.trim();

        if (!comment) {
            alert('Le commentaire ne peut pas être vide!');
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/posts/${postId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ comment })
        })
        .then(response => {
            // Log the response for debugging
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            // Log the data for debugging
            console.log('Response data:', data);
            if (data.success) {
                commentInput.value = '';
                displayComment(postId, comment);
            } else {
                alert('Erreur: ' + data.message);
            }
        })

    }
}
    </script>
</body>
</html>
