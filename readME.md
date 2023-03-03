<!-- terminal commands -->
```
npm install jquery
nom install popperjs@core
npm install bootstrap
```

CREATE TABLE `device_locations` ( 
   `imei` BIGINT NOT NULL, 
   `location_name` VARCHAR(255) NOT NULL,
    UNIQUE `imei` (`imei`)
);

INSERT INTO `device_locations` (`imei`, `location_name`) VALUES ('123456789987654321', 'Eldoret');

INSERT INTO `device_locations` (`imei`, `location_name`) VALUES ('987567896543211234', 'Kisii');

INSERT INTO `device_locations` (`imei`, `location_name`) VALUES ('123456798987654321', 'Nairobi');

CREATE TABLE `users` (
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `firstname` VARCHAR(128) NOT NULL,
    `lastname` VARCHAR(128) NOT NULL,
    `email` VARCHAR(128) NOT NULL,
    `password` VARCHAR(128) NOT NULL,
    `is_admin` BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (user_id)
);

INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`) VALUES ('John', 'Doe', 'johndoe@gmail.com', '123');

INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`) VALUES ('J', 'Qualin', 'jqwalin@gmail.com', '123');

ALTER TABLE `device_locations` ADD `locked` BOOLEAN NOT NULL DEFAULT FALSE AFTER `email`;

ALTER TABLE `device_locations` ADD `alarm` BOOLEAN NOT NULL DEFAULT FALSE AFTER `locked`;

CREATE TABLE `reports` (
    `report_id` INT NOT NULL AUTO_INCREMENT,
    `detail` VARCHAR(128) NOT NULL,
    `created_at` DATETIME,
    `FK_UserReport` BIGINT(20) NOT NULL,
    PRIMARY KEY (report_id),
);