<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HASDS') }} — نظام التوزيع الذكي للمساعدات الإنسانية</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/landing.css', 'resources/js/landing/main.jsx'])
</head>
<body class="antialiased">
    <div id="root"></div>
</body>
</html>
