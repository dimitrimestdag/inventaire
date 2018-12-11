#!/bin/bash
DATE=`date +%y_%m_%d`
mysqldump -u root --single-transaction --password=root --all-databases > "/mnt/ankama_helpdesk/backup_$DATE.sql"

# On compte le nombre d'archive presente dans le dossier
NbArchive=$(ls -A /mnt/ankama_helpdesk/ |wc -l)
# Si il y a plus de 4 archives, on supprime la plus ancienne
if [ "$NbArchive" -gt 15 ];then
# On recupere l'archive la plus ancienne
  Old_backup=$(ls -lrt /mnt/ankama_helpdesk/ |grep ".sql" | head -n 1 | cut -d ":" -f 2 | cut -d " " -f 2);
# On supprime l'archive la plus ancienne
  rm /mnt/ankama_helpdesk/$Old_backup
fi