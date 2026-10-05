<?php 

require_once "db.php";

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search != "") {

    $stmt = $conn->prepare(
        "SELECT * FROM posts
         WHERE title LIKE ?
         OR content LIKE ?
         OR author LIKE ?
         ORDER BY created_at DESC"
    );

    $keyword = "%" . $search . "%";

    $stmt->bind_param(
        "sss",
        $keyword,
        $keyword,
        $keyword
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $sql = "SELECT * FROM posts ORDER BY created_at DESC";

    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MyBlog - Simple Blogging Platform</title>

    <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    >

    <style>

        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .hero {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 60px 20px;
            border-radius: 0 0 25px 25px;
            text-align: center;
        }

        .hero h1 {
            font-size: 42px;
            font-weight: bold;
        }

        .hero p {
            font-size: 18px;
        }

        .search-box {
            max-width: 600px;
            margin: 30px auto;
        }

        .search-box input {
            border-radius: 25px 0 0 25px;
        }

        .search-box button {
            border-radius: 0 25px 25px 0;
        }

        .post-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: 0.2s;
            height: 100%;
        }

        .post-card:hover {
            transform: translateY(-4px);
        }

        .post-title {
            font-weight: bold;
            color: #343a40;
        }

        .post-content {
            color: #666;
        }

        .date {
            font-size: 13px;
            color: #888;
        }

        .btn {
            border-radius: 25px;
        }

        .empty {
            text-align: center;
            padding: 50px;
            color: #777;
        }

        footer {
            margin-top: 50px;
            padding: 25px;
            background: #343a40;
            color: white;
            text-align: center;
        }

    </style>

</head>


<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="container">

        <a
            class="navbar-brand"
            href="index.php"
        >
            📝 MyBlog
        </a>


        <a
            href="create.php"
            class="btn btn-primary"
        >
            + Create Post
        </a>

    </div>

</nav>


<!-- HERO -->

<section class="hero">

    <h1>
        Welcome to MyBlog
    </h1>

    <p>
        Share your thoughts, ideas and stories with the world.
    </p>


    <!-- SEARCH -->

    <form
        method="GET"
        action="index.php"
        class="search-box"
    >

        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search posts..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <div class="input-group-append">

                <button
                    type="submit"
                    class="btn btn-light"
                >
                    🔍 Search
                </button>

            </div>

        </div>

    </form>

</section>


<!-- POSTS -->

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            <?php
            echo $search != ""
                ? "Search Results"
                : "Latest Posts";
            ?>
        </h2>

        <?php if ($search != ""): ?>

            <a
                href="index.php"
                class="btn btn-secondary"
            >
                Clear Search
            </a>

        <?php endif; ?>

    </div>


    <div class="row">


        <?php if ($result && $result->num_rows > 0): ?>


            <?php while ($post = $result->fetch_assoc()): ?>


                <div class="col-md-6 mb-4">

                    <div class="card post-card">

                        <div class="card-body">


                            <h4 class="post-title">

                                <?php
                                echo htmlspecialchars($post['title']);
                                ?>

                            </h4>


                            <p class="date">

                                ✍️ By

                                <?php
                                echo htmlspecialchars($post['author']);
                                ?>

                                &nbsp; • &nbsp;

                                📅

                                <?php
                                echo date(
                                    "d M Y, h:i A",
                                    strtotime($post['created_at'])
                                );
                                ?>

                            </p>


                            <p class="post-content">

                                <?php

                                $content =
                                    htmlspecialchars($post['content']);

                                echo strlen($content) > 180
                                    ? substr($content, 0, 180) . "..."
                                    : $content;

                                ?>

                            </p>


                            <div class="mt-3">

                                <a
                                    href="view.php?id=<?php echo $post['id']; ?>"
                                    class="btn btn-outline-primary"
                                >
                                    Read More
                                </a>


                                <a
                                    href="edit.php?id=<?php echo $post['id']; ?>"
                                    class="btn btn-warning"
                                >
                                    ✏️ Edit
                                </a>


                                <a
                                    href="delete.php?id=<?php echo $post['id']; ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this post?');"
                                >
                                    🗑️ Delete
                                </a>

                            </div>


                        </div>

                    </div>

                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="col-12">

                <div class="empty">

                    <h3>
                        <?php
                        echo $search != ""
                            ? "No posts found 🔍"
                            : "No blog posts yet 📝";
                        ?>
                    </h3>


                    <p>

                        <?php
                        echo $search != ""
                            ? "Try searching with another keyword."
                            : "Create your first post to get started!";
                        ?>

                    </p>


                    <?php if ($search == ""): ?>

                        <a
                            href="create.php"
                            class="btn btn-primary"
                        >
                            Create First Post
                        </a>

                    <?php endif; ?>

                </div>

            </div>


        <?php endif; ?>


    </div>

</div>


<!-- FOOTER -->

<footer>

    <p class="mb-0">
        MyBlog • PHP & MySQL Blogging Platform
    </p>

    <small>
        1Stop Web Development Internship
    </small>

</footer>


</body>

</html>