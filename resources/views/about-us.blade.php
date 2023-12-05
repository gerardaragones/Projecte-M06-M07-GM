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
        <img src="{{ asset("img/MarianoSerious.jpg") }}" data-video-id="0EfJJM5J-2M">
        <p id="member-1-description">Gerard Aragones Cidoncha<br>Developer</p>
    </div>

    <div class="team-member-2">
        <img src="{{ asset("img/LuffySerious.jpg") }}" data-video-id="vVT2MUHRe_k">
        <p id="member-2-description">Marc Lorenzo Oltra<br>Developer</p>
    </div>

    <script>
        // Código para cambio de imágenes y eventos mouseover/mouseout (ya existente)
        const images = document.querySelectorAll('img');
        const audioElement = new Audio("{{ asset('mp3/Mariano.mp3') }}");

        images.forEach(image => {
            const originalSrc = image.src;
            const hoverSrc = image.src.replace('Serious', 'Funny');

            image.addEventListener('mouseover', function() {
                this.src = hoverSrc;
                audioElement.play();
            });

            image.addEventListener('mouseout', function() {
                this.src = originalSrc;
                audioElement.pause();
                audioElement.currentTime = 0;
            });

            // Agregar evento de clic para abrir el pop-up con el video de YouTube
            image.addEventListener('click', function() {
                const videoId = this.dataset.videoId; // Obtener el ID del video de YouTube desde el atributo data-video-id de la imagen

                const popupWidth = 560; // Ancho del video de YouTube
                const popupHeight = 315; // Alto del video de YouTube

                const leftPosition = window.screen.width / 2 - popupWidth / 2;
                const topPosition = window.screen.height / 2 - popupHeight / 2;

                const videoPopup = window.open(
                    '',
                    'videoPopup',
                    `width=${popupWidth},height=${popupHeight},left=${leftPosition},top=${topPosition}`
                );

                videoPopup.document.body.innerHTML = `
                    <div style="position: relative;">
                        <button style="position: absolute; top: 5px; right: 5px; padding: 5px 10px; cursor: pointer;" onclick="window.close()">Close</button>
                        <iframe
                            src="https://www.youtube.com/embed/${videoId}?autoplay=1"
                            frameborder="0"
                            allowfullscreen
                            style="width: 100%; height: calc(100% - 40px);"
                        ></iframe>
                    </div>
                `;
            });
        });


    </script>
</body>
</html>
