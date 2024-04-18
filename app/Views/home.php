<!-- landing.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Welcome to Our Website</h1>
        <?php if (session()->get('user_id')) : ?>
            <?php
                $userName = session()->get('user_name');
                $userProfilePicture = session()->get('user_profile_picture');
                $profilePictureUrl = $userProfilePicture ? base_url($userProfilePicture) : ''; // Jika $userProfilePicture null, gunakan string kosong
            ?>
            <p>Hello, <?= $userName ?></p>
            <?php if ($profilePictureUrl) : ?>
                <img src="<?= $profilePictureUrl ?>" alt="Profile Picture" width="100" height="100">
            <?php endif; ?>
            <a href="/logout" class="btn btn-primary">Logout</a>
        <?php else : ?>
            <a href="/login" class="btn btn-primary">Login</a>
            <a href="/register" class="btn btn-primary">Register</a>
        <?php endif; ?>
    </div>
</body>
</html>
