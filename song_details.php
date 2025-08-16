<!-- Css Styles -->
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
<link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
<link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
<link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
<link rel="stylesheet" href="css/style.css?v=2">

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "megapod");

// Get song id from URL
$song_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch song details
$song = null;
if ($song_id > 0) {
    $sql = "SELECT * FROM songs WHERE id = $song_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $song = $result->fetch_assoc();
    }
}

// Fetch reviews
$reviews = [];
if ($song_id > 0) {
    $sql_reviews = "SELECT * FROM reviews WHERE song_id = $song_id ORDER BY created_at DESC";
    $reviews = $conn->query($sql_reviews);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Song Details</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px;
        }

        .song-left {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .song-left img {
            max-width: 95%;
            border-radius: 15px;
            box-shadow: 0 0 15px #6763fd;
        }

        .song-right {
            flex: 1;
            padding: 10px;
        }

        .song-right h1 {
            color: #6763fd;
            font-size: 50px;
        }

        audio {
            width: 100%;
            margin-top: 15px;
        }

        .review-section {
            margin: 40px auto;
            width: 80%;
            background: #111;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px #6763fd;
        }

        .review-section h2 {
            color: #6763fd;
            margin-bottom: 20px;
        }

        .review-form {
            margin-bottom: 30px;
        }

        .review-form input,
        .review-form textarea,
        .review-form button {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        .review-form input,
        .review-form textarea {
            background: #222;
            color: #fff;
        }

        .review-form button {
            background: #6763fd;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        .review-form button:hover {
            background: #4e48d5;
        }

        .review {
            border-bottom: 1px solid #333;
            padding: 10px 0;
        }

        .review strong {
            color: #6763fd;
        }

        .song-player {
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(103, 99, 253, 0.6);
            margin-bottom: 40px;
        }

        .song-player h2 {
            color: #6763fd;
            margin-bottom: 10px;
            font-size: 32px !important
        }

        audio {
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <!-- Mini Header -->
    <header
        style="background-color: black; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255, 255, 255, 0.3);">
        <!-- Logo -->
        <div style="display: flex; align-items: center;">
            <a href="index.php"><img src="./img/logo.png" alt="MEGAPOD" style="height: 35px; margin-left: 15px;"></a>
        </div>

        <!-- Search Form -->
        <div class="col-lg-4 d-flex justify-content-end align-items-center"
            style="margin-top: 15px; margin-right: 15px;">
            <div class="header__right__search">
                <form action="search.php" method="GET">
                    <input type="text" name="q" placeholder="Search and hit enter..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
    </header>

    <?php if ($song): ?>
        <div class="container">
            <div class="song-left">
                <img src="<?php echo $song['image_path']; ?>" alt="Song Image">
            </div>
            <div class="song-right">
                <h1><?php echo $song['name']; ?></h1>
                <p><strong>Artist:</strong> <?php echo $song['artist']; ?></p>
                <p><strong>Album:</strong> <?php echo $song['album']; ?></p>
                <p><strong>Year:</strong> <?php echo $song['year']; ?></p>
                <div class="song-player">
                    <h2><?php echo $song['name']; ?> - <?php echo $song['artist']; ?></h2>
                    <audio controls style="width:100%;">
                        <source src="<?php echo $song['file_path']; ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>

            </div>
        </div>

        <!-- Review Section -->
        <div class="review-section">
            <h2>Leave a Review</h2>
            <form class="review-form" action="submit_review.php" method="POST">
                <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                <input type="text" name="username" placeholder="Your Name" required>
                <textarea name="review" rows="4" placeholder="Write your review..." required></textarea>
                <button type="submit">Submit Review</button>
            </form>

            <h2>Reviews</h2>
            <?php if ($reviews->num_rows > 0): ?>
                <?php while ($rev = $reviews->fetch_assoc()): ?>
                    <div class="review">
                        <strong><?php echo $rev['username']; ?></strong>
                        <p><?php echo $rev['review']; ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No reviews yet. Be the first to review!</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p style="text-align:center; color:#6763fd; margin-top:50px;">Song not found!</p>
    <?php endif; ?>

    <footer class="footer set-bg" data-setbg="./img/footer-bg.jpg" style="margin-top: 10%;">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9">
                    <div class="footer__widget">
                        <div class="footer__logo">
                            <a href="#"><img src="img/logo.png" alt=""></a>
                        </div>
                        <p class="footer__copyright__text">Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | This website is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Muhammed Shayan</a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="footer__social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-pinterest"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-youtube-play"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>