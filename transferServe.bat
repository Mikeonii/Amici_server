@echo off
REM Get full path of current directory
set "CURRENT_DIR=%~dp0"

REM Define source and destination
set "SOURCE_FILE=%CURRENT_DIR%server-autorun.bat"
set "STARTUP_FOLDER=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup"
set "DEST_FILE=%STARTUP_FOLDER%\server-autorun.bat"

REM Copy serve.bat to Startup folder
copy "%SOURCE_FILE%" "%DEST_FILE%" /Y

echo serve.bat has been added to Startup: %DEST_FILE%

