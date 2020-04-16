include(cryptobot/inc/common.inc)

/* ********************************************************************************************** */

M4_DEF_DOMAIN_FOR_NAME_NN(CRYPTOBOT.D_COIN_NAME,64);

/* ********************************************************************************************** */

M4_DEF_DOMAIN_FOR_NAME_NN_UCASE(CRYPTOBOT.D_COIN_SHORT_NAME,16);

/* ********************************************************************************************** */

M4_DEF_DOMAIN_FOR_NAME_NN(CRYPTOBOT.D_MARKET_NAME,64);

/* ********************************************************************************************** */

M4_DEF_DOMAIN_FOR_NAME_NN_UCASE(CRYPTOBOT.D_MARKET_SHORT_NAME,32);

/* ********************************************************************************************** */

M4_DEF_DOMAIN_FOR_NAME_NN(CRYPTOBOT.D_MARKET_ITEM_NAME,64);

/* ********************************************************************************************** */

M4_DEF_DOMAIN_FOR_NAME_NN(CRYPTOBOT.D_MARKET_ITEM_SHORT_NAME,16);

/* ********************************************************************************************** */

CREATE DOMAIN CRYPTOBOT.D_PRICE_VALUE_NN
AS
DOUBLE PRECISION
NOT NULL
CHECK(VALUE>0);

/*пока без TIME ZONE*/
CREATE DOMAIN CRYPTOBOT.D_PRICE_DATE_NN
AS
TIMESTAMP
NOT NULL;

/* ********************************************************************************************** */

CREATE DOMAIN CRYPTOBOT.D_AMOUNT_VALUE_NN
AS
DOUBLE PRECISION
NOT NULL
CHECK(VALUE>0);

/*пока без TIME ZONE*/
CREATE DOMAIN CRYPTOBOT.D_AMOUNT_DATE_NN
AS
TIMESTAMP
NOT NULL;

/* ********************************************************************************************** */
