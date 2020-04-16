/* ********************************************************************************************** */
/* Вспомогательные макросы для генерации SQL конструкций */
/* ********************************************************************************************** */

define([[M4_CONCAT]],[[ifelse([[$#]],[[0]],,[[$#]],[[1]],[[[[$1]]]],[[[[$1]]M4_CONCAT(shift($@))]])]])

/* ********************************************************************************************** */

define([[M4_DEF_OBJECT_PK]], [[CONSTRAINT M4_CONCAT(PK_,TBLID_$1)
PRIMARY KEY (OBJECT_ID, OBJECT_CLASS)]])

define([[M4_DEF_OBJECT_FK]], [[CONSTRAINT M4_CONCAT(FK_,TBLID_$1,_,TBLID_$2)
FOREIGN KEY (OBJECT_ID, OBJECT_CLASS) REFERENCES TBLNAME_$2 (OBJECT_ID,OBJECT_CLASS) ON DELETE CASCADE]])

/* ********************************************************************************************** */

define([[M4_REG_TABLE_NAME1]], [[INSERT INTO CORE.TABLE_NAMES (ID,PARENT_ID,TABLE_SCHEMA,TABLE_NAME) VALUES (TBLID_$1,NULL,'TABLE_SCHEMA__$1','TABLE_NAME__$1')]])

define([[M4_REG_TABLE_NAME2]], [[INSERT INTO CORE.TABLE_NAMES (ID,PARENT_ID,TABLE_SCHEMA,TABLE_NAME) VALUES (TBLID_$1,TBLID_$2,'TABLE_SCHEMA__$1','TABLE_NAME__$1')]])

define([[M4_REG_CLASS]],[[INSERT INTO CORE.CLASSES (ID,NAME,TABLE_ID,SEQ_SCHEMA,SEQ_NAME,DESCRIPTION)
VALUES (CLASSID_$1,'CLASSNAME_$1',TBLID_$2,$3,$4,$5)]])

define([[M4_REG_CLASS2]],[[M4_REG_CLASS($1,$2,'TABLE_SCHEMA__$2',$3,$4)]])

define([[M4_REG_POS_OBJ_LINKS]],[[INSERT INTO CORE.POSSIBLE_OBJECTS_LINKS (OWNER_CLASS,CHILD_CLASS) VALUES (CLASSID_$1,CLASSID_$2)]])

/* ********************************************************************************************** */

define([[M4_CREATE_TRIGGER_OBJ_DELETE]],[[CREATE TRIGGER M4_CONCAT(AD_,TBLID_$1,_DELETE_BASE)
 AFTER DELETE
 ON TBLNAME_$1
 FOR EACH ROW
 EXECUTE PROCEDURE CORE.FN_TR__DELETE_OBJECT()]])

/* ********************************************************************************************** */

define([[M4_CREATE_TRIGGER_RO_OBJ_GUARD]],[[CREATE TRIGGER M4_CONCAT(BUD_,TBLID_$1,_RO_GUARD)
 BEFORE UPDATE OR DELETE
 ON TBLNAME_$1
 FOR EACH ROW
 EXECUTE PROCEDURE CORE.FN_TR__PREVENT_CHANGE_RO_OBJ()]])

/* ********************************************************************************************** */

define([[M4_DEF_DOMAIN_FOR_NAME_NN]],[[CREATE DOMAIN $1_NN
AS
VARCHAR($2)
NOT NULL
CHECK ((VALUE <> '') AND (TRIM(BOTH ' ' FROM VALUE) = VALUE))]])

/* ********************************************************************************************** */

define([[M4_DEF_DOMAIN_FOR_NAME_NN_UCASE]],[[CREATE DOMAIN $1_NN_UCASE
AS
VARCHAR($2)
NOT NULL
CHECK ((VALUE <> '') AND (TRIM(BOTH ' ' FROM VALUE) = VALUE))
CHECK (VALUE = UPPER(VALUE))]])

/* ********************************************************************************************** */
