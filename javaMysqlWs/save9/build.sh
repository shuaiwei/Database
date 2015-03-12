sourceFile="ohsumed87.txt"
totalLines=0

#grep -n "<I>" ohsumed87.txt : show the line number and <I> like 6994:<I>;
#cut -d ':' -f1 : delimit by : get the first one;
for i in `grep -n "<I>" $sourceFile | cut -d ':' -f1`; 
do
   lineNumber[$totalLines]=$i
   let "totalLines++"
done
let "totalLines--"

rm init.sql
php create.php

rm -rf classifiedDocuments
mkdir classifiedDocuments

index=0
while [ "$index" -lt "$totalLines" ]; 
do
   let "lineStart=lineNumber[index]"
   let "lineEnd=lineNumber[index+1]-1"
   let "index++"
   awk 'NR >='$lineStart' && NR <= '$lineEnd'' $sourceFile > tempFile
   php parser.php
done

#rm tempFile
