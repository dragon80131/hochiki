#!/bin/sh

ROOTPATH=D:/xampp/htdocs/kotei/

LIBPATH=${ROOTPATH}SPFW/
GENERATION=3

for files in log/db.log log/debug.log log/error.log log/sql.log 
do
	cd $LIBPATH
	count=$GENERATION
	until [ $count -eq 1 ];
	do
		from=`expr $count - 1`
		mv -f $files.$from $files.$count
		count=`expr $count - 1`
	done

	mv -f $files $files.1
	touch $files
	chmod 666 $files
done

#mysqldump -u root --opt --password=2wsx#EDC kojikotei >  ${LIBPATH}kojikotei.db
#gzip -c ${LIBPATH}user.db > ${LIBPATH}user.db.gz
#rm -f ${LIBPATH}user.db

#rm -f ${LIBPATH}backup.tar.gz
#tar cfz /tmp/backup.tar.gz ${ROOTPATH}SPFW ${ROOTPATH}httpdocs
#mv /tmp/backup.tar.gz ${LIBPATH}backup.tar.gz
