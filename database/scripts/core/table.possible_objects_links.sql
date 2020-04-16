include(core/inc/common.inc)

/* ********************************************************************************************** */

CREATE TABLE CORE.POSSIBLE_OBJECTS_LINKS
(
 OWNER_CLASS CORE.D_OBJECT_CLASS NOT NULL,

 CHILD_CLASS CORE.D_OBJECT_CLASS NOT NULL,
 
 CONSTRAINT PK_POSSIBLE_OBJECTS_LINKS PRIMARY KEY (OWNER_CLASS, CHILD_CLASS),

 FOREIGN KEY (OWNER_CLASS) REFERENCES CORE.CLASSES (ID),

 FOREIGN KEY (CHILD_CLASS) REFERENCES CORE.CLASSES (ID)
);/*TABLE CORE.POSSIBLE_OBJECTS_LINKS*/

/* ********************************************************************************************** */
