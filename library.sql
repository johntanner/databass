drop table Members CASCADE CONSTRAINTS;
drop table Librarians CASCADE CONSTRAINTS;
drop table Branch CASCADE CONSTRAINTS;
drop table Rental_Due_On CASCADE CONSTRAINTS;
drop table Book_Copy CASCADE CONSTRAINTS;
drop table Renews CASCADE CONSTRAINTS;
drop table Makes_Reservation_Or_Rental CASCADE CONSTRAINTS;
drop table Modifies CASCADE CONSTRAINTS;
drop table Has_Books CASCADE CONSTRAINTS;
drop table Time_Period CASCADE CONSTRAINTS;
drop table Reservation_For CASCADE CONSTRAINTS;
drop table Reserved_On CASCADE CONSTRAINTS;


CREATE TABLE Members 
(
member_id CHAR(8),
address VARCHAR(50),
first_name VARCHAR(10),
last_name VARCHAR(10),
phone_number CHAR(10),
owing REAL,
username VARCHAR(12),
password VARCHAR(12),
permissions int NOT NULL CHECK(permissions >= 0 and permissions <= 2),
PRIMARY KEY (member_id)
);

insert into Members
values('00000001', '111 Alpha Drive Vancouver BC, V5Y 1S1', 'Annie', 'Appleseed', '7781111111', 000000, 'aapple', 'applepie00', 2);
insert into Members
values('00000002', '222 Beta Drive Vancouver BC, V5Y 1S2', 'Billy', 'Bedford', '7782222222', 000000, 'bbedfo', 'bedframe11', 2);
insert into Members
values('00000003', '333 Gamma Drive Vancouver BC, V5Y 1S3', 'Connie', 'Chang', '7783333333', 000124, 'cchang', 'changeling22', 1);
insert into Members
values('00000004', '444 Delta Drive Vancouver BC, V5Y 1S4', 'Douglas', 'Dobson', '7784444444', 000000, 'ddobs', 'dougie33', 1);
insert into Members
values('00000005', '555 Beta Drive Vancouver BC, V5Y 1S5', 'Ellie', 'Everett', '7785555555', 000025, 'eever', 'neverever44', 0);
insert into Members
values('00000006', '666 Epsilon Drive Vancouver BC, V5Y 1S6', 'Fanny', 'Frampton', '7781111111', 000000, 'ffanny', 'fanman55', 2);
insert into Members
values('00000007', '777 Zeta Drive Vancouver BC, V5Y 1S7', 'Gary', 'Gordon', '7782222222', 000000, 'ggordo', 'garment66', 2);
insert into Members
values('00000008', '888 Eta Drive Vancouver BC, V5Y 1S8', 'Harry', 'Hilton', '7782222222', 000000, 'hhilt', 'hillside77', 2);

CREATE TABLE Librarians
(
member_id CHAR(8),
employee_id CHAR(8),
PRIMARY KEY (member_id, employee_id),
FOREIGN KEY (member_id) REFERENCES Members ON DELETE CASCADE
);

insert into Librarians
values('00000001', '10000001');
insert into Librarians
values('00000002', '10000002');
insert into Librarians
values('00000006', '10000006');
insert into Librarians
values('00000007', '10000007');
insert into Librarians
values('00000008', '10000008');


