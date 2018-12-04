use #dbName#;

ALTER TABLE buffetmenumap ADD Status tinyint not null after MenuID;
UPDATE buffetmenumap set Status = 1;

ALTER TABLE discountgroupmenumap ADD Status tinyint not null AFTER Quantity;
UPDATE discountgroupmenumap set Status = 1;

ALTER TABLE menunote ADD Status tinyint not null AFTER NoteID;
UPDATE menunote set Status = 1;


CREATE TABLE `specialpriceprogramday` (
  `SpecialPriceProgramDayID` int(11) NOT NULL,
  `SpecialPriceProgramID` int(11) NOT NULL,
  `Day` tinyint(4) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


