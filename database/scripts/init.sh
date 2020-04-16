#!/bin/bash
# НЕ СТАВЬ ПРОБЕЛЫ ВОКРУГ ЗНАКА РАВЕНСТВА!
#if [ -z $2 ];
#then
# echo -e "Как использовать:\n\tbash init.sh generator postgres" && exit;
#fi

export PGPASSWORD=pg

arg_pguser=postgres
arg_pgdb=bigdata_test

echo "База данных: "$arg_pgdb" , юзер: "$arg_pguser;

cmd="psql -h localhost -d "$arg_pgdb" -U "$arg_pguser" -w";
path="./";

#массив имен файлов
files=(
 'core/drop_public_schema.sql'
 'core/schema.sql'
 'core/domains.sql'
 'core/table.table_names.sql'
 'core/table.classes.sql'
 'core/table.possible_objects_links.sql'
 'core/table.objects.sql'
 'core/table.type_objects_common_attrs.sql'
 'core/table.objects_common_attrs.sql'
 'core/view.v_possible_objects_links.sql'
 'common/schema.sql'
 'common/domains.sql'
 'common/table.employee.sql'
 'common/table.currencies.sql'
 'common/table.type_customer_states.sql'
 'common/table.customers.sql'
 'common/table.robots.sql'
 'cryptobot/schema.sql'
 'cryptobot/domains.sql'
 'cryptobot/table.coins.sql'
 'cryptobot/table.markets.sql'
 'cryptobot/table.markets_items_names.sql'
 'cryptobot/table.prices.sql'
 'cryptobot/table.prices_history.sql'
 'cryptobot/table.amounts_history.sql'
)

dropdb --if-exists -h localhost -U $arg_pguser -w -e $arg_pgdb
createdb  -E UTF8 -e -w -h localhost -U $arg_pguser $arg_pgdb

rm -r $path/m4;

mkdir $path/m4;
mkdir $path/m4/core;
mkdir $path/m4/common;
mkdir $path/m4/generator;
mkdir $path/m4/cryptobot;

for f in ${files[*]}
do
 echo ------------------------------------$f------------------------------------

 m4 $path$f > $path'm4/'$f
 $cmd -f $path'm4/'$f
done
