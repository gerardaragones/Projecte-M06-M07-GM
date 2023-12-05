<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .team-member {
            display: inline-block;
            margin: 20px;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }
        .team-member img:hover {
            transform: scale(1.1);
        }
        .team-member p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Nuestro Equipo</h1>

    <div class="team-member">
        <img src="{{ asset("img/MarianoSerious.jpg") }}" alt="Logo" onmouseover="changeImage(this)" onmouseout="changeImageBack(this)">
        <p>Gerard Aragones Cidoncha<br>Borderline</p>
    </div>

    <div class="team-member">
        <img src="{{ asset("img/MrIncreibleSerious.jpg") }}" alt="Logo" onmouseover="changeImage2(this)" onmouseout="changeImageBack2(this)">
        <p>Marc Lorenzo Oltra<br>Filantropo, putero, cocainomano...</p>
    </div>

    <script>
        function changeImage(element) {
            element.src = "{{ asset("img/MarianoFunny.jpg") }}"; // Reemplaza con la ruta de la imagen de hover
        }

        function changeImageBack(element) {
            element.src = element.getAttribute("src").replace("_hover", ""); // Restaura la imagen original
        }

        function changeImage2(element) {
            element.src = "{{ asset("img/MrIncreibleFunny.jpg") }}"; // Reemplaza con la ruta de la imagen de hover
        }

        function changeImageBack2(element) {
            element.src = element.getAttribute("src").replace("_hover", ""); // Restaura la imagen original
        }
    </script>
</body>
</html>
