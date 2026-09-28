<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>@yield('title', 'Panel') · Inventario Claro</title><link rel="stylesheet" href="{{ asset('css/app.css') }}"></head>
<body>
<div class="shell">
 <aside class="sidebar">
  <a class="brand" href="{{ route('dashboard') }}"><span class="brand-icon">IC</span><span>inventario<small>CLARO</small></span></a>
  <div class="side-label">MENÚ PRINCIPAL</div>
  <nav aria-label="Navegación principal">
   <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span>▦</span> Resumen</a>
   <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}"><span>▤</span> Productos</a>
   <a class="nav-link" href="{{ route('products.index', ['filter'=>'low']) }}"><span>◉</span> Alertas de stock</a>
  </nav>
  <div class="side-bottom"><div class="user-icon">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div class="user-text"><strong>{{ auth()->user()->name }}</strong><small>Administrador</small></div><form action="{{ route('logout') }}" method="post">@csrf <button type="submit" class="logout" aria-label="Cerrar sesión" title="Cerrar sesión">↪</button></form></div>
 </aside>
 <div class="main"><header class="topbar"><span>Gestión de inventario <span class="top-divider">/</span> @yield('breadcrumb', 'Resumen')</span><span class="top-right"><span class="online-dot"></span> Sistema activo</span></header><main class="content">@if(session('success'))<div class="notice" role="status">✓ {{ session('success') }}</div>@endif @yield('content')</main></div>
</div>
</body></html>
