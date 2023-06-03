<?php
/**
 * ddColumnBuilder
 * @version 6.0 (2019-10-02)
 * 
 * @see README.md
 * 
 * @copyright 2010–2019 Ronef {@link https://Ronef.ru }
 */

//Include (MODX)EvolutionCMS.libraries.ddTools
require_once($modx->getConfig('base_path') . 'assets/libs/ddTools/modx.ddtools.class.php');

//The snippet must return an empty string even if result is absent
$snippetResult = '';

$source_itemsDelimiter =
	isset($source_itemsDelimiter) ?
	$source_itemsDelimiter :
	'<!--ddColumnBuilder-->'
;
$source_items =
	isset($source_items) ?
	explode(
		$source_itemsDelimiter,
		$source_items
	) :
	[]
;

//Количество колонок
$columnsNumber =
	(
		isset($columnsNumber) &&
		is_numeric($columnsNumber) &&
		$columnsNumber > 0
	) ?
	$columnsNumber :
	1
;
//Минимальное количество строк
$minItemsInColumn =
	isset($minItemsInColumn) ?
	intval($minItemsInColumn) :
	0
;
//Сортировка между колонками
$orderBy =
	isset($orderBy) ?
	$orderBy :
	'column'
;

$tpls_column =
	isset($tpls_column) ?
	\ddTools::getTpl($tpls_column) :
	'<div>[+items+]</div>'
;
//Если шаблон последней колонки, не задан — будет как и все
$tpls_columnLast =
	isset($tpls_columnLast) ?
	\ddTools::getTpl($tpls_columnLast) :
	$tpls_column
;

//Всего строк
$itemsTotal = count($source_items);

//Если что-то есть
if ($itemsTotal > 0){
	//Количество элементов в колонке (общее количество элементов / количество колонок) 
	$itemsNumberInColumn = ceil($itemsTotal / $columnsNumber);
	
	//Если задано минимальное количество строк в колонке
	if ($minItemsInColumn){
		//Количество колонок при минимальном количестве строк
		$columnsNumberWithMinRows = ceil($itemsTotal / $minItemsInColumn);
		
		//Если это количество меньше заданного
		if ($columnsNumberWithMinRows < $columnsNumber){
			//Тогда элементов в колонке будет меньше заданного (логика)
			$itemsNumberInColumn = $minItemsInColumn;
			//И колонок тоже
			$columnsNumber = $columnsNumberWithMinRows;
		}
	}
	
	//Если сортировка по строкам
	if ($orderBy == 'row'){
		$resultArray = array_fill(
			0,
			$columnsNumber,
			[]
		);
		
		$i = 0;
		
		//Пробегаемся по результатам
		foreach (
			$source_items as
			$val
		){
			//Запоминаем уже готовые отпаршенные значения в нужную колонку
			$resultArray[$i][] = $val;
			
			$i++;
			if ($i == $columnsNumber){$i = 0;}
		}
	//В противном случае по колонкам
	}else{
		$resultArray = [];
		
		//Проходка по кол-ву колонок-1
		for (
			$i = 1;
			$i < $columnsNumber;
			$i++
		){ 
			//Заполняем колонку нужным кол-вом
			$resultArray[] = array_splice(
				$source_items,
				0,
				$itemsNumberInColumn
			);
			
			//Пересчет кол-ва в колонке для оставшегося кол-ва элементов и колонок
			$itemsNumberInColumn = ceil(count($source_items) / ($columnsNumber - $i));
		}
		
		if (count($source_items) > 0){
			//Последняя колонка с остатком
			$resultArray[] = $source_items;
		}
	}
	
	$i = 0;
	
	//Проверим на всякий случай. Вылет бывает, когда указываешь 2 колонки, а Ditto возвращает один элемент (который на 2 колонки не разделить).
	if ($columnsNumber > count($resultArray)){
		$columnsNumber = count($resultArray);
	}
	
	//Перебираем колонки
	while ($i < $columnsNumber){
		//Выбираем нужный шаблон (если колонка последняя, но не единственная)
		if (
			$columnsNumber > 1 &&
			$i == $columnsNumber - 1
		){
			$columnTpl = $tpls_columnLast;
		}else{
			$columnTpl = $tpls_column;
		}
		
		//Парсим колонку
		$snippetResult .= ddTools::parseText([
			'text' => $columnTpl,
			'data' => [
				'items' => implode(
					'',
					$resultArray[$i]
				),
				//Порядковый номер колонки
				'columnNumber' => $i + 1
			]
		]);
		
		$i++;
	}
	
	if (isset($tpls_outer)){
		$snippetResult = ddTools::parseText([
			'text' => \ddTools::getTpl($tpls_outer),
			'data' => [
				'snippetResult' => $snippetResult,
				'columnsTotal' => $columnsNumber,
				'itemsTotal' => $itemsTotal
			]
		]);
	}
	
	//Если переданы дополнительные данные
	if (isset($placeholders)){
		//Парсим
		$snippetResult = ddTools::parseText([
			'text' => $snippetResult,
			'data' => ddTools::encodedStringToArray($placeholders)
		]);
	}
}

return $snippetResult;
?>