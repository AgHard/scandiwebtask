CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);

CREATE TABLE products_base (
  id VARCHAR(255) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  in_stock BOOLEAN NOT NULL,
  description TEXT,
  category VARCHAR(255) NOT NULL,
  brand VARCHAR(255),
  product_type VARCHAR(255) NOT NULL
);

CREATE TABLE clothing_products (
  id VARCHAR(255) PRIMARY KEY,
  size VARCHAR(255),
  FOREIGN KEY (id) REFERENCES products_base(id)
);

CREATE TABLE tech_products (
  id VARCHAR(255) PRIMARY KEY,
  FOREIGN KEY (id) REFERENCES products_base(id)
);

CREATE TABLE attributes_base (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id VARCHAR(255),
  name VARCHAR(255) NOT NULL,
  type VARCHAR(255) NOT NULL,
  value VARCHAR(255),
  FOREIGN KEY (product_id) REFERENCES products_base(id)
);

CREATE TABLE attribute_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  attribute_id INT,
  display_value VARCHAR(255),
  value VARCHAR(255),
  FOREIGN KEY (attribute_id) REFERENCES attributes_base(id)
);

CREATE TABLE prices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id VARCHAR(255),
  amount DECIMAL(10, 2),
  currency_label VARCHAR(50),
  currency_symbol VARCHAR(5),
  FOREIGN KEY (product_id) REFERENCES products_base(id)
);

CREATE TABLE galleries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id VARCHAR(255),
  image_url VARCHAR(255),
  FOREIGN KEY (product_id) REFERENCES products_base(id)
);
