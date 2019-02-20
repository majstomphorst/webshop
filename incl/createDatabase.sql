SELECT product_id, SUM(amount)
FROM orders_products
GROUP BY product_id;


SELECT product_id, SUM(amount) AS sum_amount
FROM orders_products
GROUP BY product_id; 

SELECT product_id, SUM(amount) AS sum_amount
FROM orders_products
GROUP BY product_id
ORDER BY sum_amount;

SELECT product_id, SUM(amount) AS sum_amount
FROM orders_products
GROUP BY product_id
ORDER BY sum_amount DESC;

SELECT		
			product_id, 
			SUM(amount) AS sum_amount
			
FROM		orders_products

JOIN 		orders ON orders_products.order_id = orders.id
JOIN 		products ON orders_products.product_id = products.id

WHERE		orders.date > ADDDATE(NOW(),INTERVAL -7 DAY)

GROUP BY	product_id
ORDER BY	sum_amount DESC;

SELECT		products.name,
			products.description,
			products.image_name,
			product_id, 
			SUM(amount) AS sum_amount
			
FROM		orders_products

JOIN 		orders ON orders_products.order_id = orders.id
JOIN 		products ON orders_products.product_id = products.id

WHERE		orders.date > ADDDATE(NOW(),INTERVAL -7 DAY)

GROUP BY	product_id
ORDER BY	sum_amount DESC
LIMIT 5;



CREATE TABLE products (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50), 
    description VARCHAR(255),
    price       FLOAT,
    image_name  VARCHAR(50)
);

CREATE USER 'php_user'@'localhost' IDENTIFIED BY 'password';


GRANT SELECT, INSERT, UPDATE, DELETE ON educom.* TO 'php_user'@'localhost';



CREATE table users (
id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(64) NOT NULL,
email VARCHAR(64) NOT NULL UNIQUE,
password VARCHAR(64) NOT NULL);

INSERT INTO users (name, email, password)
            VALUES (name, email,password);


ALTER TABLE users
ADD CONSTRAINT email_unique UNIQUE KEY(email);

