drop table Members
drop table Librarians
drop table Branch
drop table Rental_Due_On
drop table Book_Copy
drop table Renews
drop table Makes_Reservation_or_Rental
drop table Modifies
drop table Has_Books
drop table Time_Period
drop table Reservation_For
drop table Reserved_On


CREATE TABLE Members 
(
member_id 		CHAR(8),
address 		VARCHAR(50),
first_name 		VARCHAR (10),
last_name 		VARCHAR (10),
phone_number 	CHAR(10),
owing	 		REAL,
PRIMARY KEY (member_id)
);

insert into Members
values('00000001', '111 Alpha Drive Vancouver BC, V5Y 1S1', 'Annie', 'Appleseed', '7781111111', 000000);
insert into Members
values('00000002', '222 Beta Drive Vancouver BC, V5Y 1S2', 'Billy', 'Bedford', '7782222222', 000000);
insert into Members
values('00000003', '333 Gamma Drive Vancouver BC, V5Y 1S3', 'Connie', 'Chang', '7783333333', 000124);
insert into Members
values('00000004', '444 Delta Drive Vancouver BC, V5Y 1S4', 'Douglas', 'Dobson', '7784444444', 000000);
insert into Members
values('00000005', '555 Beta Drive Vancouver BC, V5Y 1S5', 'Ellie', 'Everett', '7785555555', 000025);


CREATE TABLE Librarians
(
member_id 		CHAR(8),
employee_id 		CHAR(8),
PRIMARY KEY (employee_id , member_id),
FOREIGN KEY (member_id) REFERENCES Members
);

insert into Librarians
values('10000001', '10000009');
insert into Librarians
values('10000002', '10000008');
insert into Librarians
values('10000003', '10000007');
insert into Librarians
values('10000004', '10000006');
insert into Librarians
values('10000005', '10000000');


CREATE TABLE Branch
(    
branch_id 		CHAR(4),
name       		CHAR(30),
city          		CHAR(20),
province  		CHAR(5),
PRIMARY KEY (branch_id)
);

insert into Branch
values('0001', 'Grouse Public Library', 'Vancouver', 'BC');
insert into Branch
values('0002', 'Seymour Public Library', 'Vancouver', 'BC');
insert into Branch
values('0003', 'Cypress Public Library', 'Vancouver', 'BC');
insert into Branch
values('0004', 'Cathedral Public Library', 'Vancouver', 'BC');
insert into Branch
values('0005', 'Coliseum Public Library', 'Vancouver', 'BC');


CREATE TABLE Rental_Due_On
(	
	copy_id		CHAR(4),
	start_date		DATETIME,
	end_date		DATETIME,
	rental_id		CHAR(10) not null,
PRIMARY KEY (rental_id),
FOREIGN KEY (start_date, end_date) REFERENCES Time_Period,
FOREIGN KEY (copy_id) REFERENCES Book_Copy
);

insert into Rental_Due_On
values('0001', 2013-10-18 13:42:24, 2013-11-01 13:42:24, '0000000001');
insert into Rental_Due_On
values('0002', 2013-10-18 08:42:00, 2013-11-01 08:42:00, '0000000002');
insert into Rental_Due_On
values('1003', 2013-10-19 09:42:24, 2013-11-02 09:42:24, '0000000003');
insert into Rental_Due_On
values('1004', 2013-10-20 12:42:24, 2013-11-03 12:42:24, '0000000004');
insert into Rental_Due_On
values('1005', 2013-10-21 00:42:24, 2013-10-04 00:42:24, '0000000005');


CREATE TABLE Book_Copy
(
copy_id		CHAR(4),
isbn            		CHAR(13)
PRIMARY KEY (copy_id, isbn),
FOREIGN KEY (isbn) REFERENCES Books
);

insert into Book_Copy
values('0001', '9780672327568');
insert into Book_Copy
values('0002', '9780672327568');
insert into Book_Copy
values('0001', '9780672327432');
insert into Book_Copy
values('0004', '9780672327231');
insert into Book_Copy
values('0012', '9780672327495');


CREATE TABLE Renews
(	
	member_id	 	CHAR(8)
	rental_id  		CHAR(10)
	PRIMARY KEY (member_id, rental_id), 
	FOREIGN KEY (member_id) REFERENCES Members
	FOREIGN KEY (transaction_id) REFERENCES Rental_Due_On
);

insert into Renews
values('00000001', '0000000001');
insert into Renews
values('10000002', '0000000002');
insert into Renews
values('00000003', '0000000003');
insert into Renews
values('00000004', '0000000004');
insert into Renews
values('00000005', '0000000005');


