<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Événements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkDeep: '#f0f7ff',
                        glassBg: 'rgba(255, 255, 255, 0.85)',
                        glassBorder: 'rgba(59, 130, 246, 0.08)',
                        accentIndigo: '#4f46e5',
                        accentViolet: '#764ba2',
                        cardDark: 'rgba(255, 255, 255, 0.9)',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/styles.css') }}">
    <style>
        body { font-family: 'Outfit', sans-serif; }

        /* Sidebar responsive behavior */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }

        .chart-container {
            height: 250px;
        }

        /* Mobile sidebar hidden by default */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }
        }

        /* Desktop sidebar behavior */
        @media (min-width: 1025px) {
            .sidebar {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none !important;
            }

            .mobile-menu-btn {
                display: none !important;
            }
        }

        /* Table responsive scrolling */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Custom scrollbar */
        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .table-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        /* Glassmorphism custom classes */
        /* Glassmorphism custom classes for Light Theme */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.04);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        .text-xxs {
            font-size: 0.65rem;
            line-height: 1rem;
        }
        /* Sidebar scrollbar */
        .scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 2px;
        }
        /* Active nav item glow */
        .nav-active {
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.15);
        }

        /* Override gradients to solid colors for cleaner design (no excessive gradients/blobs) */
        .bg-gradient-to-r.from-accentIndigo.to-accentViolet,
        .bg-gradient-to-tr.from-accentIndigo.to-accentViolet {
            background-image: none !important;
            background-color: #4f46e5 !important;
        }

        .bg-gradient-to-r.from-accentIndigo\/20.to-accentViolet\/20 {
            background-image: none !important;
            background-color: rgba(79, 70, 229, 0.15) !important;
        }

        .bg-gradient-to-r.from-emerald-500.to-teal-600 {
            background-image: none !important;
            background-color: #10b981 !important;
        }

        .bg-gradient-to-br.from-indigo-500\/10.to-purple-500\/10 {
            background-image: none !important;
            background-color: rgba(79, 70, 229, 0.08) !important;
        }

        /* Disable text gradient overlays */
        .bg-gradient-to-r.from-white.via-gray-100.to-gray-300,
        .bg-gradient-to-r.from-white.via-gray-200.to-gray-400,
        .bg-gradient-to-r.from-white.to-gray-300 {
            background-image: none !important;
            -webkit-background-clip: initial !important;
            -webkit-text-fill-color: initial !important;
            color: #0f172a !important;
        }

        /* Light theme overrides for admin dashboard templates */
        .text-white:not(.btn):not(.btn *):not(.badge):not(.badge *):not(.keep-white) {
            color: #0f172a !important;
        }
        .text-gray-200, .text-gray-300, .text-gray-400 {
            color: #334155 !important;
        }
        .text-gray-100 {
            color: #0f172a !important;
        }
        .text-gray-500 {
            color: #475569 !important;
        }
        .text-indigo-300, .text-indigo-400, .text-indigo-500 {
            color: #4f46e5 !important;
        }
        .text-emerald-400 {
            color: #16a34a !important;
        }
        
        .bg-white\/5 {
            background-color: rgba(59, 130, 246, 0.02) !important;
        }
        .bg-white\/10 {
            background-color: rgba(59, 130, 246, 0.05) !important;
        }
        
        .border-white\/5 {
            border-color: rgba(59, 130, 246, 0.04) !important;
        }
        .border-white\/10 {
            border-color: rgba(59, 130, 246, 0.08) !important;
        }
        
        /* Sidebar active styling corrections & dark theme text color restorations */
        .sidebar a {
            color: rgba(255, 255, 255, 0.65) !important;
        }
        .sidebar a:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
        .sidebar a.text-white {
            color: #ffffff !important;
        }
        
        .sidebar .text-white {
            color: #ffffff !important;
        }
        .sidebar .text-gray-200 {
            color: #e2e8f0 !important;
        }
        .sidebar .text-gray-300 {
            color: #cbd5e1 !important;
        }
        .sidebar .text-gray-400 {
            color: #94a3b8 !important;
        }
        .sidebar .text-gray-500 {
            color: #64748b !important;
        }
        .sidebar .border-white\/5 {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        .sidebar .bg-white\/5 {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        .sidebar .bg-white\/10 {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Stats card icons */
        .p-2.bg-indigo-500\/10 {
            background-color: rgba(79, 70, 229, 0.08) !important;
            color: #4f46e5 !important;
        }
        
        select option {
            background-color: #ffffff !important;
            color: #1f2937 !important;
        }
    </style>
</head>
<body class="bg-darkDeep text-gray-200 font-sans min-h-screen relative overflow-x-hidden">
    <!-- Glow Spots -->
    <div class="fixed top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-blue-500/5 blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-emerald-500/5 blur-[120px] pointer-events-none z-0"></div>

    <div class="relative z-10">
      @include('organisateur.include.navbar')
        <!-- Main Content -->
        <div class="main-content flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-glassBg/80 backdrop-blur-md border-b border-glassBorder py-4 px-4 sm:px-6 flex justify-between items-center sticky top-0 z-30">
                <div class="flex items-center space-x-4">
                    <!-- Mobile menu button -->
                    <button id="openSidebar" class="mobile-menu-btn lg:hidden p-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-colors">
                        <i data-feather="menu" class="w-6 h-6 text-gray-300"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-white via-gray-200 to-gray-400 bg-clip-text text-transparent">Tableau de bord</h1>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Search - Hidden on very small screens -->
                    <div class="relative hidden sm:block">
                        <i data-feather="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo focus:border-transparent w-48 lg:w-64 transition-all duration-300">
                    </div>

                    <!-- Search icon for mobile -->
                    <button class="sm:hidden p-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-colors">
                        <i data-feather="search" class="w-5 h-5 text-gray-300"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-colors">
                            <i data-feather="bell" class="w-5 h-5 sm:w-6 sm:h-6 text-gray-300"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse">3</span>
                        </button>
                    </div>
                </div>
            </header>

         @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize Feather Icons
        feather.replace();

        // Mobile Menu Functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

        function toggleSidebar(show) {
            if (show) {
                sidebar.classList.add('open');
                sidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
                sidebarOverlay.classList.add('opacity-100', 'pointer-events-auto');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
                sidebarOverlay.classList.remove('opacity-100', 'pointer-events-auto');
                document.body.style.overflow = '';
            }
        }

        openSidebar.addEventListener('click', () => toggleSidebar(true));
        closeSidebar.addEventListener('click', () => toggleSidebar(false));
        sidebarOverlay.addEventListener('click', () => toggleSidebar(false));

        // Close sidebar on window resize if desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                toggleSidebar(false);
            }
        });

        // Handle ESC key to close sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                toggleSidebar(false);
            }
        });

        // Chart initialization with responsive options (only on pages with the chart)
        const chartCanvas = document.getElementById('eventsChart');
        if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');
        const eventsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [
                    {
                        label: 'Participants',
                        data: [120, 190, 170, 220, 300, 450],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Événements',
                        data: [3, 5, 4, 6, 8, 10],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#374151',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 11
                            },
                            padding: 10
                        }
                    }
                },
                elements: {
                    line: {
                        capBezierPoints: false
                    }
                },
                // Mobile responsiveness
                onResize: function(chart, size) {
                    if (size.width < 768) {
                        chart.options.plugins.legend.position = 'bottom';
                        chart.options.plugins.legend.labels.padding = 15;
                    } else {
                        chart.options.plugins.legend.position = 'top';
                        chart.options.plugins.legend.labels.padding = 20;
                    }
                }
            }
        });
        } // end if chartCanvas

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states for buttons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function() {
                if (this.textContent.includes('Nouvel événement')) {
                    this.disabled = true;
                    this.innerHTML = '<i data-feather="loader" class="w-4 h-4 animate-spin mr-2"></i>Création...';
                    setTimeout(() => {
                        this.disabled = false;
                        this.innerHTML = '<i data-feather="plus" class="w-4 h-4"></i><span>Nouvel événement</span>';
                        feather.replace();
                    }, 2000);
                }
            });
        });

        // Add table row hover effects and mobile optimization
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.classList.add('bg-gray-50');
            });
            row.addEventListener('mouseleave', function() {
                this.classList.remove('bg-gray-50');
            });

            // Add click handler for mobile (show more info)
            row.addEventListener('click', function(e) {
                if (window.innerWidth < 640 && !e.target.closest('button')) {
                    const hiddenCells = this.querySelectorAll('.hidden');
                    hiddenCells.forEach(cell => {
                        cell.classList.toggle('hidden');
                    });
                }
            });
        });

        // Add search functionality
        const searchInput = document.querySelector('input[placeholder="Rechercher..."]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase();
                    // Here you would implement actual search functionality
                    console.log('Recherche:', searchTerm);
                }, 300);
            });
        }

        // Add notification functionality
        const bellIcon = document.querySelector('[data-feather="bell"]');
        if (bellIcon) {
            bellIcon.parentElement.addEventListener('click', function() {
                // Toggle notification panel (mock implementation)
                console.log('Afficher les notifications');
            });
        }

        // Add progress bar animations
        const progressBars = document.querySelectorAll('.bg-green-600, .bg-yellow-500, .bg-red-500');
        const animateProgressBars = () => {
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                bar.style.transition = 'width 1s ease-in-out';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        };

        // Trigger progress bar animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateProgressBars();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            observer.observe(tableContainer);
        }

        // Add stats counter animation
        const animateCounters = () => {
            const counters = document.querySelectorAll('.text-xl.sm\\:text-2xl.font-bold');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = counter.textContent.replace(/[0-9,]+/, target.toLocaleString());
                        clearInterval(timer);
                    } else {
                        counter.textContent = counter.textContent.replace(/[0-9,]+/, Math.floor(current).toLocaleString());
                    }
                }, 20);
            });
        };

        // Trigger counter animation on page load
        setTimeout(animateCounters, 500);

        // Add touch gestures for mobile sidebar
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipeGesture();
        });

        function handleSwipeGesture() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;

            // Swipe right to open sidebar (from left edge)
            if (touchStartX < 50 && swipeDistance > swipeThreshold && window.innerWidth < 1024) {
                toggleSidebar(true);
            }

            // Swipe left to close sidebar
            if (sidebar.classList.contains('open') && swipeDistance < -swipeThreshold) {
                toggleSidebar(false);
            }
        }

        // Add focus trap for sidebar when open
        const focusableElements = sidebar.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );

        const firstFocusableElement = focusableElements[0];
        const lastFocusableElement = focusableElements[focusableElements.length - 1];

        document.addEventListener('keydown', (e) => {
            if (!sidebar.classList.contains('open')) return;

            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusableElement) {
                        lastFocusableElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusableElement) {
                        firstFocusableElement.focus();
                        e.preventDefault();
                    }
                }
            }
        });

        // Performance optimization: Lazy load chart
        let chartLoaded = false;
        const chartContainer = document.querySelector('.chart-container');

        const chartObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !chartLoaded) {
                    // Chart is already loaded above, but this could be used for lazy loading
                    chartLoaded = true;
                    chartObserver.unobserve(entry.target);
                }
            });
        });

        if (chartContainer) {
            chartObserver.observe(chartContainer);
        }

        console.log('Dashboard responsive entièrement initialisé ✅');
    </script>
</body>
</html>
