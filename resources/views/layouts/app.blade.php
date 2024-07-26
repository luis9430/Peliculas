<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body>
<div class="container mt-5">
    @yield('content')
</div>
@vite('resources/js/app.js')
@yield('scripts')
</body>
</html>
