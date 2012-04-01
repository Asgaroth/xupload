#!/bin/bash
echo "The script you are running has basename `basename $0`, dirname `dirname $0`"
echo "The present working directory is `pwd`"

echo "Deleting runtime contents..."
rm -Rf "`pwd`/protected/runtime"/*

echo "Deleting assets..."
rm -Rf "`pwd`/assets"/*

echo "Setting folder ownwer and group..."
chown alex:www-data -R "`pwd`"


echo "Setting folder rights..."
chmod 775 -R "`pwd`"
chmod 777 -R "`pwd`/css/"
chmod 777 -R "`pwd`/images/"
chmod 777 -R "`pwd`/assets/"

