@extends('admin.layouts.master')

@section('content')
<div class="p-6 sm:p-10 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Нова публикация</h1>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-1">Добавяне на нова публикация</p>
        </div>
        <a href="{{ route('admin.blog.index') }}"
           class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all active:scale-95 flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs text-gray-400"></i> Назад
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm p-8">
        <form class="blog-form" action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Изображение -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                        Изображение <span class="text-red-400">(*)</span>
                    </label>
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
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <!-- Дата -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Дата</label>
                    <input type="text" name="date_published" class="datepicker w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <!-- Статус -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Статус</label>
                    <select name="status"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="1">Публично</option>
                        <option value="0">Скрито</option>
                    </select>
                </div>

                <!-- Категория -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Категория</label>
                    <select name="category" class="main-category w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Изберете</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Подкатегория -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Подкатегория</label>
                    <select name="sub_category_id" class="sub-category w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Изберете</option>
                    </select>
                </div>

                <!-- Галерия -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Галерия</label>
                    <select name="gallery_id" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                        <option value="">Изберете</option>
                        @foreach ($galleries as $gallery)
                            <option value="{{ $gallery->id }}">{{ $gallery->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Youtube -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Youtube Адрес</label>
                    <input type="text" name="youtube_key" value="{{ old('youtube_key') }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <!-- Описание -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Описание</label>
                    <textarea name="description" class="summernote w-full">{{ old('description') }}</textarea>
                </div>

                <!-- SEO Заглавие -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">SEO Заглавие</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title') }}"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>

                <!-- SEO Описание -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">SEO Описание</label>
                    <textarea name="seo_description" rows="3"
                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl text-sm font-medium text-gray-900 dark:text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all resize-none"></textarea>
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



       <!-- preview image -->
       <script type="text/javascript">
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>

<script src="{{asset('backend/assets/js/croppie.min.js')}}" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="{{asset('backend/assets/css/croppie.css')}}" integrity="sha512-2eMmukTZtvwlfQoG8ztapwAH5fXaQBzaMqdljLopRSA0i6YKM8kBAOrSSykxu9NN9HrtD45lIqfONLII2AFL/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script>
    $(document).ready(function() {
        $('.blog-form').each(function() {
            var $form = $(this);
            var $preview = $form.find('.image-preview');
            var $input = $form.find('.image-input');
            var $base64Image = $form.find('.base64-image');
            var $imageUrlInput = $form.find('.image-url');

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

            // Handle file input change
            $input.on('change', function(e) {
                var file = e.target.files[0];
                var reader = new FileReader();

                reader.onload = function() {
                    var base64data = reader.result;
                    $base64Image.val(base64data);

                    preview.bind({
                        url: base64data
                    }).then(function() {
                        console.log('Croppie bind complete for upload');
                    });
                };

                reader.readAsDataURL(file);
            });

            // Handle image URL input
            $imageUrlInput.on('change', function() {
                var url = $imageUrlInput.val();
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
                                
                                // Create a new Image object
                                var img = new Image();
                                img.onload = function() {
                                    // Ensure the image is fully loaded before binding
                                    preview.bind({
                                        url: imageUrl
                                    }).then(function() {
                                        console.log('Croppie bind complete for URL');
                                    });
                                };
                                img.onerror = function() {
                                    alert('Failed to load the image from the URL.');
                                };
                                img.src = imageUrl; // Set source to trigger loading
                            } else {
                                alert('The URL provided does not point to a valid image.');
                            }
                        },
                        error: function() {
                            alert('Failed to download the image.');
                        }
                    });
                }
            });

            // Enable/Disable inputs based on presence of value
            $imageUrlInput.on('input', function() {
                if ($imageUrlInput.val()) {
                    $input.prop('disabled', true);
                } else {
                    $input.prop('disabled', false);
                }
            });

            $input.on('change', function() {
                if ($input.val()) {
                    $imageUrlInput.prop('disabled', true);
                } else {
                    $imageUrlInput.prop('disabled', false);
                }
            });

            // Handle form submission
            $form.on('submit', function(e) {
                e.preventDefault();

                // If no URL was provided, get the base64 result from Croppie
                if ($imageUrlInput.val()) {
                    // Image URL case, no need to reprocess
                    $form[0].submit();
                } else {
                    // Croppie cropping for uploaded image
                    preview.result('base64').then(function(result) {
                        $base64Image.val(result);
                        $form[0].submit();
                    });
                }
            });
        });
    });
    
</script>


<script>
    $(document).ready(function(){
        $('body').on('change', '.main-category', function(e){
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

