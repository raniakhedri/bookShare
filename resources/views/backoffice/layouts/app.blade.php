<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>

@if (\Request::is('rtl'))
  <html dir="rtl" lang="ar">
@else
  <html lang="en">
@endif

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  @if (env('IS_DEMO'))
    <x-demo-metas></x-demo-metas>
  @endif

  <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="/assets/img/favicon.png">
  <title>
    Bookly
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="/assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />

  <!-- Custom CSS to show all sidebar items without scrolling -->
  <style>
    .sidenav {
      height: auto !important;
      max-height: none !important;
      overflow: visible !important;
    }

    .sidenav .navbar-nav {
      height: auto !important;
      max-height: none !important;
      overflow: visible !important;
    }

    .sidenav .collapse {
      height: auto !important;
      max-height: none !important;
    }

    /* BookShare Primary Color Override */
    :root {
      --bs-primary: #F86D72;
      --bs-primary-rgb: 248, 109, 114;
    }

    /* Override Soft UI Dashboard primary colors */
    .bg-gradient-primary {
      background: linear-gradient(87deg, #F86D72 0, #e55a5f 100%) !important;
    }

    .bg-primary {
      background-color: #F86D72 !important;
    }

    .text-primary {
      color: #F86D72 !important;
    }

    .border-primary {
      border-color: #F86D72 !important;
    }

    .btn-primary {
      background-color: #F86D72 !important;
      border-color: #F86D72 !important;
    }

    .btn-primary:hover {
      background-color: #e55a5f !important;
      border-color: #e55a5f !important;
    }

    .btn-outline-primary {
      color: #F86D72 !important;
      border-color: #F86D72 !important;
    }

    .btn-outline-primary:hover {
      background-color: #F86D72 !important;
      border-color: #F86D72 !important;
    }

    /* Navigation colors */
    .navbar-brand .navbar-brand-img,
    .navbar-brand .navbar-brand-img:hover {
      filter: hue-rotate(340deg) saturate(1.2);
    }

    /* Sidebar active item */
    .sidenav .navbar-nav .nav-item .nav-link.active {
      background: linear-gradient(87deg, #F86D72 0, #e55a5f 100%) !important;
    }

    /* Chart and accent colors */
    .bg-gradient-info {
      background: linear-gradient(87deg, #F86D72 0, #e55a5f 100%) !important;
    }

    .bg-info {
      background-color: #F86D72 !important;
    }

    .text-info {
      color: #F86D72 !important;
    }

    .text-info.text-gradient {
      background: linear-gradient(310deg, #F86D72, #FF8A8E);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .border-info {
      border-color: #F86D72 !important;
    }

    .badge.bg-info {
      background-color: #F86D72 !important;
      color: white !important;
    }

    .badge.bg-gradient-info {
      background: linear-gradient(310deg, #F86D72, #FF8A8E) !important;
      color: white !important;
    }

    /* Link colors */
    a {
      color: #F86D72;
    }

    a:hover {
      color: #e55a5f;
    }

    /* Form focus colors */
    .form-control:focus {
      border-color: #F86D72;
      box-shadow: 0 0 0 0.2rem rgba(248, 109, 114, 0.25);
    }

    /* Progress bars */
    .progress-bar {
      background-color: #F86D72 !important;
    }

    /* Badges */
    .badge-primary {
      background-color: #F86D72 !important;
    }

    /* Alerts */
    .alert-primary {
      background-color: rgba(248, 109, 114, 0.1) !important;
      border-color: #F86D72 !important;
      color: #d63447 !important;
    }

    /* Custom gradient text */
    .text-gradient-primary {
      background: linear-gradient(87deg, #F86D72 0, #e55a5f 100%) !important;
      -webkit-background-clip: text !important;
      -webkit-text-fill-color: transparent !important;
      background-clip: text !important;
      color: transparent !important;
      display: inline-block;
    }
  </style>
</head>

<body
  class="g-sidenav-show  bg-gray-100 {{ (\Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '')) }} ">
  @auth
    @yield('auth')
  @endauth
  @guest
    @yield('guest')
  @endguest

  @if(session()->has('success'))
    <div x-data="{ show: true}" x-init="setTimeout(() => show = false, 4000)" x-show="show"
      style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; min-width: 250px;"
      class="bg-success text-white rounded shadow text-sm py-2 px-4 animate__animated animate__fadeInUp">
      <p class="m-0">{{ session('success')}}</p>
    </div>
  @endif
  <!--   Core JS Files   -->
  <script src="/assets/js/core/popper.min.js"></script>
  <script src="/assets/js/core/bootstrap.min.js"></script>
  <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="/assets/js/plugins/fullcalendar.min.js"></script>
  <script src="/assets/js/plugins/chartjs.min.js"></script>
  @stack('rtl')
  @stack('dashboard')
  <script>
    // Removed scrollbar initialization to show all sidebar items
  </script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="/assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>