<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Mesa Ludica | Tienda de Juegos de Mesa</title>
    <meta name="description" content="Tienda online de juegos de mesa modernos para familias, amigos y jugadores expertos.">
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#A31212',
                        'primary-dark': '#7A0D0D',
                        accent: '#C62828',
                        'beige-light': '#F5E6D3',
                        'beige-medium': '#EAD2B3',
                        'yellow-soft': '#F2B544',
                        'warm-white': '#FFF8F0',
                        'text-soft': '#6B5E57',
                        'soft-black': '#2B1E1E'
                    }
                }
            }
        };
    </script>
</head>

<body class="min-h-screen bg-beige-light text-soft-black antialiased">
    <header class="border-b border-primary-dark bg-primary text-warm-white">
        <nav class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-5 md:flex-row md:items-center md:justify-between" aria-label="Navegacion principal">
            <a class="inline-flex items-center gap-4 self-start px-2 py-1" href="index.php?seccion=home">
                <img class="h-14 w-14 object-cover md:h-16 md:w-16" src="imgs/logo-2.png" alt="Logo de Galmir">
                <span class="text-2xl font-bold tracking-wide">Galmir</span>
            </a>

            <ul class="flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                <li>
                    <a class="inline-flex rounded-lg border px-4 py-2.5 text-base font-semibold <?= $seccionActual === 'home' ? 'border-accent bg-accent text-warm-white' : 'border-warm-white/40 bg-warm-white/10 text-warm-white hover:bg-yellow-soft hover:text-soft-black' ?>" href="index.php?seccion=home" <?= $seccionActual === 'home' ? ' aria-current="page"' : '' ?>>Inicio</a>
                </li>
                <li>
                    <a class="inline-flex rounded-lg border px-4 py-2.5 text-base font-semibold <?= $seccionActual === 'listado' ? 'border-accent bg-accent text-warm-white' : 'border-warm-white/40 bg-warm-white/10 text-warm-white hover:bg-yellow-soft hover:text-soft-black' ?>" href="index.php?seccion=listado" <?= $seccionActual === 'listado' ? ' aria-current="page"' : '' ?>>Listado</a>
                </li>
                <li>
                    <a class="inline-flex rounded-lg border px-4 py-2.5 text-base font-semibold <?= $seccionActual === 'contacto' ? 'border-accent bg-accent text-warm-white' : 'border-warm-white/40 bg-warm-white/10 text-warm-white hover:bg-yellow-soft hover:text-soft-black' ?>" href="index.php?seccion=contacto" <?= $seccionActual === 'contacto' ? ' aria-current="page"' : '' ?>>Contacto</a>
                </li>
            </ul>

            <form class="flex w-full max-w-sm items-center gap-2 self-start md:self-auto" action="index.php" method="get" role="search" aria-label="Buscar productos">
                <input type="hidden" name="seccion" value="listado">
                <label class="sr-only" for="busqueda-header">Buscar</label>
                <input class="w-full rounded-lg border border-warm-white/40 bg-warm-white px-4 py-2.5 text-base text-soft-black placeholder:text-text-soft focus:border-yellow-soft focus:outline-none" id="busqueda-header" name="q" type="search" placeholder="Buscar juego...">
                <button class="rounded-lg border border-warm-white/40 bg-warm-white/10 px-4 py-2.5 text-base font-semibold text-warm-white hover:bg-yellow-soft hover:text-soft-black" type="submit">Buscar</button>
            </form>
        </nav>
    </header>
    <main class="mx-auto w-full max-w-6xl px-4 py-6 md:py-8">
