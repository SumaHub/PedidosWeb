#!/bin/sh
cd /home/web/sisweb/pedido.sumagroups.com ;

php bin/console app:invoices-open-report:send
