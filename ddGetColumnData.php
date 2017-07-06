<?php
/**
 * ddGetColumnData
 * @version 4.1 (2016-10-16)
 * 
 * @desc Выводит элементы (например, результаты Ditto) в несколько колонок, стараясь равномерно распределить количество.
 * 
 * @note Сниппет берёт результаты Ditto (2.1) из плэйсхолдера, так что перед его вызовом необходимо вызывать Ditto с параметром «save» == 3.
 * 
 * @uses PHP >= 5.4.
 * @uses MODXEvo >= 1.1.
 * @uses MODXEvo.library.ddTools >= 0.15.4.
 * 
 * @param $source_items {string} — Source items. @required
 * @param $source_itemsDelimiter {string} — Source items delimiter. Default: '<!--ddGetColumnData-->'.
 * @param $columnsNumber {integer} — Количество колонок. Default: 1.
 * @param $minItemsInColumn {integer} — Минимальное количество элементов в одной колонке (0 — любое). Default: 0.
 * @param $orderItemsBy {'column'|'row'} — Порядок элементов: 'column' — сначала заполняется первая колонка, потом вторая и т.д. ([[1, 2, 3], [4, 5, 6], [7, 8, 9]]); 'row' — элементы располагаются по срокам ([[1, 4, 7], [2, 5, 8], [3, 6, 9]]). Default: 'column'.
 * @param $tpls_column {string_chunkName|string} — Шаблон колонки (чанк или строка, начинающаяся с «@CODE:»). Доступные плэйсхолдеры: [+items+], [+columnNumber+] (порядковый номер колонки). @required
 * @param $tpls_columnLast {string_chunkName|string} — Шаблон последней колонки (чанк или строка, начинающаяся с «@CODE:»). Доступные плэйсхолдеры: [+items+]. Default: = $tpls_column.
 * @param $tpls_outer {string_chunkName|string} — Шаблон внешней обёртки (чанк или строка, начинающаяся с «@CODE:»). Доступные плэйсхолдеры: [+result+] (непосредственно результат), [+columnsNumber+] (фактическое количество колонок). Default: '@CODE:[+result+]'.
 * @param $placeholders {string_queryString} — Additional data as query string {@link https://en.wikipedia.org/wiki/Query_string } has to be passed into the result string. E. g. “pladeholder1=value1&pagetitle=My awesome pagetitle!”. Arrays are supported too: “some[a]=one&some[b]=two” => “[+some.a+]”, “[+some.b+]”; “some[]=one&some[]=two” => “[+some.0+]”, “[some.1]”. Default: ''.
 * 
 * @copyright 2010–2016 DivanDesign {@link http://www.DivanDesign.biz }
 */

//Include MODXEvo.library.ddTools
require_once $modx->getConfig('base_path').'assets/libs/ddTools/modx.ddtools.class.php';

$source_itemsDelimiter = isset($source_itemsDelimiter) ? $source_itemsDelimiter : '<!--ddGetColumnData-->';
$source_items = isset($source_items) ? explode($source_itemsDelimiter, $source_items) : [];

//Количество колонок
$columnsNumber = (isset($columnsNumber) && is_numeric($columnsNumber) && $columnsNumber > 0) ? $columnsNumber : 1;
//Минимальное количество строк
$minItemsInColumn = isset($minItemsInColumn) ? intval($minItemsInColumn) : 0;
//Сортировка между колонками
$orderBy = isset($orderBy) ? $orderBy : 'column';
//Если шаблон последней колонки, не задан — будет как и все
$tpls_columnLast = isset($tpls_columnLast) ? $tpls_columnLast : $tpls_column;

//Всего строк
$itemsTotal = count($source_items);

//Если что-то есть
if ($itemsTotal > 0){
	//Количество элементов в колонке (общее количество элементов / количество колонок) 
	$elementsInColumnNumber = ceil($itemsTotal / $columnsNumber);
	
	//Если задано минимальное количество строк в колонке
	if ($minItemsInColumn){
		//Количество колонок при минимальном количестве строк
		$colsWithRowMin = ceil($itemsTotal / $minItemsInColumn);
		
		//Если это количество меньше заданного
		if ($colsWithRowMin < $columnsNumber){
			//Тогда элементов в колонке будет меньше заданного (логика)
			$elementsInColumnNumber = $minItemsInColumn;
			//И колонок тоже
			$columnsNumber = $colsWithRowMin;
		}
	}
	
	//Если сортировка по строкам
	if ($orderBy == 'row'){
		$res = array_fill(0, $columnsNumber, []);
		
		$i = 0;
		
		//Пробегаемся по результатам
		foreach ($source_items as $val){
			//Запоминаем уже готовые отпаршенные значения в нужную колонку
			$res[$i][] = $val;
			
			$i++;
			if ($i == $columnsNumber){$i = 0;}
		}
	//В противном случае по колонкам
	}else{
		$res = [];
		
		//Проходка по кол-ву колонок-1
		for ($i = 1; $i < $columnsNumber; $i++){ 
			//Заполняем колонку нужным кол-вом
			$res[] = array_splice($source_items, 0, $elementsInColumnNumber);
			//Пересчет кол-ва в колонке для оставшегося кол-ва элементов и колонок
			$elementsInColumnNumber = ceil(count($source_items) / ($columnsNumber - $i));
		}
		
		//Последняя колонка с остатком
		$res[] = $source_items;
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
			$tpl = $tpls_columnLast;
		}else{
			$tpl = $tpls_column;
		}
		
		//Парсим колонку
		$result .= ddTools::parseText($modx->getTpl($tpl), [
			'items' => implode('', $res[$i]),
			//Порядковый номер колонки
			'columnNumber' => $i + 1
		]);
		$i++;
	}
	
	if (isset($tpls_outer)){
		$result = ddTools::parseText($modx->getTpl($tpls_outer), [
			'result' => $result,
			'columnsNumber' => $columnsNumber
		]);
	}
	
	//Если переданы дополнительные данные
	if (isset($placeholders)){
		//Parse a query string
		parse_str($placeholders, $placeholders);
		//Корректно инициализируем при необходимости
		if (is_array($placeholders)){
			//Unfold for arrays support (e. g. “some[a]=one&some[b]=two” => “[+some.a+]”, “[+some.b+]”; “some[]=one&some[]=two” => “[+some.0+]”, “[some.1]”)
			$placeholders = ddTools::unfoldArray($placeholders);
			
			//Парсим
			$result = ddTools::parseText($result, $placeholders);
		}
	}
	
	return $result;
}
?>