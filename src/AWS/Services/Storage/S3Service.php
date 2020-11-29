<?php
namespace AWS\Services\Storage;

use Aws\S3\S3Client;
use Aws\Credentials\CredentialProvider;


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

class S3Service {

    private $region = "us-west-2";

    private $bucket = "folder"; //carpeta raiz

    private $factory = array(
        'region' => '',
        'version'=>'latest'
    );

    private $profile = 'default';
    private $path = './.aws/credentials.ini';

    /**
     * Constructor de clase
     *
     * @param string $region
     * @param string $bucket
     * @param string $raizArchivos
     */
    public function __construct(string  $bucket = null,string $region = null,int $getCrefentialsFrom=0)
    {
        $this->region = !empty($region) ? $region :$this->region;
        $this->bucket = !empty($bucket) ? $bucket : $this->bucket;
        $this->factory['region'] = $this->region;
        
        if($getCrefentialsFrom>0){
            $this->setCredentials($getCrefentialsFrom);
        }

    }
   
    /**
     * Obtiene las credenciales mediante otro metodo
     *
     * @param integer $getCrefentialsFrom
     * @return void
     */
    private function setCredentials(int $getCrefentialsFrom){
        switch ($getCrefentialsFrom) {
            case 1:
                $provider = CredentialProvider::ini($this->profile,$this->path);
                $provider = CredentialProvider::memoize($provider);

                $this->factory['credentials']=$provider;

                break;
            
            default:
                break;
        }
    }

    /**
     * Carga un archivo al servicio S3 
     *
     * @param string $sourceFile        Nombre del archivo origen
     * @param string $sourceFolder      Folder del archivo origen
     * @param string $fileName          nombre del archivo que se guardara
     * @param string $type              tipo de contenido mime
     * @return string
     */
    public function uploadFile(string $sourceFile,string  $sourceFolder,string $destinationFolder,string $fileName,string $type = "image/jpeg")
    {
        
        // Instantiate the client.
        $s3 = new S3Client($this->factory);

        $data = array(
            'Bucket'       => $this->bucket,
            'Key'          => $destinationFolder."/".$fileName,
            'SourceFile'   => $sourceFolder."/".$sourceFile,
            'ContentType'  => $type,
            'ACL'          => 'private',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        );

        // Upload a file.
        $result = $s3->putObject($data);

        return $result['ObjectURL'];
    }

    /**
     * Crea y guarda el contenido de un archivo en el servicio s3
     *
     * @param string $nombre        nombre del archivo
     * @param mixed $contenido      Contenido de un archivo
     * @param string $carpeta       Carpeta donde se va a guardar el archivo
     * @param string $type          tipo de contenido del archivo mime
     * @return string
     */
    //public function guarda_archivo($contenido,string $carpeta,string $nombre, $type = "image/jpeg")
    public function saveFileContent(string $filename,$content,string $folderttoSave,string  $type = "image/jpeg")
    {

        // Instantiate the client.
        $s3 = new  S3Client($this->factory);

        $data = array(
            'Bucket'       => $this->bucket,
            'Key'          => $folderttoSave."/".$filename,
            'Body'         => $content,
            'ContentType'  => $type,
            'ACL'          => 'private',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        );

        // Upload a file.
        $result = $s3->putObject($data);

        return $result['ObjectURL'];
    }

    /**
     * Elimina un archivo del servicio S3
     *
     * @param string $file      nombre del archivo 
     * @param string $folder    carpeta donde se encuentra el archivo (opcional)
     * @return void
     */
    public function deleteFile(string $file, string $folder="")
    {
        $s3 = new S3Client($this->factory);


        $folderpath=(!empty($folder)) ? $folder."/" : "";
        $result = $s3->deleteObject(array(
            'Bucket' => $folderpath.$this->bucket,
            'Key'    => $file,
        ));
    }

    /**
     * Verifica si existe un recurso en Servicio S3
     *
     * @param  string $fileName Ruta del recurso dentro del Bucket ej /images/recurso.extension
     * @return bool
     */
    public function fileExist(string $fileName) : bool
    {
        $s3 = new S3Client($this->factory);

        return (bool) $s3->doesObjectExist($this->bucket, $fileName);
    }

    /**
     * Devuelve Información de un Objeto en Amazon S3
     * @param  string $fileName Ruta del recurso dentro del Bucket ej /images/recurso.extension
     * @return string|mixed
     */
    public function getObjectUrl(string $fileName)
    {
        $s3 = new S3Client($this->factory);

        return $s3->getObjectUrl($this->bucket, $fileName);
    }

