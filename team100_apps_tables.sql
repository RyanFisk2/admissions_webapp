SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user CASCADE;
DROP TABLE IF EXISTS position CASCADE;
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

/*position table keeps track of user title
* i.e. applicant, GA, SAS, etc */
create table position (

    roleNum int(4) UNIQUE,
    title varchar(25),
    primary key (roleNum)
);


create table user (
	userID int(8) AUTO_INCREMENT,
	username varchar(25) not null,
	name varchar(25),
	email varchar(25),
	password varchar(25) not null,
	address varchar(25),
	ssn int(9),
	roleID int(4),

	primary key (userID),
	CONSTRAINT FK_user0 foreign key user (roleID) references position (roleNum)
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
 	foreign key (userID) references user (userID),
 	foreign key (finalReviewer) references user (userID),
 	foreign key (decision) references decisions (decisionID)
);

create table transcript (
	applicationID int(8),
	pathToFile varchar(25),
	received DATE,

	primary key (applicationID),
	foreign key (applicationID) references application_form (applicationID) ON UPDATE CASCADE ON DELETE CASCADE
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
	constraint FK_letter0 foreign key (facultyID) references user (userID) on UPDATE CASCADE ON DELETE CASCADE,
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
	constraint FK_courses0 foreign key (applicantID) references user (userID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table review_form (
	facultyID int(8),
	applicantID int(8),
	letterRating int(4),
	suggested_decision int(4),
	reasons varchar(100),
	comments varchar(100),

	primary key (facultyID, applicantID),
	constraint FK_revForm0 foreign key (facultyID) references user (userID) ON UPDATE CASCADE ON DELETE NO ACTION,
	constraint FK_revForm1 foreign key (applicantID) references user (userID) ON UPDATE CASCADE ON DELETE CASCADE,
	/*constraint FK_revForm2 foreign key (facultyID) references letter_rating (letterID) ON UPDATE CASCADE ON DELETE NO ACTION,*/
	constraint FK_revForm3 foreign key (applicantID) references deficient_courses (applicantID) ON UPDATE CASCADE ON DELETE NO ACTION
);

/* Possible decisions */
INSERT ignore INTO decisions (decisionID, description) VALUES
(0, 'Application incomplete'),
(1, 'Application complete and under review'),
(2, 'Admitted'),
(3, 'Admitted with aid'),
(4, 'Rejected');

INSERT ignore INTO recommendations (recID, description) VALUES
(0, 'Reject'),
(1, 'Boderline Admit'),
(2, 'Admit Without Aid'),
(3, 'Admit With Aid');

create table final_decision (
	facultyID int(8),
	applicantID int(8),
	decision int(4),

	primary key (facultyID, applicantID),
	foreign key (facultyID) references user (userID) ON UPDATE CASCADE ON DELETE NO ACTION,
	foreign key (applicantID) references user (userID) ON UPDATE CASCADE ON DELETE CASCADE
);


/*
For position, 0 means admin, 1 means applicant, 2 means CAC, 3 means GS
*/
INSERT ignore INTO position VALUES
(-1, 'Systems administrator');

INSERT ignore INTO position VALUES
(1, 'Applicant');

INSERT ignore INTO position VALUES
(2, 'Chair of Committee');

INSERT ignore INTO position VALUES
(3, 'Grad Secretary');

INSERT ignore INTO position VALUES
(4, 'Faculty Reviewer');

/*
For roleID, 0 means admin, 1 means applicant, 2 means CAC, 3 means GS
*/
INSERT ignore INTO user (userID, username, name, email, password, address, ssn, roleID) VALUES
(00000000, 'admin', 'admin', null, 'admin', null, null, -1),
(55555555, 'John555', 'John Lennon', 'John@gmail.com', 'John', null, 111111111, 1),
(66666666, 'Ringo666', 'Ringo Starr', 'Ringo@gmail.com', 'Ringo', null, 222111111, 1),
(12345678, 'BNarahari', 'Bhagirath Narahari', 'narahari@gwu.edu', 'HelloBN', null, null, 2),
(12345679, 'TWood', 'Timothy Wood', 'timwood@gwu.edu', 'HelloTW', null, null, 3),
(12345680, 'RHeller', 'Rachelle S Heller', 'sheller@gwu.edu', 'HelloRH', null, null, 4);


INSERT IGNORE INTO application_form (applicationID, userID, submitted, decision) VALUES (1, 55555555, NOW(), 1);
INSERT ignore INTO transcript (applicationID, pathToFile, received) VALUES (1, 'fake-transcript.pdf', '2020-01-20');
INSERT ignore INTO rec_letter(letterID, applicationID, writerName, writerTitle, writerEmployer, writerEmail) VALUES
(1, 1, 'Test Recommender', 'Test Title', 'Test Employer', 'test@gmail.com');

SET FOREIGN_KEY_CHECKS = 1;

