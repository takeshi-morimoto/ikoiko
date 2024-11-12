<?php 
/**
* 
*/
class IOExcelCsv
{
	protected $data = [];
	protected $lineFeed = "\r\n";
	
	function __construct()
	{
	}

	public function setLineFeed($value)
	{
		$this->lineFeed = $value;
	}

	public function import($filePath, $offset = 0)
	{
		if ( !file_exists( $filePath ) ){
			throw new Exception("ファイルが存在しません。");
			return false;
		}

		$tmpFile = dirname($filePath) . '/tmp';
		$contents = file_get_contents($filePath);
		$contents = preg_replace("/\r\n|\r|\n/s", $this->lineFeed, $contents);

		file_put_contents($tmpFile, $contents);
		$handle = fopen($tmpFile, 'r');

		$n = 0;
		while ( $row = fgetcsv($handle) ) {

				$n++;
				// 列が空だった場合とオフセットに該当する場合はスキップ
				if ( empty( array_filter($row) ) || $n <= $offset ) {
					continue;
				}

				mb_convert_variables('UTF-8', 'SJIS', $row);
				$this->data[] = $row;
		}
		
		fclose($handle);
		unlink($tmpFile);

		return true;
	}

	public function fetch()
	{
		$res = current($this->data);
		next($this->data);

		return $res;
	}

	public function fetchAll()
	{
		return $this->data;
	}

	public function clear()
	{
		$this->data = [];
	}

	/**
	 * 引数は２次元配列もしくはPDOStatementオブジェクトとします。
	 */
	public function export($filePath, ...$dataList)
	{
		$tmpFile = dirname($filePath) . '/tmp';
		$handle = fopen($tmpFile, 'w');

		foreach ($dataList as $data) {

			// PDOStatemenを２次元配列に変換
			if ($data instanceof PDOStatement ) {

				$newData = [];

				while ( $row = $data->fetch(PDO::FETCH_ASSOC) ) {
					$newData[] = $row;
				}

				$data = $newData;
			}

			foreach ($data as $row) {

				mb_convert_variables('SJIS', 'UTF-8', $row);
				fputcsv($handle, $row);
			}
		}

		fclose($handle);

		// 改行コードのバッファリング
		$contents = file_get_contents($tmpFile);
		$contents = preg_replace("/\r\n|\r|\n/s", $this->lineFeed, $contents);

		file_put_contents($filePath, $contents);
		unlink($tmpFile);
	}
}