<!DOCTYPE html>
<html lang="{{ str_replace('', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="bg-magnet-frost-mist">
    <div class="flex min-h-screen">
        <x-admin.sidebar />

        <div class="w-full">
            <x-topbar />
            <div class="p-8">
                @yield('content')

                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>