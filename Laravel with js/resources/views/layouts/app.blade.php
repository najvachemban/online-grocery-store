<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Grocery Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Tailwind CSS CDN (Optional but good for styling quickly) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS (latest CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <!-- Alpine.js for sidebar toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function addToCart(itemDescription) {
            // This function can be expanded to actually add the item to a cart
            // For now, it just shows an alert
        alert("Added to cart : " + itemDescription);
        }
    </script>

</head>
<body class="bg-gray-100">
    <style>
        .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    </style>


    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
            <!-- Hamburger -->
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Site Logo/Name -->
            <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-700">
                Online Grocery Store
            </a>

            <!-- Auth Links -->
            
            <div class="space-x-4">
                     <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a>
                @auth
                    
                    <a href="{{ route('user.cart') }}" class="text-blue-600 hover:underline">Go to Cart 🛒</a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-none p-0">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign Up</a>
                @endauth
            </div>

        </header>

        <!-- Sidebar -->
        <div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="md:hidden bg-white shadow-md p-4">
            <ul>
                <li><a href="#" class="text-gray-700 hover:text-blue-600">Contact Us</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <main class="flex-grow p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
