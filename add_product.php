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
    <title>Add Product | Products App</title>
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
        $category = $name = $price = $image = "";
        $category = intval(clean_input($_POST["pcategory"]));

        $name = clean_input($_POST["pname"]);
        if (strlen($name) > 50) {
            $error = true;
            $message .= "The product name must not be over 50 symbols.<br/>";
        }

        $price = floatval(clean_input($_POST["pprice"]));
        if ($price > 99999999) {
            $error = true;
            $message .= "The price must be under 100 million.<br/>";
        }

        //image upload
        if (getimagesize($_FILES["pimage"]["tmp_name"]) === false) {
            $error = true;
            $message .= "Please upload a valid image file.<br/>";
        } else {
            if ($_FILES["pimage"]["size"] > 2097152) {
                $error = true;
                $message .= "The image must not be over 2MB in size.<br/>";
            } else {
                $image = clean_input(basename($_FILES["pimage"]["name"]));
                move_uploaded_file($_FILES["pimage"]["tmp_name"], "images/$image");
            }
        }
    }

    try {
        //open db connection
        $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //get categories for dropdown
        $prep = $conn->prepare("SELECT CatId, CatName FROM Categories");
        $prep->execute();
        $catArr = $prep->fetchAll();

        //add product to db
        if (isset($_POST['submit']) && !$error) {
            $prep = $conn->prepare("INSERT INTO Products(ProductCatId, ProductName, ProductPrice, ProductImage) VALUES (:pcat, :pname, :pprice, :pimage)");
            $prep->bindParam(':pcat', $category);
            $prep->bindParam(':pname', $name);
            $prep->bindParam(':pprice', $price);
            $prep->bindParam(':pimage', $image);
            $prep->execute();
            $message = "$name successfully added to products.";
        }
    } catch (PDOException $e) {
        $error = true;
        $message .= "Connection Error: " . $e->getMessage();
    }
    //close db connection
    $conn = null;
    ?>
    <header class="row w-100 p-3">
        <h1 class="col-6 fw-bold text-center">Add Product</h1>
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
    <main class="row w-100 px-5 mt-4">
        <?php
        $msg_type = $error ? "danger" : "success";
        $show = isset($_POST['submit']) ? "show" : "";
        echo "<div class='alert alert-$msg_type alert-dismissible fade $show' role='alert'>
                <strong>$message</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>"
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" class="form col-12 row px-5 mt-4" method="post" enctype="multipart/form-data">
            <div class="col-6 mb-4">
                <label for="pname" class="form-label">Product Name:</label>
                <input type="text" class="form-control" id="pname" name="pname" required>
            </div>
            <div class="col-6 mb-4">
                <label for="pcategory" class="form-label">Product Category:</label>
                <select class="form-select" aria-label="Select Category" id="pcategory" name="pcategory" required>
                    <?php foreach ($catArr as $index => $cat) {
                        $selected = $index === array_key_first($catArr) ? "selected" : "";
                        echo "<option $selected value='$cat[0]'>$cat[1]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-6 mb-4">
                <label for="pprice" class="form-label">Product Price:</label>
                <input type="number" step="0.01" class="form-control" id="pprice" name="pprice" required>
            </div>
            <div class="col-6 mb-4">
                <label for="pimage" class="form-label">Upload Image:</label>
                <input class="form-control" type="file" id="pimage" name="pimage" required>
            </div>
            <input type="submit" name="submit" class="btn btn-primary col-4 mx-auto mt-4" value="Add Product">
        </form>
    </main>
</body>

</html>