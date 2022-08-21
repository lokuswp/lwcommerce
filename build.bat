@echo off
DEL lwcommerce.zip
mkdir "lwcommerce"
robocopy src\. lwcommerce\src\. /IS /S /XD
robocopy languages\. lwcommerce\languages\.
COPY  "languages\lwcommerce.pot" "lwcommerce\languages\lwcommerce.pot"
COPY  "languages\lwcommerce-id_ID.mo" "lwcommerce\languages\lwcommerce-id_ID.mo"
COPY  "languages\lwcommerce-id_ID.po" "lwcommerce\languages\lwcommerce-id_ID.po"

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