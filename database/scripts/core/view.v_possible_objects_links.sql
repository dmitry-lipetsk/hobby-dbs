include(core/inc/common.inc)

/* ********************************************************************************************** */

CREATE VIEW CORE.V_POSSIBLE_OBJECTS_LINKS
AS
SELECT CL1.ID   AS OWNER_CLASS_ID,
       CL1.NAME AS OWNER_CLASS_NAME,
       CL2.ID   AS CHILD_CLASS_ID,
       CL2.NAME AS CHILD_CLASS_NAME
FROM CORE.CLASSES CL1
     JOIN CORE.POSSIBLE_OBJECTS_LINKS P
      ON CL1.ID=P.OWNER_CLASS
     JOIN CORE.CLASSES CL2
      ON P.CHILD_CLASS=CL2.ID
ORDER BY CL1.ID, CL2.ID

/* ********************************************************************************************** */
