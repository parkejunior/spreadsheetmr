<?php
require_once __DIR__ . '/../vendor/autoload.php';

use SimpleXLSX;
use SimpleXLS;
use Exception;

/**
 * 
 */
class SpreadSheetMR
{
	private $data;
	private $path;
	private $extension;
	public $headerIndex;
	
	function __construct($path, $extension)
	{
		$this->path = $path;
		$this->extension = strtolower(ltrim($extension, '.'));

		$this->data = $this->readByFormat();
	}

	private function readByFormat()
	{
		switch($this->extension) {
			case 'xlsx':
				if ( $xlsx = SimpleXLSX::parse($this->path) ) {
					return $xlsx->rows();
				} else {
					throw new Exception(SimpleXLSX::parseError());
				}
				break;
			case 'xls':
				if ( $xls = SimpleXLS::parse($this->path) ) {
					return $xls->rows();
				} else {
					throw new Exception(SimpleXLS::parseError());
				}
				break;
			case 'csv':
				$data = array();
				$fileData = fopen($this->path, 'r');

				while ($row = fgetcsv($fileData, 0, ",", '"')) {
				    $data[] = $row;
				}

				return $data;
				break;
			case 'txt':
				# Code for Magic...
				break;
			default:
				throw new Exception("Formato não reconhecido.");
				break;
		}
	}

	public function verifyFile($config) 
	{
		$error = false;

		if(isset($config['first_title'])) {
			if($this->data[0][0] != $config['first_title']) {
				$error = true;
			}
		}

		if(isset($config['last_title'])) {
			if($this->data[0][count($this->data[0])-1] != $config['last_title']) {
				$error = true;
			}
		}

		if(isset($config['total_columns'])) {
			if(count($this->data[0]) != $config['total_columns']) {
				$error = true;
			}
		}

		if($error) throw new Exception("Modelo de importação incorreto, verifique o arquivo!");
	}

	public function ignoreRow($index)
	{
		if(isset($this->data[$index])) unset($this->data[$index]);
	}

	public function ignoreColumn($index)
	{
		foreach ($this->data as $k => $row) {
			if(isset($row[$index])) unset($this->data[$k][$index]);
		}
	}

	public function getObject()
	{
		if(isset($this->headerIndex)) {
			$data = array();
			$header = $this->data[$this->headerIndex];

			foreach ($this->data as $k => $row) {
				// Remove header
				if($k === $this->headerIndex) continue;

				// Combine header assoc with rows
				$data[] = (object) array_combine($header, $row);
			}

			return $data;
		} else {
			return (object) $this->data;
		}
	}
}