# ADD INDEX
ALTER TABLE `GeneralCrud` ADD INDEX index_gc_id (gc_id);

ALTER TABLE `GeneralCrud` ADD COLUMN gc_status BOOLEAN DEFAULT 1;