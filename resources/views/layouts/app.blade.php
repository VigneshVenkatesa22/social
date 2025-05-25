<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Medium Clone') }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.1.0/ckeditor5.css" />

</head>

<body class="bg-gray-100 text-gray-900">

    {{-- Hide navbar on login and register --}}
    @unless (request()->routeIs('login') || request()->routeIs('register'))
        @include('partials.navbar')
    @endunless

    <main class="container mx-auto mt-6">
        @yield('content')
    </main>
@yield('scripts')

</body>

</html>
