<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <style>
        header {
            background-color: #433528;
            color: #fff;
            padding: 20px;
        }

        nav ul {
            list-style: none;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #dab284; /* Color al hacer hover */
        }

        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: linear-gradient(to bottom, #433528, #664d38);
            min-height: calc(100vh - 70px); /* Altura del viewport menos la altura del header */
            color: #fff;
        }

        .team-member-1 {
            display: inline-block;
            margin: 20px;
        }
        .team-member-1 img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
            filter: grayscale(100%);
        }
        .team-member-1 img:hover {
            transform: scaleY(-1);
            filter: grayscale(0%) contrast(150%);
        }
        .team-member-1 p {
            margin: 10px 0;
        }

        .team-member-2 {
            display: inline-block;
            margin: 20px;
        }
        .team-member-2 img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
            filter: grayscale(100%);
        }
        .team-member-2 img:hover {
            transform: scaleX(-1);
            filter: grayscale(0%) contrast(150%);
        }
        .team-member-2 p {
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <header>
        <nav>
            <ul>
                <li><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li><a href="{{ url('/posts') }}">Posts</a></li>
                <li><a href="{{ url('/places') }}">Places</a></li>
                <li><a href="{{ route('about.us') }}">About Us</a></li>
            </ul>
        </nav>
    </header>

    <h1>Nuestro Equipo</h1>

    <div class="team-member-1">
        <img src="{{ asset("img/MarianoSerious.jpg") }}">
        <p>Gerard Aragones Cidoncha<br>Borderline</p>
    </div>

    <div class="team-member-2">
        <img src="{{ asset("img/LuffySerious.jpg") }}">
        <p>Marc Lorenzo Oltra<br>...</p>
    </div>

    <script>
        const images = document.querySelectorAll('img');

        images.forEach(image => {
            const originalSrc = image.src;
            const hoverSrc = image.src.replace('Serious', 'Funny');

            image.addEventListener('mouseover', function() {
                this.src = hoverSrc;
            });

            image.addEventListener('mouseout', function() {
                this.src = originalSrc;
            });
        });


    </script>
</body>
</html>
