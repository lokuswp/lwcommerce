@echo off
DEL lwcommerce.zip
mkdir "lwcommerce"
robocopy src\. lwcommerce\src\. /IS /S /XD
robocopy languages\. lwcommerce\languages\. /IS /S /XD
COPY  "lwcommerce.php" "lwcommerce\lwcommerce.php"
COPY  "uninstall.php" "lwcommerce\uninstall.php"
COPY  "CHANGELOG.md" "lwcommerce\CHANGELOG.md"
echo on
for /f "tokens=3,2,4 delims=/- " %%x in ("%date%") do set d=%%y%%x%%z
set data=%d%
Echo zipping...
"C:\Program Files\7-Zip\7z.exe" a -tzip lwcommerce.zip lwcommerce
echo Done!
DEL /s /q lwcommerce\.
DEL /s /q lwcommerce