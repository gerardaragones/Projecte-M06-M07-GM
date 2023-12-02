<x-geomir-layout>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>App Layout</title>
<style>
  body, html {
    height: 100%;
    font-family: Arial, sans-serif;
  }

  .logob {
    height: 15vw;
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #FFF7E7;
  }

  .menu button {
    padding: 10px 20px;
    margin-left: 10px; /* Espacio entre los botones */
    border: none;
    border-radius: 5px;
    background-color: #3498db; /* Cambia el color de fondo según tu diseño */
    color: white;
    cursor: pointer;
  }

  .footer {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px;
    background-color: #FFF7E7;
  }

  .footer button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
  }

</style>
</head>
<body>
<div class="app-container">
  <div class="header">
    <img class="logob" src="{{ asset("img/Logo-Marron.png") }}" alt="Logo">
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
    <button>Add</button>
    <button>Like</button>
    <button>Share</button>
  </div>
</div>
</body>
</x-geomir-layout>