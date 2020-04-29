set foreign_key_checks=0;
drop table if exists user cascade;
drop table if exists student cascade;
drop table if exists alumni cascade;
drop table if exists faculty cascade;
drop table if exists grad_secretary cascade;
drop table if exists courses cascade;
drop table if exists transcript cascade;
drop table if exists semester cascade;
drop table if exists form1 cascade;

create table user(
    uid int,
    password varchar(50),
    fname varchar(30),
    minit varchar(1),
    lname varchar(30),
    email varchar(50),
    dob date,
    address varchar(80),
    account int,
    primary key (uid)
);

create table student(
    uid int,
    degree varchar(30),
    gpa decimal(3,2),
    advisor int,
    gradapp int,
    form1status int,
    primary key (uid),
    foreign key (advisor) references faculty(uid) on delete set null
);

create table alumni(
    uid int,
    degree varchar(30),
    gpa decimal(3,2),
    gradyear int,
    primary key (uid)
);

create table faculty(
    uid int,
    advisee int,
    primary key (uid),
    foreign key (advisee) references student(uid)
);

create table grad_secretary(
    uid int,
    primary key (uid)
);

create table courses(
    dept varchar(4),
    cno int,
    title varchar(50),
    credits int,
    p1dept varchar(4),
    p1cno int,
    p2dept varchar(4),
    p2cno int,
    primary key (dept, cno),
    foreign key (p1dept) references courses(dept),
    foreign key (p2dept) references courses(dept)
);

create table transcript(
    uid int,
    dept varchar(4),
    cno int,
    grade varchar(2),
    semesterid int,
    inform1 bool,
    primary key (uid, dept, cno),
    foreign key (semesterid) references semester(semesterid)
);

create table semester(
    semesterid int,
    semester varchar(10),
    year int,
    primary key (semesterid)
);

create table form1(
    uid int,
    dept varchar(4),
    cno int,
    primary key (uid, dept, cno)
);

INSERT INTO user (uid, password, fname, minit, lname, email, dob, address, account) VALUES
(1110, 'pass10', 'Lucas', 'J', 'Schiller', 'lucas@email.com', '2000-01-01', '123 Main St. USA', 1), /*Students*/
(1111, 'pass11', 'Josh', 'F', 'Moon', 'josh@email.com', '1998-06-12', '1874 W. 34th St. NYC', 1),
(1112, 'pass12', 'Sandy', 'I', 'Jones', 'sandjones@email.com', '2004-12-04', '5325 Beach Dr. Miami, FL 52315', 1),
(1113, 'pass13', 'Sophie', 'L', 'Laccro', 'bigsoph@au.edu', '2000-04-23', '782 N. Campus Dr. Washington, DC 20045', 1),
(1114, 'pass14', 'Michael', 'B', 'Jordan', 'michaelb@gwu.edu', '1987-02-09', '7843 E. Holly Blvd.', 1),
(1115, 'pass15', 'Daniel', 'H', 'Overtue', 'danielo@gwu.edu', '2000-07-30', '782 N. Campus Dr. Washington, DC 20045', 1),
(55555555, 'pass55', 'Paul', 'G', 'McCartney', 'paul@gwu.edu', '1955-01-23', '194 Britain Ln. NY', 1),
(66666666, 'pass66', 'George', 'T', 'Harrsion', 'george@gwu.edu', '1964-09-21', '6543 W. Hydro St. WY', 1),


(2220, 'pass20', 'Liam', 'K', 'Johnston', 'liam@email.com', '2002-02-02', '321 That Ave. NY', 2), /*Alumni*/
(2221, 'pass21', 'Benjamin', 'G', 'Devep', 'ben@gmail.com', '1980-09-07', '12 Orchard Ln. Singapore', 2),
(2222, 'pass22', 'Deon', 'N', 'Nguyen', 'deonnnn@asu.edu', '2000-04-02', '415 N Rio Verde Dr. Tempe, AZ', 2),
(77777777, 'pass77', 'Eric', 'I', 'Clapton', 'eric@gwu.edu', '1994-06-12', '1600 Pennsylvania Ave. Washington, DC', 2),

