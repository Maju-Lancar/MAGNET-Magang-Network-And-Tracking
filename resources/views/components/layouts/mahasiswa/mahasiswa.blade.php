<!DOCTYPE html>
<html lang="{{ str_replace('', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="bg-[#E0F7FA]">
    <div class="flex min-h-screen">
        <x-mahasiswa.sidebar />

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