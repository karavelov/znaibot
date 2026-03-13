<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Административен панел &mdash; Robohelios</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('backend/assets/modules/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/modules/fontawesome/css/all.min.css')}}">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{asset('backend/assets/modules/jqvmap/dist/jqvmap.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/modules/weather-icon/css/weather-icons.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/modules/weather-icon/css/weather-icons-wind.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/modules/summernote/summernote-bs4.css')}}">
  {{-- Datatable Bootstrap 5 --}}
  <link rel="stylesheet" href="{{asset('backend/assets/css/jquery.dataTables.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/css/dataTables.bootstrap5.min.css')}}">
  {{-- <link rel="stylesheet" href="{{asset('backend/assets/css/all.css')}}"/> --}}
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
  <link rel="stylesheet" href="{{asset('backend/assets/css/bootstrap-iconpicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/modules/select2/dist/css/select2.min.css')}}">
  
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/css/components.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/css/ios-admin.css')}}">

  <script src="https://cdn.tailwindcss.com"></script>


<script>
    tailwind.config = {
        darkMode: 'class', 
        theme: {
            extend: {
            }
        }
    }
</script>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  {{-- Toastr  --}}
  <link rel="stylesheet" href="{{asset('backend/assets/css/toastr.min.css')}}">

  {{-- RTL CSS --}}
  @if($settings->layout == 'RTL')
  <link rel="stylesheet" href="{{asset('backend/assets/css/rtl.css')}}">
 @endif

</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

        <!-- Navbar Content -->
            @include('admin.layouts.nav')
        <!-- Navbar Content End-->

        <!-- sidebar Content -->
            @include('admin.layouts.sidebar')
        <!-- sidebar Content -->

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>

    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{asset('backend/assets/modules/jquery.min.js')}}"></script>
  <script src="{{asset('backend/assets/modules/popper.js')}}"></script>
  <script src="{{asset('backend/assets/modules/tooltip.js')}}"></script>
  <script src="{{asset('backend/assets/modules/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('backend/assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>
  <script src="{{asset('backend/assets/modules/moment.min.js')}}"></script>
  <script src="{{asset('backend/assets/js/stisla.js')}}"></script>

  <!-- JS Libraies -->
  <script src="{{asset('backend/assets/modules/simple-weather/jquery.simpleWeather.min.js')}}"></script>
  {{-- <script src="{{asset('backend/assets/modules/chart.min.js')}}"></script> --}}
  <script src="{{asset('backend/assets/modules/jqvmap/dist/jquery.vmap.min.js')}}"></script>
  <script src="{{asset('backend/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
  <script src="{{asset('backend/assets/modules/summernote/summernote-bs4.js')}}"></script>
  <script src="{{asset('backend/assets/modules/chocolat/dist/js/jquery.chocolat.min.js')}}"></script>
  {{-- Datatable Bootstrap 5 --}}
  <script src="{{asset('backend/assets/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/assets/js/dataTables.bootstrap5.min.js')}}"></script>
  {{-- SweetAlert2 --}}
  {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
  <script src="{{asset('backend/assets/js/sweetalert2@11')}}"></script>
  <script src="{{asset('backend/assets/js/bootstrap-iconpicker.bundle.min.js')}}"></script>
  <script src="{{asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
  <script src="{{asset('backend/assets/modules/select2/dist/js/select2.full.min.js')}}"></script>
  {{-- Toastr --}}
  <script src="{{asset('backend/assets/js/toastr.min.js')}}"></script>
  <!-- Page Specific JS File -->
  {{-- <script src="{{asset('backend/assets/js/page/index-0.js')}}"></script> --}}

  <!-- Template JS File -->
  <script src="{{asset('backend/assets/js/scripts.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom.js')}}"></script>

  <script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{$error}}")
        @endforeach
    @endif
  </script>



<script>
  $(document).ready(function(){

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });


      $('body').on('click', '.delete-item', function(event){
          event.preventDefault();

          let deleteUrl = $(this).attr('href');

          Swal.fire({
              title: 'Потвърждение за изтриване?',
              text: "Това действие е необратимо!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Изтрий!',
              cancelButtonText: 'Отказ'
              }).then((result) => {
              if (result.isConfirmed) {

                  $.ajax({
                      type: 'DELETE',
                      url: deleteUrl,

                      success: function(data){

                          if(data.status == 'success'){
                              Swal.fire(
                                  'Успешно изтриване!',
                                  data.message,
                                  'success'
                              )
                              window.location.reload();
                          }else if (data.status == 'error'){
                              Swal.fire(
                                  'Възникна грешка при изтриване!',
                                  data.message,
                                  'error'
                              )
                          }
                      },
                      error: function(xhr, status, error){
                          console.log(error);
                      }
                  })
              }
          })
      })

  })
</script>

{{-- Dark Mode Toggle --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('dark-mode-toggle');
      const body = document.body;

      // Check the user's preference on load
      if (localStorage.getItem('dark-mode') === 'enabled') {
          body.classList.add('dark-mode');
      }

      if (toggle) {
          toggle.addEventListener('click', () => {
              const isDarkMode = body.classList.toggle('dark-mode');
              localStorage.setItem('dark-mode', isDarkMode ? 'enabled' : 'disabled');
          });
      }
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
      const revealElements = document.querySelectorAll('.ios-reveal');

      if (!('IntersectionObserver' in window)) {
          revealElements.forEach((element) => element.classList.add('is-visible'));
          return;
      }

      const observer = new IntersectionObserver((entries, intersectionObserver) => {
          entries.forEach((entry) => {
              if (entry.isIntersecting) {
                  entry.target.classList.add('is-visible');
                  intersectionObserver.unobserve(entry.target);
              }
          });
      }, {
          threshold: 0.15,
      });

      revealElements.forEach((element) => observer.observe(element));
  });
</script>

{{-- It's related to @push('scripts') in blades where we use datatables --}}
  @stack('scripts')
</body>
</html>