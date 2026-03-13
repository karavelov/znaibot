<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЗнайБот | Вашият училищен асистент</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        apple: {
                            blue: '#0066cc',
                            dark: '#1d1d1f',
                            gray: '#f5f5f7',
                            light: '#bf4800'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .bento-hover {
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
        }
        .bento-hover:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        model-viewer {
            --poster-color: transparent;
        }
        model-viewer:focus-visible {
            outline: none;
        }
    </style>
</head>
<body class="bg-white text-apple-dark antialiased selection:bg-apple-blue selection:text-white">

    <nav class="fixed w-full top-0 z-50 glass-nav border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
                    <img src="{{ asset('frontend/znaibot.png') }}" alt="ЗнайБот Лого" class="w-8 h-8 object-contain">
                    <span class="font-semibold text-xl tracking-tight">ЗнайБот</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-black transition">Функции</a>
                    <a href="#gamification" class="text-sm font-medium text-gray-600 hover:text-black transition">Викторина</a>
                    <a href="#ecosystem" class="text-sm font-medium text-gray-600 hover:text-black transition">Предназначение</a>
                </div>
                <div>
                    <a href="https://client.znaibot.karavelov.com" target="_blank" class="bg-apple-dark text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-gray-800 transition shadow-sm">Вход в системата</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-40 pb-20 px-4 text-center overflow-hidden">
        <h1 class="text-5xl md:text-7xl font-bold tracking-tighter mb-4">
            Запознайте се със <br/>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500">ЗнайБот.</span>
        </h1>
        <p class="text-xl md:text-2xl text-gray-500 font-normal max-w-3xl mx-auto mb-10 tracking-tight">
            Вашият интелигентен помощник в училище. <br class="hidden md:block"/>Изкуствен интелект, създаден да направи училището по-умно, по-сигурно и по-забавно.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
            <a href="#robot" class="bg-apple-blue text-white px-8 py-3.5 rounded-full text-lg font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Вижте робота</a>
            <a href="#ecosystem" class="bg-apple-gray text-apple-dark px-8 py-3.5 rounded-full text-lg font-medium hover:bg-gray-200 transition">Научете повече &rarr;</a>
        </div>

        <div id="robot" class="max-w-5xl mx-auto relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-[2.5rem] blur opacity-20 group-hover:opacity-30 transition duration-1000"></div>
            <div class="relative bg-apple-gray rounded-[2rem] h-[450px] md:h-[650px] flex items-center justify-center overflow-hidden border border-gray-200 shadow-2xl">
                
                <model-viewer
                    src="{{ asset('frontend/robomodel.glb') }}"
                    camera-controls
                    auto-rotate
                    rotation-per-second="20deg"
                    shadow-intensity="1.5"
                    shadow-softness="1"
                    environment-image="neutral"
                    exposure="1"
                    camera-orbit="0deg 75deg 105%"
                    class="w-full h-full cursor-grab active:cursor-grabbing outline-none"
                    alt="3D интерактивен модел на ЗнайБот">
                    
                    <div slot="poster" class="absolute inset-0 flex items-center justify-center bg-apple-gray">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 border-4 border-gray-300 border-t-apple-blue rounded-full animate-spin mb-4"></div>
                            <span class="text-sm font-medium text-gray-500">Зареждане на 3D модела...</span>
                        </div>
                    </div>
                </model-viewer>

            </div>
        </div>
    </section>

    <section id="features" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center tracking-tight mb-16">Изключително умен. <br/>Напълно защитен.</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="bg-apple-gray rounded-3xl p-8 lg:col-span-2 bento-hover overflow-hidden relative flex flex-col justify-between">
                <div class="z-10 relative">
                    <h3 class="text-2xl font-semibold mb-2">Локален изкуствен интелект</h3>
                    <p class="text-gray-600 text-lg max-w-md">Задвижван от модела BgGPT локално на сървъри в училището. Данните Ви остават при Вас, без да излизат в интернет.</p>
                </div>
                <div class="mt-8 z-10">
                    <span class="inline-block bg-white text-apple-dark font-medium px-4 py-2 rounded-full text-sm shadow-sm">Сигурност: Максимална</span>
                </div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-100 rounded-full blur-3xl opacity-50"></div>
            </div>

            <div class="bg-black text-white rounded-3xl p-8 bento-hover flex flex-col justify-between relative overflow-hidden">
                <div class="z-10">
                    <svg class="w-10 h-10 mb-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <h3 class="text-2xl font-semibold mb-2">NFC Идентификация</h3>
                    <p class="text-gray-400">Достъп само с едно докосване чрез телефон или сигурен чип.</p>
                </div>
            </div>

            <div class="bg-apple-gray rounded-3xl p-8 bento-hover text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                    <svg class="w-8 h-8 text-apple-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold mb-1">Гласово общуване</h3>
                <p class="text-gray-600 text-sm">ЗнайБот Ви чува и Ви отговаря с глас в реално време.</p>
            </div>

            <div id="gamification" class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-3xl p-8 lg:col-span-2 bento-hover flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-6 md:mb-0">
                    <span class="text-orange-500 font-semibold tracking-wide uppercase text-sm mb-2 block">Играйте и учете</span>
                    <h3 class="text-3xl font-bold mb-3">Дневна викторина</h3>
                    <p class="text-gray-700 mb-6">Отговаряйте на въпроси, трупайте точки и спечелете реален 3D принтиран медал в края на месеца!</p>
                    <a href="#" class="text-apple-dark font-medium border-b border-black pb-0.5 hover:text-orange-600 hover:border-orange-600 transition">Вижте класирането</a>
                </div>
                <div class="md:w-5/12 h-48 bg-white/50 rounded-2xl border border-white/60 shadow-inner flex items-center justify-center">
                    <img src="medal.png" alt="Медал">
                </div>
            </div>

        </div>
    </section>

    <section id="ecosystem" class="py-24 bg-apple-dark text-white text-center px-4">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-6">Едно приложение. <br/>За цялото училище.</h2>
            <p class="text-xl text-gray-400 mb-16 max-w-2xl mx-auto">ЗнайБот обединява всички – от ученика до директора, в една сигурна среда.</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-left">
                <div class="bg-gray-800/50 p-6 rounded-3xl backdrop-blur-sm border border-gray-700">
                    <h4 class="text-xl font-semibold mb-2">За Ученици</h4>
                    <p class="text-sm text-gray-400">Въпроси към AI, интерактивна карта, викторина и изгубени вещи.</p>
                </div>
                <div class="bg-gray-800/50 p-6 rounded-3xl backdrop-blur-sm border border-gray-700">
                    <h4 class="text-xl font-semibold mb-2">За Родители</h4>
                    <p class="text-sm text-gray-400">Проследяване на програмата, навигация в сградата и предстоящи събития.</p>
                </div>
                <div class="bg-gray-800/50 p-6 rounded-3xl backdrop-blur-sm border border-gray-700">
                    <h4 class="text-xl font-semibold mb-2">За Учители</h4>
                    <p class="text-sm text-gray-400">Бърз достъп до програма, класове и локация в училището.</p>
                </div>
                <div class="bg-gray-800/50 p-6 rounded-3xl backdrop-blur-sm border border-gray-700">
                    <h4 class="text-xl font-semibold mb-2">Столова & Охрана</h4>
                    <p class="text-sm text-gray-400">Следене на алергии и качване на снимки на намерени вещи.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-bold tracking-tight mb-6">Готови ли сте за бъдещето?</h2>
        <p class="text-xl text-gray-500 mb-10">Влезте в системата и започнете да използвате ЗнайБот още днес.</p>
        <a href="https://client.znaibot.karavelov.com" class="inline-block bg-apple-dark text-white px-10 py-4 rounded-full text-xl font-semibold hover:bg-black transition transform hover:scale-105 shadow-2xl">
            Към платформата
        </a>
    </section>

    <footer class="bg-apple-gray border-t border-gray-200 pt-16 pb-8 px-4 text-center md:text-left text-sm text-gray-500">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; 2026 СУ „Любен Каравелов“, гр. Добрич</p>
            <div class="space-x-4">
                <a href="#" class="hover:text-black">Поверителност</a>
                <a href="https://github.com/karavelov/znaibot" target="_blank" class="hover:text-black">GitHub</a>
            </div>
        </div>
    </footer>

</body>
</html>