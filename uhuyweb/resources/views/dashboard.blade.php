<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UhuY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 rounded-full">
                        <i class="fas fa-images text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">UhuY</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <button onclick="navigateTo('explore')" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        <i class="fas fa-compass mr-2"></i>Explore
                    </button>
                    <button onclick="navigateTo('my-pins')" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        <i class="fas fa-bookmark mr-2"></i>My Pins
                    </button>
                    <button onclick="navigateTo('profile')" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        <i class="fas fa-user mr-2"></i>Profile
                    </button>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium hidden sm:inline" id="username"></span>
                    <button id="logoutBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Pins</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2" id="totalPins">0</p>
                    </div>
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                        <i class="fas fa-image text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Followers</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2" id="totalFollowers">0</p>
                    </div>
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Following</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2" id="totalFollowing">0</p>
                    </div>
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                        <i class="fas fa-user-check text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Pin Section -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Pin</h2>
            <form id="createPinForm" class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Pin Title</label>
                    <input type="text" id="title" required placeholder="Enter pin title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" rows="3" placeholder="Enter pin description"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                    <input type="url" id="image_url" required placeholder="Enter image URL"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i> Create Pin
                </button>
            </form>
        </div>

        <!-- Your Pins Section -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Pins</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="pinsContainer">
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-image text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">No pins yet. Create your first pin!</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));

        if (!token || !user) {
            window.location.href = '/login';
        } else {
            document.getElementById('username').textContent = user.name;
        }

        // Logout handler
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            try {
                const response = await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    
                    await Swal.fire({
                        icon: 'success',
                        title: 'Logged Out!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        didClose: () => {
                            window.location.href = '/login';
                        }
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to logout',
                });
            }
        });

        // Load user pins
        async function loadUserPins() {
            try {
                const response = await fetch('/api/pins', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                displayPins(data.data || []);
            } catch (error) {
                console.error('Error loading pins:', error);
            }
        }

        function displayPins(pins) {
            const container = document.getElementById('pinsContainer');
            if (pins.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-12"><i class="fas fa-image text-gray-400 text-4xl mb-4"></i><p class="text-gray-500">No pins yet. Create your first pin!</p></div>';
                return;
            }

            container.innerHTML = pins.map(pin => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="${pin.image_url}" alt="${pin.title}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 truncate">${pin.title}</h3>
                        <p class="text-gray-600 text-sm line-clamp-2 mt-1">${pin.description || 'No description'}</p>
                    </div>
                </div>
            `).join('');
        }

        // Create pin handler
        document.getElementById('createPinForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                image_url: document.getElementById('image_url').value,
            };

            try {
                const response = await fetch('/api/pins', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const data = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Pin created successfully',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                    document.getElementById('createPinForm').reset();
                    loadUserPins();
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to create pin', 'error');
            }
        });

        function navigateTo(page) {
            console.log('Navigate to:', page);
        }

        // Initial load
        loadUserPins();
    </script>
</body>
</html>
