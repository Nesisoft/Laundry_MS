<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Laundry Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="container">
        @yield('content')
    </div>

    <script src="{{ asset('js/ui/toast.js') }}"></script>
    @yield('scripts')

</body>
</html>
