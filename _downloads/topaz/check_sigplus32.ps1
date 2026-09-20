try {
    New-Object -ComObject SigPlus.SigPlusCtrl.1 | Out-Null
    Write-Output "COM32_OK"
}
catch {
    Write-Output ("COM32_FAIL: " + $_.Exception.Message)
}
