SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS student CASCADE;
DROP TABLE IF EXISTS faculty CASCADE;
DROP TABLE IF EXISTS alumni CASCADE;
DROP TABLE IF EXISTS applicant CASCADE;
DROP TABLE IF EXISTS review_form CASCADE;
DROP TABLE IF EXISTS letter_rating CASCADE;
DROP TABLE IF EXISTS application_form CASCADE;
DROP TABLE IF EXISTS prior_degrees CASCADE;
DROP TABLE IF EXISTS transcript CASCADE;
DROP TABLE IF EXISTS experience CASCADE;
DROP TABLE IF EXISTS GRE_score CASCADE;
DROP TABLE IF EXISTS TOEFL_score CASCADE;
DROP TABLE IF EXISTS Adv_GRE CASCADE;
DROP TABLE IF EXISTS rec_letter CASCADE;
DROP TABLE IF EXISTS deficient_courses CASCADE;
DROP TABLE IF EXISTS final_decision CASCADE;
DROP TABLE IF EXISTS decisions CASCADE;
DROP TABLE IF EXISTS recommendations CASCADE;
DROP TABLE IF EXISTS semester CASCADE;
DROP TABLE IF EXISTS form1 CASCADE;
DROP TABLE IF EXISTS catalog CASCADE;
DROP TABLE IF EXISTS schedule CASCADE;
DROP TABLE IF EXISTS courses_taken CASCADE;
DROP TABLE IF EXISTS courses_taught CASCADE;
DROP TABLE IF EXISTS prereqs CASCADE;
DROP TABLE IF EXISTS student_transcript CASCADE;


create table users(
id int UNIQUE,
p_level int NOT NULL, /* 1 for admin, 2 for gs, 4 for faculty, 5 for students, 6 for alumni, 7 for applicants*/
password varchar(20) NOT NULL,
primary key (id)
);

DROP TABLE IF EXISTS student CASCADE;
create table student(
u_id int NOT NULL,
fname varchar(20) NOT NULL,
lname varchar(20) NOT NULL,
addr varchar(50) NOT NULL,
email varchar(30) NOT NULL,
major varchar(20) NOT NULL,
degree varchar(3),
gpa decimal(3,2),
gradapp int,
form1status int,
advisor int,
primary key (u_id),
foreign key (u_id) references users(id)
);

DROP TABLE IF EXISTS faculty CASCADE;
CREATE TABLE faculty(
f_id int NOT NULL,
fname varchar(20) NOT NULL,
lname varchar(20) NOT NULL,
addr varchar(50) NOT NULL,
email varchar(30) NOT NULL,
dept varchar(4) NOT NULL,
reviewer int(1) NOT NULL, /*1 means reviewer, 0 means not reviewer*/
chair int(1), /*1 means chairman, 0 means not chairman, only 1 chairman allowed*/
primary key (f_id),
foreign key (f_id) references users(id)
);

create table alumni(
    a_id int NOT NULL,
    fname varchar(20) NOT NULL,
    lname varchar(20) NOT NULL,
    degree varchar(3),
    gpa decimal(3,2),
    email varchar(30),
    gradyear int,
    addr varchar(50) NOT NULL,
    primary key (a_id),
    foreign key (a_id) references users(id)
);

create table applicant(
app_id int NOT NULL,
fname varchar(20) NOT NULL,
lname varchar(20) NOT NULL,
degree varchar(3) NOT NULL,
ssn int(9) NOT NULL,
app_status int(1),
primary key (app_id),
foreign key (app_id) references users(id)
);


create table decisions (
	decisionID int(1),
	description varchar(140),

	primary key (decisionID)
);

create table recommendations (
	recID int(1),
	description varchar(100),

	primary key (recID)
);

/*application form
* main form has its own table and references to supplements
* Prior Degrees, test scores, and letters are referenced from application
* form */
create table application_form (
	applicationID int(8) AUTO_INCREMENT,
	address1 varchar(32),
	address2 varchar(32),
	city varchar(32),
	state varchar(2),
	zip int(5),
	userID int(8) UNIQUE,
	interest varchar(32),
	term varchar(32),
	degree varchar(32),
	submitted int(1) DEFAULT 0,
	decision int(1) DEFAULT 0,
	finalReviewer int(8) DEFAULT NULL,

	primary key (applicationID),
 	foreign key (userID) references users (id),
 	foreign key (finalReviewer) references users (id),
 	foreign key (decision) references decisions (decisionID)
);

