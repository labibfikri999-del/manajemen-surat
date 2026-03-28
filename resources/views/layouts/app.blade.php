<!DOCTYPE html>
<html lang="en" class="overscroll-y-none">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manajemen Surat')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
</head>
<body class="bg-gray-100 min-h-screen overscroll-y-none">
    <div class="container mx-auto py-8">
        @yield('content')
    </div>

    <!-- Include Global Chatbot Widget -->
    @include('components.chatbot-widget')
</body>
</html>
