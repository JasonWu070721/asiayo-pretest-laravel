DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS bnbs;

CREATE TABLE bnbs(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE rooms(
    id INT AUTO_INCREMENT PRIMARY KEY,
    bnb_id INT,
    name VARCHAR(255),
    FOREIGN KEY (bnb_id) REFERENCES bnbs(id)
);

CREATE TABLE orders(
    id INT AUTO_INCREMENT PRIMARY KEY,
    bnb_id INT,
    room_id INT UNIQUE,
    currency VARCHAR(3),
    amount INT,
    check_in_date DATETIME,
    check_out_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bnb_id) REFERENCES bnbs(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);
