CREATE DATABASE CST310_PAXTONPORENTA;

USE CST310_PAXTONPORENTA;

CREATE TABLE tblUser(
	user_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(255),
    pass VARCHAR(255),
    firstName VARCHAR(40),
    lastName VARCHAR(80),
    address VARCHAR(255),
    phone VARCHAR(20),
    salary FLOAT(7),
    ssn INTEGER(9),
    role_id INTEGER DEFAULT 1,
    
    PRIMARY KEY (user_id)
);

CREATE TABLE roles (
  role_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  role_name VARCHAR(50) NOT NULL,

  PRIMARY KEY (role_id)
);

INSERT INTO roles (role_name) VALUES ('employee');
INSERT INTO roles (role_name) VALUES ('manager');
INSERT INTO roles (role_name) VALUES ('administrator');

CREATE TABLE permissions (
  perm_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  perm_desc VARCHAR(50) NOT NULL,

  PRIMARY KEY (perm_id)
);

INSERT INTO permissions (perm_desc) VALUES ('create');
INSERT INTO permissions (perm_desc) VALUES ('read');
INSERT INTO permissions (perm_desc) VALUES ('update');
INSERT INTO permissions (perm_desc) VALUES ('delete');

CREATE TABLE role_perm (
  role_id INTEGER UNSIGNED NOT NULL,
  perm_id INTEGER UNSIGNED NOT NULL,

  FOREIGN KEY (role_id) REFERENCES roles(role_id),
  FOREIGN KEY (perm_id) REFERENCES permissions(perm_id)
);

INSERT INTO role_perm (role_id, perm_id) VALUES (1, 2);
INSERT INTO role_perm (role_id, perm_id) VALUES (1, 3);
INSERT INTO role_perm (role_id, perm_id) VALUES (2, 1);
INSERT INTO role_perm (role_id, perm_id) VALUES (2, 2);
INSERT INTO role_perm (role_id, perm_id) VALUES (2, 3);
INSERT INTO role_perm (role_id, perm_id) VALUES (3, 1);
INSERT INTO role_perm (role_id, perm_id) VALUES (3, 2);
INSERT INTO role_perm (role_id, perm_id) VALUES (3, 3);
INSERT INTO role_perm (role_id, perm_id) VALUES (3, 4);

CREATE TABLE user_role (
  user_id INTEGER UNSIGNED NOT NULL,
  role_id INTEGER UNSIGNED NOT NULL,

  FOREIGN KEY (user_id) REFERENCES tblUser(user_id),
  FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

DELIMITER $$

CREATE TRIGGER after_tbluser_insert AFTER INSERT ON tbluser FOR EACH ROW
BEGIN
	INSERT INTO user_role (user_id, role_id)
	VALUES (new.user_id, new.role_id);
END$$

DELIMITER ;