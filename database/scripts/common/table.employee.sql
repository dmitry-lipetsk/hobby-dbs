include(common/inc/common.inc)

/* ********************************************************************************************** */

/*reserve first 99 ids*/
CREATE SEQUENCE COMMON.SEQ_EMPLOYEE START WITH 100;

CREATE TABLE COMMON.EMPLOYEE
(
 OBJECT_ID     CORE.D_OBJECT_ID NOT NULL,
 OBJECT_CLASS  CORE.D_OBJECT_CLASS NOT NULL,

 NAME          COMMON.D_EMPLOYER_NAME_NN,
 LOGIN         COMMON.D_EMPLOYER_LOGIN_NN,
 PSWD_MD5      COMMON.D_MD5_CODE NOT NULL,

 ENABLE        BOOLEAN NOT NULL,

 /* ---------- */
 M4_DEF_OBJECT_PK(COMMON_EMPLOYEE),

 M4_DEF_OBJECT_FK(COMMON_EMPLOYEE,CORE_OBJECTS),

 CONSTRAINT M4_CONCAT(UNIQUE_, TBLID_COMMON_EMPLOYEE, __login)
  UNIQUE(LOGIN,OBJECT_CLASS)
);/*TABLE COMMON.EMPLOYEE*/

M4_CREATE_TRIGGER_OBJ_DELETE(COMMON_EMPLOYEE);

M4_REG_TABLE_NAME2(COMMON_EMPLOYEE,CORE_OBJECTS);

M4_REG_CLASS2(COMMON_EMPLOYEE,COMMON_EMPLOYEE,'seq_employee','Служащий (оператор)');

M4_REG_POS_OBJ_LINKS(CORE_OBJECTS,COMMON_EMPLOYEE);

M4_CREATE_TRIGGER_RO_OBJ_GUARD(COMMON_EMPLOYEE);

/* ********************************************************************************************** */

/*Admin: 123*/

INSERT INTO TBLNAME_CORE_OBJECTS
 (OBJECT_ID,
  OBJECT_CLASS,
  OWNER_ID,
  OWNER_CLASS)
VALUES
 (1,
  CLASSID_COMMON_EMPLOYEE,
  0,
  CLASSID_CORE_OBJECTS);

INSERT INTO TBLNAME_COMMON_EMPLOYEE
 (OBJECT_ID,
  OBJECT_CLASS,
  NAME,
  LOGIN,
  PSWD_MD5,
  ENABLE)
VALUES
 (1,
  CLASSID_COMMON_EMPLOYEE,
  'Built-in admin account',
  'Admin',
  '202cb962ac59075b964b07152d234b70',
  true);

/* ********************************************************************************************** */
