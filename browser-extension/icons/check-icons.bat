@echo off
echo ===================================
echo BookShare Icon Generator
echo ===================================
echo.
echo The HTML icon generator should have opened in your browser.
echo.
echo Please follow these steps:
echo.
echo 1. In the browser window that opened, you'll see 3 buttons
echo 2. Click each button to download the icons:
echo    - Download 128x128
echo    - Download 48x48  
echo    - Download 16x16
echo.
echo 3. Save all 3 PNG files to this folder:
echo    %~dp0
echo.
echo 4. The files should be named:
echo    - icon128.png
echo    - icon48.png
echo    - icon16.png
echo.
echo 5. After saving all 3 files, press any key to verify...
pause
echo.
echo Checking for icon files...
echo.

if exist "%~dp0icon16.png" (
    echo [OK] icon16.png found
) else (
    echo [MISSING] icon16.png
)

if exist "%~dp0icon48.png" (
    echo [OK] icon48.png found
) else (
    echo [MISSING] icon48.png
)

if exist "%~dp0icon128.png" (
    echo [OK] icon128.png found
) else (
    echo [MISSING] icon128.png
)

echo.
echo ===================================
echo.
if exist "%~dp0icon16.png" if exist "%~dp0icon48.png" if exist "%~dp0icon128.png" (
    echo SUCCESS! All icons are ready!
    echo.
    echo You can now:
    echo 1. Go to chrome://extensions/
    echo 2. Click "Reload" on the BookShare extension
    echo.
) else (
    echo Some icons are still missing.
    echo Please download them from the browser page and try again.
    echo.
)

pause
