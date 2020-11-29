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
## Uso de libreria  <a name = "usageses"></a>

Para la libreria AWS cuenta con los siguientes metodos y descripciones

Metodos:
 __construct() : constructor de clase
    - Parametros
        - bucket    : La configuracion el bucket
        - region    : Configuracion del la region
        - getCrefentialsFrom    : Enum de EObtainCredentialsFrom sirve para Configuraicion de obtener las credenciasles de un archivo ini o 
                                  del archivo .env

setCredentials() : configura la ruta de las credenciales AWS se archivo INI
        - Parametros
            getCrefentialsFrom : ruta de donde se encuentra el archivo INI

uploadFile() : funcion para cargar un archivo al servicio S3
    - Parametros
            sourceFile  : nombre del archivo a cargar
            sourceFolder    : ruta del archivo a cargar
            destinationFolder   : ruta donde se cargara el archivo
            fileName    : nombre del archivo que tendra al guardar en el S3
            type    : tipo de archivo mime

saveFileContent() : funcion para guardar el contenido de un archivo, en el servicio S3
    - Parametros
            filename    : nombre del archivo que se generara en el S3
            content     : contenido de archivo
            folderttoSave   :   folder donde se cargara
            type        : tipo de archivo mime

deleteFile() : funcion para borrar un archivo del servicio S3
    - Parametros
            file : Nombre del archivo a borrar.
            folder : ruta del archivo a borrar.

fileExist() : funcion para verificar que exista un archivo en el S3
    - Parametros
        fileName : ruta y nombre de archivo a consultar

getObjectUrl() :  obtiene el URL de un archivo en el S3
    - Parametros
        fileName : ruta y nombre de archivo a consultar

getS3Client() : obtiene el cliente del S3

renameFile() : renombra un archivo del servicio del S3
    - Parametros
        oldName : nombre del archivo a cambiar
        newName : nuevo nombre del archivo

copyFile() : funcion para copiar un archivo entre el servicio S3
    - Parametros:}
        sourceName : nombre y ruta del archivo a copiar
        targetBucket : bucket destino a copiar
        targetName : nombre y ruta del archivo a crear
        contentType : tipo de archivo mime
        deleteSourceFile : opcion a borrar el archivo fuente

listObjectsBuckets():  funcion para listar el contenido de un bucket.
    - Parametros
        prefix  : prefijos y configuracion del S3
        bucket  : bucket seleccionado para obtener

getFileObject() : obtiene el archivo en formato objeto del S3
    - Parametros:
        fileName : ruta y nombre de archivo.

getFileContent() : obtiene el contenido de un archivo almacenado del S3
    - Parametros
        fileName    : ruta y nombre de archivo.

saveFileByStreamWraper() : funcion para guardar un archivo del servicio S3
    - Parametros
        filename : nombre de archivo
        path    :   ruta de archivo a guardar
        content :   contenido del archivo a guardar

setBucket() : configura el bucket que se desea utilizar
    - Parametros
        bucket : nombre del bucket a selecionar

setProfileIniTagCredentials() : define la etiqueta de las credenciales a obtener del archivo INI
    - Parametros    
        profile : etiqueta que consultara del archivo ini para obtener las credenciales

setCredentialsIniFilePath() : estable la ruta para obtener el archivo INI para las credenciales.
    - Parametros
        path : ruta de la localizacion del archivo ini para el servicio S3

setRegion() : configura la region para el servicio S3.

getBucket() : obtiene el bucket configurado para la libreria

getCredentialsIniFilePath() : obtiene la ruta del archivo de las credenciales AWS configurado.

getProfileIniTagCredentials() : obtiene la etiqueta que tomara para el archivo INI.
        
