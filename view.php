<?php

require_once "db.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$post = $result->fetch_assoc();

$stmt->close();

if (!$post) {
    echo "Post not found.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($post['title']); ?> - MyBlog
    </title>

    <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    >

    <style>

        body {
            background: #f5f6fa;
        }

        .navbar {
            background: #343a40;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }

        .blog-container {
            max-width: 850px;
            margin: 50px auto;
        }

        .blog-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .blog-title {
            font-size: 38px;
            font-weight: bold;
            color: #343a40;
        }

        .blog-meta {
            color: #777;
            margin-bottom: 30px;
        }

        .blog-content {
            font-size: 18px;
            line-height: 1.8;
            color: #444;
            white-space: pre-line;
        }

        .btn {
            border-radius: 25px;
        }

    </style>

</head>

<body>

<nav class="navbar">

    <div class="container">

        <a
            class="navbar-brand"
            href="index.php"
        >
            📝 MyBlog
        </a>

        <a
            href="index.php"
            class="btn btn-light"
        >
            ← Back to Posts
        </a>

    </div>

</nav>


<div class="container blog-container">

    <div class="card blog-card">

        <div class="card-body p-5">

            <h1 class="blog-title">

                <?php
                echo htmlspecialchars($post['title']);
                ?>

            </h1>


            <p class="blog-meta">

                ✍️ By

                <strong>
                    <?php
                    echo htmlspecialchars($post['author']);
                    ?>
                </strong>

                &nbsp; • &nbsp;

                📅

                <?php
                echo date(
                    "d M Y, h:i A",
                    strtotime($post['created_at'])
                );
                ?>

            </p>


            <hr>


            <div class="blog-content mt-4">

                <?php
                echo htmlspecialchars($post['content']);
                ?>

            </div>


            <hr class="mt-5">


            <div class="mt-3">

                <a
                    href="edit.php?id=<?php echo $post['id']; ?>"
                    class="btn btn-warning mr-2"
                >
                    ✏️ Edit Post
                </a>


                <a
                    href="delete.php?id=<?php echo $post['id']; ?>"
                    class="btn btn-danger mr-2"
                    onclick="return confirm('Are you sure you want to delete this post?');"
                >
                    🗑️ Delete Post
                </a>


                <a
                    href="index.php"
                    class="btn btn-primary"
                >
                    ← Back to All Posts
                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>