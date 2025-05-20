@echo off
echo Copying db.sqlite to C:\Users\User\AppData\Local\
copy "db.sqlite" "C:\Users\User\AppData\Local\db.sqlite" /Y
if %errorlevel%==0 (
    echo Copy successful.
) else (
    echo Failed to copy the file.
)
pause
