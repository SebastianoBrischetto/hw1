create table user(
	email varchar(255) PRIMARY KEY,
	name varchar(255) NOT NULL,
	surname varchar(255) NOT NULL,
	birth_date date NOT NULL,
	password varchar(255) NOT NULL,
	admin BOOLEAN default 0
)engine='innoDB';  

create table course(
	course_code int PRIMARY KEY AUTO_INCREMENT COMMENT 'AUTO_INCREMENT',
	name varchar(255) NOT NULL,
	type varchar(255) NOT NULL COMMENT 'TYPE' ,
	description varchar(2048) COMMENT 'LONG_TEXT',
	image varchar(255) COMMENT 'IMAGE' 
) engine='innoDB';  

create table user_courses(
	email varchar(255) COMMENT 'INDEX_OF user',
	course_code int COMMENT 'INDEX_OF course',
	index idx_user(email), 
	index idx_course(course_code),
	foreign key(email) references user(email) ON DELETE CASCADE ON UPDATE CASCADE,
	foreign key(course_code) references course(course_code) ON DELETE CASCADE ON UPDATE CASCADE,
	primary key(email,course_code)
)engine='innoDB';

create table subscription(
	subscription_code int PRIMARY KEY AUTO_INCREMENT COMMENT 'AUTO_INCREMENT',
	name varchar(255) NOT NULL,
	cost decimal (6,2) NOT NULL
) engine='innoDB';

create table subscription_courses(
	subscription_code int COMMENT 'INDEX_OF subscription',
	course_code int COMMENT 'INDEX_OF course',
	index idx_subscription(subscription_code), 
	index idx_course(course_code),
	foreign key(subscription_code) references subscription(subscription_code) ON DELETE CASCADE ON UPDATE CASCADE,
	foreign key(course_code) references course(course_code) ON DELETE CASCADE ON UPDATE CASCADE,
	primary key(subscription_code,course_code)
)engine='innoDB';

create table user_subscriptions_active(
	email varchar(255) COMMENT 'INDEX_OF user',
	subscription_code int COMMENT 'INDEX_OF subscription',
	duration_months int NOT NULL,
	start_date date NOT NULL DEFAULT CURRENT_TIME,
	index idx_user(email), 
	index idx_subscription(subscription_code), 
	foreign key(email) references user(email) ON DELETE CASCADE ON UPDATE CASCADE,
	foreign key(subscription_code) references subscription(subscription_code) ON DELETE CASCADE ON UPDATE CASCADE,
	primary key(email,subscription_code)
)engine='innoDB';

create table location(
	address varchar(255) PRIMARY KEY,
	city varchar(255) NOT NULL,
	description varchar(2048) COMMENT 'LONG_TEXT',
	phone_number  varchar(255) NOT NULL,
	email varchar(255) NOT NULL
) engine='innoDB';

create table location_courses(
	address varchar(255) COMMENT 'INDEX_OF location',
	course_code int COMMENT 'INDEX_OF course',
	index idx_location(address),
	index idx_course(course_code),
	foreign key(address) references location(address) ON DELETE CASCADE ON UPDATE CASCADE,
	foreign key(course_code) references course(course_code) ON DELETE CASCADE ON UPDATE CASCADE,
	primary key(address,course_code)
)engine='innoDB';

create table location_times(
	address varchar(255) COMMENT 'INDEX_OF location',
	days varchar(255),
	times varchar(255) NOT NULL,
	index idx_location(address),
	foreign key(address) references location(address) ON DELETE CASCADE ON UPDATE CASCADE,
	primary key(address,days)
)engine='innoDB';
create table location_images(
	address varchar(255) COMMENT 'INDEX_OF location',
	image varchar(255) COMMENT 'IMAGE',
	index idx_location(address),
	foreign key(address) references location(address) ON DELETE CASCADE ON UPDATE CASCADE,
	primary key(address,image)
)engine='innoDB';
create table trainer(
	trainer_id int PRIMARY KEY AUTO_INCREMENT COMMENT 'AUTO_INCREMENT',
	address varchar(255) NOT NULL COMMENT 'INDEX_OF location',
	name varchar(255) NOT NULL,
	surname varchar(255) NOT NULL,
	description varchar(2048) COMMENT 'LONG_TEXT',
	image varchar(255) COMMENT 'IMAGE',
	index idx_location(address),
	foreign key(address) references location(address) ON DELETE CASCADE ON UPDATE CASCADE
)engine='innoDB';

CREATE VIEW view_subscription_courses AS 
select s.subscription_code,s.name as subscription_name,s.cost as subscription_cost,
c.course_code,c.name as course_name,c.type as course_type,c.description as course_description,c.image as course_image 
from subscription s join subscription_courses sc on s.subscription_code=sc.subscription_code join course c on sc.course_code=c.course_code;