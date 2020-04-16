include(cryptobot/inc/common.inc)

/* ********************************************************************************************** */

/*reserve first 999 ids*/
CREATE SEQUENCE CRYPTOBOT.SEQ_AMOUNTS_HISTORY START WITH 1000;

CREATE TABLE CRYPTOBOT.AMOUNTS_HISTORY
(
 OBJECT_ID         CORE.D_OBJECT_ID NOT NULL,
 OBJECT_CLASS      CORE.D_OBJECT_CLASS NOT NULL,

 ASSET_ID          CORE.D_OBJECT_ID NOT NULL,
 ASSET_CLASS       CORE.D_OBJECT_CLASS NOT NULL,

 AMOUNT_DATE       CRYPTOBOT.D_AMOUNT_DATE_NN,

 AMOUNT_VALUE      CRYPTOBOT.D_AMOUNT_VALUE_NN,

 /* ---------- */
 M4_DEF_OBJECT_PK(CRYPTOBOT_AMOUNTS_HISTORY),

 CONSTRAINT M4_CONCAT(FK_, TBLID_CRYPTOBOT_AMOUNTS_HISTORY, _, TBLID_CORE_OBJECTS, __BY_ASSET)
  FOREIGN KEY (ASSET_ID,ASSET_CLASS) REFERENCES TBLNAME_CORE_OBJECTS (OBJECT_ID,OBJECT_CLASS),

 CONSTRAINT M4_CONCAT(UNIQUE_, TBLID_CRYPTOBOT_AMOUNTS_HISTORY, __AMOUNT_DATE)
  UNIQUE (OBJECT_CLASS,ASSET_ID,ASSET_CLASS,AMOUNT_DATE)
);/*TABLE CRYPTOBOT.AMOUNTS_HISTORY*/

/* ********************************************************************************************** */

M4_REG_TABLE_NAME1(CRYPTOBOT_AMOUNTS_HISTORY);

M4_REG_CLASS2(CRYPTOBOT_AMOUNTS_HISTORY,CRYPTOBOT_AMOUNTS_HISTORY,'seq_amounts_history','История количества');

/* ********************************************************************************************** */
