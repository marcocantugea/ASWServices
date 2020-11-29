<?php

namespace Tests;

use Exception;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use AWS\Services\Storage\S3Service;
use Aws\Credentials\CredentialProvider;
use AWS\Services\Storage\Enums\EObtainCredentialsFrom;


/*
 * Copyright (C) 2020 Marco Cantu Gea
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

final class S3serviceUnitTest extends TestCase{

    private $AWSKeyId="";
    private $AWSSecret="";

    public function setUp():void{
        $dotenv = Dotenv::createImmutable('./');
        $dotenv->load();
        
        $this->AWSKeyId=(!isset($_ENV['AWS_ACCESS_KEY_ID'])) ? "" : $_ENV['AWS_ACCESS_KEY_ID'];
        $this->AWSSecret=(!isset($_ENV['AWS_SECRET_ACCESS_KEY'])) ? "" : $_ENV['AWS_SECRET_ACCESS_KEY'];
        
    }


    /**
     * Prueba para subir un archivo al servicio S3
     *
     * @return void
     */
    public function test_UploadFileSuccess(){
    
        try {
            //crea el 
            $S3Service= new S3Service("gme-proveedores-cfdi","us-west-2",EObtainCredentialsFrom::INI_FILE);            
            $result=$S3Service->uploadFile("filesample.txt",  __DIR__."\\testsamples","New Folder","samplefile.txt","text/html");
            $this->assertTrue(true);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    /**
     * Prueba para guardar contenido a un archivo al S3
     *
     * @return void
     */
    public function test_saveContentToFile(){
        try {
            $S3Service= new S3Service("gme-proveedores-cfdi","us-west-2",EObtainCredentialsFrom::INI_FILE);
            $contenido="En ambos ejemplos se abriría un archivo llamado que cuelga del directorio padre del actual, ruta

                Acceso a las variables de entorno
                Una vez que hemos cargado los archivos de variables de entorno, estarán disponibles sus variables y valores en el código de las aplicaciones por medio de varios métodos diferentes.

                La función getenv(), en la que pasamos la cadena de la variable de entorno que queremos acceder.

                A través del array superglobal 
                A través del array superglobal 
            ";
            $result=$S3Service->saveFileContent("sample2.txt",$contenido,"New Folder","text/html");
            $this->assertTrue(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Prueba para revisar si existe un archivo en el s3
     *
     * @return void
     */
    public function test_fileExistOnS3(){
        try {
            $S3Service= new S3Service("gme-proveedores-cfdi","us-west-2",EObtainCredentialsFrom::INI_FILE);
            $this->assertTrue($S3Service->fileExist("New Folder/samplefile.txt"));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Obtiene el url de un archivo del servicio S3
     *
     * @return void
     */
    public function test_GetURLFromFile(){
        try {
            $S3Service= new S3Service("gme-proveedores-cfdi","us-west-2",EObtainCredentialsFrom::INI_FILE);
            $url=$S3Service->getObjectUrl("New Folder/filesample.txt");
            $this->assertIsString($url);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * prueba de obtner el contenido de un archivo
     *
     * @return void
     */
    public function test_GetFileContent(){
        try {
            $S3Service= new S3Service("gme-proveedores-cfdi","us-west-2",EObtainCredentialsFrom::INI_FILE);
            $content = $S3Service->getFileContent("New Folder/samplefile.txt");
            print($content);
            $this->assertTrue(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}