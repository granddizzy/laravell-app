<header class="bg-primary text-white text-center py-3">
    <h1>Моя Шапка</h1>
    <nav>
        @if(Auth::check())
            <span>Привет, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
            <a class="text-white" href="{{ route('user.logout') }}">Выход</a>
        @else
            <a class="text-white" href="{{ route('register') }}">Регистрация</a>
            <a class="text-white" href="{{ route('login') }}">Вход</a>
        @endif
    </nav>
</header>
