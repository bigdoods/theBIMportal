CREATE TABLE team_members(id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,name TEXT, phone VARCHAR(20), email VARCHAR(255), join_date TIMESTAMP, activation_date TIMESTAMP, company TEXT, designation VARCHAR(255));
CREATE TABLE project_team_members (project_id INT UNSIGNED NOT NULL,team_member_id INT UNSIGNED NOT NULL);