CREATE TABLE Branch
(    
branch_id CHAR(4),
name CHAR(30),
city CHAR(20),
province CHAR(5),
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


CREATE TABLE Time_Period
(
start_date DATE,
end_date DATE,
PRIMARY KEY (start_date, end_date)
);

insert into Time_Period
values('19-Oct-13', '01-Nov-13');
insert into Time_Period
values('20-Oct-13', '02-Nov-13');
insert into Time_Period
values('21-Oct-13', '03-Nov-13');
insert into Time_Period
values('22-Oct-13', '04-Nov-13');
insert into Time_Period
values('23-Oct-13', '05-Nov-13');


CREATE TABLE Has_Books 
(
isbn CHAR(13),
publisher CHAR(35),
title CHAR(40),
author CHAR(20),
branch_id CHAR(4) NOT NULL,
PRIMARY KEY (isbn),
FOREIGN KEY (branch_id) REFERENCES Branch ON DELETE CASCADE
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
values('9780672327433', 'Eggplant Publishing', 'Winning Really Is Everything', 'Nigella Ness', '0005');


CREATE TABLE Book_Copy
(
copy_id CHAR(4),
isbn CHAR(13),
PRIMARY KEY (copy_id, isbn),
FOREIGN KEY (isbn) REFERENCES Has_Books ON DELETE CASCADE
);

insert into Book_Copy
values('0001', '9780672327432');
insert into Book_Copy
values('0002', '9780672327231');
insert into Book_Copy
values('0001', '9780672327454');
insert into Book_Copy
values('0004', '9780672327243');
insert into Book_Copy
values('0012', '9780672327433');


CREATE TABLE Rental_Due_On
(	
copy_id CHAR(4),
isbn CHAR(13),
start_date DATE,
end_date DATE,
rental_id CHAR(10) NOT NULL,
PRIMARY KEY (rental_id),
FOREIGN KEY (start_date, end_date) REFERENCES Time_Period ON DELETE CASCADE,
FOREIGN KEY (copy_id, isbn) REFERENCES Book_Copy ON DELETE CASCADE
);

insert into Rental_Due_On
values('0001', '9780672327432', '19-Oct-13', '01-Nov-13', '0000000001');
insert into Rental_Due_On
values('0002', '9780672327231', '20-Oct-13', '02-Nov-13', '0000000002');
insert into Rental_Due_On
values('0001', '9780672327454', '21-Oct-13', '03-Nov-13', '0000000003');
insert into Rental_Due_On
values('0004', '9780672327243', '22-Oct-13', '04-Nov-13', '0000000004');
insert into Rental_Due_On
values('0012', '9780672327433', '23-Oct-13', '05-Nov-13', '0000000005');


CREATE TABLE Renews
(	
member_id CHAR(8),
rental_id CHAR(10),
PRIMARY KEY (member_id, rental_id), 
FOREIGN KEY (member_id) REFERENCES Members ON DELETE CASCADE,
FOREIGN KEY (rental_id) REFERENCES Rental_Due_On ON DELETE CASCADE
);

insert into Renews
values('00000001', '0000000001');
insert into Renews
values('00000002', '0000000002');
insert into Renews
values('00000003', '0000000003');
insert into Renews
values('00000004', '0000000004');
insert into Renews
values('00000005', '0000000005');


CREATE TABLE Reservation_For
(
reservation_id CHAR(10),
isbn CHAR(13),
PRIMARY KEY (reservation_id),
FOREIGN KEY (isbn) REFERENCES Has_Books
);

insert into Reservation_For
values('0000000001', '9780672327432');
insert into Reservation_For
values('0000000002', '9780672327231');
insert into Reservation_For
values('0000000003', '9780672327454');
insert into Reservation_For
values('0000000004', '9780672327243');
insert into Reservation_For
values('0000000005', '9780672327433');


CREATE TABLE Makes_Reservation_Or_Rental
(
member_id CHAR(8),
rental_id CHAR(10),
reservation_id CHAR(10),
PRIMARY KEY (member_id, rental_id, reservation_id),
FOREIGN KEY (member_id) REFERENCES Members ON DELETE CASCADE,
FOREIGN KEY (rental_id) REFERENCES Rental_Due_On ON DELETE CASCADE,
FOREIGN KEY (reservation_id) REFERENCES Reservation_For ON DELETE CASCADE
);

insert into Makes_Reservation_Or_Rental
values('00000001', '0000000001', '0000000001');
insert into Makes_Reservation_Or_Rental
values('00000002', '0000000002', '0000000002');
insert into Makes_Reservation_Or_Rental
values('00000003', '0000000003', '0000000003');
insert into Makes_Reservation_Or_Rental
values('00000004', '0000000004', '0000000004');
insert into Makes_Reservation_Or_Rental
values('00000005', '0000000005', '0000000005');


CREATE TABLE Modifies
(
member_id CHAR(8),
employee_id CHAR(8),
isbn CHAR(13),
PRIMARY KEY (member_id, employee_id),
FOREIGN KEY (member_id, employee_id) REFERENCES Librarians ON DELETE CASCADE,
FOREIGN KEY (isbn) REFERENCES Has_Books ON DELETE CASCADE
);

insert into Modifies
values('00000001', '10000001', '9780672327432');
insert into Modifies
values('00000002', '10000002', '9780672327454');
insert into Modifies
values('00000006', '10000006', '9780672327243');
insert into Modifies
values('00000007', '10000007', '9780672327433');
insert into Modifies
values('00000008', '10000008', '9780672327231');



CREATE TABLE Reserved_On
(	
member_id CHAR(8),
start_date DATE,
end_date DATE,
PRIMARY KEY (start_date, end_date, member_id),
FOREIGN KEY (member_id) REFERENCES Members ON DELETE CASCADE,
FOREIGN KEY (start_date, end_date) REFERENCES Time_Period ON DELETE CASCADE
);

insert into Reserved_On
values('00000001', '19-Oct-13', '01-Nov-13');
insert into Reserved_On
values('00000002', '20-Oct-13', '02-Nov-13');
insert into Reserved_On
values('00000003', '21-Oct-13', '03-Nov-13');
insert into Reserved_On
values('00000004', '22-Oct-13', '04-Nov-13');
insert into Reserved_On
values('00000005', '23-Oct-13', '05-Nov-13');
