#!/bin/sh
cd /home/web/sisweb/pedido.sumagroups.com ;

php bin/console app:products-without-price-report:send
