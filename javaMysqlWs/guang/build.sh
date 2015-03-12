#!/bin/bash
sourcefile="ohsumed87.txt"
#sourcefile=$1
qcount=0
prefix="q"
suffix="sql"
for i in `grep -n "<I>" $sourcefile | cut -d ':' -f 1`; do
   fileNum[$qcount]=$i
   let "qcount++"
done
let "qcount--" #the first and last line both start with "--"
rm init.sql
php setup.php

qidx=0
rm -r files
mkdir files
while [ "$qidx" -lt "$qcount" ]; do
   let "qidx++"
   let "line_start=fileNum[qidx-1]"
   let "line_end=fileNum[qidx]-1"
   let "ndiff=line_end - line_start + 1"
#        newfilename=$prefix$qidx.$suffix
   head -n $line_end $sourcefile |tail -n $ndiff>temp
#   content="$(head -n $line_end $sourcefile |tail -n $ndiff)"
#   echo $content
   php parser.php
   rm temp
#   php parser.php "`head -n $line_end $sourcefile |tail -n $ndiff`"
done
