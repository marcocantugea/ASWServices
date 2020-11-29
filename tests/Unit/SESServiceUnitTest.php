<?php

namespace Tests;

use AWS\Services\Mail\SESService;
use PHPUnit\Framework\TestCase;


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

final class SESServiceUnitTest extends TestCase{

    public function setUp():void{

    }

    public function tearDown():void{

    }

    public function test_PruebaConstructorClase(){
        try {
            $EmailAWS= new SESService(["marco.cantu@gme.mx"],"subject","This is a plain text","here are the html document");
            $this->assertCount(1,$EmailAWS->getRecipients());
            $this->assertIsString($EmailAWS->getSubject());
            $this->assertIsString($EmailAWS->getPlainText());
            $this->assertIsString($EmailAWS->getHtmlBody());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function test_SendEmail(){
        try {
            $EmailAWS= new SESService(["marco.cantu@gme.mx"],"subject","This is a plain text","here are the html document");
            $EmailAWS->setSesKey("")->setSesSecret("");
            $EmailAWS->send();
            $response=$EmailAWS->getResponse();
            
            if(isset($response['error'])){
                $this->assertTrue(false, 'There was a not valid response ' . $response['error']);
            }

            $this->assertTrue(true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

}