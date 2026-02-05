<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
<!-- Contenedor full-width para login mitad/mitad -->
<div class="bg-background flex min-h-screen flex-row items-stretch">
    {{ $slot }}
</div>

@fluxScripts
</body>
</html>
