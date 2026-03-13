@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Редактиране на публикация</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">{{ $blog->title }}</p>
        </div>
        <a href="{{ route('admin.blog.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
        <form class="blog-form" action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Текущо изображение -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Текущо изображение</label>
                    <img src="{{ asset($blog->image) }}" class="h-24 rounded-2xl object-cover" alt="">
                </div>

                <!-- Ново изображение -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Изображение</label>
                    <div class="space-y-3">
                        <input type="file" name="image" class="image-input w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white transition-all file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" accept="image/*">
                        <input type="text" name="image_url" class="image-url w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all" placeholder="Адрес на изображение от интернет">
                        <div class="image-preview rounded-2xl overflow-hidden"></div>
                        <input type="hidden" name="base64_image" class="base64-image">
                    </div>
                </div>

                <!-- Заглавие -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Заглавие</label>
                    <input type="text" name="title" value="{{ $blog->title }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <!-- Статус -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Статус</label>
                    <select name="status"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option {{ $blog->status == 1 ? 'selected' : '' }} value="1">Публично</option>
                        <option {{ $blog->status == 0 ? 'selected' : '' }} value="0">Скрито</option>
                    </select>
                </div>

                <!-- Категория -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Категория</label>
                    <select name="category" class="main-category w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Изберете</option>
                        @foreach ($categories as $category)
                            <option {{ $category->id == $blog->category_id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Подкатегория -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Подкатегория</label>
                    <select name="sub_category_id" class="sub-category w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Изберете</option>
                        @foreach ($subcategories as $subCategory)
                            <option {{ $subCategory->id == $blog->sub_category_id ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Галерия -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Галерия</label>
                    <select name="gallery_id" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Изберете</option>
                        @foreach ($galleries as $gallery)
                            <option {{ $gallery->id == $blog->gallery_id ? 'selected' : '' }} value="{{ $gallery->id }}">{{ $gallery->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Youtube -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Youtube Адрес</label>
                    <input type="text" name="youtube_key" value="{{ $blog->youtube_key }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                    @if ($blog->youtube_key)
                        <div class="mt-3 rounded-2xl overflow-hidden aspect-video">
                            <iframe src="{{ $blog->youtube_key }}" class="w-full h-full" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    @endif
                </div>

                <!-- Описание -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Описание</label>
                    <textarea name="description" class="summernote w-full">{!! $blog->description !!}</textarea>
                </div>

                <!-- SEO Заглавие -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">SEO Заглавие</label>
                    <input type="text" name="seo_title" value="{{ $blog->seo_title }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <!-- SEO Описание -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">SEO Описание</label>
                    <textarea name="seo_description" rows="3"
                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all resize-none">{!! $blog->seo_description !!}</textarea>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    Запази
                </button>
                <a href="{{ route('admin.blog.index') }}"
                   class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                    Отказ
                </a>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/js/croppie.min.js') }}"
        integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/croppie.css') }}"
        integrity="sha512-2eMmukTZtvwlfQoG8ztapwAH5fXaQBzaMqdljLopRSA0i6YKM8kBAOrSSykxu9NN9HrtD45lIqfONLII2AFL/Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <script>
        /* Croppie Image Upload */
        $(document).ready(function() {
            $('.blog-form').each(function() {
                var $form = $(this);
                var $preview = $form.find('.image-preview');
                var $input = $form.find('.image-input');
                var $imageUrl = $form.find('.image-url');
                var $base64Image = $form.find('.base64-image');
                var existingImageUrl = "{{ asset($blog->image) }}";

                var preview = new Croppie($preview[0], {
                    viewport: {
                        width: 1140,
                        height: 400,
                        type: 'square'
                    },
                    boundary: {
                        width: 1140,
                        height: 400
                    },
                    enableResize: false,
                    enableOrientation: true,
                    enableExif: true,
                });

                // Pre-load the existing image
                preview.bind({
                    url: existingImageUrl
                }).then(function() {
                    console.log('Croppie bind complete');
                });


                /* Image URL */

                $imageUrl.on('change', function() {
                    var url = $imageUrl.val();
                    if (url) {
                        $.ajax({
                            url: "{{ route('admin.product.url-image') }}",
                            type: 'POST',
                            data: {
                                image_url: url,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    var imageUrl = response.temp_path;
                                    preview.bind({
                                        url: imageUrl
                                    }).then(function() {
                                        console.log('Croppie bind complete');
                                    });
                                } else {
                                    alert(
                                        'The URL provided does not point to a valid image.'
                                        );
                                }
                            },
                            error: function() {
                                alert('Failed to download the image.');
                            }
                        });
                    }
                });

                $input.on('change', function() {
                    if ($input.val()) {
                        $imageUrl.prop('disabled', true);
                    } else {
                        $imageUrl.prop('disabled', false);
                    }
                });

                $imageUrl.on('input', function() {
                    if ($imageUrl.val()) {
                        $input.prop('disabled', true);
                    } else {
                        $input.prop('disabled', false);
                    }
                });

                /* End of Image URL */


                $input.on('change', function(e) {
                    var file = e.target.files[0];
                    var reader = new FileReader();

                    reader.onload = function() {
                        var base64data = reader.result;
                        $base64Image.val(base64data);

                        preview.bind({
                            url: base64data
                        }).then(function() {
                            console.log('Croppie bind complete');
                        });
                    }

                    reader.readAsDataURL(file);
                });

                $form.on('submit', function(e) {
                    e.preventDefault();

                    preview.result('base64').then(function(result) {
                        $base64Image.val(result);
                        $form[0].submit();
                    });
                });
            });
        });

        /* End of Croppie Image Upload */
    </script>


<script>
        $(document).ready(function(){
            $('body').on('change', '.main-category', function(e){

                $('.child-category').html('<option value="">Select</option>')

                let id = $(this).val();
                $.ajax({
                    method: 'GET',
                    url: "{{route('admin.blog.get-subcategories')}}",
                    data: {
                        id:id
                    },
                    success: function(data){
                        $('.sub-category').html('<option value="">Изберете</option>')

                        $.each(data, function(i, item){
                            $('.sub-category').append(`<option value="${item.id}">${item.name}</option>`)
                        })
                    },
                    error: function(xhr, status, error){
                        console.log(error);
                    }
                })
            })
        })
    </script>

@endpush
