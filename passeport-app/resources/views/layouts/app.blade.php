<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('includes.head')
    </head>
    <body>
        <div id="app">
            <header>
                @include('includes.header')
            </header>
            <main class="container py-4">
                @yield('content')
            </main>
            <footer>
                @include('includes.footer')
            </footer>
        </div>
    </body>
    @stack('scripts')
</html>
