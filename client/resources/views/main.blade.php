<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЗнайБот</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        html { 
            /* Върнато на стандартен размер (100% = 16px) */
            font-size: 100%; 
            height: 100%;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #FBFBFD; 
            min-height: 100vh;
            width: 100%;
            -webkit-tap-highlight-color: transparent;
            /* Позволяваме селектиране на текст, ако е нормален сайт */
            user-select: text; 
        }

        /* СВОБОДЕН СКРОЛ - ПО ПОДРАЗБИРАНЕ */
        .scroll-mode {
            overflow-y: auto !important;
            overflow-x: hidden;
            position: relative !important;
            height: auto !important;
            touch-action: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        /* КИОСК РЕЖИМ - САМО АКО СЕ ВКЛЮЧИ ИЗРИЧНО */
        .kiosk-mode {
            overflow: hidden !important;
            position: fixed;
            width: 100%;
            height: 100%;
            touch-action: none;
        }
        
        .bg-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 25px 25px; /* Малко по-ситна шарка за по-фин вид */
        }

        main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body class="bg-pattern text-gray-800 @yield('body_class', 'scroll-mode')">

    <!-- Намалихме max-width от 1800px на 1280px за по-събран вид, ако желаеш -->
    <main class="w-full max-w-[1400px] mx-auto">
        @yield('content')
    </main>

    <script>
        function isKiosk() {
            return document.body.classList.contains('kiosk-mode');
        }

        // Блокира zoom само ако сме в kiosk-mode
        document.addEventListener('touchstart', function (event) {
            if (isKiosk() && event.touches.length > 1) {
                event.preventDefault();
            }
        }, { passive: false });

        document.addEventListener('gesturestart', function (event) {
            if (isKiosk()) {
                event.preventDefault();
            }
        });

        // Блокира само Ctrl + Scroll (Zoom), за да не се разваля дизайна случайно
        document.addEventListener('wheel', function (event) {
            if (event.ctrlKey) {
                event.preventDefault();
            }
        }, { passive: false });
    </script>
</body>
</html>