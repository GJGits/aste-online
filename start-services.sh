#!/bin/bash

# start mysql
sudo docker run --rm --name ganesh-mysql-v3 \
-v /home/gjcode/Politecnico/CodeProjects/PD1/aste-online/www.aste-online.com/db:/var/lib/mysql \
-p 3306:3306 --network=host \
-e MYSQL_ROOT_PASSWORD=mypasswd -d mysql:5.7.27

# start apache
sudo docker run -it --rm \
-v /home/gjcode/Politecnico/CodeProjects/PD1/aste-online/www.aste-online.com:/var/www/html/www.aste-online.com/ \
-p 80:80 -p 443:443 --network=host \
--name running-aste-online aste-online
