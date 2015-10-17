/*==============================================================*/
/* Nom de SGBD :  MySQL 4.0                                     */
/* Date de création :  17.10.15 19:55:32                        */
/*==============================================================*/


drop table if exists can_do;

drop table if exists computer;

drop table if exists discover;

drop table if exists file;

drop table if exists has_installed;

drop table if exists message;

drop table if exists objectif;

drop table if exists program;

drop table if exists quest;

/*==============================================================*/
/* Table : can_do                                               */
/*==============================================================*/
create table can_do
(
   user_id                        int(11)                        not null,
   quest_id                       int(11)                        not null,
   primary key (user_id, quest_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Index : can_do_fk                                            */
/*==============================================================*/
create index can_do_fk on can_do
(
   user_id
);

/*==============================================================*/
/* Index : can_do2_fk                                           */
/*==============================================================*/
create index can_do2_fk on can_do
(
   quest_id
);

/*==============================================================*/
/* Table : computer                                             */
/*==============================================================*/
create table computer
(
   user_id                        int(11)                        not null auto_increment,
   user_ip                        longtext,
   user_name                      longtext,
   user_email                     longtext,
   user_appid                     longtext,
   primary key (user_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Table : discover                                             */
/*==============================================================*/
create table discover
(
   user_id                        int(11)                        not null,
   com_user_id                    int(11)                        not null,
   primary key (user_id, com_user_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Index : discover_fk                                          */
/*==============================================================*/
create index discover_fk on discover
(
   user_id
);

/*==============================================================*/
/* Index : discover2_fk                                         */
/*==============================================================*/
create index discover2_fk on discover
(
   com_user_id
);

/*==============================================================*/
/* Table : file                                                 */
/*==============================================================*/
create table file
(
   file_id                        int(11)                        not null auto_increment,
   user_id                        int(11)                        not null,
   file_name                      longtext,
   file_content                   text,
   primary key (file_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Index : contains_fk                                          */
/*==============================================================*/
create index contains_fk on file
(
   user_id
);

/*==============================================================*/
/* Table : has_installed                                        */
/*==============================================================*/
create table has_installed
(
   user_id                        int(11)                        not null,
   program_id                     int(11)                        not null,
   primary key (user_id, program_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Index : has_installed_fk                                     */
/*==============================================================*/
create index has_installed_fk on has_installed
(
   user_id
);

/*==============================================================*/
/* Index : has_installed2_fk                                    */
/*==============================================================*/
create index has_installed2_fk on has_installed
(
   program_id
);

/*==============================================================*/
/* Table : message                                              */
/*==============================================================*/
create table message
(
   message_id                     int(11)                        not null auto_increment,
   user_id                        int(11)                        not null,
   message_subject                longtext,
   message_content                text,
   message_date                   timestamp,
   primary key (message_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Index : get_fk                                               */
/*==============================================================*/
create index get_fk on message
(
   user_id
);

/*==============================================================*/
/* Table : objectif                                             */
/*==============================================================*/
create table objectif
(
   objectif_id                    int(11)                        not null auto_increment,
   quest_id                       int(11)                        not null,
   objectif_subject               longtext,
   objectif_content               text,
   primary key (objectif_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Index : required_fk                                          */
/*==============================================================*/
create index required_fk on objectif
(
   quest_id
);

/*==============================================================*/
/* Table : program                                              */
/*==============================================================*/
create table program
(
   program_id                     int(11)                        not null auto_increment,
   primary key (program_id)
)
ENGINE=innodb;

/*==============================================================*/
/* Table : quest                                                */
/*==============================================================*/
create table quest
(
   quest_id                       int(11)                        not null auto_increment,
   quest_subject                  longtext,
   quest_content                  text,
   primary key (quest_id)
)
ENGINE=innodb;

alter table can_do add constraint fk_can_do foreign key (user_id)
      references computer (user_id) on delete restrict on update restrict;

alter table can_do add constraint fk_can_do2 foreign key (quest_id)
      references quest (quest_id) on delete restrict on update restrict;

alter table discover add constraint fk_discover foreign key (user_id)
      references computer (user_id) on delete restrict on update restrict;

alter table discover add constraint fk_discover2 foreign key (com_user_id)
      references computer (user_id) on delete restrict on update restrict;

alter table file add constraint fk_contains foreign key (user_id)
      references computer (user_id) on delete restrict on update restrict;

alter table has_installed add constraint fk_has_installed foreign key (user_id)
      references computer (user_id) on delete restrict on update restrict;

alter table has_installed add constraint fk_has_installed2 foreign key (program_id)
      references program (program_id) on delete restrict on update restrict;

alter table message add constraint fk_get foreign key (user_id)
      references computer (user_id) on delete restrict on update restrict;

alter table objectif add constraint fk_required foreign key (quest_id)
      references quest (quest_id) on delete restrict on update restrict;