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
        <img src="{{ asset("img/MarianoSerious.jpg") }}" alt="Logo" onmouseover="changeImage(this, '{{ asset("img/MarianoFunny.jpg") }}')" onmouseout="changeImageBack(this, '{{ asset("img/MarianoSerious.jpg") }}')">
        <p>Gerard Aragones Cidoncha<br>Borderline</p>
    </div>

    <div class="team-member">
        <img src="{{ asset("img/MrIncreibleSerious.jpg") }}" alt="Logo" onmouseover="changeImage(this, '{{ asset("img/MrIncreibleFunny.jpg") }}')" onmouseout="changeImageBack(this, '{{ asset("img/MrIncreibleSerious.jpg") }}')">
        <p>Marc Lorenzo Oltra<br>Filantropo, putero, cocainómano...</p>
    </div>

    <script>
        function changeImage(element, newImage) {
            element.classList.add('hover-effect');
            element.src = newImage;
        }

        function changeImageBack(element, originalImage) {
            element.classList.remove('hover-effect');
            element.src = originalImage;
        }
    </script>
</body>
</html>
