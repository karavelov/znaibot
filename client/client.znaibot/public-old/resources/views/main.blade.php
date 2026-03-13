<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZnaiBot Main</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-[#F8F9FA] bg-pattern text-gray-800">

    <!-- Навигация (Модерна и чиста) -->

    <!-- Основно съдържание -->
    <main class="container mx-auto px-6 py-12 min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 text-gray-500 text-center py-8">
        <p class="font-medium">&copy; 2026 ZnaiBot School System. Всички права запазени.</p>
    </footer>
</body>
</html>