include(cryptobot/inc/common.inc)

/* ********************************************************************************************** */

/*reserve first 99 ids*/
CREATE SEQUENCE CRYPTOBOT.SEQ_PRICES START WITH 100;

CREATE TABLE CRYPTOBOT.PRICES
(
 OBJECT_ID       CORE.D_OBJECT_ID NOT NULL,
 OBJECT_CLASS    CORE.D_OBJECT_CLASS NOT NULL,

 MARKET_ID       CORE.D_OBJECT_ID NOT NULL,
 MARKET_CLASS    CORE.D_OBJECT_CLASS NOT NULL,

 ASSET_ID        CORE.D_OBJECT_ID NOT NULL,
 ASSET_CLASS     CORE.D_OBJECT_CLASS NOT NULL,

 CURRENCY_ID     CORE.D_OBJECT_ID NOT NULL,
 CURRENCY_CLASS  CORE.D_OBJECT_CLASS NOT NULL,

 /* ---------- */
 M4_DEF_OBJECT_PK(CRYPTOBOT_PRICES),

 M4_DEF_OBJECT_FK(CRYPTOBOT_PRICES,CORE_OBJECTS),

 CONSTRAINT M4_CONCAT(FK_, TBLID_CRYPTOBOT_PRICES, _, TBLID_CORE_OBJECTS, __BY_MARKET)
  FOREIGN KEY (MARKET_ID,MARKET_CLASS) REFERENCES TBLNAME_CORE_OBJECTS (OBJECT_ID,OBJECT_CLASS),

 CONSTRAINT M4_CONCAT(FK_, TBLID_CRYPTOBOT_PRICES, _, TBLID_CORE_OBJECTS, __BY_ASSET)
  FOREIGN KEY (ASSET_ID,ASSET_CLASS) REFERENCES TBLNAME_CORE_OBJECTS (OBJECT_ID,OBJECT_CLASS),

 CONSTRAINT M4_CONCAT(FK_, TBLID_CRYPTOBOT_PRICES, _, TBLID_CORE_OBJECTS, __BY_CURRENCY)
  FOREIGN KEY (CURRENCY_ID,CURRENCY_CLASS) REFERENCES TBLNAME_CORE_OBJECTS (OBJECT_ID,OBJECT_CLASS),

 CONSTRAINT UNIQUE_OF_MARKET_ASSET_CURRENCY
  UNIQUE(MARKET_ID,MARKET_CLASS,ASSET_ID,ASSET_CLASS,CURRENCY_ID,CURRENCY_CLASS,OBJECT_CLASS),

 CONSTRAINT CHECK_DISABLE_PRICE_BY_HIMSELF
  CHECK(NOT(ASSET_ID=CURRENCY_ID AND ASSET_CLASS=CURRENCY_CLASS))
);/*TABLE CRYPTOBOT.PRICES*/

M4_CREATE_TRIGGER_OBJ_DELETE(CRYPTOBOT_PRICES);

M4_REG_TABLE_NAME2(CRYPTOBOT_PRICES,CORE_OBJECTS);

M4_REG_CLASS2(CRYPTOBOT_PRICES,CRYPTOBOT_PRICES,'seq_prices','Описание цены');

M4_REG_POS_OBJ_LINKS(CORE_OBJECTS,CRYPTOBOT_PRICES);

M4_CREATE_TRIGGER_RO_OBJ_GUARD(CRYPTOBOT_PRICES);

/* ********************************************************************************************** */
