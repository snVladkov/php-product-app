--create db with dedicated user
CREATE USER 'productapp'@'localhost' IDENTIFIED BY 'products123';
CREATE DATABASE 'ProductAppDB';
GRANT ALL PRIVILEGES ON ProductAppDB.* TO 'productapp'@'localhost';
USE 'ProductAppDB';

--create tables
CREATE TABLE Categories (
    CatId int NOT NULL AUTO_INCREMENT,
    CatName varchar(50) NOT NULL,
    CatDescription varchar(500) NOT NULL,
    PRIMARY KEY (CatId)
);

CREATE TABLE Products (
    ProductId int NOT NULL AUTO_INCREMENT,
    ProductCatId int NOT NULL,
    ProductName varchar(50) NOT NULL,
    ProductPrice decimal(10,2) NOT NULL,
    ProductImage varchar(150) NOT NULL,
    PRIMARY KEY (ProductId),
    FOREIGN KEY (ProductCatId) REFERENCES Categories(CatId)
);

--add dummy data
INSERT INTO Categories(CatName, CatDescription)
VALUES ('Laptops', 'This category contains laptops, notebooks and similar portable computing devices.'),
('Shoes','This category contains trainers, boots and other footwear products.'),
('Watches', 'This category contains premium wristwatches.');


INSERT INTO Products(ProductCatId, ProductName, ProductPrice, ProductImage)
VALUES (1, 'Lenovo IdeaPad 5 Pro', 999.99, 'ideapad-5-pro-16.webp'),
(1, 'Asus ROG Zephyrus G14', 1894.89, 'asus_rog_zephyrus_g14.jpg'),
(2, 'Nike Airmax 270G', 139.99, 'nike_airmax_270g.jpg'),
(2, 'Adidas Ultra Boost 20', 189.95, 'adidas_ultra_boost_20.jpg'),
(3, 'Casio Edifice 100D', 599.89, 'casio_edifice_100d.jpg'),
(3, 'Rolex Cosmograph Daytona', 5899.99, 'rolex_cosmograph_daytona.jpg');
