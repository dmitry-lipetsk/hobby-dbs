#!/bin/bash
# НЕ СТАВЬ ПРОБЕЛЫ ВОКРУГ ЗНАКА РАВЕНСТВА!

cmd="php.exe -d include_path='.;..;../../../../code'";

#массив имен файлов
files=(
 'source/upload.php'
)

bad_files=()

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

  bad_files=(${bad_files[@]} $f)
 fi
done

########################################################################################

echo
echo '------------------ SUMMARY'

if [ ${#bad_files[@]} -eq 0 ]
then
 echo 'NO ERRORS ['$number__ok' file(s) succeeded]'
else
 echo 'SUCCEEDED: '$number__ok' file(s)'
 echo 'FAILED   : '$number__failed' file(s)'
 echo
 echo LIST OF FAILED TESTS:

 n=0

 for f in ${bad_files[*]}
 do
  let "n += 1"
  echo ' '$n'. '$f
 done
fi

########################################################################################
