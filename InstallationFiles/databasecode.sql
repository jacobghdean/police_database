
DROP TABLE IF EXISTS Tasks;
DROP TABLE IF EXISTS Users;

# We first set up a table to record the login details of police officers as well whether or not they have administrator privileges
# By default, new accounts will not be admin accounts and we set the auto-incrementing UserID as the primary key
# Usernames should also be unique and neither usernames nor passwords should be able to be set as NULL

CREATE TABLE Users (
	UserID int(11) NOT NULL AUTO_INCREMENT,
	Username varchar(50) NOT NULL UNIQUE,
    Password varchar(50) NOT NULL,
    Permissions varchar(50) DEFAULT 'Regular',
    CONSTRAINT pk_userid PRIMARY KEY (UserID)
);

# Here we enter the login data provided into the Users table

INSERT INTO Users (Username, Password, Permissions) VALUES
('McNulty', 'plod123', 'Regular'),
('Moreland','fuzz42', 'Regular'),
('Daniels','copper99', 'Administrator');

# For one of the bonus features, we set up functionality for administrators to assign regular officers tasks
# These are stored in this table along with the ID of the officer who is responsible for it
# UserID comes from the Users table, making it a foreign key relationship

CREATE TABLE Tasks (
	TaskID int(11) NOT NULL AUTO_INCREMENT,
	Task_Description varchar(200) NOT NULL,
	UserID int(11) NOT NULL,
	CONSTRAINT pk_taskid PRIMARY KEY (TaskID),
	CONSTRAINT fk_task_user FOREIGN KEY (UserID)
	REFERENCES Users (UserID)
);

# Here is some sample task data that we insert in order to demonstrate the functionality of the task setting feature - the tasks of officer X can be viewed on the home page by logging into officer X's account

INSERT INTO Tasks (Task_Description, UserID) VALUES
('Add car incident records from last week', 1),
('Remove Jennifer Allen from the database', 1),
('Prosecute Terry Brown', 1),
('Make a report of the most common incidents', 2),
('Think about which fines should be changed', 2)
;

# At the bottom of this SQL file, I have made some modifications to the foreign key relationships
# This is because I have added the ability for officers to delete people, vehicles, and incidents from the database and there are default restrictions in place because of these relationships
# If a person or vehicle is deleted, I have set the convention to ON DELETE CASCADE for the Ownership table because the relationship should vanish when one or the other is deleted
# However, for the Incident table I have set the convention to ON DELETE SET NULL, because there is still useful incident information that should remain even if either the vehicle or person involved is deleted

