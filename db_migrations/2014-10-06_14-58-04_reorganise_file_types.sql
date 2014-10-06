ALTER TABLE doctype ADD COLUMN parent_id INT UNSIGNED NOT NULL DEFAULT 0;

DELETE FROM doctype;


INSERT INTO `doctype` VALUES (1,'Drawing','drawing',1,1,0);
INSERT INTO `doctype` VALUES (2,'Documentation','documentation',1,2,0);
INSERT INTO `doctype` VALUES (3,'Gannt Chart','This type of file hold the gantt chart type of files',1,3,0);
INSERT INTO `doctype` VALUES (4,'Quantity Takeoff','Quantity Take Off',1,4,0);
INSERT INTO `doctype` VALUES (5,'Other','other',1,5,0);

INSERT INTO `doctype` VALUES (6,'Elevation','Elevation',1,1,1);
INSERT INTO `doctype` VALUES (7,'Detail','Detail',1,2,1);
INSERT INTO `doctype` VALUES (8,'Section','Section',1,3,1);
INSERT INTO `doctype` VALUES (9,'Perspective','Perspective',1,4,1);
INSERT INTO `doctype` VALUES (10,'Concept','Concept',1,5,1);

INSERT INTO `doctype` VALUES (11,'General Civils','Concept',1,1,2);
INSERT INTO `doctype` VALUES (12,'Mechanical','Concept',1,2,2);
INSERT INTO `doctype` VALUES (13,'Miscellaneous','Concept',1,3,2);
INSERT INTO `doctype` VALUES (14,'Permenent Way','Concept',1,4,2);
INSERT INTO `doctype` VALUES (15,'Property','Concept',1,5,2);
INSERT INTO `doctype` VALUES (16,'Roads. Highways. Car Park','Concept',1,6,2);
INSERT INTO `doctype` VALUES (17,'Safety','Concept',1,7,2);
INSERT INTO `doctype` VALUES (18,'Signalling','Concept',1,8,2);
INSERT INTO `doctype` VALUES (19,'Telecoms','Concept',1,9,2);
INSERT INTO `doctype` VALUES (20,'Track','Concept',1,10,2);

INSERT INTO `doctype` VALUES (21,'Gannt Chart','Gannt Chart',1,1,3);
INSERT INTO `doctype` VALUES (22,'Quantity Takoff','Quantity Takeoff',1,1,4);
INSERT INTO `doctype` VALUES (23,'Other','Other',1,1,5);
