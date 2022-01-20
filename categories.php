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
    <script src="./js/getCategories.js"></script>
    <script src="./js/deleteCategory.js"></script>
    <script src="./js/exportCategories.js"></script>
    <title>Categories | Products App</title>
</head>

<body class="container mx-auto h-100 py-1">
    <?php
    $error = false;
    $message = "";

    //input clean
    function clean_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //get user input and validate
    if (isset($_POST['submit'])) {
        $id = $name = $description = "";
        $id = intval(clean_input($_POST["cid"]));

        $name = clean_input($_POST["cname"]);
        if (strlen($name) > 50) {
            $error = true;
            $message .= "The category name must not be over 50 symbols.<br/>";
        }

        $description = clean_input($_POST["cdescription"]);
        if (strlen($name) > 500) {
            $error = true;
            $message .= "The category description must not be over 500 symbols.<br/>";
        }
    }

    try { //open db connection
        $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //update category in db
        if (isset($_POST['submit']) && !$error) {
            $prep = $conn->prepare("UPDATE Categories SET CatName = :cname, CatDescription = :cdesc WHERE CatId = :cid");
            $prep->bindParam(':cid', $id);
            $prep->bindParam(':cname', $name);
            $prep->bindParam(':cdesc', $description);
            $prep->execute();
            $message = "Category $name successfully updated.";
        }
    } catch (PDOException $e) {
        $error = true;
        $message .= "Connection Error: " . $e->getMessage();
    }
    //close db connection
    $conn = null;
    ?>
    <header class="row w-100 p-3">
        <h1 class="col-5 fw-bold text-center">My Categories</h1>
        <nav class="col-3 d-flex align-items-center nav nav-pills">
            <a href="./index.php" class="nav-link">Products</a>
            <a href="./categories.php" class="nav-link active">Categories</a>
        </nav>
        <div class="col-2 dropdown py-3">
            <button class="btn btn-success dropdown-toggle" type="button" id="add" data-bs-toggle="dropdown" aria-expanded="false">
                Add New
            </button>
            <ul class="dropdown-menu bg-success" aria-labelledby="add">
                <li><a class="dropdown-item fw-bold" href="./add_product.php">Add Product</a></li>
                <li><a class="dropdown-item fw-bold" href="./add_category.php">Add Category</a></li>
            </ul>
        </div>
        <button class="col-2 btn btn-dark my-3" id="export">Export</button>
    </header>
    <?php
        $msg_type = $error ? "alert-danger" : "alert-success";
        $show = isset($_POST['submit']) ? "show" : "";
        echo "<div class='alert $msg_type alert-dismissible fade $show' role='alert' id='errorBox'>
                <strong id='errorMsg'>$message</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>"
        ?>
    <main class="h-75 overflow-auto mt-5" id="main">
        <table class="table table-striped text-center bg-light" style="height: 300px;">
            <thead>
                <tr class="row px-3">
                    <th class="col-3">Category</th>
                    <th class="col-6">Description</th>
                    <th class="col-3">Actions</th>
                </tr>
            </thead>
            <tbody id="catList">
            </tbody>
        </table>
    </main>
</body>

</html>