create table transcript (
	applicationID int(8),
	pathToFile varchar(25),
	received DATE,

	primary key (applicationID),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table student_transcript (
	t_id int, 
	dept varchar(4),
    cno int,
    grade varchar(2),
    semesterid int,
    inform1 bool,
	primary key (t_id, dept, cno), 
	foreign key (semesterid) references semester(semesterid),
    foreign key (t_id) references users(id)
);


/*application form
* main form has it's own table and references to supplements
* Prior Degrees, test scores, and letters are referenced from application
* form */

create table prior_degrees (
	applicationID int(8),
	institution varchar(25),
	gpa decimal(3, 2),
	major varchar(25),
	gradYear int(4),
	degreeType varchar(10),

	primary key(applicationID, institution, degreeType, gradYear),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table rec_letter (
	letterID int(4) AUTO_INCREMENT,
	applicationID int(8),
	writerName varchar(25),
	writerTitle varchar(25),
	writerEmployer varchar(25),
	writerEmail varchar(25),
	letter varchar(25) DEFAULT NULL,
	received DATE,

	primary key (letterID),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE,
	unique (applicationID, writerEmail)
);

create table experience (
	applicationID int(8),
	employer varchar(25),
	startDate DATE,
	endDate DATE,
	position varchar(25),
	description varchar(100),

	primary key(applicationID, employer, position),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table GRE_score (
	applicationID int(8),
	totalScore int(3),
	examDate DATE,
	verbalScore int(3),
	writtenScore decimal(2, 1),
	quantScore int(3),

	primary key (applicationID, examDate),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table TOEFL_score (
	applicationID int(8),
	examDate DATE,
	totalScore int(8),

	primary key (applicationID, examDate),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table Adv_GRE (
	applicationID int(8),
	examDate DATE,
	totalScore int(8),
	subject varchar(32),

	primary key (applicationID, examDate),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
);


/*create tables for letter review and review form
* to be filled out by faculty reviewers */
create table letter_rating (
	facultyID int(8),
	applicationID int(8),
	letterID int(4),
	score int(4),

	primary key (facultyID, letterID),
	constraint FK_letter0 foreign key (facultyID) references users (id) on UPDATE CASCADE ON DELETE CASCADE,
	constraint FK_letter1 foreign key (letterID) references rec_letter (letterID) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint FK_letter2 foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
);

/* seperate table for deficient courses prevents update anomalies
* to add a deficient course for an applicant we would need to seach
* all of the review table */
create table deficient_courses (
	applicantID int(8),
	courseName varchar(32),

	primary key (applicantID, courseName),
	constraint FK_courses0 foreign key (applicantID) references users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table review_form (
	facultyID int(8),
	applicantID int(8),
	letterRating int(4),
	suggested_decision int(4),
	reasons varchar(100),
	comments varchar(100),

	primary key (facultyID, applicantID),
	constraint FK_revForm0 foreign key (facultyID) references users (id) ON UPDATE CASCADE ON DELETE NO ACTION,
	constraint FK_revForm1 foreign key (applicantID) references users (id) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint FK_revForm3 foreign key (applicantID) references deficient_courses (applicantID) ON UPDATE CASCADE ON DELETE NO ACTION
);

create table final_decision (
	facultyID int(8),
	applicantID int(8),
	decision int(4),

	primary key (facultyID, applicantID),
	foreign key (facultyID) references users (id) ON UPDATE CASCADE ON DELETE NO ACTION,
	foreign key (applicantID) references users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table semester(
    semesterid int,
    semester varchar(10),
    year int,
    primary key (semesterid)
);

create table form1(
    f1_id int,
    dept varchar(4),
    cno int,
    primary key (f1_id, dept, cno),
    foreign key (f1_id) references student(u_id)
);

CREATE TABLE catalog(
c_id int AUTO_INCREMENT,
department varchar(20) NOT NULL,
c_no int NOT NULL,
title varchar(30) NOT NULL,
credits int NOT NULL,
primary key (c_id)
);

create table schedule(
crn int AUTO_INCREMENT,
course_id int NOT NULL,
section_no int NOT NULL,
sem int NOT NULL,
day char(1) NOT NULL,
start_time TIME NOT NULL,
end_time TIME NOT NULL,
primary key (crn),
foreign key (course_id) references catalog(c_id)
);

create table courses_taken(
u_id int NOT NULL,
crn int NOT NULL,
grade varchar(2) NOT NULL,
primary key (u_id, crn),
foreign key (u_id) references student(u_id),
foreign key (crn) references schedule(crn)
);

create table courses_taught(
f_id int NOT NULL,
crn int NOT NULL,
primary key (f_id, crn),
foreign key (f_id) references faculty(f_id),
foreign key (crn) references schedule(crn)
);

create table prereqs(
course_Id int NOT NULL,
prereq1 varchar(20) NOT NULL,
prereq2 varchar(20) DEFAULT NULL,
primary key (course_Id, prereq1),
foreign key (course_Id) references catalog(c_id)
);

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users (id, p_level, password) VALUES (10000000, 1, 'admin');
INSERT INTO users (id, p_level, password) VALUES (10000001, 2, 'gs123');
INSERT INTO users (id, p_level, password) VALUES (10000111, 4, 'Chairman');
INSERT INTO users (id, p_level, password) VALUES (10000002, 4, 'bhagiweb');
INSERT INTO users (id, p_level, password) VALUES (10000003, 4, 'choi123');
INSERT INTO users (id, p_level, password) VALUES (10000004, 4, 'PASS');
INSERT INTO users (id, p_level, password) VALUES (10000005, 4, 'Pass999');
INSERT INTO users (id, p_level, password) VALUES (10000006, 4, 'PASSWORD');
INSERT INTO users (id, p_level, password) VALUES (10000007, 4, 'pass123');
INSERT INTO users (id, p_level, password) VALUES (10000008, 4, 'pass789');
INSERT INTO users (id, p_level, password) VALUES (10000009, 4, 'pass456');
INSERT INTO users (id, p_level, password) VALUES (10000010, 4, 'passwd');
INSERT INTO users (id, p_level, password) VALUES (10000011, 4, 'safepwrd');
INSERT INTO users (id, p_level, password) VALUES (10000012, 4, 'pwrd1');
INSERT INTO users (id, p_level, password) VALUES (10000013, 4, 'Pwrd2');
INSERT INTO users (id, p_level, password) VALUES (88888888, 5, 'password');
INSERT INTO users (id, p_level, password) VALUES (99999999, 5, 'pword');
INSERT INTO users (id, p_level, password) VALUES (23456789, 5, 'pwrd3');
INSERT INTO users (id, p_level, password) VALUES (87654321, 5, 'pwrd4');
INSERT INTO users (id, p_level, password) VALUES (45678901, 5, 'pwrd5');
INSERT INTO users (id, p_level, password) VALUES (14444444, 5, 'pwrd6');
INSERT INTO users (id, p_level, password) VALUES (66666666, 5, 'pwrd7');
INSERT INTO users (id, p_level, password) VALUES (12345678, 5, 'pwrd8');
INSERT INTO users (id, p_level, password) VALUES (77777777, 6, 'pwrd9');
INSERT INTO users (id, p_level, password) VALUES (34567890, 6, 'pwrd10');
INSERT INTO users (id, p_level, password) VALUES (15555555, 7, 'pwrd11');
INSERT INTO users (id, p_level, password) VALUES (16666666, 7, 'pwrd12');
INSERT INTO users (id, p_level, password) VALUES (00001234, 7, 'pwrd13');
INSERT INTO users (id, p_level, password) VALUES (00001235, 7, 'pwrd14');
INSERT INTO users (id, p_level, password) VALUES (00001236, 7, 'pwrd15');

INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gradapp, form1status, advisor) VALUES (88888888, 'Billie', 'Holiday', '11111 Street St. City, ST 22222', 'jacobpritchard9@gwu.edu', 'Computer Science', 'MS', 0, 0, 10000012);
INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gradapp, form1status, advisor) VALUES (99999999, 'Diana', 'Krall', '33333 Drive Dr. City, ST 44444', 'jacobpritchard9@gwu.edu', 'Computer Science', 'MS', 0, 0, 10000009);
INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gradapp, form1status, advisor) VALUES (23456789, 'Ella', 'Fitzgerald', '12121 Street Dr. City, ST 22325', 'jacobpritchard9@gwu.edu', 'Computer Science', 'PhD', 0, 0, 10000002);
INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gpa, gradapp, form1status, advisor) VALUES (87654321, 'Eva', 'Cassidy', '34373 Drive St. City, ST 47424', 'jacobpritchard9@gwu.edu', 'Computer Science', 'MS', '3.40', 0, 1, 10000011);
INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gpa, gradapp, form1status, advisor) VALUES (45678901, 'Jimi', 'Hendrix', '71121 Street Ct. City, ST 12325', 'jacobpritchard9@gwu.edu', 'Computer Science', 'MS', '3.77', 0, 0, 10000010);
INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gpa, gradapp, form1status, advisor) VALUES (14444444, 'Paul', 'McCartney', '43393 Drive Ct. City, ST 40041', 'jacobpritchard9@gwu.edu', 'Computer Science', 'MS','3.50', 0, 2, 10000002);
INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gpa, gradapp, form1status, advisor) VALUES (66666666, 'George', 'Harrison', '19010 Street Pl. City, ST 22032', 'jacobpritchard9@gwu.edu', 'Computer Science', 'MS', '2.93', 0, 0, 10000010);
INSERT INTO student (u_id, fname, lname, addr, email, major, degree, gpa, gradapp, form1status, advisor) VALUES (12345678, 'Stevie', 'Nicks', '43638 Drive Dr. City, ST 47423', 'jacobpritchard9@gwu.edu', 'Computer Science', 'PhD', '3.58', 0, 1, 10000012);

INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer, chair) VALUES (10000111, 'Robert', 'Pless', '33579 Street Rd. City, ST 12854', 'jacobpritchard9@gwu.edu', 'CSCI', 1, 1);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000002, 'Bhagi', 'Narahari', '55555 Road Rd. City, ST 66666', 'jacobpritchard9@gwu.edu', 'CSCI', 1);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000003, 'Hyeong-Ah', 'Choi', '77777 Place Pl. City, ST 88888', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000004, 'Roxana', 'Leontie', '99999 F St. Washington, D.C. 11111', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000005, 'Rahul', 'Simha', '12345 E St. Washington, D.C. 11111', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000006, 'Pablo', 'Frank-Bolton', '56789 I St. Washington, D.C. 11111', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000007, 'Abdou', 'Youssef', '13579 K St. Washington, D.C. 11111', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000008, 'James', 'Taylor', '24681 D St. Washington, D.C. 11111', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000009, 'Gabe', 'Parmer', '38273 C St. Washington, D.C. 11111', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000010, 'Tim', 'Wood', '48264 K St. Washington, D.C. 12121', 'jacobpritchard9@gwu.edu', 'CSCI', 1);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000011, 'Shelly', 'Heller', '32471 B St. Washington, D.C. 28191', 'jacobpritchard9@gwu.edu', 'CSCI', 1);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000012, 'Sarah', 'Morin', '91283 C St. Washington, D.C. 16513', 'jacobpritchard9@gwu.edu', 'CSCI', 0);
INSERT INTO faculty (f_id, fname, lname, addr, email, dept, reviewer) VALUES (10000013, 'Kevin', 'Deems', '62157 N St. Washington, D.C. 12891', 'jacobpritchard9@gwu.edu', 'CSCI', 0);