DROP TABLE IF EXISTS Fines;
CREATE TABLE Fines (
  Fine_ID int(11) NOT NULL,
  Fine_Amount int(11) NOT NULL,
  Fine_Points int(11) NOT NULL,
  Incident_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Fines (Fine_ID, Fine_Amount, Fine_Points, Incident_ID) VALUES
(1, 2000, 6, 3),
(2, 50, 0, 2),
(3, 500, 3, 4);

DROP TABLE IF EXISTS Incident;
CREATE TABLE Incident (
  Incident_ID int(11) NOT NULL,
  Vehicle_ID int(11) DEFAULT NULL,
  People_ID int(11) DEFAULT NULL,
  Incident_Date date NOT NULL,
  Incident_Report varchar(500) NOT NULL,
  Offence_ID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Incident (Incident_ID, Vehicle_ID, People_ID, Incident_Date, Incident_Report, Offence_ID) VALUES
(1, 15, 4, '2017-12-01', '40mph in a 30 limit', 1),
(2, 20, 8, '2017-11-01', 'Double parked', 4),
(3, 13, 4, '2017-09-17', '110mph on motorway', 1),
(4, 14, 2, '2017-08-22', 'Failure to stop at a red light - travelling 25mph', 8),
(5, 13, 4, '2017-10-17', 'Not wearing a seatbelt on the M1', 3);

DROP TABLE IF EXISTS Offence;
CREATE TABLE Offence (
  Offence_ID int(11) NOT NULL,
  Offence_description varchar(50) NOT NULL,
  Offence_maxFine int(11) NOT NULL,
  Offence_maxPoints int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Offence (Offence_ID, Offence_description, Offence_maxFine, Offence_maxPoints) VALUES
(1, 'Speeding', 1000, 3),
(2, 'Speeding on a motorway', 2500, 6),
(3, 'Seat belt offence', 500, 0),
(4, 'Illegal parking', 500, 0),
(5, 'Drink driving', 10000, 11),
(6, 'Driving without a licence', 10000, 0),
(7, 'Driving without a licence', 10000, 0),
(8, 'Traffic light offences', 1000, 3),
(9, 'Cycling on pavement', 500, 0),
(10, 'Failure to have control of vehicle', 1000, 3),
(11, 'Dangerous driving', 1000, 11),
(12, 'Careless driving', 5000, 6),
(13, 'Dangerous cycling', 2500, 0);

DROP TABLE IF EXISTS Ownership;
CREATE TABLE Ownership (
  People_ID int(11) NOT NULL,
  Vehicle_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Ownership (People_ID, Vehicle_ID) VALUES
(3, 12),
(8, 20),
(4, 15),
(4, 13),
(1, 16),
(2, 14),
(5, 17),
(6, 18),
(7, 21);

DROP TABLE IF EXISTS People;
CREATE TABLE People (
  People_ID int(11) NOT NULL,
  People_name varchar(50) NOT NULL,
  People_address varchar(50) DEFAULT NULL,
  People_licence varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO People (People_ID, People_name, People_address, People_licence) VALUES
(1, 'James Smith', '23 Barnsdale Road, Leicester', 'SMITH92LDOFJJ829'),
(2, 'Jennifer Allen', '46 Bramcote Drive, Nottingham', 'ALLEN88K23KLR9B3'),
(3, 'John Myers', '323 Derby Road, Nottingham', 'MYERS99JDW8REWL3'),
(4, 'James Smith', '26 Devonshire Avenue, Nottingham', 'SMITHR004JFS20TR'),
(5, 'Terry Brown', '7 Clarke Rd, Nottingham', 'BROWND3PJJ39DLFG'),
(6, 'Mary Adams', '38 Thurman St, Nottingham', 'ADAMSH9O3JRHH107'),
(7, 'Neil Becker', '6 Fairfax Close, Nottingham', 'BECKE88UPR840F9R'),
(8, 'Angela Smith', '30 Avenue Road, Grantham', 'SMITH222LE9FJ5DS'),
(9, 'Xene Medora', '22 House Drive, West Bridgford', 'MEDORH914ANBB223');

DROP TABLE IF EXISTS Vehicle;
CREATE TABLE Vehicle (
  Vehicle_ID int(11) NOT NULL,
  Vehicle_type varchar(20) NOT NULL,
  Vehicle_colour varchar(20) NOT NULL,
  Vehicle_licence varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Vehicle (Vehicle_ID, Vehicle_type, Vehicle_colour, Vehicle_licence) VALUES
(12, 'Ford Fiesta', 'Blue', 'LB15AJL'), # Why on earth does Vehicle ID start from 12?
(13, 'Ferrari 458', 'Red', 'MY64PRE'),
(14, 'Vauxhall Astra', 'Silver', 'FD65WPQ'),
(15, 'Honda Civic', 'Green', 'FJ17AUG'),
(16, 'Toyota Prius', 'Silver', 'FP16KKE'),
(17, 'Ford Mondeo', 'Black', 'FP66KLM'),
(18, 'Ford Focus', 'White', 'DJ14SLE'),
(20, 'Nissan Pulsar', 'Red', 'NY64KWD'),
(21, 'Renault Scenic', 'Silver', 'BC16OEA'),
(22, 'Hyundai i30', 'Grey', 'AD223NG');


ALTER TABLE Fines
  ADD PRIMARY KEY (Fine_ID),
  ADD KEY Incident_ID (Incident_ID);

ALTER TABLE Incident
  ADD PRIMARY KEY (Incident_ID),
  ADD KEY fk_incident_vehicle (Vehicle_ID),
  ADD KEY fk_incident_people (People_ID),
  ADD KEY fk_incident_offence (Offence_ID);

ALTER TABLE Offence
  ADD PRIMARY KEY (Offence_ID);

ALTER TABLE Ownership
  ADD KEY fk_people (People_ID),
  ADD KEY fk_vehicle (Vehicle_ID);

ALTER TABLE People
  ADD PRIMARY KEY (People_ID);

ALTER TABLE Vehicle
  ADD PRIMARY KEY (Vehicle_ID);


ALTER TABLE Fines
  MODIFY Fine_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE Incident
  MODIFY Incident_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE Offence
  MODIFY Offence_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE People
  MODIFY People_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
ALTER TABLE Vehicle
  MODIFY Vehicle_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

ALTER TABLE Fines
  ADD CONSTRAINT fk_fines FOREIGN KEY (Incident_ID) REFERENCES Incident (Incident_ID) ON DELETE CASCADE;

ALTER TABLE Incident
  ADD CONSTRAINT fk_incident_offence FOREIGN KEY (Offence_ID) REFERENCES Offence (Offence_ID),
  ADD CONSTRAINT fk_incident_people FOREIGN KEY (People_ID) REFERENCES People (People_ID) ON DELETE SET NULL,
  ADD CONSTRAINT fk_incident_vehicle FOREIGN KEY (Vehicle_ID) REFERENCES Vehicle (Vehicle_ID) ON DELETE SET NULL;

ALTER TABLE Ownership
  ADD CONSTRAINT fk_person FOREIGN KEY (People_ID) REFERENCES People (People_ID) ON DELETE CASCADE,
  ADD CONSTRAINT fk_vehicle FOREIGN KEY (Vehicle_ID) REFERENCES Vehicle (Vehicle_ID) ON DELETE CASCADE;

