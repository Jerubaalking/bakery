<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
   
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title Page-->
    <title>misana home bakery</title>
      <!-- Fontfaces CSS-->
      <link href="{{ asset('css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-5/css/fontawesome-all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all">
    {{-- SweetAlert2 --}}
    <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
    

    <link href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css ')}}">
     <!-- Vendor CSS-->
     <link href="{{ asset('vendor/animsition/animsition.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/wow/animate.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/slick/slick.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" media="all">
    <script src="{{asset('assets/plugins/canvasjs.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Main CSS-->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{ asset('assets/css/new_dash.css ')}}">
      <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/misana.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/misana.png')}}">
    <style>
   table.dataTable tbody tr:hover {
     background-color:#E8CFC9  !important;
    }
    .select2-container{ 
        width: 100% !important;
     }
     .select2-selection__rendered {
        line-height: 31px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
    }
    #sidebar .sidebar-header{
            display:none;
    } 
    #mobileNavBar #mobile-btn{
        display:none;
    }
    #mobileNavBar #mobile-nav{
        display:none;
    }
    #desktopNav{
        display:visible;
    }
    @media only screen and (max-width:991px) {
            #desktopNav{
            display:none;
        }
        #mobileNavBar #mobile-btn{
            display:block;
        }
        #mobileNavBar #mobile-nav{
            display:block;
        }
        #sidebar{
            display:none;
        }
        #sidebar .sidebar-header{
            display:none;
        }
        #searchBtn{
            display:none;
        }
        .desktopBtns{
            display:none;
        }
    }
    </style>
    @yield('top')
</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
                @include('layouts.mobileNav') 
        
        <div class="page-container">
            <div class="content side-menu">
                <div class="col">

                </div>
                @include('layouts.sidebar') 
                <section class="content" style = "margin:10px;">
    
                    @yield('content')


                </section>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

  <script src="{{  asset('vendor/bootstrap-4.1/popper.min.js')}}"></script>
  <script src="{{  asset('assets/bower_components/jquery/dist/jquery.min.js') }} "></script>
 <script src="{{asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }} "></script>

    <script src="{{  asset('vendor/slick/slick.min.js')}}">
    </script>
    <script src="{{  asset('vendor/wow/wow.min.js')}}"></script>
    <script src="{{  asset('vendor/animsition/animsition.min.js')}}"></script>
    <script src="{{  asset('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
    </script>
    <script src="{{  asset('vendor/counter-up/jquery.waypoints.min.js')}}"></script>
    <script src="{{  asset('vendor/counter-up/jquery.counterup.min.js')}}">
    </script>
    <script src="{{  asset('vendor/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{  asset('vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{  asset('vendor/chartjs/Chart.bundle.min.js')}}"></script>
    <!-- <script src="{{  asset('vendor/select2/select2.min.js')}}"> -->
  
    </script>

    <!-- Main JS-->
    <script src="{{  asset('js/main.js')}}"></script>

  @yield('bot')
</body>

</html>
<!-- end document-->
