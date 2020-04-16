#!/bin/bash
# НЕ СТАВЬ ПРОБЕЛЫ ВОКРУГ ЗНАКА РАВЕНСТВА!

cmd="php.exe -d include_path='.;../../../../../code'";

#массив имен файлов
files=(
 'lib.dbms.pgsql.connection/test_001-create.php'
 'lib.dbms.pgsql.connection/test_001-create--fail.php'
 'lib.dbms.pgsql.connection/test_002-build_dbobject_name.php'
 'lib.dbms.pgsql.connection/test_002-build_dbobject_name--err-empty.php'
 'lib.dbms.pgsql.connection/test_002-build_dbobject_name--err-bad.php'
 'lib.dbms.pgsql.transaction/test_001-begin.php'
 'lib.dbms.pgsql.transaction/test_001-begin--already_started.php'
 'lib.dbms.pgsql.transaction/test_002-commit.php'
 'lib.dbms.pgsql.transaction/test_002-commit--already_completed.php'
 'lib.dbms.pgsql.transaction/test_002-commit--not_exists.php'
 'lib.dbms.pgsql.transaction/test_002-commit--work.php'
 'lib.dbms.pgsql.transaction/test_003-rollback.php'
 'lib.dbms.pgsql.transaction/test_003-rollback--already_completed.php'
 'lib.dbms.pgsql.transaction/test_003-rollback--not_exists.php'
 'lib.dbms.pgsql.transaction/test_003-rollback--work.php'
 'lib.dbms.pgsql.transaction/test_004-query.php'
 'lib.dbms.pgsql.transaction/test_004-query--already_completed.php'
 'lib.dbms.pgsql.transaction/test_004-query--fail.php'
 'lib.dbms.pgsql.transaction/test_005-gen_id.php'
 'lib.dbms.pgsql.transaction/test_005-gen_id--already_completed.php'
 'lib.dbms.pgsql.transaction/test_005-gen_id--fail.php'
 'lib.dbms.pgsql.transaction/test_006-build_dbobject_name.php'
 'lib.dbms.pgsql.transaction/test_006-build_dbobject_name--err-empty.php'
 'lib.dbms.pgsql.transaction/test_006-build_dbobject_name--err-bad.php'
 'lib.dbms.pgsql.transaction/test_006-build_dbobject_name--err-already_completed.php'
)

bad_tests=()

########################################################################################

number__total=0
number__ok=0
number__faled=0

for f in ${files[*]}
do
 let "number__total+=1"

 echo ------------------------------------ $number__total'. '$f ---------------------

 $cmd -f $f

if [ $? -eq 0 ]
 then
  let "number__ok+=1"

  echo OK
 else
  let "number__failed+=1"

  echo FAILED

  bad_tests=(${bad_tests[@]} $f)
 fi
done

########################################################################################

echo
echo '------------------ SUMMARY'

if [ ${#bad_tests[@]} -eq 0 ]
then
 echo 'NO ERRORS ['$number__ok' test(s) succeeded]'
else
 echo 'SUCCEEDED: '$number__ok' test(s)'
 echo 'FAILED   : '$number__failed' test(s)'
 echo
 echo LIST OF FAILED TESTS:

 n=0

 for f in ${bad_tests[*]}
 do
  let "n += 1"
  echo ' '$n'. '$f
 done
fi

########################################################################################
