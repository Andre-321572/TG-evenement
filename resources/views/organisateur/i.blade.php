<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Événements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

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
            background: #f1f5f9;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-50">

      @include('organisateur.include.navbar')
        <!-- Main Content -->
        <div class="main-content flex-1 overflow-auto lg:ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm py-4 px-4 sm:px-6 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center space-x-4">
                    <!-- Mobile menu button -->
                    <button id="openSidebar" class="mobile-menu-btn lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <i data-feather="menu" class="w-6 h-6 text-gray-600"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Tableau de bord</h1>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Search - Hidden on very small screens -->
                    <div class="relative hidden sm:block">
                        <i data-feather="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-48 lg:w-64">
                    </div>

                    <!-- Search icon for mobile -->
                    <button class="sm:hidden p-2 rounded-md hover:bg-gray-100">
                        <i data-feather="search" class="w-5 h-5 text-gray-600"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 rounded-md hover:bg-gray-100">
                            <i data-feather="bell" class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
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

        // Chart initialization with responsive options
        const ctx = document.getElementById('eventsChart').getContext('2d');
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
