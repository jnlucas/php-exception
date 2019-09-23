<?php

use exception\SaldoInsuficienteException;

class ContaCorrente{

	private $titular;

	public  $agencia;

	private $numero;

	private $saldo;

	public static $totalDeContas;

	public static $taxaOperacao;

	public $totalSaquesNaoPermitidos;



	public function __construct($titular,$agencia,$numero,$saldo){

		$this->titular = $titular;
		$this->agencia = $agencia;
		$this->numero = $numero;
		$this->saldo = $saldo;

		self::$totalDeContas ++;


		try{
			if(self::$totalDeContas < 1 ){
				throw new Exception("Valor inferior a zero!");

			}
			self::$taxaOperacao = (30/self::$totalDeContas);

		}catch(Exception $erro){
			echo $erro->getMessage();
			exit;
		}




	}



	public function __get($atributo){

		Validacao::protegeAtributo($atributo);

		return $this->$atributo;

	}


	public function __set($atributo,$valor){

		Validacao::protegeAtributo($atributo);

		$this->$atributo = $valor;

	}

	public function transferir($valor, ContaCorrente $contaCorrente){

		Validacao::verificaNumerico($valor);

		if($valor < 0){
			throw new Exception("o valor não é permitido");
		}

		$this->sacar($valor);

		$contaCorrente->depositar($valor);

		return $this;

	}

	public function getTitular(){
		return $this->titular;
	}

	public function sacar($valor){

		Validacao::verificaNumerico($valor);

		if($valor > $this->saldo){
			throw new SaldoInsuficienteException("saldo insuficiente");

		}

		$this->saldo = $this->saldo - $valor;
		return $this;

	}

	public function depositar($valor){

		Validacao::verificaNumerico($valor);

		$this->saldo = $this->saldo + $valor;
		return $this;

	}



	public function setNumero($numero){
		return $this->numero = $numero;
	}





	private function formataSaldo(){

		return "R$ ".number_format($this->saldo,2,",",".");
	}


	public function getSaldo(){
		return $this->formataSaldo();
	}

	public function __toString(){

		return $this->saldo;
	}


}
