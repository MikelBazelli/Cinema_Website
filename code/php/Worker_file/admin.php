<?php
session_start();
// ONLY ADMIN can view this page
if (isset($_SESSION["workeremail"]) && strpos($_SESSION["workeremail"], '@admin.com') !== false) {

    require "../Tables-MakeDB/makeDBConnection.php";
    require "../../html/worker1.php";
    include "insertFormData.php";



?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../../css/woker1.css">


        <title>Admin</title>
        <style>
            .form-grid-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 70px;
                padding: 20px;
                margin: auto;
                background-color: #c9c9c9;
                width: 90%;

            }

            .delete {
                background-color: #f44336;
            }

            .delete:hover {
                background-color: #d32f2f;

            }

            .form-container {
                margin-top: 20px;
                background-color: #f0f0f0;
                padding: 20px;
                border-radius: 10px;
                width: 85%;
                height: min-content;
                margin-bottom: 60px;

            }

            table {
                width: 80%;
                margin: auto;
                margin-top: 60px;
                text-align: center;


            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            /* media tags for responsiveness */

            @media (max-width: 1100px) {
                .form-grid-container {
                    grid-template-columns: 1fr;
                }


            }

            @media (max-width: 1100px) {
                .form-grid-container {
                    grid-template-columns: 1fr;
                }

                table {
                    width: 100%;
                }

                th,
                td {
                    padding: 2px;
                    font-size: 14px;

                }
            }

            @media (max-width: 660px) {


                table {
                    width: 100%;
                }

                th,
                td {
                    padding: 2px;
                    font-size: 12px;
                }
            }
        </style>
    </head>

    <body>
        <div class="form-grid-container">

            <div class="form-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-style">
                    <h2>Delete Workers</h2>

                    <label for="name" class="form-label">Worker ID: </label>
                    <input type="text" id="worker_id" name="worker_id" class="form-input">

                    <input type="submit" value="Delete" name="delete1" class="form-submit delete">

                </form>
            </div>

            <div class="form-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-style">
                    <h2>Delete Users</h2>

                    <label for="id" class="form-label">User ID: </label>
                    <input type="text" id="id" name="id" class="form-input">

                    <input type="submit" value="Delete" name="delete2" class="form-submit delete">
                </form>
            </div>



        </div>

        <!-- Printing all users, Admin can only view them -->

        <table>
            <tr>
                <td style='border:1px; border-style:solid; border-color:black'>User Id</td>
                <td style='border:1px; border-style:solid; border-color:black'>First Name</td>
                <td style='border:1px; border-style:solid; border-color:black'>Last Name</td>
                <td style='border:1px; border-style:solid; border-color:black'>Email</td>
                <td style='border:1px; border-style:solid; border-color:black'>Reg_date</td>

            </tr>
            <?php
            require "../Tables-MakeDB/makeDBConnection.php";


            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr style='border:1px; border-style:solid; border-color:black'>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["id"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["firstname"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["lastname"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["email"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["reg_date"] . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "0 results";
            }
            ?>
        </table>

        <!-- Admin can view all workers too -->
        <table>
            <tr>
                <td style='border:1px; border-style:solid; border-color:black'>Worker Id</td>
                <td style='border:1px; border-style:solid; border-color:black'>First Name</td>
                <td style='border:1px; border-style:solid; border-color:black'>Last Name</td>
                <td style='border:1px; border-style:solid; border-color:black'>Email</td>
                <td style='border:1px; border-style:solid; border-color:black'>Reg_date</td>

            </tr>
            <?php
            require "../Tables-MakeDB/makeDBConnection.php";


            $sql = "SELECT * FROM workers";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr style='border:1px; border-style:solid; border-color:black'>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["id"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["firstname"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["lastname"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["email"] . "</td>";
                    echo "<td style='border:1px; border-style:solid; border-color:black'>" . $row["reg_date"] . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "0 results";
            }
            ?>
        </table>

        <section style="margin-bottom: 50px;"></section>

    </body>

    </html>


<?php
} else {
    // Redirect if not logged in
    echo "<script>alert('Only admin can view this page!'); window.location.href = 'worker.php';</script>";
}
