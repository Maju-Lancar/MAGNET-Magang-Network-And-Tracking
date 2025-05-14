<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="bg-[linear-gradient(245deg,_#AEE1FC_8.6%,_#FFF_82.92%)]">
    {{ $slot }}
    
    @fluxScripts
</body>

</html>