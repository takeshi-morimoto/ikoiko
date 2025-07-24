<?php 

/**
* コンストラクタはPDOオブジェクトとターゲットのテーブル名を引数に取ります。
*/
class MysqlBulkUpdate
{
	protected $DBCon;
	protected $tableName;
	protected $cols 		= []; 		// 変更するカラム
	protected $locked 		= []; 		// 変更しないよう保護するカラムリスト
	protected $primaryKey 	= 'id'; 	// 挿入か更新の判断に使用します。
	protected $blank 		= '!'; 		// 挿入か更新の判断に使用します。
	
	function __construct($PDO, $tableName)
	{
		$this->DBCon = $PDO;
		$this->tableName = $tableName;

		// 主キーを取得
		$res = $PDO->query('SHOW INDEXES FROM events where Key_name = "PRIMARY";')->fetch(PDO::FETCH_ASSOC);
		$this->primaryKey = $res['Column_name'];
	}

	public function setTable($tableName = '')
	{
		$this->tableName = $tableName;
	}

	public function setBlank($char = '!')
	{
		$this->blank = $char;
	}

	public function setLockedCol($cols = [])
	{
		$this->locked = $cols;
	}

	public function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;
	}

	/**
	 * インポートするデータは２次元配列です。
	 * カラム名を格納した配列を先頭に置くか、もしくは第3引数に配列を別で渡します。
	 * 第2引数には各レコードを処理する際にフックする関数を渡せます。
	 */
	public function update($data, $hook = null, $headerColList = null )
	{
		$header 		= [];
		$primaryKey 	= $this->primaryKey;
		$table 			= $this->tableName;

		// ヘッダーが別で渡された場合
		if ( is_array($headerColList) ) {
			$header = $headerColList;

		} else {
			$header = $data[0];
			unset($data[0]);
		}

		// importするデータを１件ずつ処理
		foreach ($data as $row) {

			$record = array_combine($header, $row);

			// フックする関数の実行
			if( !is_null($hook) ){ 
				$hook($record); 
			}

			// フラグの処理
			$flag = ( isset($record['flag']) ) ? $record['flag'] : '';
			unset($record['flag']);

			// 主キーが空なら新規レコードとして追加
			if ( empty($record[$primaryKey]) || $flag === 'i' ) {

				$target 	 = '';
				$placeHolder = '';

				foreach ($record as $colName => $val) {
					$target 		.= " {$colName},";
					$placeHolder 	.= " :{$colName},";
				}
				$target 		= rtrim($target, ',');
				$placeHolder 	= rtrim($placeHolder, ',');

				$sql = "insert into {$table} ({$target}) values ({$placeHolder});";
				$insert = $this->DBCon->prepare($sql);
				$insert->execute($record);

			// dフラグなら削除の処理
			} elseif ( $flag === 'd' ) {

				$sql = "delete from {$table} where {$primaryKey} = :{$primaryKey};";
				$insert = $this->DBCon->prepare($sql);
				$param = [ $primaryKey => $record[$primaryKey] ];
				$insert->execute($param);

			// 主キーが指定されていればupdateの処理
			} else {

				$target = '';
				foreach ($record as $colName => $val) {

					// 主キーならターゲットに加えない
					if ( $colName === $this->primaryKey ){
						continue;
					}

					// 「保護されたカラム」「値が空」のどれかに該当すれば無視して要素を削除
					if ( in_array($colName, $this->locked) || empty($val) ){
						unset($record[$colName]);
						continue;
					}

					// 空白を表す文字列だった場合は空文字列に変換
					if ( $val === $this->blank ){
						$record[$colName] = '';
					}

					$target .= " {$colName} = :{$colName},";
				}

				$target = rtrim($target, ',');
				$sql = "update {$table} set {$target} where {$primaryKey} = :{$primaryKey};";

				$update = $this->DBCon->prepare($sql);
				$update->execute($record);

			}
		}

	} // method end
} //class end