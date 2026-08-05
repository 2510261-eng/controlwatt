<aside class="sidebar">


<div class="logo">
    <img src="{{ asset('images/logo.png') }}" class="logo-img">
</div>



<nav class="menu">


<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

🏠 Dashboard

</a>



<a href="{{ route('homes.index') }}" class="{{ request()->routeIs('homes.*') ? 'active' : '' }}">

🏡 Hogares

</a>



<a href="{{ route('devices.index') }}" class="{{ request()->routeIs('devices.*') ? 'active' : '' }}">

⚡ Dispositivos

</a>



<a href="{{ route('scanner.index') }}" class="{{ request()->routeIs('scanner.*') ? 'active' : '' }}">

📷 Escanear

</a>



<a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">

📄 Reportes

</a>



<hr>
</nav>


</aside>
