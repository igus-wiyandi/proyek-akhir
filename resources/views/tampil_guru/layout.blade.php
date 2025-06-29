<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Nurul Ikhsan</title>
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        .navbar-shadow {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .hover-scale {
            transition: all 0.2s ease-in-out;
        }

        .hover-scale:hover {
            transform: translateX(4px);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .menu-item {
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .menu-item:hover::before {
            transform: scaleY(1);
        }

        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }

            .sidebar-mobile.show {
                transform: translateX(0);
            }
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
        }

        .dropdown-open .dropdown-arrow {
            transform: rotate(180deg);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Navbar -->
    <nav class="fixed w-full top-0 z-50 glass-effect navbar-shadow border-b border-gray-200">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 gradient-bg rounded-xl flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Welcome</p>
                    <h1 class="text-xl font-bold text-gray-800 capitalize">{{ Session::get('ambilUser')->nama }}</h1>
                </div>
            </div>
        </div>
    </nav>

    <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-white shadow-xl z-40 sidebar-transition sidebar-mobile">
        <div class="flex flex-col h-full">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-500 to-purple-600">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="md:hidden text-white hover:bg-white hover:bg-opacity-20 p-1 rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Sidebar Content -->
            <div class="flex-1 overflow-y-auto py-6">
                <!-- Guru Section -->
                <div class="px-6 mb-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Menu Guru</h3>

                    <!-- Rekap Absen -->
                    <a href="{{ route('guru.info') }}" class="menu-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-50 hover-scale mb-2 group">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-user-circle text-yellow-600 text-sm"></i>
                        </div>
                        <span class="font-medium">Profil</span>
                    </a>

                    <a href="#" class="menu-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-50 hover-scale mb-2 group">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-calendar-check text-blue-600 text-sm"></i>
                        </div>
                        <span class="font-medium">Rekap Absen</span>
                    </a>

                    <!-- Rekap Gaji -->
                    <a href="#" class="menu-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-50 hover-scale mb-2 group">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-money-bill-wave text-green-600 text-sm"></i>
                        </div>
                        <span class="font-medium">Rekap Gaji</span>
                    </a>

                    <!-- Mata Pelajaran -->
                    <div class="mb-2">
                        <button id="mapelToggleBtn" class="menu-item w-full flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-50 hover-scale group">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                                <i class="fas fa-book-open text-purple-600 text-sm"></i>
                            </div>
                            <span class="font-medium flex-1 text-left">Mata Pelajaran</span>
                            <i class="fas fa-chevron-down dropdown-arrow text-gray-400 text-sm"></i>
                        </button>

                        <!-- Dropdown Mata Pelajaran -->
                        <div id="mapelDropdown" class="hidden mt-2 ml-11 space-y-1">
                            <a href="{{ route('status10.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <i class="fas fa-users text-xs mr-2"></i>
                                Kelas 10
                            </a>
                            <a href="{{ route('status11.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <i class="fas fa-users text-xs mr-2"></i>
                                Kelas 11
                            </a>
                            <a href="{{ route('status12.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <i class="fas fa-users text-xs mr-2"></i>
                                Kelas 12
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Logout Section -->
                <div class="px-6 border-t border-gray-200 pt-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Akun</h3>

                    <a href="{{ route('logoutGuru') }}" class="menu-item flex items-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 hover-scale group">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-200 transition-colors">
                            <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                        </div>
                        <span class="font-medium">Log Out</span>
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="mainContent" class="transition-all duration-300 pt-20 md:ml-64">
        <div class="p-6">
            <!-- Content Area -->
            @yield('content')
        </div>
    </main>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const overlay = document.getElementById('overlay');

        // Desktop sidebar toggle
        sidebarToggleBtn?.addEventListener('click', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.toggle('-translate-x-full');
                if (sidebar.classList.contains('-translate-x-full')) {
                    mainContent.classList.remove('md:ml-64');
                    mainContent.classList.add('md:ml-0');
                } else {
                    mainContent.classList.add('md:ml-64');
                    mainContent.classList.remove('md:ml-0');
                }
            }
        });

        // Mobile menu toggle
        mobileMenuBtn?.addEventListener('click', function() {
            sidebar.classList.add('show');
            overlay.classList.remove('hidden');
        });

        // Close mobile menu
        sidebarCloseBtn?.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.add('hidden');
        });

        // Close mobile menu when clicking overlay
        overlay?.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.add('hidden');
        });

        // Dropdown mata pelajaran
        document.getElementById('mapelToggleBtn').addEventListener('click', function() {
            const dropdown = document.getElementById('mapelDropdown');
            const button = this;

            dropdown.classList.toggle('hidden');
            button.classList.toggle('dropdown-open');
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('show');
                overlay.classList.add('hidden');
                if (!sidebar.classList.contains('-translate-x-full')) {
                    mainContent.classList.add('md:ml-64');
                }
            }
        });

        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>
