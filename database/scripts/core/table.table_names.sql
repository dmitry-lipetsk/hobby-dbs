include(core/inc/common.inc)

/* ********************************************************************************************** */

CREATE TABLE CORE.TABLE_NAMES
(
 ID           CORE.D_TABLE_ID NOT NULL,
 PARENT_ID    CORE.D_TABLE_ID,

 TABLE_SCHEMA CORE.D_DBOBJ_NAME,
 TABLE_NAME   CORE.D_DBOBJ_NAME NOT NULL,

 CONSTRAINT PK_TABLE_NAMES
  PRIMARY KEY (ID),

 CONSTRAINT M4_CONCAT(UNIQUE_TABLE_DBNAME_ID)
  UNIQUE (TABLE_SCHEMA,TABLE_NAME),

 CONSTRAINT FK_TABLE_TREE
  FOREIGN KEY (PARENT_ID) REFERENCES CORE.TABLE_NAMES (ID),

 CONSTRAINT M4_CONCAT(CHECK_TABLE_DBNAME_ID)
  CHECK((TABLE_SCHEMA IS NULL) OR (TABLE_NAME IS NOT NULL))
);/*TABLE CORE.TABLE_NAMES*/

/* ********************************************************************************************** */
