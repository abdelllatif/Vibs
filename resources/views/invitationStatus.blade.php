<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitations</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-5 px-4">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <div id="invitationTabsContent">
                    <div class="flex items-center justify-center mb-5">
                        <button onclick="afficher('sent')" class="bg-gray-400 hover:bg-green-600 text-white py-2 px-10 mr-4 rounded-xl transition duration-300">Invitations envoyées</button>
                        <button onclick="afficher('received')" class="bg-green-500 hover:bg-green-600 text-white py-2 px-10 ml-4 rounded-xl transition duration-300">Invitations reçues</button>
                    </div>

                    <!-- Received Invitations Tab -->
                    <div id="received" class="hidden">
                        @if(count($receivedRequests) > 0)
                        <div class="list-group">
                            @foreach($receivedRequests as $request)
                                <div class="list-group-item d-flex align-items-center p-3">
                                    <!-- Profile Image -->
                                    <div class="flex-shrink-0">
                                        @if(isset($request->profile_image))
                                            <img src="{{ asset('storage/' . $request->profile_image) }}" alt="Profile image" class="rounded-circle" width="60" height="60">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <span class="text-white fs-4">{{ substr($request->nom, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- User Info -->
                                    <div class="ms-3 flex-grow-1">
                                        <h5 class="mb-1">{{ $request->nom }} {{ $request->prenom }}</h5>
                                        <p class="text-muted mb-0">@if(isset($request->pseudo)){{ '@' . $request->pseudo }}@endif</p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="ms-auto">
                                        @if($request->pivot->status == 'pending')
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('invitations.accept', $request->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary bg-green-500">
                                                        <i class="fas fa-check me-1"></i> Accept
                                                    </button>
                                                </form>
                                                <form action="{{ route('invitations.reject', $request->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger bg-red-500">
                                                        <i class="fas fa-times me-1"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($request->pivot->status == 'accepted')
                                            <span class="badge bg-success py-2 px-3">
                                                <i class="fas fa-check-circle me-1 bg-green-500"></i> Accepted
                                            </span>
                                        @elseif($request->pivot->status == 'rejected')
                                            <span class="badge bg-danger py-2 px-3">
                                                <i class="fas fa-times-circle me-1 bg-red-500"></i> Rejected
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-user-friends fa-3x text-muted"></i>
                            </div>
                            <h4>No invitations received</h4>
                            <p class="text-muted">You don't have any pending friend requests at the moment</p>
                        </div>
                    @endif
                    </div>

                    <!-- Sent Invitations Tab -->
                    <div id="sent" class="hidden">
                        @if(count($sentRequests) > 0)
                        <div class="list-group w-full">
                            @foreach($sentRequests as $request)
                                <div class="list-group-item flex justify-around items-center p-3 w-38">
                                    <!-- Profile Image -->
                                    <div class="flex items-center">
                                    <div class="flex-shrink-0 mr-14 ">
                                        @if(isset($request->photo_profil))
                                            <img src="{{ asset('storage/' . $request->photo_profil) }}" alt="Profile image" class="rounded-full" width="60" height="60">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <span class="text-white fs-4">{{ substr($request->nom, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- User Info -->
                                    <div class="ms-3 flex-grow-1">
                                        <h5 class="mb-1">{{ $request->nom }} {{ $request->prenom }}</h5>
                                    </div>
                                </div>
                                    <!-- Status Badge -->
                                    <div class="ms-auto">
                                        @if($request->pivot->status == 'pending')
                                            <span class="badge bg-warning text-dark bg-yellow-500 py-2 px-3 rounded-xl">
                                                <i class="fas fa-clock me-1"></i> Pending
                                            </span>
                                        @elseif($request->pivot->status == 'accepted')
                                            <span class="badge bg-success py-2 px-3 bg-green-500">
                                                <i class="fas fa-check-circle me-1"></i> Accepted
                                            </span>
                                        @elseif($request->pivot->status == 'rejected')
                                            <span class="badge bg-danger py-2 px-3 bg-red-500">
                                                <i class="fas fa-times-circle me-1"></i> Rejected
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-paper-plane fa-3x text-muted"></i>
                            </div>
                            <h4>No invitations sent</h4>
                            <p class="text-muted">You haven't sent any friend requests yet</p>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function afficher(type) {
            // Hide all tabs
            document.getElementById('sent').classList.add('hidden');
            document.getElementById('received').classList.add('hidden');

            // Show the selected tab
            document.getElementById(type).classList.remove('hidden');
        }

        // Set default tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            afficher('sent');
        });
    </script>
</body>
</html>