(3330, 'pass30', 'Joseph', 'L', 'Miller', 'joseph@email.com', '2001-03-03', '6234 Living Dr. CA', 3), /*Faculty*/
(3331, 'pass31', 'Jerry', 'B', 'Feldman', 'JF@email.com', '1975-10-12', '616 23rd St. NW', 3),
(3332, 'pass32', 'Diane', 'T', 'Denton', 'dd@email.com', '1965-06-24', '6234 Cactus St. AZ', 3),
(88888888, 'pass88', 'Bhagi', 'T', 'Narahari', 'bnarahari@email.com', '1969-01-09', '6114 GWU St. DC', 3),
(99999999, 'pass99', 'Gabe', 'T', 'Parmer', 'parmer@email.com', '1973-12-08', '1199 GWU St. DC', 3),

(4440, 'pass40', 'Kyle', 'H', 'Lingis', 'kyle@gmail.com', '1999-12-29', '1423 West Street MN', 4), /*Grad Secretaries*/
(4441, 'pass41', 'Daniel', 'G', 'Avila', 'daniel@gmail.com', '2000-03-21', '412 N 21st. St. Apt. 253', 4),

(5550, 'pass50', 'AJ', 'J', 'Hends', 'AJ@gwu.edu', '1998-09-29', '737 Oranges PA', 5), /*System Admins*/
(5551, 'pass51', 'Aaron', 'G', 'Fawley', 'AJF@pvcc.edu', '2000-06-12', '412 N 21st. St Apt. 213', 5);

INSERT INTO student (uid, degree, gpa, advisor, gradapp, form1status) VALUES
(1110, 'MS', '3.74', 3330, 0, 0),
(1111, 'MS', '2.35', 3330, 0, 0),
(1112, 'MS', '3.78', 3331, 0, 0),
(1113, 'PhD', '3.14', 3332, 0, 0),
(1114, 'PhD', '3.64', 3332, 0, 0),
(1115, 'PhD', '2.88', 3332, 0, 0),
(55555555, 'MS', '3.50', 88888888, 0, 0),
(66666666, 'MS', '3.08', 99999999, 0, 0);

INSERT INTO alumni (uid, degree, gpa, gradyear) VALUES
(2220, 'MS', '3.50', 2018),
(2221, 'PhD', '3.98', 2019),
(2222, 'MS', '4.00', 2020),
(77777777, 'MS', '3.50', 2014);

INSERT INTO courses (dept, cno, title, credits, p1dept, p1cno, p2dept, p2cno) VALUES
('CSCI', 6221, 'SW Paradigms', 3, NULL, NULL, NULL, NULL),
('CSCI', 6461, 'Computer Architecture', 3, NULL, NULL, NULL, NULL),
('CSCI', 6212, 'Algorithms', 3, NULL, NULL, NULL, NULL),
('CSCI', 6220, 'Machine Learning', 3, NULL, NULL, NULL, NULL),
('CSCI', 6232, 'Networks 1', 3, NULL, NULL, NULL, NULL),
('CSCI', 6233, 'Networks 2', 3, 'CSCI', 6232, NULL, NULL),
('CSCI', 6241, 'Database 1', 3, NULL, NULL, NULL, NULL),
('CSCI', 6242, 'Database 2', 3, 'CSCI', 6241, NULL, NULL),
('CSCI', 6246, 'Compilers', 3, 'CSCI', 6461, 'CSCI', 6212),
('CSCI', 6260, 'Multimedia', 3, NULL, NULL, NULL, NULL),
('CSCI', 6251, 'Cloud Computing', 3, 'CSCI', 6461, NULL, NULL),
('CSCI', 6254, 'SW Engineering', 3, 'CSCI', 6221, NULL, NULL),
('CSCI', 6262, 'Graphics 1', 3, NULL, NULL, NULL, NULL),
('CSCI', 6283, 'Security 1', 3, 'CSCI', 6212, NULL, NULL),
('CSCI', 6284, 'Cryptography', 3, 'CSCI', 6212, NULL, NULL),
('CSCI', 6286, 'Network Security', 3, 'CSCI', 6283, 'CSCI', 6232),
('CSCI', 6325, 'Algorithms 2', 3, 'CSCI', 6212, NULL, NULL),
('CSCI', 6339, 'Embedded Systems', 3, 'CSCI', 6461, 'CSCI', 6212),
('CSCI', 6384, 'Cryptography 2', 3, 'CSCI', 6284, NULL, NULL),
('ECE', 6241, 'Communication Theory', 3, NULL, NULL, NULL, NULL),
('ECE', 6242, 'Information Theory', 2, NULL, NULL, NULL, NULL),
('MATH', 6210, 'Logic', 2, NULL, NULL, NULL, NULL);

