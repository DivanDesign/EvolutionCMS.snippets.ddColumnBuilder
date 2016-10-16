<?php
/**
 * ddGetColumnData.php
 * @version 4.0 (2015-05-21)
 * 
 * @desc Выводит элементы (например, результаты Ditto) в несколько колонок, стараясь равномерно распределить количество.
 * 
 * @note Сниппет берёт результаты Ditto (2.1) из плэйсхолдера, так что перед его вызовом необходимо вызывать Ditto с параметром «save» == 3.
 * 
 * @uses The library modx.ddTools 0.15.4.
 * 
 * @param $columnsNumber {integer} — Количество колонок. Default: 1.
 * @param $rowsMin {integer} — Минимальное количество строк в одной колонке (0 — любое). Default: 0.
 * @param $orderBy {'column'|'row'} — Порядок элементов: 'column' — сначала заполняется первая колонка, потом вторая и т.д. ([[1, 2, 3], [4, 5, 6], [7, 8, 9]]); 'row' — элементы располагаются по срокам ([[1, 4, 7], [2, 5, 8], [3, 6, 9]]). Default: 'column'.
 * @param $columnTpl {string_chunkName} — Шаблон колонки. Доступные плэйсхолдеры: [+rows+], [+columnNumber+] (порядковый номер колонки). @required
 * @param $columnLastTpl {string_chunkName} — Шаблон последней колонки. Доступные плэйсхолдеры: [+rows+]. Default: = $columnTpl.
 * @param $outerTpl {string_chunkName} — Шаблон внешней обёртки. Доступные плэйсхолдеры: [+result+] (непосредственно результат), [+columnsNumber+] (фактическое количество колонок). Default: —.
 * @param $placeholders {separated string} — Additional data for parsed result chunk. Format: separated string with '::' for pair key-value and '||' between pairs. Default: ''.
 * @param $source {string|'ditto'} — Плэйсходлер (элемент массива «$modx->placeholders»), содержащий одномерный массив со строками исходных данных. Default: 'ditto'.
 * @param $dittoId {integer} — Уникальный ID сессии Ditto. Default: ''.
 * 
 * @copyright 2010–2016 DivanDesign {@link http://www.DivanDesign.biz }
 */

//Количество колонок
$columnsNumber = (isset($columnsNumber) && is_numeric($columnsNumber) && $columnsNumber > 0) ? $columnsNumber : 1;
//Минимальное количество строк
$rowsMin = isset($rowsMin) ? intval($rowsMin) : 0;
//Сортировка между колонками
$orderBy = isset($orderBy) ? $orderBy : 'column';
//Если шаблон последней колонки, не задан — будет как и все
$columnLastTpl = isset($columnLastTpl) ? $columnLastTpl : $columnTpl;
//Источник
$source = isset($source) ? $source : 'ditto';

$rowsTotal = 0;

if (strtolower($source) == 'ditto'){
	//ID сессии Ditto
	$dittoId = isset($dittoId) ? $dittoId.'_' : '';
	
	//Получаем необходимые результаты дитто
	if ($dittoRes = $modx->getPlaceholder($dittoId.'ditto_resource')){
		$source = array();
		
		foreach ($dittoRes as $key => $val){
			$source[] = $modx->getPlaceholder($dittoId.'item['.$key.']');
		}
	}
}else{
	$source = $modx->getPlaceholder($source);
	
	if (!is_array($source)){
		$source = array();
	}
}

//Всего строк
$rowsTotal = count($source);

//Если что-то есть
if ($rowsTotal > 0){
	//Количество элементов в колонке (общее количество элементов / количество колонок) 
	$elementsInColumnNumber = ceil($rowsTotal / $columnsNumber);
	
	//Если задано минимальное количество строк в колонке
	if ($rowsMin){
		//Количество колонок при минимальном количестве строк
		$colsWithRowMin = ceil($rowsTotal / $rowsMin);
		
		//Если это количество меньше заданного
		if ($colsWithRowMin < $columnsNumber){
			//Тогда элементов в колонке будет меньше заданного (логика)
			$elementsInColumnNumber = $rowsMin;
			//И колонок тоже
			$columnsNumber = $colsWithRowMin;
		}
	}
	
	//Если сортировка по строкам
	if ($orderBy == 'row'){
		$res = array_fill(0, $columnsNumber, array());
		
		$i = 0;
		
		//Пробегаемся по результатам
		foreach ($source as $val){
			//Запоминаем уже готовые отпаршенные значения в нужную колонку
			$res[$i][] = $val;
			
			$i++;
			if ($i == $columnsNumber){$i = 0;}
		}
	//В противном случае по колонкам
	}else{
		$res = array();
		
		//Проходка по кол-ву колонок-1
		for ($i = 1; $i < $columnsNumber; $i++){ 
			//Заполняем колонку нужным кол-вом
			$res[] = array_splice($source, 0, $elementsInColumnNumber);
			//Пересчет кол-ва в колонке для оставшегося кол-ва элементов и колонок
			$elementsInColumnNumber = ceil(count($source) / ($columnsNumber - $i));
		}
		
		//Последняя колонка с остатком
		$res[] = $source;
	}
	
	$result = '';
	$i = 0;
	
	//Проверим на всякий случай. Вылет бывает, когда указываешь 2 колонки, а Ditto возвращает один элемент (который на 2 колонки не разделить).
	if ($columnsNumber > count($res)){
		$columnsNumber = count($res);
	}
	
	//Перебираем колонки
	while ($i < $columnsNumber){
		//Выбираем нужный шаблон (если колонка последняя, но не единственная)
		if ($columnsNumber > 1 && $i == $columnsNumber - 1){
			$tpl = $columnLastTpl;
		}else{
			$tpl = $columnTpl;
		}
		
		//Парсим колонку
		$result .= $modx->parseChunk($tpl, array(
			'rows' => implode('', $res[$i]),
			//Порядковый номер колонки
			'columnNumber' => $i + 1
		),'[+','+]');
		$i++;
	}
	
	if (isset($outerTpl)){
		$result = $modx->parseChunk($outerTpl, array(
			'result' => $result,
			'columnsNumber' => $columnsNumber
		), '[+', '+]');
	}
	
	//Если переданы дополнительные данные
	if (isset($placeholders)){
		//Подключаем modx.ddTools
		require_once $modx->getConfig('base_path').'assets/libs/ddTools/modx.ddtools.class.php';
		//Разбиваем их
		$placeholders = ddTools::explodeAssoc($placeholders);
		//Парсим
		$result = ddTools::parseText($result, $placeholders);
	}
	
	return $result;
}
?>