<?php

namespace simplerest\controllers\tests;

use PHPUnit\Framework\TestCase;

final class ValidatorController extends TestCase
{   
	public function testvalidateEmptyRuleException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::validate([],['username'=>'admin','correo'=>'admin@serviya.com']);
    }

	public function testvalidateEmptyDataException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::validate([['field'=>'username','required'=>true],
							['field'=>'correo','required'=>false]],[]);
    }
	
    public function testvalidate()
    {
		// caso base, no hay "reglas" => no se valida nada
		$this->assertTrue(
            Validator::validate(
			[
				['field'=>'username'],
				['field'=>'correo'],
			],
			[
				'username'=>'admin',
				'correo'=>'admin@serviya.com'
			])
        );
		
		// campos requeridos
		$this->assertTrue(
            Validator::validate(
			[
				['field'=>'username','required'=>true],
				['field'=>'correo','required'=>false],
			],
			[
				'username'=>'admin',
				'correo'=>'admin@serviya.com'
			])
        );
		
		// si la validacion falla, la salida es un array con los errores encontrados
		$this->assertInternalType('array',
            Validator::validate(
			[
				'username' => ['required'=>true], // vacio
				'correo'   => ['required'=>false],
			],
			[
				'username'=>'',
				'correo'=>'admin@serviya.com'
			])
        );
		
		$this->assertInternalType('array',Validator::validate(
			[
				'username' => [],
				'correo'   => ['type'=>'string','max'=>'30'],
			],
			[
				'username'=>'admin',
				'correo'=>'administradormundialdelmundoconocidouniversal@serviya.com'
			]));
		
		$this->assertInternalType('array',Validator::validate(
			[
				'nombre' => ['type'=>'notnum']
			],
			
				[
				'nombre'=>'Sebastian2'
				]
			));	
			
		$this->assertInternalType('array',Validator::validate(
			[
				'fuerza' => ['type'=>'integer','min'=>'30']
			],
			
				[
				'fuerza'=>''
				]
			));		
		
		// valido IPv4 con regex, formato regex:/expresion/	
		// (hay otras formas de validatelas, es solo un ejemplo de uso)
		$this->assertTrue(Validator::validate(
			[
				'IPv4' => ['type'=>'regex:/^((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?1)){3}\z/']
			],
				[
				'IPv4'=>'192.168.0.27'	
				]
			));	
			
		$this->assertInternalType('array',Validator::validate(
			[
				'IPv4' =>  ['type'=>'regex:/^((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?1)){3}\z/']
			],
				[
					'IPv4'=>'192.168.0.300'
				]
			));	
	
		$this->assertInternalType('array',Validator::validate(
			[
				'frutas_favoritas' => ['type'=>'array','min'=>3]
			],
				[
					'frutas_favoritas'=>['bananas','manzanas']
				]
			));	
			
			$this->assertTrue(Validator::validate(
			[
				'frutas_favoritas' => ['type'=>'array','min'=>3]
			],
				[
					'frutas_favoritas'=>['bananas','manzanas','peras']
				]
			));		
	
    }
	
	public function testisTypeExceptionPorTipoEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::isType('Brayan','');
    }
	
	public function testisTypeExceptionPorTipoDesconocido()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::isType('Brayan','entero');
    }
	
	public function testisTypeExceptionPorDatoNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::isType(NULL,'int');
    }
	
	public function testisTypeExceptionPorRegexInvalida()
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::isType('190-200-200','regex:/(.*/');
    }
	
	public function testisType()
    {
		$this->assertTrue(
			Validator::isType('Brayan','string')
        );
		
		$this->assertFalse(
			Validator::isType('1250','notnum')
        );
		
		$this->assertFalse(
			Validator::isType('Sebastian2','notnum')
        );
		
		$this->assertTrue(
			Validator::isType('1250','string')
        );
		
		$this->assertTrue(
			Validator::isType('','string')
        );
		
		$this->assertTrue(
			Validator::isType('   ','string')
        );
		
		$this->assertTrue(
			Validator::isType('32543','numeric')
        );
		
		$this->assertTrue(
			Validator::isType('-32543','numeric')
        );
		
		$this->assertTrue(
			Validator::isType(' 16  ','numeric')
        );
		
		$this->assertTrue(
			Validator::isType(' 16  ','int')
        );
		
		$this->assertFalse(
			Validator::isType('16.3','int')
        );
		
		$this->assertFalse(
			Validator::isType(' 16.3  ','int')
        );
		
		$this->assertTrue(
			Validator::isType('16.3','decimal')
        );
		
		$this->assertTrue(
			Validator::isType('-.023','decimal')
        );
		
		$this->assertTrue(
			Validator::isType('.023','numeric')
        );
		
		$this->assertTrue(
			Validator::isType('192.168.0.27','regex:/^((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?1)){3}\z/')
        );
		
		$this->assertFalse(
			Validator::isType('192.168.0.300','regex:/^((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?1)){3}\z/')
        );
	}
	
	
}
