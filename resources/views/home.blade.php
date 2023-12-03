<x-geomir-layout>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>App Layout</title>

<!-- Enlace al archivo CSS -->
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

</head>
<body>
<div class="app-container">
  <div class="header">
    <img class="logob" src="{{ asset("img/Logo-Marron.png") }}" alt="Logo">
    <h1>GeoMir</h1>
    <nav class="menu">
      <button>🗄️ FILES</button>
      <button>📑 POSTS</button>
      <button>📍 PLACES</button>
    </nav>
  </div>

  <div class="main-content">
    <!-- Main content goes here -->
  </div>

  <div class="footer">
  </div>
</div>
</body>
</x-geomir-layout>