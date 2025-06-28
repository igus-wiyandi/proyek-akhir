<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Nurul Ikhsan - Admin Dashboard</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(20, 184, 166, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }

        .sidebar-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #14b8a6;
            border-radius: 10px;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Enhanced Sidebar -->
        <div id="sidebar" class="sidebar-transition w-72 bg-white sidebar-shadow flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ Session::get('ambilUser')->nama}}</h1>
                        <p class="text-sm text-gray-500">Admin Dashboard</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Content -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-4">
                <!-- Dashboard Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mr-3"></div>
                        <p class="uppercase text-xs font-semibold text-gray-400 tracking-wider">Dashboard</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="#" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                            <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <i class="fad fa-chart-pie text-blue-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </nav>
                </div>

                <!-- Admin Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-teal-500 to-blue-500 rounded-full mr-3"></div>
                        <p class="uppercase text-xs font-semibold text-gray-400 tracking-wider">Administration</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('admin.index') }}" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                            <div class="w-10 h-10 bg-emerald-100 group-hover:bg-emerald-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <i class="fad fa-user-shield text-emerald-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Admin</span>
                        </a>

                        <a href="{{ route('guru.index') }}" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                            <div class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <i class="fad fa-users text-green-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Data Guru</span>
                        </a>
                    </nav>
                </div>

                <!-- Attendance Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-3"></div>
                        <p class="uppercase text-xs font-semibold text-gray-400 tracking-wider">Attendance</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('absensi.index') }}" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                            <div class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <i class="fad fa-clock text-purple-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Absensi</span>
                        </a>

                        <!-- Mata Pelajaran Dropdown -->
                        <div class="nav-link">
                            <button id="mapelToggleBtn" class="group w-full flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                                <div class="w-10 h-10 bg-orange-100 group-hover:bg-orange-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                    <i class="fad fa-book text-orange-600 text-sm"></i>
                                </div>
                                <span class="font-medium flex-1 text-left">Mata Pelajaran</span>
                                <i class="fad fa-chevron-down text-xs transition-transform duration-200" id="mapelChevron"></i>
                            </button>
                            <div id="mapelDropdown" class="ml-14 mt-2 space-y-1 hidden">
                                <a href="{{ route('mapel.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                                    Kelas 10
                                </a>
                                <a href="{{ route('mapel11.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                                    Kelas 11
                                </a>
                                <a href="{{ route('mapel12.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                                    Kelas 12
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Reports Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-red-500 to-orange-500 rounded-full mr-3"></div>
                        <p class="uppercase text-xs font-semibold text-gray-400 tracking-wider">Reports</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('kategori.index') }}" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                            <div class="w-10 h-10 bg-red-100 group-hover:bg-red-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <i class="fad fa-tasks text-red-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Tugas Tambahan</span>
                        </a>

                        <a href="{{ route('jabatan.index') }}" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                            <div class="w-10 h-10 bg-indigo-100 group-hover:bg-indigo-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <i class="fad fa-user-tie text-indigo-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Jabatan</span>
                        </a>

                        <a href="{{ route('perhitungan_gaji.index') }}" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                            <div class="w-10 h-10 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <i class="fad fa-calculator text-yellow-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Perhitungan</span>
                        </a>

                        <!-- Laporan Dropdown -->
                        <div class="nav-link">
                            <button id="laporanToggleBtn" class="group w-full flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                                <div class="w-10 h-10 bg-cyan-100 group-hover:bg-cyan-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                    <i class="fad fa-chart-bar text-cyan-600 text-sm"></i>
                                </div>
                                <span class="font-medium flex-1 text-left">Laporan</span>
                                <i class="fad fa-chevron-down text-xs transition-transform duration-200" id="laporanChevron"></i>
                            </button>
                            <div id="laporanDropdown" class="ml-14 mt-2 space-y-1 hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                                    Laporan Absen
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                                    Laporan Gaji
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Download Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-green-500 to-teal-500 rounded-full mr-3"></div>
                        <p class="uppercase text-xs font-semibold text-gray-400 tracking-wider">Download</p>
                    </div>

                    <a href="#" class="nav-link group flex items-center px-4 py-3 text-gray-700 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-300">
                        <div class="w-10 h-10 bg-teal-100 group-hover:bg-teal-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                            <i class="fad fa-download text-teal-600 text-sm"></i>
                        </div>
                        <span class="font-medium">Download Report</span>
                    </a>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-200">
                <a href="{{ route('loginAdmin') }}" class="group flex items-center px-4 py-3 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-300">
                    <div class="w-10 h-10 bg-red-100 group-hover:bg-red-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                        <i class="fad fa-sign-out-alt text-red-600 text-sm"></i>
                    </div>
                    <span class="font-medium">Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Enhanced Top Navigation -->
            <header class="glass-effect border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <!-- Mobile Menu Button -->
                    <div class="flex items-center">
                        <button id="sliderBtn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fad fa-bars text-gray-600"></i>
                        </button>
                    </div>

                    <!-- Empty space for future content -->
                    <div class="flex-1"></div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Content will be yielded here -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('sliderBtn').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });

        // Mata Pelajaran dropdown
        document.getElementById('mapelToggleBtn').addEventListener('click', function () {
            const dropdown = document.getElementById('mapelDropdown');
            const chevron = document.getElementById('mapelChevron');

            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        });

        // Laporan dropdown
        document.getElementById('laporanToggleBtn').addEventListener('click', function () {
            const dropdown = document.getElementById('laporanDropdown');
            const chevron = document.getElementById('laporanChevron');

            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        });
    </script>
     @stack('scripts')
</body>

</html>
