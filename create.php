<?php

require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $author = trim($_POST["author"]);

    if ($title == "" || $content == "" || $author == "") {

        $message = "Please fill in all fields.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO posts (title, content, author, created_at, updated_at)
             VALUES (?, ?, ?, NOW(), NOW())"
        );

        $stmt->bind_param(
            "sss",
            $title,
            $content,
            $author
        );

        if ($stmt->execute()) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Something went wrong. Please try again.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Post - MyBlog</title>

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

        .form-container {
            max-width: 800px;
            margin: 50px auto;
        }

        .form-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .form-title {
            font-weight: bold;
        }

        textarea {
            resize: vertical;
            min-height: 220px;
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


<div class="container form-container">

    <div class="card form-card">

        <div class="card-body p-5">

            <h2 class="form-title mb-4">
                ✍️ Create a New Blog Post
            </h2>


            <?php if ($message != ""): ?>

                <div class="alert alert-danger">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>


            <form method="POST">


                <div class="form-group">

                    <label>
                        Post Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Enter your post title"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Author Name
                    </label>

                    <input
                        type="text"
                        name="author"
                        class="form-control"
                        placeholder="Enter author name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Blog Content
                    </label>

                    <textarea
                        name="content"
                        class="form-control"
                        placeholder="Write your blog post here..."
                        required
                    ></textarea>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary px-4"
                >
                    Publish Post
                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary px-4"
                >
                    Cancel
                </a>


            </form>

        </div>

    </div>

</div>


</body>

</html>