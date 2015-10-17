/*==============================================================*/
/* Nom de SGBD :  MySQL 4.0                                     */
/* Date de création :  17.10.15 19:55:32                        */
/*==============================================================*/


DROP TABLE IF EXISTS can_do;

DROP TABLE IF EXISTS computer;

DROP TABLE IF EXISTS discover;

DROP TABLE IF EXISTS file;

DROP TABLE IF EXISTS has_installed;

DROP TABLE IF EXISTS message;

DROP TABLE IF EXISTS objectif;

DROP TABLE IF EXISTS program;

DROP TABLE IF EXISTS quest;

/*==============================================================*/
/* Table : can_do                                               */
/*==============================================================*/
CREATE TABLE can_do
(
  user_id  INT(11) NOT NULL,
  quest_id INT(11) NOT NULL,
  PRIMARY KEY (user_id, quest_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Index : can_do_fk                                            */
/*==============================================================*/
CREATE INDEX can_do_fk ON can_do
(
  user_id
);

/*==============================================================*/
/* Index : can_do2_fk                                           */
/*==============================================================*/
CREATE INDEX can_do2_fk ON can_do
(
  quest_id
);

/*==============================================================*/
/* Table : computer                                             */
/*==============================================================*/
CREATE TABLE computer
(
  user_id    INT(11) NOT NULL AUTO_INCREMENT,
  user_ip    LONGTEXT,
  user_name  LONGTEXT,
  user_email LONGTEXT,
  user_appid LONGTEXT,
  PRIMARY KEY (user_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Table : discover                                             */
/*==============================================================*/
CREATE TABLE discover
(
  user_id     INT(11) NOT NULL,
  com_user_id INT(11) NOT NULL,
  PRIMARY KEY (user_id, com_user_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Index : discover_fk                                          */
/*==============================================================*/
CREATE INDEX discover_fk ON discover
(
  user_id
);

/*==============================================================*/
/* Index : discover2_fk                                         */
/*==============================================================*/
CREATE INDEX discover2_fk ON discover
(
  com_user_id
);

/*==============================================================*/
/* Table : file                                                 */
/*==============================================================*/
CREATE TABLE file
(
  file_id      INT(11) NOT NULL AUTO_INCREMENT,
  user_id      INT(11) NOT NULL,
  file_name    LONGTEXT,
  file_content TEXT,
  PRIMARY KEY (file_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Index : contains_fk                                          */
/*==============================================================*/
CREATE INDEX contains_fk ON file
(
  user_id
);

/*==============================================================*/
/* Table : has_installed                                        */
/*==============================================================*/
CREATE TABLE has_installed
(
  user_id    INT(11) NOT NULL,
  program_id INT(11) NOT NULL,
  PRIMARY KEY (user_id, program_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Index : has_installed_fk                                     */
/*==============================================================*/
CREATE INDEX has_installed_fk ON has_installed
(
  user_id
);

/*==============================================================*/
/* Index : has_installed2_fk                                    */
/*==============================================================*/
CREATE INDEX has_installed2_fk ON has_installed
(
  program_id
);

/*==============================================================*/
/* Table : message                                              */
/*==============================================================*/
CREATE TABLE message
(
  message_id      INT(11) NOT NULL AUTO_INCREMENT,
  user_id         INT(11) NOT NULL,
  message_subject LONGTEXT,
  message_content TEXT,
  message_date    TIMESTAMP,
  PRIMARY KEY (message_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Index : get_fk                                               */
/*==============================================================*/
CREATE INDEX get_fk ON message
(
  user_id
);

/*==============================================================*/
/* Table : objectif                                             */
/*==============================================================*/
CREATE TABLE objectif
(
  objectif_id      INT(11) NOT NULL AUTO_INCREMENT,
  quest_id         INT(11) NOT NULL,
  objectif_subject LONGTEXT,
  objectif_content TEXT,
  PRIMARY KEY (objectif_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Index : required_fk                                          */
/*==============================================================*/
CREATE INDEX required_fk ON objectif
(
  quest_id
);

/*==============================================================*/
/* Table : program                                              */
/*==============================================================*/
CREATE TABLE program
(
  program_id INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (program_id)
)
  ENGINE = innodb;

/*==============================================================*/
/* Table : quest                                                */
/*==============================================================*/
CREATE TABLE quest
(
  quest_id      INT(11) NOT NULL AUTO_INCREMENT,
  quest_subject LONGTEXT,
  quest_content TEXT,
  PRIMARY KEY (quest_id)
)
  ENGINE = innodb;

ALTER TABLE can_do ADD CONSTRAINT fk_can_do FOREIGN KEY (user_id)
REFERENCES computer (user_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE can_do ADD CONSTRAINT fk_can_do2 FOREIGN KEY (quest_id)
REFERENCES quest (quest_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE discover ADD CONSTRAINT fk_discover FOREIGN KEY (user_id)
REFERENCES computer (user_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE discover ADD CONSTRAINT fk_discover2 FOREIGN KEY (com_user_id)
REFERENCES computer (user_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE file ADD CONSTRAINT fk_contains FOREIGN KEY (user_id)
REFERENCES computer (user_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE has_installed ADD CONSTRAINT fk_has_installed FOREIGN KEY (user_id)
REFERENCES computer (user_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE has_installed ADD CONSTRAINT fk_has_installed2 FOREIGN KEY (program_id)
REFERENCES program (program_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE message ADD CONSTRAINT fk_get FOREIGN KEY (user_id)
REFERENCES computer (user_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE objectif ADD CONSTRAINT fk_required FOREIGN KEY (quest_id)
REFERENCES quest (quest_id)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;