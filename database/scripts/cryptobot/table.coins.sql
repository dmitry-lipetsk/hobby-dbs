include(cryptobot/inc/common.inc)

/* ********************************************************************************************** */

/*reserve first 999 ids*/
CREATE SEQUENCE CRYPTOBOT.SEQ_COINS START WITH 1000;

CREATE TABLE CRYPTOBOT.COINS
(
 OBJECT_ID     CORE.D_OBJECT_ID NOT NULL,
 OBJECT_CLASS  CORE.D_OBJECT_CLASS NOT NULL,

 NAME          CRYPTOBOT.D_COIN_NAME_NN,
 SHORT_NAME    CRYPTOBOT.D_COIN_SHORT_NAME_NN_UCASE,

 CREATION_DATE TIMESTAMP,
 
 /* ---------- */
 M4_DEF_OBJECT_PK(CRYPTOBOT_COINS),

 M4_DEF_OBJECT_FK(CRYPTOBOT_COINS,CORE_OBJECTS),

 CONSTRAINT M4_CONCAT(UNIQUE_, TBLID_CRYPTOBOT_COINS, __short_name)
  UNIQUE(SHORT_NAME,OBJECT_CLASS)
);/*TABLE CRYPTOBOT.COINS*/

M4_CREATE_TRIGGER_OBJ_DELETE(CRYPTOBOT_COINS);

M4_REG_TABLE_NAME2(CRYPTOBOT_COINS,CORE_OBJECTS);

M4_REG_CLASS2(CRYPTOBOT_COINS,CRYPTOBOT_COINS,'seq_coins','Криптовалюта (монета)');

M4_REG_POS_OBJ_LINKS(CORE_OBJECTS,CRYPTOBOT_COINS);

M4_CREATE_TRIGGER_RO_OBJ_GUARD(CRYPTOBOT_COINS);

/* ********************************************************************************************** */
