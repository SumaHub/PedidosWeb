# Pedidos Web
## Plataforma web para la toma de pedidos 

La plataforma de Pedidos Web tiene la capacidad de comunicarse con el ERP iDempiere utilizando como framework Symfony para una mejora escalabilidad y mantenimiento de la aplicacion

## Características

-  Gestion de Catalogo
-  Pedidos
-  Cobros
-  Terceros
  
## Tecnologias
-   Symfony
-   Jaxon

### Configurar Jaxon
Es necesario realizar algunas modificaciones en la libreria de `jaxon-php/jaxon-symfony` para que Jaxon funcione de manera adecuada.

Primero se debe agregar en la clase del Jaxon
```php
public function __construct(KernelInterface $kernel,
        LoggerInterface $logger, TemplateEngine $template, array $config)
```
el parametro de ManagerRegistry
```php
public function __construct(KernelInterface $kernel,
        LoggerInterface $logger, TemplateEngine $template, ManagerRegistry $manager, array $config)
```
Es necesario especificar de donde provendra la instancia de ManagerRegistry, por lo que tambien sera necesario modificar el `src/Resource/config/services.yaml` de la libreria `jaxon-php/jaxon-symfony` de la siguiente manera:
```yaml
arguments:
    - ...
    - "@Doctrine\\Persistence\\ManagerRegistry"
    - "%jaxon%"
```
> Esto es necesario para poder realizar busquedas con Doctrine de manera asincrona



## License

GPLv2

**Free Software, Hell Yeah!**