CREATE TABLE Makes_Reservation_or_Rental
(
	member_id 		CHAR(8),
	rental_id		CHAR(10),
	reservation_id	CHAR(10),
	PRIMARY KEY (member_id, rental_id, reservation_id),
	FOREIGN KEY (member_id) REFERENCES Members,
	FOREIGN KEY (rental_id) REFERENCES Rental_Due_On,
	FOREIGN KEY (reservation_id) REFERENCES Reservation_For
);

insert into Makes_Reservation_or_Rental
values('00000001', '0000000001', '0000000001');
insert into Makes_Reservation_or_Rental
values('00000002', '0000000002', '0000000002');
insert into Makes_Reservation_or_Rental
values('00000003', '0000000003', '0000000003');
insert into Makes_Reservation_or_Rental
values('00000004', '0000000004', '0000000004');
insert into Makes_Reservation_or_Rental
values('00000005', '0000000005', '0000000005');


CREATE TABLE Modifies
(
	employee_id 		CHAR(8)
	isbn			CHAR(13)
	PRIMARY KEY (employee_id, isbn)
	FOREIGN KEY (employee_id) REFERENCES Librarians
	FOREIGN KEY (isbn) REFERENCES Books
);

insert into Modifies
values('10000001', '9780672327432');
insert into Modifies
values('10000002', '9780672327564');
insert into Modifies
values('10000003', '9780672327245');
insert into Modifies
values('10000004', '9780672327436');
insert into Modifies
values('10000005', '9780672327231');


CREATE TABLE Has_Books 
(
isbn 			CHAR(13),
publisher 		CHAR(35),
title	    		CHAR(40),
author	    		CHAR(20),
branch_id 		CHAR(4) NOT NULL,
PRIMARY KEY (isbn),
FOREIGN KEY (branch_id) REFERENCES Branch,
ON DELETE CASCADE
);

insert into Has_Books
values('9780672327432', 'Apple Publishing', 'Win In 20 Days', 'Jimmy Johnson', '0001');
insert into Has_Books
values('9780672327231', 'Banana Publishing', 'Lose In 10 Days', 'Kimberly Kant', '0002');
insert into Has_Books
values('9780672327454', 'Carrot Publishing', 'How To Win Or Lose', 'Loretta Louis', '0003');
insert into Has_Books
values('9780672327243', 'Durian Publishing', 'Winning Is Not Everything', 'Marlene Mayall', '0004');
insert into Has_Books
values('9780672327432', 'Eggplant Publishing', 'Winning Really Is Everything', 'Nigella Ness', '0005');


CREATE TABLE Time_Period
(
	start_date		DATETIME
	end_date		DATETIME
	PRIMARY KEY (start_date, end_date)
);

insert into Time_Period
values(2013-10-19 09:42:25, 2013-11-01 09:42:25);
insert into Time_Period
values(2013-10-19 09:42:26, 2013-11-01 09:42:26);
insert into Time_Period
values(2013-10-19 09:42:26, 2013-11-01 09:42:26);
insert into Time_Period
values(2013-10-19 09:42:27, 2013-11-01 09:42:27);
insert into Time_Period
values(2013-10-19 09:42:28, 2013-11-01 09:42:28);


CREATE TABLE Reservation_For
(
reservation_id		CHAR(10)
isbn			CHAR(13)
PRIMARY KEY (reservation_id)
FOREIGN KEY (isbn) REFERENCES Books
);

insert into Reserved_For
values('0000000001', '9780672327432');
insert into Reserved_For
values('0000000002', '9780672327475');
insert into Reserved_For
values('0000000003', '9780672327456');
insert into Reserved_For
values('0000000004', '9780672327443');
insert into Reserved_For
values('0000000005', '9780672327425');


CREATE TABLE Reserved_On
(	
	member_id		CHAR(8)
	start_date		DATETIME
	end_date		DATETIME
	PRIMARY KEY (start_date, end_date, member_id)
	FOREIGN KEY (member_id) REFERENCES Members
	FOREIGN KEY (start_date, end_date) REFERENCES Time_Period
);

insert into Reserved_On
values('00000001', 2013-10-19 09:42:26, 2013-10-22 09:42:26);
insert into Reserved_On
values('00000002', 2013-10-20 09:42:26, 2013-10-23 09:42:26);
insert into Reserved_On
values('00000003', 2013-10-21 09:42:26, 2013-10-24 09:42:26);
insert into Reserved_On
values('00000004', 2013-10-22 09:42:26, 2013-10-25 09:42:26);
insert into Reserved_On
values('00000005', 2013-10-23 09:42:26, 2013-10-26 09:42:26);
