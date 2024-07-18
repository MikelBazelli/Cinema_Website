<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name
require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/navBar.php";
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Cinemax</title>

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/navBar.css">



        <style>
            .outer {
                margin: auto;
                margin-top: 20px;
                max-width: 53%;
                padding: 30px;
                text-align: justify;
                border-radius: 10px;
                border: 2px solid white;
                background-color: transparent;
                backdrop-filter: blur(12px);
            }

            .content {
                max-width: 90%;
                margin: auto;
                color: white;
            }

            @media (max-width: 1200px) {
                .outer {
                    max-width: 80%;
                }

                p {
                    font-size: 16px;
                }

                h2 {
                    font-size: 24px;
                }

                .title {
                    font-size: 28px;
                }
            }


            .title {
                color: red;
                font-size: 30px;
                font-weight: 400;

            }
        </style>
    </head>

<body style="background-color: black;">
    <div class="outer">
        <div class="content">
            <h2>Welcome to<span class="title"> CINEMAX</span></h2>
            <p>Step into the world of Cinemax, where the magic of cinema has been enchanting audiences in Greece for
                decades. Discover our rich history, commitment to excellence, and the extraordinary experiences that await
                you.</p>

            <h2>Our Cinematic Legacy</h2>
            <p>Embark on a journey through time as we share the story of Cinemax's inception. Born out of a love for
                storytelling, we have evolved into a cornerstone of the Greek cinema landscape. Learn how our legacy
                continues to shape the local entertainment scene.</p>

            <h2>Nestled in Greece's Heart</h2>
            <p>Explore the heart of Greece through the lens of Cinemax. Located in the heart of Athens, our theaters have
                become beloved gathering spots for movie enthusiasts of all ages. Immerse yourself in our commitment to
                providing a diverse selection of films, from the latest blockbusters to timeless classics.</p>

            <h2>Your Cinematic Experience</h2>
            <p>Welcome to the future of cinema at Cinemax. Our focus is on the technology that makes every movie special.
                Each time you visit us, you'll enjoy bright, clear images on our big screens and sound so real, it's like
                you're part of the story. Experience the difference at Cinemax, where we make every movie showing
                outstanding.</p>

            <h2>More Than Movies</h2>
            <p>At Cinemax, we believe in the power of community. Explore how our cinema has become a cultural hub where
                friends and family come together to create lasting memories. Learn about the shared experiences that make
                Cinemax more than just a place to watch movies.</p>

            <h2>Devoted to Your Enjoyment</h2>
            <p>Explore our commitment to exceptional customer service, a key aspect that sets Cinemax apart. Our team is
                devoted to ensuring that your visit is consistently a delightful experience. Whether you're a seasoned movie
                enthusiast or a first time visitor, we invite you to join us as we continue to provide the best in
                entertainment.</p>

            <h2>Thank You for Being a Part of Our Story</h2>
            <p>As we express our gratitude, we invite you to become a part of our cinematic journey. Thank you for choosing
                Cinemax. We look forward to serving you with the finest in entertainment for years to come. Sit back, relax,
                and let the magic of movies come to life at Cinemax.</p>
        </div>
    </div>



    <div class="container" style="margin-top: 90px;">
        <footer class="py-3 my-4">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="userViewMovies.php" class="nav-link px-2 text-white">Home</a></li>
                <li class="nav-item"><a href="moreMovies2.php" class="nav-link px-2 text-white">Movies</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link px-2 text-white">Contact us</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link px-2 text-white">About</a></li>
            </ul>

            <!-- making the  copyright year responsive by using php date method and adding the (Y)ear that server has -->
            <p class="text-center text-white">© <?php echo date("Y"); ?> Cinemax, Inc</p>

        </footer>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>