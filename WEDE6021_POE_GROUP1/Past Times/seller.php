<?php
include 'connect.php';

$message = "";

if (isset($_POST['submit'])) {

    $seller_id = isset($_POST['seller_id']) ? mysqli_real_escape_string($conn, $_POST['seller_id']) : '';
    $item_name = isset($_POST['item_name']) ? mysqli_real_escape_string($conn, $_POST['item_name']) : '';
    $brand = isset($_POST['brand']) ? mysqli_real_escape_string($conn, $_POST['brand']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $image = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $_FILES['image']['name']);
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($image);

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed)) {
            $message = "Invalid image type!";
        } else {

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {

                $sql = "INSERT INTO clothes_requests 
                        (seller_id, item_name, brand, description, image)
                        VALUES 
                        ('$seller_id', '$item_name', '$brand', '$description', '$target_file')";

                if (mysqli_query($conn, $sql)) {
                    $message = "Request submitted successfully!";
                } else {
                    $message = "Database error: " . mysqli_error($conn);
                }

            } else {
                $message = "Failed to upload image.";
            }
        }

    } else {
        $message = "Please select an image to upload.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 40px;
            min-height: 100vh;
            position: relative;
        }

        /* FIXED BACKGROUND */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("background.png") no-repeat center center;
            background-size: 1250px;
            opacity: 0.60;
            filter: blur(1px);
            z-index: -1;
            transform: scale(1.1);
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(255, 255, 255, 0.88);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(6px);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #111827;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background: #2563eb;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        button:hover {
            background: #1e4bb8;
        }

        .message {
            padding: 12px 15px;
            margin-bottom: 20px;
            background: #d1fae5;
            border-left: 5px solid #10b981;
            border-radius: 8px;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            background: #6b7280;
        }

        .back-button:hover {
            background: #4b5563;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="seller_dashboard.php" class="back-button">
        <button type="button">← Back to Dashboard</button>
    </a>

    <h2>Request to Sell Clothes</h2>

    <?php if (!empty($message)) { ?>
        <div class="message"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <input type="text" name="item_name" placeholder="Item Name" required>
        </div>

        <div class="form-group">
            <input type="text" name="brand" placeholder="Brand Name" required>
        </div>

        <div class="form-group">
            <textarea name="description" placeholder="Description" required></textarea>
        </div>

        <div class="form-group">
            <input type="file" name="image" required>
        </div>

        <button type="submit" name="submit">Request to Sell</button>

    </form>

</div>

</body>
</html>