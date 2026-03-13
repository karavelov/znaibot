@extends('main')
@section('body_class', 'scroll-mode')

@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12 relative overflow-hidden">
    
    <!-- Декоративен фон -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-amber-50/50 rounded-full blur-[120px] -z-10"></div>
    <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-amber-50/50 rounded-full blur-[100px] -z-10"></div>

    <!-- Бутон Начало -->
    <a href="{{ route('home') }}" class="absolute top-8 left-8 z-20 inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 hover:shadow-sm transition-all duration-300 group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-amber-500"></i> Начало
    </a>

    <!-- Заглавие -->
    <div class="max-w-4xl mx-auto mb-12 pt-12 md:pt-0 animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1D1D1F]">
            История на училището <span class="text-amber-500">.</span>
        </h1>
        <p class="text-gray-400 mt-4 text-lg font-light tracking-wide italic">
            "От 1985 година до днес – училище с възрожденски дух и поглед в бъдещето."
        </p>
    </div>

    <!-- Основна карта със съдържание -->
    <div class="max-w-4xl mx-auto animate-fade-in-up">
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 md:p-12 shadow-[0_20px_50px_rgba(0,0,0,0.03)] relative overflow-hidden">
            
            <!-- Икона за начало -->
            <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mb-10 shadow-sm border border-amber-100/50">
                <i class="fas fa-school text-2xl text-amber-500"></i>
            </div>

            <div class="relative z-10 space-y-12">
                
                <!-- Секция: Откриването -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="w-2 h-8 bg-amber-500 rounded-full mr-3"></span>
                        Откриването (1985)
                    </h2>
                    <p class="text-gray-600 leading-[1.8] text-lg font-light">
                        С решение на Общински съвет от 01.09.1985 г. е открито ново Средно образователно училище в квартал Балик, в съвместна експлоатация с ОУ „Панайот Волов”. През учебната 1985/86 г. в училището работят 60 учители и 4 възпитатели, а още през следващата година то се разраства до 58 паралелки с 1804 ученици.
                    </p>
                </section>

                <!-- Секция: През годините -->
                <section>
                    <h2 class="text-xl font-bold text-gray-800 mb-6">През годините на утвърждаване</h2>
                    <div class="space-y-6 pl-4 border-l-2 border-gray-100">
                        
                        <div class="relative">
                            <span class="absolute -left-[25px] top-1 w-4 h-4 rounded-full bg-white border-4 border-amber-200"></span>
                            <h3 class="font-bold text-gray-800">1995 – Ерата на технологиите</h3>
                            <p class="text-gray-600 mt-2 font-light">СОУ "Любен Каравелов" сключва договор с МОН и IBM-България. Училището е едно от 15-те в страната, спечелили конкурс по Образователната инициатива на IBM. Започва обучение по информационни технологии и "Бизнес и финанси".</p>
                        </div>

                        <div class="relative">
                            <span class="absolute -left-[25px] top-1 w-4 h-4 rounded-full bg-white border-4 border-amber-200"></span>
                            <h3 class="font-bold text-gray-800">1996 - 1999 – Професионално развитие</h3>
                            <p class="text-gray-600 mt-2 font-light">Въвеждат се паралелки "Организатор на фирмен мениджмънт" и "Бизнес-администрация". Поставя се началото на ранно чуждоезиково обучение по английски език и разширено изучаване на изобразително изкуство.</p>
                        </div>
                    </div>
                </section>

                <!-- Секция: XXI век -->
                <section>
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Училището в XXI век</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-2xl hover:bg-gray-100 transition-colors">
                            <span class="text-amber-500 font-bold block mb-2">2001 - 2003</span>
                            <p class="text-sm text-gray-600">Училището става базово за студенти от ШУ „Св. Константин Преславски”. Откриват се профили „Информационни технологии” и „Стопански мениджмънт”. Създава се химнът на училището.</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-2xl hover:bg-gray-100 transition-colors">
                            <span class="text-amber-500 font-bold block mb-2">2006 - 2008</span>
                            <p class="text-sm text-gray-600">Открива се първият ECDL тест център в област Добрич. Обновяват се библиотеката и актовата зала, създава се училищна галерия.</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-2xl hover:bg-gray-100 transition-colors md:col-span-2">
                            <span class="text-amber-500 font-bold block mb-2">2013 - 2014</span>
                            <p class="text-sm text-gray-600">Въвежда се план-прием „Предприемачество и бизнес“ с английски език. Училището внедрява модерни практики като „Джъмпидо“ и „Енвижън“, утвърждавайки авторитета си в Добрич.</p>
                        </div>
                    </div>
                </section>

                <!-- Секция: Юбилеи (Акцент) -->
                <div class="bg-amber-50 rounded-2xl p-8 border border-amber-100">
                    <h2 class="text-2xl font-bold text-amber-600 mb-6 flex items-center">
                        <i class="fas fa-birthday-cake mr-3"></i> Юбилеи и Тържества
                    </h2>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-check text-amber-400 mt-1.5 mr-3"></i>
                            <span class="text-gray-700"><strong class="text-gray-900">2004/2005:</strong> 20-годишен юбилей и 170 години от рождението на патрона Любен Каравелов.</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-amber-400 mt-1.5 mr-3"></i>
                            <span class="text-gray-700"><strong class="text-gray-900">2009/2010:</strong> 25-годишен юбилей, отбелязан със създаването на филм за училището.</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-amber-400 mt-1.5 mr-3"></i>
                            <span class="text-gray-700"><strong class="text-gray-900">2015/2016:</strong> 30 години „Възрожденски дух“ – грандиозен концерт в ДТ „Йордан Йовков“.</span>
                        </li>
                    </ul>
                </div>

                <!-- Статистика (Footer) -->
                <div class="pt-10 border-t border-gray-50 flex flex-wrap gap-8 justify-between md:justify-start">
                    <div>
                        <p class="text-3xl font-bold text-gray-800">1985</p>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mt-1">Година на основаване</p>
                    </div>
                    <div class="w-[1px] h-10 bg-gray-100 hidden sm:block"></div>
                    <div>
                        <p class="text-3xl font-bold text-gray-800">Програмист на ИИ</p>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mt-1">Професия</p>
                    </div>
                    <div class="w-[1px] h-10 bg-gray-100 hidden sm:block"></div>
                    <div>
                        <p class="text-3xl font-bold text-gray-800">35+</p>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mt-1">Години история</p>
                    </div>
                </div>
            </div>

            <!-- Декоративен фон икона -->

        </div>
    </div>

    <!-- Разделител -->
    <div class="max-w-4xl mx-auto mt-12 flex justify-center opacity-30">
        <div class="h-[1px] w-24 bg-gray-300"></div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
</style>
@endsection