ALTER TABLE ticket_status ADD COLUMN visible TINYINT(1) UNSIGNED DEFAULT 0;
ALTER TABLE ticket_status ADD COLUMN only_user_role INT UNSIGNED DEFAULT 1;

UPDATE ticket_status SET visible = (name NOT IN ('File is requested', 'File is uploaded', 'Issue identified'));
UPDATE ticket_status SET only_user_role=0 WHERE visible = 0;

INSERT INTO ticket_status VALUES(NULL, 'Further comment', 'Further comment', 1, 0);

INSERT INTO ticket_status VALUES(NULL, 'Model Check in progress', 'Model Check in progress', 1, 1);
INSERT INTO ticket_status VALUES(NULL, 'Model Check complete', 'Model Check complete', 1, 1);

INSERT INTO ticket_status VALUES(NULL, 'FTAO Users/Disciplines', 'FTAO Users/Disciplines', 1, 2);
