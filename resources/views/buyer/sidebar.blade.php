<!--begin::Sidebar-->
<aside class="main-sidebar app-sidebar bg-body-secondary shadow" data-bs-theme="white">
<!--begin::Sidebar Brand-->
<div class="sidebar-brand">
<!--begin::Brand Link-->
<a href="./index.html" class="brand-link">
<!--begin::Brand Image-->
<img
src="{{ asset("image/logo.png") }}"
alt="Logo"
class="brand-image opacity-75 shadow"
/>
<!--end::Brand Image-->
<!--begin::Brand Text-->
<span class="brand-text fw-light">Buyer</span>
<!--end::Brand Text-->
</a>
<!--end::Brand Link-->
</div>
<!--end::Sidebar Brand-->
<!--begin::Sidebar Wrapper-->
<div class="sidebar-wrapper">
<nav class="mt-2">
<!--begin::Sidebar Menu-->
<ul
class="nav sidebar-menu flex-column"
data-lte-toggle="treeview"
role="menu"
data-accordion="false"
>
<li class="nav-item">
    <a href="{{ route('buyer.dashboard') }}" class="nav-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-gauge"></i>
    <p>Dashboard</p>
    </a>
    </li>
<li class="nav-item">
<a href="./generate/theme.html" class="nav-link">
<i class="nav-icon bi bi-palette"></i>
<p>Theme Generate</p>
</a>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="nav-icon bi bi-box-seam-fill"></i>
<p>
Widgets
<i class="nav-arrow bi bi-chevron-right"></i>
</p>
</a>
<ul class="nav nav-treeview">
<li class="nav-item">
<a href="./widgets/small-box.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Small Box</p>
</a>
</li>
<li class="nav-item">
<a href="./widgets/info-box.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>info Box</p>
</a>
</li>
<li class="nav-item">
<a href="./widgets/cards.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Cards</p>
</a>
</li>
</ul>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="nav-icon bi bi-clipboard-fill"></i>
<p>
Layout Options
<span class="nav-badge badge text-bg-secondary me-3">6</span>
<i class="nav-arrow bi bi-chevron-right"></i>
</p>
</a>
<ul class="nav nav-treeview">
<li class="nav-item">
<a href="./layout/unfixed-sidebar.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Default Sidebar</p>
</a>
</li>
<li class="nav-item">
<a href="./layout/fixed-sidebar.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Fixed Sidebar</p>
</a>
</li>
<li class="nav-item">
<a href="./layout/layout-custom-area.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Layout <small>+ Custom Area </small></p>
</a>
</li>
<li class="nav-item">
<a href="./layout/sidebar-mini.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Sidebar Mini</p>
</a>
</li>
<li class="nav-item">
<a href="./layout/collapsed-sidebar.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Sidebar Mini <small>+ Collapsed</small></p>
</a>
</li>
<li class="nav-item">
<a href="./layout/logo-switch.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Sidebar Mini <small>+ Logo Switch</small></p>
</a>
</li>
<li class="nav-item">
<a href="./layout/layout-rtl.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Layout RTL</p>
</a>
</li>
</ul>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="nav-icon bi bi-tree-fill"></i>
<p>
UI Elements
<i class="nav-arrow bi bi-chevron-right"></i>
</p>
</a>
<ul class="nav nav-treeview">
<li class="nav-item">
<a href="./UI/general.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>General</p>
</a>
</li>
<li class="nav-item">
<a href="./UI/icons.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Icons</p>
</a>
</li>
<li class="nav-item">
<a href="./UI/timeline.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Timeline</p>
</a>
</li>
</ul>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="nav-icon bi bi-pencil-square"></i>
<p>
Forms
<i class="nav-arrow bi bi-chevron-right"></i>
</p>
</a>
<ul class="nav nav-treeview">
<li class="nav-item">
<a href="./forms/general.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>General Elements</p>
</a>
</li>
</ul>
</li>
<li class="nav-item">
<a href="#" class="nav-link">
<i class="nav-icon bi bi-table"></i>
<p>
Tables
<i class="nav-arrow bi bi-chevron-right"></i>
</p>
</a>
<ul class="nav nav-treeview">
<li class="nav-item">
<a href="./tables/simple.html" class="nav-link">
<i class="nav-icon bi bi-circle"></i>
<p>Simple Tables</p>
</a>
</li>
</ul>
</li>
<li class="nav-header">PENGATURAN</li>
<li class="nav-item">
    <a href="{{ route('profile.buyer.index') }}" class="nav-link {{ request()->routeIs('profile.buyer.index') ? 'active' : '' }}">
        <i class="fa-solid fa-user"></i>
        <p>Profile</p>
    </a>
</li>

<li class="nav-item">
    <a href="#" class="nav-link logout-btn">
        <i class="nav-icon bi bi-box-arrow-right"></i>
        <p>Logout</p>
    </a>
</li>
</ul>
<!--end::Sidebar Menu-->
</nav>
</div>
<!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
