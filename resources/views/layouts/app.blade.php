<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/dist/css/AdminLTE.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/dist/css/skins/skin-blue.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/plugins/datepicker/datepicker3.css') }}">

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    {{-- header --}}
    <header class="main-header">
        <a href="#" class="logo">
        <span class="logo-mini"><b>DM</b></span>
        <span class="logo-lg"><b>Dik</b>Mart</span>    
        </a>

<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle Navigation</span>
    </a>

<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('public/images/'. Auth::user()->foto) }}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>

            <ul class="dropdown-menu">
                <li class="user-header">
                    <img src="{{ asset('public/images/' . Auth::user()->foto) }}" class="img-circle" alt="User Image">
                
                    <p>{{ Auth::user()->name }}</p>
                </li>

<li class="user-footer">
    <div class="pull-left">
        <a href="{{ route('user.profil') }}" class="btn btn-default btn-flat" >Edit Profile</a>
    </div>

<div class="pull-right">
    <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
    @csrf
    </form>
</div>
</li>
            </ul>
        </li>
    </ul>
</div>
</nav>
    </header>
    {{-- end header --}}

    {{-- sidebar --}}
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
<li class="header">Menu Navigasi</li>
<li>
    <a href="{{ route('home') }}">
<i class="fa fa-dashboard"></i> <span>Dashboard</span>    
</a>
</li>
  

@if(Auth::user()->level == 1)
<li><a href="{{ route('kategori.index') }}">
<i class="fa fa-cube"></i><span>Kategori</span>
</a></li>

<li><a href="{{ route('produk.index') }}">
<i class="fa fa-cubes"></i><span>Produk</span>
</a></li>

<li><a href="{{ route('member.index') }}">
<i class="fa fa-credit-card"></i> <span>Member</span>
</a></li>

<li><a href="{{ route('supplier.index') }}">
<i class="fa fa-truck"></i> <span>Supplier</span>
</a></li>

<li><a href="{{ route('pengeluaran.index') }}">
<i class="fa fa-money"></i> <span>Pengeluaran</span>
</a></li>

<li><a href="{{ route('user.index') }}">
<i class="fa fa-user"></i> <span>User</span>
</a></li>

<li><a href="{{ route('penjualan.index') }}">
<i class="fa fa-upload"></i> <span>Penjualan</span>
</a></li>

<li><a href="{{ route('pembelian.index') }}">
<i class="fa fa-download"></i> <span>Pembelian</span>
</a></li>

<li><a href="{{ route('laporan.index') }}">
<i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
</a></li>

<li><a href="{{ route('setting.index') }}">
<i class="fa fa-gears"></i> <span>Setting</span>
</a></li>

@else
<li><a href="{{ route('transaksi.index') }}">
<i class="fa fa-shopping-cart"></i> <span>Transaksi</span>
</a></li>

<li><a href="{{ route('transaksi.new') }}">
<i class="fa fa-cart-plus"></i> <span>Transaksi Baru</span>
</a>
</li>
@endif
</ul>
    </section>
</aside>
{{-- end sidebar --}}

{{-- content --}}
<div class="content-wrapper">
    <section class="content-header">
        <h1>@yield('title')</h1>
    <ol class="breadcrumb">
        @section('breadcrumb')
        <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
    @show
    </ol>
    </section>

<section class="content">
    @yield('content')
</section>
</div>
{{-- End Content --}}

{{-- footer --}}
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Aplikasi POS oleh: Dhiki Adriansyah
    </div>
    <strong>Copyright &copy; 2023 <a href="#">Company</a></strong>
    All Right Reversed
</footer>

</div>

<script src="{{ asset('public/adminLTE/plugins/jQuery/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/dist/js/app.min.js') }}"></script>

<script src="{{ asset('public/adminLTE/plugins/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/adminLTE/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('public/js/validator.min.js') }}"></script>

@yield('script')

  
</body>
</html>
