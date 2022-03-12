<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"
          integrity="sha512-UJfAaOlIRtdR+0P6C3KUoTDAxVTuy3lnSXLyLKlHYJlcSU8Juge/mjeaxDNMlw9LgeIotgz5FP8eUQPhX1q10A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/main.css')}}">
    @yield('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
            integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('/js/main.js')}}"></script>
    @yield('js-head')
</head>
<body>
<nav class="green darken-3">
    <div class="navbar-wrapper">
        <a href="/" class="brand-logo left">phompper</a>
    </div>
</nav>
<main>
    @yield('content')
</main>
<footer class="page-footer green darken-4">
    <div class="footer-copyright">
        <div class="container grey-text text-lighten-5 center">
            &copy;2022 sudnonk12.<br>
            The software itself is licensed <a href="http://opensource.org/licenses/mit-license.php">MIT</a>, and
            using packages which were distributed in <a href="https://opensource.org/licenses/Apache-2.0">Apache License
                2.0</a> and <a href="https://opensource.org/licenses/BSD-3-Clause">The 3-Clause BSD License</a>.<br>
            The sourcecode can be found at GitHub: <a
                href="https://github.com/sudnonk/phompper">sudnonk/phompper</a>.
        </div>
    </div>
</footer>
</body>
@yield('js-tail')
</html>