INSERT INTO transcript (uid, dept, cno, grade, semesterid, inform1) VALUES
(55555555, 'CSCI', 6221, 'A', 0, false),
(55555555, 'CSCI', 6212, 'A', 0, false),
(55555555, 'CSCI', 6461, 'A', 0, false),
(55555555, 'CSCI', 6232, 'A', 0, false),
(55555555, 'CSCI', 6233, 'A', 1, false),
(55555555, 'CSCI', 6241, 'B', 1, false),
(55555555, 'CSCI', 6246, 'B', 1, false),
(55555555, 'CSCI', 6262, 'B', 2, false),
(55555555, 'CSCI', 6283, 'B', 2, false),
(55555555, 'CSCI', 6242, 'B', 2, false),

(66666666, 'CSCI', 6221, 'B', 0, false),
(66666666, 'CSCI', 6461, 'B', 0, false),
(66666666, 'CSCI', 6212, 'B', 0, false),
(66666666, 'CSCI', 6232, 'B', 0, false),
(66666666, 'CSCI', 6233, 'B', 1, false),
(66666666, 'CSCI', 6241, 'B', 1, false),
(66666666, 'CSCI', 6242, 'B', 1, false),
(66666666, 'CSCI', 6283, 'B', 1, false),
(66666666, 'CSCI', 6284, 'B', 2, false),
(66666666, 'ECE', 6244, 'C', 2, false),

(77777777, 'CSCI', 6221, 'B', 0, false),
(77777777, 'CSCI', 6212, 'B', 0, false),
(77777777, 'CSCI', 6461, 'B', 0, false),
(77777777, 'CSCI', 6232, 'B', 0, false),
(77777777, 'CSCI', 6233, 'B', 1, false),
(77777777, 'CSCI', 6241, 'B', 1, false),
(77777777, 'CSCI', 6242, 'B', 1, false),
(77777777, 'CSCI', 6283, 'A', 2, false),
(77777777, 'CSCI', 6284, 'A', 2, false),
(77777777, 'CSCI', 6286, 'A', 2, false),

(1110, 'CSCI', 6284, 'A-', 2, false),
(1111, 'CSCI', 6241, 'B+', 1, false),
(1112, 'CSCI', 6241, 'B-', 1, false),
(1113, 'CSCI', 6241, 'C+', 1, false),
(1114, 'CSCI', 6241, 'C-', 1, false),
(1115, 'CSCI', 6241, 'D+', 1, false),

(2220, 'CSCI', 6241, 'D', 1, false),
(2221, 'CSCI', 6241, 'D-', 1, false),
(2222, 'CSCI', 6241, 'F', 1, false);


INSERT INTO semester (semesterid, semester, year) VALUES
(0, 'FALL', 2019),
(1, 'SPRING', 2020),
(2, 'FALL', 2020),
(3, 'SPRING', 2021);

set foreign_key_checks=1;
