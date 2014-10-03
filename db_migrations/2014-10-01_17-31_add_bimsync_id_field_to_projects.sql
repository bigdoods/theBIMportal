ALTER TABLE projects ADD COLUMN bimsync_id VARCHAR(255)
UPDATE projects SET bimsync_id='281d698b42174ffca1799eac19869385' WHERE name='Cambridge Science Park';