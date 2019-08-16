#!/bin/bash
sudo docker run --name ganesh-mysql-v3 \
-v /home/gjcode/Politecnico/prenotazioni-mediche/db:/var/lib/mysql \
-e MYSQL_ROOT_PASSWORD=mypasswd -d mysql:5.7.27