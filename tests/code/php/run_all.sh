#!/bin/bash
# НЕ СТАВЬ ПРОБЕЛЫ ВОКРУГ ЗНАКА РАВЕНСТВА!

cmd="php.exe -d include_path='.;../../../code'";

#массив имен файлов
files=(
 'database/data/core/code.database.data.core.class_names/test_001-check_names.php'
 'database/data/common/code.database.data.common.class_names/test_001-check_names.php'
 'database/data/cryptobot/code.database.data.cryptobot.class_names/test_001-check_names.php'
 'common/code.common.dbclass_manager/test_001.php'
 'common/code.common.dbclass_manager/test_002-get_class_by_id--not_found.php'
 'common/code.common.dbclass_manager/test_003-get_class_by_name--not_found.php'
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