    /**
     * Obtiene el cliente de S3
     * @return S3Client
     */
    public function getS3Client()
    {
        return $s3 = new S3Client($this->factory);
    }

    /**
     * Cambia el nombre de un archivo en amazon s3, usando Amazon S3 Stream Wrapper
     * Ejemplo: $aws = new AWS();
     *          $aws->rename('resourcesml/descarga1.jpg','resourcesml/descarga2.jpg');
     *
     * @param $oldName Nombre del archivo
     * @param $oldName Nuebo nombre del archivo
     */
    public function renameFile(string $oldName,string  $newName)
    {
        $oldName = "s3://".$oldName;
        $newName = "s3://".$newName;

        // $factory = array(
        //     'key' => self::AWS_KEY,
        //     'secret' => self::AWS_SEC,
        //     'region' => 'us-west-1',
        // );

        $client = new S3Client($this->factory);
        $client->registerStreamWrapper();

        return rename($oldName, $newName);
    }

  

    /**
     * Copia un archivo de una ruta a otra.
     *
     * @param  string  $sourceName       Nombre del archivo con su ruta
     * @param  string  $targetBucket     Nombre del bucket
     * @param  string  $targetName       Nombre del archivo con su ruta
     * @param  string  $contentType      Tipo de contenido del archivo mime
     * @param  boolean $deleteSourceFile True para eliminar el archivo origen
     * @return void
     */
    public function copyFile(string $sourceName,string  $targetBucket,string $targetName,string  $contentType,bool $deleteSourceFile = true)
    {
        $s3 = $this->getS3Client();
        $s3->CopyObject(array(
            'Bucket'            => $targetBucket,
            'Key'               => $targetName,
            'ContentType'       => $contentType,
            'GrantFullControl'  => 'uri="http://acs.amazonaws.com/groups/global/AllUsers"',
            'CopySource'        => $this->bucket.'/'.$sourceName,
            'MetadataDirective' => 'REPLACE',
            )
        );

        if ($deleteSourceFile) {
            $this->deleteFile($sourceName);
        }
    }

    /**
     * Lista todos los objetos del bucket especificado.
     * 
     * @param  string $prefix Prefijo (subdirectorio)
     * @param  string $bucket Bucket a utilizar
     * @return array          Array de objetos
     */
    public function listObjectsBuckets($prefix = null, $bucket = null) {
        if (is_null($bucket)) {
            $bucket = $this->bucket;
        }

        $client = new S3Client($this->factory);
        $params = array('Bucket' => $bucket);

        if (!is_null($prefix)) {
            $params['Prefix'] = $prefix;
        }

        return $client->getIterator('ListObjects', $params);
    }

    /**
     * Get the value of bucket
     */ 
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * Set the value of bucket
     *
     * @return  self
     */ 
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;

        return $this;
    }

    /**
     * Obtiene el archivo del bucket del S3
     *
     * @param string $fileName
     * @return mixed|array
     */
    public function getFileObject(string $fileName){
        $s3 = new S3Client($this->factory);
        return $s3->getObject([
            'Bucket'=>$this->bucket, 
            'Key'=>$fileName
        ]);
    }

    /**
     * Obtiene el contenido de un archivo que este guardado en el servicio S3
     *
     * @param string $fileName
     * @return mixed
     */
    public function getFileContent(string $fileName){

        if(empty($fileName)){
            return $this;
        }

        //obtenemos el Objeto del s3
        $FileObj= $this->getFileObject($fileName);
        $body = $FileObj['Body'];
        $body->rewind();
        $content = $body->read($FileObj['ContentLength']);

        return $content;
    }

    /**
     * Save a file to S3 using StreamWraper
     *
     * @param string $filename
     * @param string $path
     * @param mixed $content
     * @return void
     */
    public function saveFileByStreamWraper(string $filename,string $path, $content){
        $s3 = new S3Client($this->factory);
        $s3->registerStreamWrapper();
        try {
            file_put_contents("s3://".$this->bucket."/".$path."/".$filename,$content,FILE_APPEND);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Set the value of region
     *
     * @return  self
     */ 
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

      /**
     * Set the value of profile
     *
     * @return  self
     */ 
    public function setProfileIniTagCredentials($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setCredentialsIniFilePath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getCredentialsIniFilePath()
    {
        return $this->path;
    }

    /**
     * Get the value of profile
     */ 
    public function getProfileIniTagCredentials()
    {
        return $this->profile;
    }
}