INSERT INTO alumni (a_id, fname, lname, degree, gpa, gradyear, email, addr) VALUES (77777777, 'Eric', 'Clapton', 'MS', '3.30', 2014, 'eric@gwu.edu', '3435 Avenue St. City, ST 41441');
INSERT INTO alumni (a_id, fname, lname, degree, gpa, gradyear, email, addr) VALUES (34567890, 'Kurt', 'Cobain', 'PhD', '3.75', 2015, 'kurt@gwu.edu', '5256 Place Ave. City, ST 25468');

INSERT INTO applicant (app_id, fname, lname, degree, ssn, app_status) VALUES (15555555, 'John', 'Lennon', 'MS', 111111111, 1);
INSERT INTO applicant (app_id, fname, lname, degree, ssn, app_status) VALUES (16666666, 'Ringo', 'Starr', 'MS', 222111111, 0);
INSERT INTO applicant (app_id, fname, lname, degree, ssn, app_status) VALUES (00001234, 'Louis', 'Armstrong', 'MS', 555111111, 1);
INSERT INTO applicant (app_id, fname, lname, degree, ssn, app_status) VALUES (00001235, 'Aretha', 'Franklin', 'MS', 666111111, 1);
INSERT INTO applicant (app_id, fname, lname, degree, ssn, app_status) VALUES (00001236, 'Carlos', 'Santana', 'PhD', 777111111, 1);

INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6221, 'SW Paradigms', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6461, 'Computer Architecture', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6212, 'Algorithms', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6220, 'Machine Learning', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6232, 'Networks 1', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6233, 'Networks 2', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6241, 'Database 1', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6242, 'Database 2', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6246, 'Compilers', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6260, 'Multimedia', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6251, 'Cloud Computing', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6254, 'SW Engineering', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6262, 'Graphics 1', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6283, 'Security 1', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6284, 'Cryptography', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6286, 'Network Security', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6325, 'Algorithms 2', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6339, 'Embedded Systems', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("CSCI", 6384, 'Cryptography 2', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("ECE", 6241, 'Communication Theory', 3);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("ECE", 6242, 'Information Theory', 2);
INSERT INTO catalog (department, c_no, title, credits) VALUES ("MATH", 6210, 'Logic', 2);

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 1, 'M', '15:00', '17:30'); /*Fall 2016*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 1, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 1, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 1, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 1, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 1, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 1, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 1, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 1, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 1, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 1, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 1, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 1, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 1, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 1, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 1, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 1, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 1, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 1, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 1, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 2, 'M', '15:00', '17:30'); /*Spring 2017*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 2, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 2, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 2, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 2, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 2, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 2, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 2, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 2, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 2, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 2, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 2, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 2, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 2, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 2, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 2, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 2, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 2, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 2, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 2, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 3, 'M', '15:00', '17:30'); /*Fall 2017*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 3, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 3, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 3, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 3, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 3, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 3, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 3, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 3, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 3, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 3, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 3, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 3, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 3, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 3, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 3, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 3, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 3, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 3, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 3, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 4, 'M', '15:00', '17:30'); /*Spring 2018*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 4, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 4, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 4, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 4, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 4, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 4, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 4, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 4, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 4, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 4, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 4, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 4, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 4, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 4, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 4, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 4, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 4, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 4, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 4, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 5, 'M', '15:00', '17:30'); /*Fall 2018*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 5, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 5, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 5, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 5, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 5, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 5, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 5, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 5, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 5, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 5, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 5, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 5, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 5, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 5, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 5, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 5, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 5, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 5, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 5, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 6, 'M', '15:00', '17:30'); /*Spring 2019*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 6, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 6, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 6, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 6, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 6, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 6, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 6, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 6, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 6, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 6, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 6, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 6, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 6, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 6, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 6, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 6, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 6, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 6, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 6, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 7, 'M', '15:00', '17:30'); /*Fall 2019*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 7, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 7, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 7, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 7, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 7, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 7, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 7, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 7, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 7, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 7, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 7, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 7, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 7, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 7, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 7, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 7, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 7, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 7, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 7, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 8, 'M', '15:00', '17:30'); /*Spring 2020*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 8, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 8, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 8, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 8, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 8, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 8, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 8, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 8, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 8, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 8, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 8, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 8, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 8, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 8, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 8, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 8, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 8, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 8, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 8, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 9, 'M', '15:00', '17:30'); /*Fall 2020*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 9, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 9, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 9, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 9, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 9, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 9, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 9, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 9, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 9, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 9, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 9, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 9, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 9, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 9, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 9, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 9, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 9, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 9, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 9, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 10, 'M', '15:00', '17:30'); /*Spring 2021*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 10, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 10, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 10, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 10, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 10, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 10, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 10, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 10, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 10, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 10, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 10, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 10, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 10, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 10, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 10, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 10, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 10, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 10, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 10, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 11, 'M', '15:00', '17:30'); /*Fall 2021*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 11, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 11, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 11, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 11, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 11, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 11, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 11, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 11, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 11, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 11, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 11, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 11, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 11, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 11, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 11, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 11, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 11, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 11, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 11, 'R', '16:00', '18:30');

INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (1, 1, 12, 'M', '15:00', '17:30'); /*Spring 2022*/
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (2, 1, 12, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (3, 1, 12, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (5, 1, 12, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (6, 1, 12, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (7, 1, 12, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (8, 1, 12, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (9, 1, 12, 'T', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (11, 1, 12, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (12, 1, 12, 'M', '15:30', '18:00');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (10, 1, 12, 'R', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (13, 1, 12, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (14, 1, 12, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (15, 1, 12, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (16, 1, 12, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (19, 1, 12, 'W', '15:00', '17:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (20, 1, 12, 'M', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (21, 1, 12, 'T', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (22, 1, 12, 'W', '18:00', '20:30');
INSERT INTO schedule (course_id, section_no, sem, day, start_time, end_time) VALUES (18, 1, 12, 'R', '16:00', '18:30');

INSERT INTO courses_taken(u_id, crn, grade) VALUES (88888888, 142, 'IP');
INSERT INTO courses_taken(u_id, crn, grade) VALUES (88888888, 143, 'IP');

INSERT INTO semester(semesterid, semester, year) VALUES (1, "FALL", 2016);
INSERT INTO semester(semesterid, semester, year) VALUES (2, "SPRING", 2017);
INSERT INTO semester(semesterid, semester, year) VALUES (3, "FALL", 2017);
INSERT INTO semester(semesterid, semester, year) VALUES (4, "SPRING", 2018);
INSERT INTO semester(semesterid, semester, year) VALUES (5, "FALL", 2018);
INSERT INTO semester(semesterid, semester, year) VALUES (6, "SPRING", 2019);
INSERT INTO semester(semesterid, semester, year) VALUES (7, "FALL", 2019);
INSERT INTO semester(semesterid, semester, year) VALUES (8, "SPRING", 2020);
INSERT INTO semester(semesterid, semester, year) VALUES (9, "FALL", 2020);
INSERT INTO semester(semesterid, semester, year) VALUES (10, "SPRING", 2021);
INSERT INTO semester(semesterid, semester, year) VALUES (11, "FALL", 2021);
INSERT INTO semester(semesterid, semester, year) VALUES (12, "SPRING", 2022);

INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (88888888, "CSCI", "6461", "IP", 8, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (88888888, "CSCI", "6212", "IP", 8, 0);

INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6221", "A", 3, 1); /*Eva Cassidy*/
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6212", "A", 3, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6461", "A", 4, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6232", "A", 4, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6233", "A", 5, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6284", "A", 5, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6286", "A", 6, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6241", "C", 6, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6246", "C", 7, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (87654321, "CSCI", "6262", "C", 8, 1);

INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6221");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6212");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6461");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6232");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6233");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6284");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6286");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6241");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6246");
INSERT INTO form1 (f1_id, dept, cno) VALUES (87654321, "CSCI", "6262");

INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6221", "A", 3, 0); /*Jimi Hendrix*/
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6212", "A", 3, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6461", "A", 4, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6232", "A", 4, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6233", "A", 5, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6284", "A", 5, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6286", "A", 6, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "CSCI", "6241", "A", 6, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "ECE", "6241", "B", 7, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "ECE", "6242", "B", 8, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (45678901, "MATH", "6210", "B", 8, 0);

INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6221", "A", 3, 1); /*Paul McCartney*/
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6212", "A", 3, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6461", "A", 4, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6232", "A", 4, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6233", "A", 5, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6241", "B", 5, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6246", "B", 6, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6262", "B", 6, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6283", "B", 7, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (14444444, "CSCI", "6242", "B", 8, 1);

INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6221"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6212");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6461");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6232");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6233");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6241");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6246");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6262");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6283");
INSERT INTO form1 (f1_id, dept, cno) VALUES (14444444, "CSCI", "6242");

INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "ECE", "6242", "C", 1, 0); /*George Harrison*/
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6221", "B", 1, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6461", "B", 2, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6232", "B", 2, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6233", "B", 3, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6241", "B", 4, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6246", "B", 6, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6284", "B", 6, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6283", "B", 7, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (66666666, "CSCI", "6242", "B", 8, 0);

INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6221", "A", 3, 1); /*Stevie Nicks*/
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6212", "A", 3, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6461", "A", 4, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6232", "A", 4, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6233", "A", 5, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6284", "A", 5, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6286", "A", 6, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6241", "B", 6, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6246", "B", 7, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6262", "B", 8, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6283", "B", 7, 1);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (12345678, "CSCI", "6242", "B", 8, 1);

INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6221"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6212"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6461"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6232"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6233"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6284"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6286"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6241"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6246"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6262"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6283"); 
INSERT INTO form1 (f1_id, dept, cno) VALUES (12345678, "CSCI", "6242"); 

INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6221", "B", 3, 0); /*Eric Clapton*/
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6212", "B", 3, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6461", "B", 4, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6232", "B", 4, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6233", "B", 5, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6241", "B", 5, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6242", "B", 6, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6283", "A", 6, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6284", "A", 6, 0);
INSERT INTO student_transcript(t_id, dept, cno, grade, semesterid, inform1) VALUES (77777777, "CSCI", "6286", "A", 6, 0);

INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 1);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 2);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 3);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 4);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 5);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 6);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 7);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 8);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 9);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 10);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 11);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 12);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 13);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 14);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 15);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 16);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 17);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 18);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 19);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 20);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 21);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 22);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 23);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 24);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 25);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 26);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 27);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 28);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 29);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 30);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 31);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 32);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 33);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 34);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 35);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 36);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 37);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 38);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 39);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 40);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 41);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 42);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 43);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 44);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 45);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 46);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 47);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 48);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 49);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 50);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 51);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 52);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 53);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 54);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 55);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 56);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 57);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 58);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 59);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 60);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 61);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 62);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 63);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 64);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 65);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 66);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 67);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 68);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 69);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 70);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 71);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 72);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 73);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 74);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 75);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 76);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 77);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 78);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 79);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 80);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 81);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 82);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 83);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 84);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 85);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 86);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 87);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 88);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 89);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 90);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 91);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 92);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 93);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 94);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 95);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 96);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 97);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 98);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 99);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 100);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 101);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 102);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 103);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 104);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 105);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 106);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 107);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 108);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 109);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 110);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 111);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 112);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 113);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 114);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 115);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 116);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 117);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 118);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 119);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 120);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 121);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 122);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 123);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 124);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 125);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 126);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 127);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 128);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 129);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 130);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 131);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 132);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 133);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 134);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 135);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 136);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 137);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 138);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 139);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 140);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 141);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 142);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 143);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 144);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 145);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 146);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 147);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 148);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 149);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 150);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 151);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 152);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 153);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 154);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 155);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 156);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 157);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 158);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 159);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 160);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 161);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 162);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 163);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 164);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 165);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 166);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 167);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 168);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 169);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 170);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 171);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 172);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 173);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 174);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 175);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 176);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 177);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 178);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 179);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 180);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 181);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 182);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 183);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 184);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 185);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 186);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 187);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 188);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 189);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 190);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 191);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 192);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 193);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 194);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 195);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 196);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 197);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 198);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 199);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 200);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 201);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 202);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 203);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 204);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 205);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 206);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 207);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 208);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 209);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 210);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 211);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 212);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 213);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 214);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 215);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 216);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 217);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 218);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 219);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 220);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 221);
INSERT INTO courses_taught(f_id, crn) VALUES (10000002, 222);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 223);
INSERT INTO courses_taught(f_id, crn) VALUES (10000003, 224);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 225);
INSERT INTO courses_taught(f_id, crn) VALUES (10000013, 226);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 227);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 228);
INSERT INTO courses_taught(f_id, crn) VALUES (10000008, 229);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 230);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 231);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 232);
INSERT INTO courses_taught(f_id, crn) VALUES (10000006, 233);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 234);
INSERT INTO courses_taught(f_id, crn) VALUES (10000009, 235);
INSERT INTO courses_taught(f_id, crn) VALUES (10000004, 236);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 237);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 238);
INSERT INTO courses_taught(f_id, crn) VALUES (10000010, 239);
INSERT INTO courses_taught(f_id, crn) VALUES (10000007, 240);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (6, 'CSCI 6232', NULL);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (8, 'CSCI 6241', NULL);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (9, 'CSCI 6461', 'CSCI 6212');
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (11, 'CSCI 6241', NULL);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (12, 'CSCI 6221', NULL);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (14, 'CSCI 6212', NULL);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (15, 'CSCI 6212', NULL);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (16, 'CSCI 6283', 'CSCI 6232');
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (17, 'CSCI 6212', NULL);
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (18, 'CSCI 6461','CSCI 6212');
INSERT INTO prereqs(course_Id, prereq1, prereq2) VALUES (19, 'CSCI 6284', NULL);

INSERT ignore INTO decisions (decisionID, description) VALUES
(0, 'Application Incomplete'),
(1, 'Application Complete and Under Review'),
(2, 'Admitted'),
(3, 'Admitted With Aid'),
(4, 'Rejected');

INSERT ignore INTO recommendations (recID, description) VALUES
(0, 'Reject'),
(1, 'Boderline Admit'),
(2, 'Admit Without Aid'),
(3, 'Admit With Aid');
