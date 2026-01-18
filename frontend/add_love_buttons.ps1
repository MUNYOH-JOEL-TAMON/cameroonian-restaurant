$htmlFile = "c:\Users\CSA\Desktop\product page\index.html"
$content = Get-Content $htmlFile -Raw -Encoding UTF8

# Add love button after each menu-image-wrapper opening tag
$pattern = '(<div class="menu-image-wrapper">)'
$replacement = '$1`r`n                            <button class="btn-love">♥</button>'
$newContent = $content -replace $pattern, $replacement

# Save the file
$newContent | Set-Content $htmlFile -Encoding UTF8 -NoNewline

Write-Host "Love buttons added to all product cards!"
