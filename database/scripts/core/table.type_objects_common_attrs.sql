include(core/inc/common.inc)

/* ********************************************************************************************** */

CREATE TABLE CORE.TYPE_OBJECTS_COMMON_ATTRS
(
 ID CORE.D_ATTR_ID NOT NULL,

 NAME VARCHAR(64) NOT NULL,

 CONSTRAINT PK_TYPE_COMMON_OBJ_ATTRS PRIMARY KEY(ID)
);/*TABLE CORE.TYPE_OBJECTS_COMMON_ATTRS */

INSERT INTO CORE.TYPE_OBJECTS_COMMON_ATTRS (ID, NAME)
VALUES (OBJECT_COMMON_ATTR__READ_ONLY, 'Объект только на чтение');

/* ********************************************************************************************** */
