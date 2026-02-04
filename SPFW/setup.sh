#!/bin/bash

rm -Rf ./log/*
mkdir ./log/reload
mkdir ./log/open
chmod 777 ./log
chmod 777 ./log/reload
chmod 777 ./log/open
chmod 777 ./text
chmod 755 ./sql/00createDB.sh
touch ./log/sql.log
touch ./log/db.log
touch ./log/debug.log
touch ./log/error.log
chmod 666 ./log/*.log

chmod -R 777 ./obj
chmod -R 777 ./text
chmod 666 ./inc/setting.properties

chmod 777 ./contents_*
chmod 777 ./modules/*
chmod 777 ./obj/*
chmod 777 ./text/*

cp -fp ./inc/setting.properties ./inc/setting.properties.original

cd ./modules
for nm in *.php; do
cp -fp $nm ${nm}.original;
done
cd ../

if [ -d "../httpdocs/images" ];
then
chmod 777 ../httpdocs/images
rm -Rf ../httpdocs/upfile
mkdir ../httpdocs/upfile
chmod 777 ../httpdocs/upfile
fi

if [ -d "../htdocs/images" ];
then
chmod 777 ../htdocs/images
rm -Rf ../htdocs/upfile
mkdir ../htdocs/upfile
chmod 777 ../htdocs/upfile
fi

if [ -d "../html/images" ];
then
chmod 777 ../html/images
rm -Rf ../html/upfile
mkdir ../html/upfile
chmod 777 ../html/upfile
fi

if [ -d "../public_html/images" ];
then
chmod 777 ../public_html/images
rm -Rf ../public_html/upfile
mkdir ../public_html/upfile
chmod 777 ../public_html/upfile
fi
