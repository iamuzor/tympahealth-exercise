CREATE TABLE devices (
    id varchar(255) unique,
	model varchar(255),
	brand varchar(255),
	release_date varchar(255) NULL,
	os varchar(255) NULL,
    created_datetime BIGINT NULL,
    updated_datetime BIGINT NULL
);
