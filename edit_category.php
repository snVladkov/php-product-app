<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: rgb(233, 245, 249);
        }
    </style>
    <title>Edit Category | Products App</title>
</head>

<body class="container mx-auto h-100 py-1">
    <?php
    //get category id
    $cid = intval($_GET['cid']);

    try {
        //open db connection
        $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //get current category
        $prep = $conn->prepare("SELECT * FROM Categories WHERE CatId = :cid");
        $prep->bindParam(':cid', $cid);
        $prep->execute();
        $category = $prep->fetchAll()[0];
    } catch (PDOException $e) {
        $error = true;
        $message .= "Connection Error: " . $e->getMessage();
    }
    //close db connection
    $conn = null;
    ?>
    <header class="row w-100 p-3">
        <h1 class="col-6 fw-bold text-center">Add Category</h1>
        <nav class="col-3 d-flex align-items-center nav nav-pills">
            <a href="./index.php" class="nav-link">Products</a>
            <a href="./categories.php" class="nav-link">Categories</a>
        </nav>
        <div class="col-3 dropdown py-3">
            <button class="btn btn-success dropdown-toggle" type="button" id="add" data-bs-toggle="dropdown" aria-expanded="false">
                Add New
            </button>
            <ul class="dropdown-menu bg-success" aria-labelledby="add">
                <li><a class="dropdown-item fw-bold" href="./add_product.php">Add Product</a></li>
                <li><a class="dropdown-item fw-bold" href="./add_category.php">Add Category</a></li>
            </ul>
        </div>
    </header>
    <main class="row w-100 px-5">
        <?php
        $show = $error ? "show" : "";
        echo "<div class='alert alert-danger alert-dismissible fade $show' role='alert'>
                <strong>$message</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>"
        ?>
        <form action="categories.php" method="post" class="form col-12 row px-5 mt-3">
            <div class="col-12 mb-4">
                <label for="cname" class="form-label">Category Name:</label>
                <input type="text" class="form-control" id="cname" name="cname" value="<?php echo $category[1] ?>" required>
            </div>
            <div class="col-12 mb-4">
                <label for="cdescription" class="form-label">Category Description:</label>
                <textarea class="form-control" id="cdescription" name="cdescription" rows="10" required><?php echo $category[2] ?></textarea>
            </div>
            <input type="hidden" name="cid" value="<?php echo $category[0] ?>">
            <input type="submit" name="submit" value="Edit Category" class="btn btn-primary col-4 mx-auto">
        </form>
    </main>
</body>

</html>