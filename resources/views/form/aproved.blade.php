<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>La Pieza.DO | Registro</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: "Roboto", sans-serif;
        }

        body {
            width: 100vw !important;
            height: 80vh !important;
            margin: 0 !important;
        }

        .final-content {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 30% auto 0 auto;
        }

        img {
            width: 50%;
        }

        .icon {
            text-align: center;
            margin-bottom: 25px;
        }

        p {
            margin-bottom: 35px;
        }
    </style>
</head>
<body>
<div class="final-content">
    <div class="icon">
        <img src="{{ asset('public/assets/mobile-assets/images/big-green-check.png') }}" alt="">
    </div>
    <h2>Pago Completado!</h2>
    <p>Gracias por tu compra.</p>
    <img class="pieza" src="{{ asset('public/assets/img/logo_black.png') }}" alt="">
    <br>
    <br>
    <p>Redireccionando...</p>
    <br>
    <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden"> Espere...</span>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
