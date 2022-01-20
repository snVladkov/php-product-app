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

        main {
            height: 85%;
        }
    </style>
    <script src="./js/getProducts.js"></script>
    <script src="./js/deleteProduct.js"></script>
    <script src="./js/exportProducts.js"></script>
    <title>Products | Products App</title>
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
        $id = $category = $name = $price = "";
        $id = intval(clean_input($_POST["pid"]));
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
        if (is_uploaded_file($_FILES["pimage"]["tmp_name"])) {
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
    }

    try { //open db connection
        $conn = new PDO("mysql:host=localhost;dbname=ProductAppDB", "productapp", "products123");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //get categories for dropdown
        $prep = $conn->prepare("SELECT CatId, CatName FROM Categories");
        $prep->execute();
        $catArr = $prep->fetchAll();
        //update product in db
        if (isset($_POST['submit']) && !$error) {
            $updateImg = $image ? ", ProductImage = :pimage" : "";
            $prep = $conn->prepare("UPDATE Products SET ProductCatId = :pcat, ProductName = :pname, ProductPrice = :pprice$updateImg WHERE ProductId = :pid");
            $prep->bindParam(':pid', $id);
            $prep->bindParam(':pcat', $category);
            $prep->bindParam(':pname', $name);
            $prep->bindParam(':pprice', $price);
            if ($image) $prep->bindParam(':pimage', $image);
            $prep->execute();
            $message = "Product $name successfully updated.";
        }
    } catch (PDOException $e) {
        $error = true;
        $message .= "Connection Error: " . $e->getMessage();
    }
    //close db connection
    $conn = null;
    ?>
    <header class="row w-100 p-3">
        <h1 class="col-5 fw-bold text-center">My Products</h1>
        <nav class="col-3 d-flex align-items-center nav nav-pills">
            <a href="./index.php" class="nav-link active me-2">Products</a>
            <a href="./categories.php" class="nav-link">Categories</a>
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
    <main class="row w-100 overflow-hidden" id="main">
        <?php
        $msg_type = $error ? "alert-danger" : "alert-success";
        $show = isset($_POST['submit']) ? "show" : "";
        echo "<div class='alert $msg_type alert-dismissible fade $show' role='alert' id='errorBox'>
                <strong id='errorMsg'>$message</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>"
        ?>
        <form class="col-12 row my-4 mx-1 py-4 bg-light rounded-3">
            <div class="col-3">
                <select class="form-select" aria-label="Default select example" id="pcat">
                    <option value="0">All Categories</option>
                    <?php
                    foreach ($catArr as $cat) {
                        echo "<option value='$cat[0]'>$cat[1]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-3">
                <input type="text" class="form-control" placeholder="Search products..." id="pname">
            </div>
            <div class="col-4">
                <label class="form-label" for="pmin">Price from:</label>
                <input type="number" step="0.01" class="form-control w-25 d-inline" id="pmin">
                <label class="form-label" for="pmax">to:</label>
                <input type="number" step="0.01" class="form-control w-25 d-inline" id="pmax">
            </div>
            <button type="button" class="col-2 btn btn-primary" id="btnSearch">Search</button>
        </form>
        <div class="h-75 overflow-auto px-0">
            <table class="table table-striped text-center bg-light pb-5" style="height: 300px;">
                <thead>
                    <tr class="row">
                        <th class='col-3'>Image</th>
                        <th class='col-2'>Name</th>
                        <th class='col-2'>Category</th>
                        <th class='col-2'>Price</th>
                        <th class='col-3'>Actions</th>
                    </tr>
                </thead>
                <tbody id="productList">
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>