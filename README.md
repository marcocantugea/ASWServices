# Libreria de Serviccios AWS de GME - gme/dev-aws-services

## Contenido

- [Acerca de](#about)
- [Requisitos](#requisitos)
- [Instalaci&oacute;n](#installing)
- [Servicio SES (Simple Email Services)](#awsses)
- [Usage](#usageMail)
- [Servicio S3 Storage](#getting_started)
- [Usage](#usageS3)
- [Contributing](../CONTRIBUTING.md)

## Acerca de  <a name = "about"></a>

Este repositorio es un conjunto de librerias para el servicio AWS, el cual consta de sercios:

- SES Mail
- S3 Storage

Estas librerias estan diseñadas para facilitar el desarollo y funcionalidad de las aplicaciones dise&ntilde;adas en PHP 7.0 y superior

## Requisitos <a name = "requisitos"></a>

Se requiere agregar al proyecto el archivo de variables de entorno(.env) los acesos al servicio AWS, ya que lo estipula en la documentacion del sdk de Amazon.

Tambien se pueden definir al momento de destar contruyendo el objeto. para mas informacion utilize la siguiente liga:

<a href="https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials.html">https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials.html</a>

### Instalaci&oacute:n <a name = "instalacion"></a>

Para la instalacion lo obtenemos mediante el comando de composer

```
composer gme/dev-aws-services

```


### Servicios SES (Simple Email Services) <a name = "awsses"></a>

Se utiliza la class AWSEmail para el envio de correos mediante el servicio SES de AMAZON.

Para la simplificacion de envio de correos a traves de la librerias utilizamos el servicios SES de amazon de la siguiente manera

```php

    $EmailAWS= new SESService(["myemail@server.mx"],"subject","This is a plain text","here are the html document");
    //si contamos con archivo .env y la configuracion de las variabels de AWS, omitimos la siguiente linea
    $EmailAWS->setSesKey("AWSSESKEY")->setSesSecret("AWSSESSECRET");
    $EmailAWS->send();
    $response=$EmailAWS->getResponse();


```


## Uso de libreria  <a name = "usageses"></a>

Para la libreria AWS cuenta con los siguientes metodos y descripciones

Metodos:
 __construct() : constructor de clase
    - Parametros
        - recipients:array(): lista de los correos electronicos en formato string (opcional)
        - subject:string    : Asunto del correo electronioco (opcional)
        - plainText:string  : Texto sin formato para el contenido del correo (opcional)
        - html_body:string  : Texto en formato html para el contenido del correo (opcional)
        - sesKey:string     : Llave de sevicio AWS SES (opcional)
        - sesSecret:string  : Texto secreto del servicio AWS SES(opcional)

setSender() : Asigna el correo que envia la notificacion(FROM:)
    -Parametros
        - sender:string     : Correo electronico en formato string

setRecipients(): Asigna un arreglo del correos electronicos
    -Parametros:
        - recipients:array  : Array de correos electronicos en formato string

setSubject(): Asigna el asunto del correo a enviar
    - Parametros:
        - subject:string    : Asunto en formato texto

setPlainText() : Asigna contenido en formato texto al mensaje
    - Parametros:
        - plainText:string  : Asunto en formato texto

setHtmlBody() : Asigna el formato html en formato texto
    - Parametros:
        - htmlBody : Contenido html para el mensaje

setSesKey() : Asigna la llave al servicio AWS SES
    - Parametros:
        - sesKey:string  : Llave en formato string del servicio AWS SES

setSesSecret() : Asigna el texto secretro del servicio AWS 
    - Parametros:
        - sesSecret:string  : formato string del texto secreto

getResponse() : Obtiene la respuesta del envio de las notificaiones

getRecipients() : Obtiene los emails asignados a los cuale se enviaran las notificaciones

getSubject() : Obtiene el asunto asignado

getPlainText() : Obtiene el contenido de texto asignado

getHtmlBody() : Obtiene el cuerpo html del mensaje asignado

getSesKey() : Obtiene la llave asignada del servicio AWS SES

getSesSecret() : Obtiene en formato string el texto secreto del servicio AWS SES

### Servicios S3  <a name = "awss3"></a>

Se utiliza la class S3Services  para subir un archivo al servicio S3 

Se requiere que las llaves de S3 esten en un directorio o en el archivo env.

```php

    $S3Service= new S3Service("gme-proveedores-cfdi","us-west-2",EObtainCredentialsFrom::INI_FILE);            
    $result=$S3Service->uploadFile("filesample.txt",  __DIR__."\\testsamples","New Folder","samplefile.txt","text/html");
    print($result);

```
