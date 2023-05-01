# Based on this answer: https://stackoverflow.com/a/61859561/1956278
# command: PowerShell.exe -ExecutionPolicy UnRestricted -File mysql_error_repair.ps1

# Backup old data
Rename-Item -Path "C:/xampp/mysql/data" -NewName "C:/xampp/mysql/data_old"

# Create new data directory
Copy-Item -Path "C:/xampp/mysql/backup" -Destination "C:/xampp/mysql/data" -Recurse
#Remove-Item "./data/test" -Recurse
$dbPaths = Get-ChildItem -Path "C:/xampp/mysql/data_old" -Exclude ('mysql', 'performance_schema', 'phpmyadmin', 'test') -Recurse -Directory
Copy-Item -Path $dbPaths.FullName -Destination "C:/xampp/mysql/data" -Recurse
Copy-Item -Path "C:/xampp/mysql/data_old/ibdata1" -Destination "C:/xampp/mysql/data/ibdata1"

# Notify user
Write-Host "Finished repairing MySQL data"
Write-Host "Previous data is located at C:/xampp/mysql/data_old"