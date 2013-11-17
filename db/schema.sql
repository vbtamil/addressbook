--Database Name address_book


--DROP DATABASE address_book;

CREATE DATABASE address_book
  WITH OWNER = postgres
       ENCODING = 'UTF8';
       
-- Table: credentials

-- DROP TABLE credentials;

CREATE TABLE credentials
(
  username character varying(50) NOT NULL,
  "password" character varying(50),
  created date,
  updated date,
  status character(1),
  "AuthId" serial NOT NULL,
  CONSTRAINT pk_username PRIMARY KEY (username),
  CONSTRAINT ck_status CHECK (status = ANY (ARRAY['I'::bpchar, 'D'::bpchar, 'U'::bpchar]))
)
WITH (OIDS=FALSE);
ALTER TABLE credentials OWNER TO postgres;

-- Table: people

-- DROP TABLE people;

CREATE TABLE people
(
  address_id serial NOT NULL,
  salutation character varying(20),
  last_name character varying(50) NOT NULL,
  first_name character varying(50) NOT NULL,
  birth_date date NOT NULL DEFAULT '1900-01-01'::date,
  address_1 character varying(100) NOT NULL,
  address_2 character varying,
  address_3 character varying(100),
  city character varying(30),
  state character varying(20),
  postal_code character varying(10),
  home_phone character varying(15),
  work_phone character varying(15),
  other_phone1 character varying(15),
  other_phone2 character varying(15),
  other_phone3 character varying,
  contact_email character varying(50) NOT NULL,
  other_phone_type1 character varying(15),
  other_phone_type2 character varying(15),
  other_phone_type3 character varying(15),
  country character varying(30),
  record_status character varying(5),
  middle_name character varying(50) NOT NULL,
  comments text,
  CONSTRAINT pk_serial PRIMARY KEY (address_id),
  CONSTRAINT uq_contact_email UNIQUE (contact_email),
  CONSTRAINT uq_home_phone UNIQUE (home_phone),
  CONSTRAINT uq_last_first_middle_email_phone UNIQUE (last_name, first_name, middle_name, birth_date, contact_email, home_phone, work_phone, postal_code, record_status),
  CONSTRAINT ck_landline_mobile_none1 CHECK (other_phone_type1::text = ANY (ARRAY['LANDLINE'::character varying, 'MOBILE'::character varying, 'NONE'::character varying]::text[])),
  CONSTRAINT ck_landline_mobile_none2 CHECK (other_phone_type2::text = ANY (ARRAY['LANDLINE'::character varying, 'MOBILE'::character varying, 'NONE'::character varying]::text[])),
  CONSTRAINT ck_landline_mobile_none3 CHECK (other_phone_type3::text = ANY (ARRAY['LANDLINE'::character varying, 'MOBILE'::character varying, 'NONE'::character varying]::text[])),
  CONSTRAINT ck_record_status CHECK (record_status::text = ANY (ARRAY['I'::character varying, 'U'::character varying, 'D'::character varying]::text[]))
)
WITH (OIDS=FALSE);
ALTER TABLE people OWNER TO postgres;
GRANT ALL ON TABLE people TO postgres;

--Alter Statements if needed to alter the constraints 

-- Constraint: pk_serial

-- ALTER TABLE people DROP CONSTRAINT pk_serial;

ALTER TABLE people
  ADD CONSTRAINT pk_serial PRIMARY KEY(address_id);
-- Constraint: uq_contact_email

-- ALTER TABLE people DROP CONSTRAINT uq_contact_email;

ALTER TABLE people
  ADD CONSTRAINT uq_contact_email UNIQUE(contact_email);

-- Constraint: uq_home_phone

-- ALTER TABLE people DROP CONSTRAINT uq_home_phone;

ALTER TABLE people
  ADD CONSTRAINT uq_home_phone UNIQUE(home_phone);

-- Constraint: uq_last_first_middle_email_phone

-- ALTER TABLE people DROP CONSTRAINT uq_last_first_middle_email_phone;

ALTER TABLE people
  ADD CONSTRAINT uq_last_first_middle_email_phone UNIQUE(last_name, first_name, middle_name, birth_date, contact_email, home_phone, work_phone, postal_code, record_status);

-- Check: ck_landline_mobile_none1

-- ALTER TABLE people DROP CONSTRAINT ck_landline_mobile_none1;

ALTER TABLE people
  ADD CONSTRAINT ck_landline_mobile_none1 CHECK (other_phone_type1::text = ANY (ARRAY['LANDLINE'::character varying, 'MOBILE'::character varying, 'NONE'::character varying]::text[]));

-- Check: ck_landline_mobile_none2

-- ALTER TABLE people DROP CONSTRAINT ck_landline_mobile_none2;

ALTER TABLE people
  ADD CONSTRAINT ck_landline_mobile_none2 CHECK (other_phone_type2::text = ANY (ARRAY['LANDLINE'::character varying, 'MOBILE'::character varying, 'NONE'::character varying]::text[]));

-- Check: ck_landline_mobile_none3

-- ALTER TABLE people DROP CONSTRAINT ck_landline_mobile_none3;

ALTER TABLE people
  ADD CONSTRAINT ck_landline_mobile_none3 CHECK (other_phone_type3::text = ANY (ARRAY['LANDLINE'::character varying, 'MOBILE'::character varying, 'NONE'::character varying]::text[]));

-- Check: ck_record_status

-- ALTER TABLE people DROP CONSTRAINT ck_record_status;

ALTER TABLE people
  ADD CONSTRAINT ck_record_status CHECK (record_status::text = ANY (ARRAY['I'::character varying, 'U'::character varying, 'D'::character varying]::text[]));

       
