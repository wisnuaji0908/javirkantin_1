<!--begin::Sidebar-->
<aside class="main-sidebar app-sidebar shadow-lg">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <a href="./index.html" class="brand-link">
            <img src="{{ asset('image/logo.png') }}" alt="Logo" class="brand-image">
            <span class="brand-text">Buyer</span>
        </a>
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-3">
            <ul class="sidebar-menu">
                <li class="nav-item">
                    <a href="{{ route('buyer.shop.index') }}" class="nav-link {{ request()->routeIs('buyer.shop.index') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <p>Toko/Warung</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('buyer.chat.index') }}" class="nav-link {{ request()->routeIs('buyer.chat.index') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        <p>Chat</p>
                        @php
                            $unreadCount = \App\Models\Chat::where('buyer_id', Auth::id())
                                ->where('sender_id', '!=', Auth::id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                        <span class="badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('buyer.cart.index') }}" class="nav-link {{ request()->routeIs('buyer.cart.index') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Keranjang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('buyer.orders.index') }}" class="nav-link {{ request()->routeIs('buyer.orders.index') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <p>Pesanan</p>
                    </a>
                </li>

                <li class="nav-header">PENGATURAN</li>

                <li class="nav-item">
                    <a href="{{ route('profile.buyer.index') }}" class="nav-link {{ request()->routeIs('profile.buyer.index') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

<!-- Toggle Button for Mobile -->
<button class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<style>
    .main-sidebar {
        width: 260px;
        background: #F7B733;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding: 15px;
        transition: all 0.3s ease;
    }
    .main-content {
        margin-left: 260px;
        padding: 20px;
        width: calc(100% - 260px);
        transition: margin-left 0.3s ease-in-out;
    }
    .sidebar-brand {
        text-align: center;
        padding: 15px 0;
    }
    .sidebar-brand .brand-link {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    .sidebar-brand .brand-image {
        width: 40px;
        height: auto;
        margin-right: 10px;
    }
    .sidebar-brand .brand-text {
        font-size: 20px;
        font-weight: bold;
        color: #FFF;
    }
    .sidebar-wrapper {
        overflow-y: auto;
        height: calc(100vh - 100px);
    }
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidebar-menu .nav-item {
        margin-bottom: 10px;
    }
    .sidebar-menu .nav-link {
        display: flex;
        align-items: center;
        padding: 12px;
        text-decoration: none;
        font-size: 16px;
        color: #FFF;
        border-radius: 8px;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .sidebar-menu .nav-link i {
        margin-right: 12px;
        font-size: 18px;
    }
    .sidebar-menu .nav-link:hover,
    .sidebar-menu .nav-link.active {
        background: #FF9800;
        transform: translateX(5px);
    }
    .sidebar-menu .nav-header {
        font-size: 14px;
        color: #FFF;
        padding: 10px 15px;
        text-transform: uppercase;
        font-weight: bold;
        margin-top: 15px;
    }
    .badge {
        background: red;
        color: white;
        border-radius: 50%;
        padding: 5px 10px;
        font-size: 0.8rem;
        margin-left: auto;
    }
    @media (max-width: 768px) {
        .main-sidebar {
            width: 260px;
            position: fixed;
            height: 100%;
            left: -260px;
            transition: left 0.3s ease-in-out;
            z-index: 999;
        }
        .main-sidebar.open {
            left: 0;
        }
        .main-content {
            margin-left: 0;
            width: 100%;
        }
        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            cursor: pointer;
            font-size: 24px;
            background: none;
            border: none;
            color: black;
        }
    }
</style>

<script>
    function toggleSidebar() {
        document.querySelector('.main-sidebar').classList.toggle('open');
    }
</script>
