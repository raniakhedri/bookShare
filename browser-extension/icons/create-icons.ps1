# BookShare Icon Generator - PowerShell
# Creates simple PNG icons using .NET System.Drawing

Add-Type -AssemblyName System.Drawing

function Create-Icon {
    param (
        [int]$Size,
        [string]$OutputPath
    )
    
    try {
        # Create bitmap
        $bitmap = New-Object System.Drawing.Bitmap($Size, $Size)
        $graphics = [System.Drawing.Graphics]::FromImage($bitmap)
        
        # Enable anti-aliasing
        $graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
        $graphics.TextRenderingHint = [System.Drawing.Text.TextRenderingHint]::AntiAlias
        
        # Create gradient brush (purple)
        $rect = New-Object System.Drawing.Rectangle(0, 0, $Size, $Size)
        $color1 = [System.Drawing.Color]::FromArgb(255, 102, 126, 234)  # #667eea
        $color2 = [System.Drawing.Color]::FromArgb(255, 118, 75, 162)   # #764ba2
        $brush = New-Object System.Drawing.Drawing2D.LinearGradientBrush($rect, $color1, $color2, 45)
        
        # Fill background with gradient
        $graphics.FillRectangle($brush, $rect)
        
        # Draw text
        $text = "B"
        
        $font = New-Object System.Drawing.Font("Segoe UI Emoji", [int]($Size * 0.5), [System.Drawing.FontStyle]::Bold)
        $textBrush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White)
        
        # Center text
        $format = New-Object System.Drawing.StringFormat
        $format.Alignment = [System.Drawing.StringAlignment]::Center
        $format.LineAlignment = [System.Drawing.StringAlignment]::Center
        
        $graphics.DrawString($text, $font, $textBrush, ($Size / 2), ($Size / 2), $format)
        
        # Save
        $bitmap.Save($OutputPath, [System.Drawing.Imaging.ImageFormat]::Png)
        
        # Cleanup
        $graphics.Dispose()
        $bitmap.Dispose()
        $brush.Dispose()
        $textBrush.Dispose()
        $font.Dispose()
        
        Write-Host "✓ Created $OutputPath" -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "✗ Failed to create $OutputPath : $_" -ForegroundColor Red
        return $false
    }
}

# Main script
Write-Host ""
Write-Host "BookShare Extension Icon Generator" -ForegroundColor Cyan
Write-Host "===================================" -ForegroundColor Cyan
Write-Host ""

$iconsDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$sizes = @(16, 48, 128)
$successCount = 0

foreach ($size in $sizes) {
    $outputPath = Join-Path $iconsDir "icon$size.png"
    if (Create-Icon -Size $size -OutputPath $outputPath) {
        $successCount++
    }
}

Write-Host ""
if ($successCount -eq 3) {
    Write-Host "✓ All icons created successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "You can now reload the extension in Chrome." -ForegroundColor Yellow
} else {
    Write-Host "⚠️  Some icons failed to generate." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Alternative method: Open icon-generator.html in your browser" -ForegroundColor Cyan
}
Write-Host ""
