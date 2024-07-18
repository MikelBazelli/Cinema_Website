<?php
require "makeDB.php";
require "makeConnection.php";
require "makeDBConnection.php";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100),
    reg_date TIMESTAMP
)";

if ($conn->query($sqlUsers) === TRUE) {
    echo "Table Users created successfully with unique email constraint<br>";
} else {
    echo "Error creating table: " . $conn->error;
}





$sqlMovies = "CREATE TABLE IF NOT EXISTS movies (
    movie_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    movie_price DECIMAL(10,2)
)";

if ($conn->query($sqlMovies) === TRUE) {
    echo "Table Movies created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}



// Rooms table
$sqlRooms = "CREATE TABLE IF NOT EXISTS rooms (
    rooms_id INT   PRIMARY KEY AUTO_INCREMENT,
    RoomName VARCHAR(255) NOT NULL,
    movie_id INT ,
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id)
)";

if ($conn->query($sqlRooms) === TRUE) {
    echo "Table rooms created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}




$sqlShowtimes = " CREATE TABLE IF NOT EXISTS showtimes (
    showtime_id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT,
    room_id INT,
    showtime DATETIME NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id),
    FOREIGN KEY (room_id) REFERENCES rooms(rooms_id)
)";


if ($conn->query($sqlShowtimes) === TRUE) {
    echo "showtimes added successfully<br>";
} else {
    echo "Error updating table: " . $conn->error;
}




// seats table
$sqlSeats = "CREATE TABLE IF NOT EXISTS seats (
    seat_id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    seat_number VARCHAR(10) NOT NULL,
    status ENUM('available', 'booked') DEFAULT 'available', /*means seat will be or avaible or booked, nothing else. Used emun to secure the seat availability*/
    UNIQUE (room_id, seat_number),
    FOREIGN KEY (room_id) REFERENCES rooms(rooms_id)
)";

if ($conn->query($sqlSeats) === TRUE) {
    echo "Table seats created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}




$sqlCreateBookingsTable = "CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    rooms_id INT,
    movie_id INT,
    seat_id INT,
    showtime_id INT,
    booking_time DATETIME,
    status BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id),
    FOREIGN KEY (rooms_id) REFERENCES rooms(rooms_id),
    FOREIGN KEY (seat_id) REFERENCES seats(seat_id),
    FOREIGN KEY (showtime_id) REFERENCES showtimes(showtime_id)
)";

if ($conn->query($sqlCreateBookingsTable) === TRUE) {
    echo "Table bookings created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}
$sqlUpdateBookingsTable = "ALTER TABLE bookings ADD UNIQUE (seat_id, showtime_id)";

if ($conn->query($sqlUpdateBookingsTable) === TRUE) {
    echo "Bookings table updated successfully with unique constraint for seat and showtime<br>";
} else {
    echo "Error updating bookings table: " . $conn->error;
}


$sqlTickets = "CREATE TABLE IF NOT EXISTS tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    customer_name VARCHAR(255),
    customer_lname VARCHAR(255),
    customer_email VARCHAR(255),
    customer_phone VARCHAR(20),
    customer_address VARCHAR(255),
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
)";




if ($conn->query($sqlTickets) === TRUE) {
    echo "Table tickets created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}


$sqlWorkers = "CREATE TABLE IF NOT EXISTS workers (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100),
    reg_date TIMESTAMP
)";

if ($conn->query($sqlWorkers) === TRUE) {
    echo "Table Workers created successfully with unique email constraint<br>";
} else {
    echo "Error creating table: " . $conn->error;
}



$name = "Mikel";
$lname = "Bazelli";
$email = "mikel@gmail.com";
$pw = "12345";

// Hashing the password
$hashedPassword = password_hash($pw, PASSWORD_DEFAULT);

// SQL to insert new user
$sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $name, $lname, $email, $hashedPassword);
    $stmt->execute();

    echo "New record created successfully";
    $stmt->close();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}





$name = "Mikel";
$lname = "Bazelli";
$password = "12345";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$email1 = "mikel@admin.com";
$email2 = "mikel@worker.com";

$sql = "INSERT INTO workers (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $name, $lname, $email1, $hashedPassword);
    $stmt->execute();
    echo "First record created successfully<br>";

    $stmt->bind_param("ssss", $name, $lname, $email2, $hashedPassword);
    $stmt->execute();
    echo "Second record created successfully<br>";

    $stmt->close();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}










