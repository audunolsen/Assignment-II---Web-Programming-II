<?php
// Connect to mysql
$db = new PDO('mysql:host=localhost', 'root', 'root');
// Drop database if it already exists, this is useful when working & iserting dummydata to avoid potential integrity concstraints
$db->exec('DROP DATABASE news');
// Create database
$db->exec('CREATE DATABASE news');
// Use database
$db->exec('USE news');

// variable containing SQL statements to create tables 
$sql = <<<'ENDSQL'

CREATE TABLE articles (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	category_id INT NOT NULL,
	title VARCHAR(100) NOT NULL,
	content mediumtext NOT NULL,
	created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	email VARCHAR(89) NOT NULL,
	username VARCHAR(25) NOT NULL,
	password VARCHAR(255) NOT NULL,
	admin BOOLEAN DEFAULT 0
);

CREATE TABLE ratings (
	user_id INT NOT NULL,
	article_id INT NOT NULL,
	vote BOOLEAN NOT NULL,
	PRIMARY KEY (user_id, article_id)
);

CREATE TABLE categories (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(32) NOT NULL
);

ENDSQL;

// Execute variable containing above SQL
$db->exec($sql);

// Populate users table by inserting dummyusers into users table, both without and with admin priviliges
$setup = $db->prepare('INSERT INTO users (username, password, email, admin) VALUES (?, ?, ?, ?)');

// Dummyusers without admin priviliges
$setup->execute([
	'darthvader',
	password_hash('starwars', PASSWORD_DEFAULT),
	'star@wars.com',
	0
]);

$setup->execute([
	'obama',
	password_hash('potus1', PASSWORD_DEFAULT),
	'obama@potus.com',
	0
]);

$setup->execute([
	'pewdiepie',
	password_hash('brofist', PASSWORD_DEFAULT),
	'pew@die.pie',
	0
]);

// Dummyusers with admin priviliges
$setup->execute([
	'admin1',
	password_hash('admin1', PASSWORD_DEFAULT),
	'admin1@admin.no',
	1
]);

$setup->execute([
	'admin2',
	password_hash('admin2', PASSWORD_DEFAULT),
	'admin2@admin.no',
	1
]);

// variable containing SQL statements to insert dummycontent into newly created tables
// Populate articles, categories and ratings tables by inserting dummydata into the tables
$sql = <<<'ENDSQL'

INSERT INTO articles (user_id, category_id, title, content) VALUES (4, 1, 'Donald Trump holds speech in Saudi Arabia!', 'The fight against terrorism is a "battle between good and evil," not a fight between "different faiths, different sects, or different civilizations," President Trump said Sunday in a widely-anticipated speech in Saudi Arabia.'),
(2, 2, 'Bernie Sanders preaches politics based on feelings, not facts', 'This is my controversial take on Bernie’s politics. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
(5, 3, 'Is Usain Bolt retiring?', 'Usain Bolt is set to make good on his word. For years, the world’s fastest man has said he’d run in the Rio Olympics, compete in the 2017 track season and then hang up his golden spikes after the 2017 world championship in London. So far, nothing has happened to derail his plans'),
(1, 4, 'The last Jedi will be the best Star Wars film yet', 'The epic space opera film written and directed by Rian Johnson is rumored to be perhaps the best movie of all time. It is the second film in the Star Wars sequel trilogy, following Star Wars: The Force Awakens (2015). The film is produced by Lucasfilm and will be distributed by Walt Disney Studios Motion Pictures.'),
(3, 5, 'Zelda: Breath of the Wild has the most perfect review scores in Metacritic’s history', 'Although it’s not the highest-rated game on Metacritic, lining up behind Ocarina of Time, Tony Hawk’s Pro Skater 2 and GTA 4, The Legend of Zelda: Breath of the Wild now has the largest number of perfect review scores of any game tracked by the site.');

INSERT INTO categories (name) VALUES ('Politics'),
('Opinions'),
('Sports'),
('Hollywood'),
('Video-games');

INSERT INTO ratings VALUES (1, 1, 1),
(2, 4, 1),
(3, 4, 1),
(4, 4, 1),
(5, 4, 1);

ENDSQL;

// Execute variable containing above SQL
$db->exec($sql);

// Redirect to index
header('location: index.php');