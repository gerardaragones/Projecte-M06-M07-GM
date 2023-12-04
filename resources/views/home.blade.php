<x-geomir-layout>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>App Layout</title>

<!-- Enlace al archivo CSS -->
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">


</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Acerca de</a></li>
            <li><a href="#">Servicios</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="hero">
      <img src="{{ asset("img/Logo-Marron.png") }}" alt="Logo">
      <h1>Bienvenido a nuestra página</h1>
      <p>Descripción breve de tu sitio</p>
      <a href="#" class="btn">Ver más</a>
    </section>
</main>

</body>

</x-geomir-layout>