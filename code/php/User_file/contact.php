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
    <title>Contact</title>

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/navBar.css">

        <!-- Using a js library to give up a map, called leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />


        <style>
            .fa-phone,
            .fa-map-marker,
            .fa-envelope {
                color: #FD8F1E;
                font-size: 40px
            }

            .contact-container {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                margin: auto;
                margin-top: 150px;
            }

            .box {
                background-color: white;
                margin: auto;
                text-align: center;
                border-radius: 10px;
                width: 280px;
                height: 180px;
            }

            .inner {
                margin-top: 30px;
            }

            /* Changing where each column will start. By using repeat(3, 1fr),
             this had a huge gap between elements where I could't make smaller 
             so I added extra columns and changed position so that its more pretty for the user
              */

            .box1 {
                grid-column-start: 2;
            }

            .box2 {
                grid-column-start: 4;
            }

            .box3 {
                grid-column-start: 6;
            }

            #map {
                width: 700px;
                margin: auto;
                margin-top: 40px;

            }


            .leaflet-top,
            .leaflet-bottom {
                position: absolute;
                z-index: 500;
                /*Maps + AND - buttons to zoom in and zoom out, 
                used to interfear with navbar, to fix this,
                 navbar has higher z-index, "floats", where the icons get hidden, which is what we want */
                pointer-events: none;
            }

            .box {
                margin-bottom: 20px;
            }

            /* Changing styling in smaller size screens, to make it responsive */
            @media screen and (max-width: 900px) {
                .contact-container {
                    grid-template-columns: repeat(2, 1fr);
                    /*This means, 1fr 1fr. We repeat it 2 times*/
                    grid-template-rows: repeat(2, 1fr);
                    gap: 10px;
                }

                .box1,
                .box2,
                .box3 {
                    grid-column-start: auto;
                    /*Above we changed where each column will start, here we use the default*/

                }

                #map {
                    width: 80%;
                    margin: auto;
                    margin-top: 40px;

                }

            }

            /* Small screens (e.g., smartphones): 1 column */
            @media screen and (max-width: 600px) {
                .contact-container {
                    grid-template-columns: 1fr;
                }

                .box1,
                .box2,
                .box3 {
                    grid-column-start: auto;
                }


                #map {
                    width: 400px;
                    margin: auto;
                    margin-top: 40px;

                }

            }
        </style>
    </head>

<body style="background-color: black;">


    <!-- The map from the library -->
    <div id="map" style="height: 400px;"></div>


    <!-- Making grid responsive boxes -->
    <div class="contact-container">
        <div class="box box1">
            <div class="inner">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <h5 style="color: black;">Phone Number</h5>
                <h6>2810 789 123</h6>
            </div>
        </div>


        <div class="box box2">
            <div class="inner">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <h5 style="color: black;">Our main office</h5>
                <h6>Heaven street 22, 71000</h6>
            </div>
        </div>


        <div class="box box3">
            <div class="inner">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <h5 style="color: black;">Main</h5>
                <h6>cinemax@info.com</h6>
            </div>
        </div>

    </div>


    <!-- Footer -->
    <div class="container" style="margin-top: 90px;">
        <footer class="py-3 my-4">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="userViewMovies.php" class="nav-link px-2 text-white">Home</a></li>
                <li class="nav-item"><a href="moreMovies2.php" class="nav-link px-2 text-white">Movies</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link px-2 text-white">Contact us</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link px-2 text-white">About</a></li>
            </ul>
            <p class="text-center text-white">© <?php echo date("Y"); ?> Cinemax, Inc</p>
        </footer>
    </div>


    <script>
        // Map functionality from leaflet library, found on the website

        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([38.04140609025604, 23.816966968344254], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([38.04140609025604, 23.816966968344254]).addTo(map);
            marker.bindPopup('Marker Name: Marousi CINEMAX').openPopup(); //added a location,(FAKE)
        });
    </script>
    <!-- Link for map responsiveness-->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>




</body>

</html>