$movieTitle = "Crystal Quest";

$description = "Crystal, a girl with luminous purple hair and magical lineage, becomes the heroine of her own story. When her aunt, a legendary guardian of their mystical realm, vanishes, Crystal is thrust into a quest that spans both her enchanted world and the unfamiliar terrains of the human realm.
With a powerful crystal that has been her family's legacy, Crystal discovers a series of otherworldly gems, each bestowing unique abilities. ";

$imagePath = "../../images/img111.jpg";
$moviePrice = 5.99;
$roomName = "Room A";
$showtimes = ['2024-01-30 14:00:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}




$movieTitle = "Galactic Guardian";
$description = "In the boundless expanse of the cosmos, Starfire reigns as a formidable warrior, bearing resemblance to the iconic Nicki Minaj. With her trusty canine companion by her side, she navigates galaxies armed with a lethal array of weaponry. 
As Starfire battles cosmic adversaries, her courage and determination light the darkest corners of space, inspiring hope across the galaxy. \"Starfire's Odyssey\" is a thrilling journey of bravery and friendship, where a warrior's spirit knows no bounds.";

$imagePath = "../../images/img7.jpg";
$moviePrice = 5.99;
$roomName = "Room B";
$showtimes = ['2024-01-30 19:30:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}



$movieTitle = "Fairy Tale";
$description = "In the heart of the magical forest, an elf fairy named Lumina dances among ancient trees, her wings shimmering with iridescent hues. 
Guided by moonlit whispers, she weaves spells of enchantment, awakening the forest\'s hidden wonders. Beneath the canopy, Lumina\'s laughter echoes, carrying the secrets of the enchanted realm. Her presence kindles the forest\'s magic, weaving dreams into reality, where every leaf tells a story of mystical delight.";


$imagePath = "../../images/img9.jpg";
$moviePrice = 5.99;
$roomName = "Room A";
$showtimes = ['2024-01-30 14:30:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}




$movieTitle = " Shadow Assassin";
$description = "\"Shadow Assassin\" follows the enigmatic journey of Elysia, a mysterious woman navigating a magical realm armed with a celestial blade and lethal skills. As an assassin, she straddles the line between light and darkness, embarking on a treacherous journey filled with ancient secrets and formidable adversaries. With each encounter, Elysia confronts her destiny, forging her path through the shadows, where magic and mayhem collide in a captivating tale of courage and cunning.";


$imagePath = "../../images/img5.jpg";
$moviePrice = 5.99;
$roomName = "Room D";
$showtimes = ['2024-02-12 17:45:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}



$movieTitle = "Midnight Love";
$description = 'In "Midnight Love," we follow Kaito, an anime boy with black hair, as he navigates the intricacies of romance to find the girl who captivates his heart. Amidst the city lights and moonlit nights, Kaito\'s search for true love unfolds with each tender moment and heartfelt gesture. Through passion and longing, Kaito discovers the timeless allure of love\'s embrace, leading him on a journey of heartfelt connection and enduring devotion.';


$imagePath = "../../images/dark.png";
$moviePrice = 8.99;
$roomName = "Room A";
$showtimes = ['2024-04-22 17:45:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}


$movieTitle = "Super Chronicles";
$description = '"Star Chronicles" follows Paytas, a blonde-haired pop star clad in vibrant pink, who leads a double life as both a beloved entertainer and a galaxy savior. By day, Paytas dazzles audiences with infectious melodies, spreading joy and hope. However, when darkness threatens the cosmos, Paytas transforms into a celestial warrior, wielding the power of stardust to protect the galaxy from impending doom. This captivating tale intertwines fame and heroism, as Paytas navigates the complexities of her extraordinary destiny.';


$imagePath = "../../images/img8.jpg";
$moviePrice = 12.99;
$roomName = "Room B";
$showtimes = ['2024-04-22 17:45:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}


$movieTitle = "Agent 22";
$description = '"Agent 22" delves into the clandestine world of warfare and espionage, where a formidable woman soldier navigates the treacherous landscape of combat as a covert agent. With unwavering determination and unparalleled skill, Agent 22 infiltrates enemy lines, uncovering secrets and thwarting threats to national security. Amidst the chaos of battle, she grapples with the moral complexities of duty and loyalty, confronting the harsh realities of war while striving to uphold justice and honor. As the stakes escalate and danger lurks at every turn, Agent 22 embarks on a perilous mission that will test her resolve and redefine the meaning of sacrifice in the pursuit of peace.';


$imagePath = "../../images/img6.jpg";
$moviePrice = 12.99;
$roomName = "Room C";
$showtimes = ['2024-04-22 17:45:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}



$movieTitle = "Blond Gold";
$description = '"Blond Gold:" is about the journey of a golden-haired guy seeking a girlfriend to alleviate his loneliness. Navigating through bustling cityscapes and tranquil landscapes, he encounters challenges and learns valuable lessons about friendship, love, and the essence of companionship. Through laughter and tears, he discovers that the greatest treasure lies not in the search itself, but in the heartfelt connections forged along the way.';


$imagePath = "../../images/blonde.png";
$moviePrice = 12.99;
$roomName = "Room A";
$showtimes = ['2024-01-12 20:30:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}


$movieTitle = "Magicika";
$description = '"Magika:," follow Astra, a purple-haired magical girl, as she harnesses extraordinary powers to combat darkness. With unwavering courage, Astra battles malevolent forces, seeking to restore balance to her world. As she confronts adversaries and uncovers her own abilities, Astra embarks on a journey of self-discovery and heroism. In a realm ruled by magic, Astra\'s quest for light becomes a beacon of hope against encroaching shadows.';


$imagePath = "../../images/anime.jpg";
$moviePrice = 9.99;
$roomName = "Room A";
$showtimes = ['2024-01-12 21:30:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}



$movieTitle = "AMENLE";
$description = '"ANMELE: Exploring the Astral Frontier" charts the epic voyage of humanity\'s bold venture into the depths of space, led by the intrepid crew of the spacecraft ANMELE. Tasked with exploring a distant and enigmatic planet, the crew encounters wonders and challenges beyond imagination as they navigate the cosmic expanse. Amidst the unknown dangers and breathtaking discoveries, bonds are forged, and the human spirit soars as they unravel the mysteries of the universe. With each step into the astral frontier, the crew of ANMELE embarks on a voyage of exploration, enlightenment, and the pursuit of the ultimate truth awaiting them among the stars.';


$imagePath = "../../images/img2.jpg";
$moviePrice = 9.99;
$roomName = "Room A";
$showtimes = ['2024-01-12 21:30:00'];

$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}




$movieTitle = "EYE";
$description = '"EYE: Mystique Unveiled" follows the captivating journey surrounding a solitary blue mystical eye, coveted for its untold secrets and unimaginable power. As seekers and guardians embark on a perilous quest to decipher its mysteries, bonds are forged and betrayals exposed amidst ethereal landscapes and treacherous terrain. In the heart of the mystique lies the power to reshape worlds and awaken dormant forces, shaping destinies and challenging the very fabric of reality.';


$imagePath = "../../images/eye.jpg";
$moviePrice = 9.99;
$roomName = "Room D";
$showtimes = ['2024-01-12 23:30:00'];



$sqlMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES (?, ?, ?, ?)";
$stmtMovie = $conn->prepare($sqlMovie);

if ($stmtMovie) {
    $stmtMovie->bind_param("sssd", $movieTitle, $description, $imagePath, $moviePrice);
    $stmtMovie->execute();
    $movieID = $conn->insert_id;
    $stmtMovie->close();

    $sqlRoom = "INSERT INTO rooms (movie_id, Roomname) VALUES (?, ?)";
    $stmtRoom = $conn->prepare($sqlRoom);
    $stmtRoom->bind_param("is", $movieID, $roomName);
    $stmtRoom->execute();
    $roomID = $conn->insert_id;
    $stmtRoom->close();

    $sqlShowtime = "INSERT INTO showtimes (movie_id, room_id, showtime) VALUES (?, ?, ?)";
    $stmtShowtime = $conn->prepare($sqlShowtime);

    foreach ($showtimes as $showtime) {
        $stmtShowtime->bind_param("iis", $movieID, $roomID, $showtime);
        $stmtShowtime->execute();
    }

    $stmtShowtime->close();

    echo "New movie and associated data created successfully";
} else {
    echo "Error: " . $sqlMovie . "<br>" . $conn->error;
}
