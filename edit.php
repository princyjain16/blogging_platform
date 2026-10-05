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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $author = trim($_POST["author"]);

    if ($title == "" || $content == "" || $author == "") {

        $message = "Please fill in all fields.";

    } else {

        $update = $conn->prepare(
            "UPDATE posts
             SET title = ?, content = ?, author = ?, updated_at = NOW()
             WHERE id = ?"
        );

        $update->bind_param(
            "sssi",
            $title,
            $content,
            $author,
            $id
        );

        if ($update->execute()) {

            header("Location: view.php?id=" . $id);
            exit();

        } else {

            $message = "Unable to update the post.";

        }

        $update->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Post - MyBlog</title>

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
                ✏️ Edit Blog Post
            </h2>


            <?php if ($message != ""): ?>

                <div class="alert alert-danger">

                    <?php
                    echo htmlspecialchars($message);
                    ?>

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
                        value="<?php echo htmlspecialchars($post['title']); ?>"
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
                        value="<?php echo htmlspecialchars($post['author']); ?>"
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
                        required
                    ><?php echo htmlspecialchars($post['content']); ?></textarea>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary px-4"
                >
                    Update Post
                </button>


                <a
                    href="view.php?id=<?php echo $id; ?>"
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