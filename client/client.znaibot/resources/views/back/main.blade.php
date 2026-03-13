<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ЗнайБот</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        /* Базови настройки */
        html, body { 
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FBFBFD; 
            font-family: 'Inter', sans-serif; 
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        /* РЕЖИМ КИОСК (БЕЗ СКРОЛ) */
        body.kiosk-mode {
            overflow: hidden !important;
            position: fixed;
            width: 100%;
            height: 100%;
            touch-action: none; /* Забранява всякакви жестове */
        }

        /* РЕЖИМ СКРОЛ (РАБОТЕЩ) */
        body.scroll-mode {
            overflow-y: auto !important;
            overflow-x: hidden;
            position: relative !important;
            height: auto !important;
            min-height: 100%;
            touch-action: pan-y !important; /* Позволява само вертикално скролване с пръст */
        }
        
        .bg-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 30px 30px;
        }

        main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Увеличаваме малко шрифта за киоск устройства */
        html { font-size: 135%; }
    </style>
</head>
<!-- По подразбиране е kiosk-mode, освен ако в страницата не подадеш scroll-mode -->
<body class="bg-pattern text-gray-800 @yield('body_class', 'kiosk-mode')">

    <main class="w-full max-w-[1800px] mx-auto">
        @yield('content')
    </main>

    <script>
        // Предотвратява зуумване с два пръста, но позволява скролване с един (ако е в scroll-mode)
        document.addEventListener('touchstart', function (event) {
            if (event.touches.length > 1) {
                event.preventDefault();
            }
        }, { passive: false });

        document.addEventListener('gesturestart', function (event) {
            event.preventDefault();
        });

        // Предотвратява зуумване с Ctrl + Wheel (мишка)
        document.addEventListener('wheel', function (event) {
            if (event.ctrlKey) {
                event.preventDefault();
            }
        }, { passive: false });
    </script>
</body>
</html>