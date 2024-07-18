<!-- THIS PAGE INCLUDES THE WHOLE CAROUSEL AND ITS BUTTONS -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineMax</title>

    <link rel="icon" type="image/x-icon" href="../images/logo.png"><!--Favicon-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> <!--Bootstrap5-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Search bar icon-->

    <link rel="stylesheet" href="../css/style.css"> <!-- link to style.css-->
</head>

<body>

    <!-- Bootstrap classes to create CAROUSEL -->
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../../images/img131.jpg" class="d-block w-100" alt="...">

                <div class="carousel-caption d-md-block">
                    <form action="moreMovies2.php">
                        <button class="button">View Movies </button>
                    </form>


                </div>
            </div>

            <div class="carousel-item">
                <img src="../../images/img141.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-md-block">

                    <form action="moreMovies2.php">
                        <button class="button"> View Movies </button>
                    </form>

                </div>
            </div>

            <div class="carousel-item">
                <img src="../../images/batImg3.png" class="d-block w-100" alt="...">
                <div class="carousel-caption d-md-block">

                    <form action="moreMovies2.php">
                        <button class="button"> View Movies </button>
                    </form>

                </div>
            </div>
        </div>

        <!-- CAROUSEL BUTTONS from bootstrap library -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>



    <div class="container drop">
        <div class="btn-group mt-5">
            <button class="btn btn-secondary btn-lg downButton" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                ALL MOVIES
            </button>
        </div>
    </div>



    <!-- Ceating a GAP -->
    <div style="margin-top:150px ;">
    </div>





    <!-- Making the CAROUSEL FUCNTCTION, to turn every 2seconds -->
    <script>
        const myCarouselElement = document.querySelector('#myCarousel')

        const carousel = new bootstrap.Carousel(myCarouselElement, {
            interval: 2000,
            touch: false
        })
    </script>

    <!-- BOOTSTRAP JS LINK -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>