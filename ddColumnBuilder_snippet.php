<?php
/**
 * ddColumnBuilder
 * @version 6.0 (2019-10-02)
 * 
 * @see README.md
 * 
 * @copyright 2010–2019 Ronef {@link https://Ronef.ru }
 */

//# Include
//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);


//# Prepare params
$params = \DDTools\ObjectTools::extend([
	'objects' => [
		(object) [
			'source_items' => [],
			'source_itemsDelimiter' => '<!--ddColumnBuilder-->',
			
			'columnsNumber' => 1,
			'minItemsInColumn' => 0,
			'orderBy' => 'column',
			
			'tpls_column' => '@CODE:<div>[+items+]</div>',
			'tpls_columnLast' => null,
			'tpls_outer' => '',
			'placeholders' => null,
		],
		$params
	]
]);

if(!is_array($params->source_items)){
	$params->source_items = explode(
		$params->source_itemsDelimiter,
		$params->source_items
	);
}

//Integers
foreach (
	[
		'columnsNumber',
		'minItemsInColumn',
	] as
	$paramName
){
	$params->{$paramName} = intval($params->{$paramName});
}

if ($params->columnsNumber <= 0){
	$params->columnsNumber = 1;
}

if (is_null($params->tpls_columnLast)){
	$params->tpls_columnLast = $params->tpls_column;
}

//Templates
foreach (
	[
		'tpls_column',
		'tpls_columnLast',
		'tpls_outer',
	] as
	$paramName
){
	$params->{$paramName} = \ddTools::getTpl($params->{$paramName});
}


//# Run

//The snippet must return an empty string even if result is absent
$snippetResult = '';

//Всего строк
$itemsTotal = count($params->source_items);

//Если что-то есть
if ($itemsTotal > 0){
	//Количество элементов в колонке (общее количество элементов / количество колонок) 
	$itemsNumberInColumn = ceil($itemsTotal / $params->columnsNumber);
	
	//Если задано минимальное количество строк в колонке
	if ($params->minItemsInColumn){
		//Количество колонок при минимальном количестве строк
		$columnsNumberWithMinRows = ceil($itemsTotal / $params->minItemsInColumn);
		
		//Если это количество меньше заданного
		if ($columnsNumberWithMinRows < $params->columnsNumber){
			//Тогда элементов в колонке будет меньше заданного (логика)
			$itemsNumberInColumn = $params->minItemsInColumn;
			//И колонок тоже
			$params->columnsNumber = $columnsNumberWithMinRows;
		}
	}
	
	//Если сортировка по строкам
	if ($params->orderBy == 'row'){
		$resultArray = array_fill(
			0,
			$params->columnsNumber,
			[]
		);
		
		$i = 0;
		
		//Пробегаемся по результатам
		foreach (
			$params->source_items as
			$val
		){
			//Запоминаем уже готовые отпаршенные значения в нужную колонку
			$resultArray[$i][] = $val;
			
			$i++;
			
			if ($i == $params->columnsNumber){
				$i = 0;
			}
		}
	//В противном случае по колонкам
	}else{
		$resultArray = [];
		
		//Проходка по кол-ву колонок-1
		for (
			$i = 1;
			$i < $params->columnsNumber;
			$i++
		){ 
			//Заполняем колонку нужным кол-вом
			$resultArray[] = array_splice(
				$params->source_items,
				0,
				$itemsNumberInColumn
			);
			
			//Пересчет кол-ва в колонке для оставшегося кол-ва элементов и колонок
			$itemsNumberInColumn = ceil(
				count($params->source_items) /
				($params->columnsNumber - $i)
			);
		}
		
		if (count($params->source_items) > 0){
			//Последняя колонка с остатком
			$resultArray[] = $params->source_items;
		}
	}
	
	$i = 0;
	
	//Проверим на всякий случай. Вылет бывает, когда указываешь 2 колонки, а Ditto возвращает один элемент (который на 2 колонки не разделить).
	if ($params->columnsNumber > count($resultArray)){
		$params->columnsNumber = count($resultArray);
	}
	
	//Перебираем колонки
	while ($i < $params->columnsNumber){
		//Выбираем нужный шаблон (если колонка последняя, но не единственная)
		if (
			$params->columnsNumber > 1 &&
			$i == $params->columnsNumber - 1
		){
			$columnTpl = $params->tpls_columnLast;
		}else{
			$columnTpl = $params->tpls_column;
		}
		
		//Парсим колонку
		$snippetResult .= \ddTools::parseText([
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
	
	if (!empty($params->tpls_outer)){
		$snippetResult = \ddTools::parseText([
			'text' => $params->tpls_outer,
			'data' => [
				'snippetResult' => $snippetResult,
				'columnsTotal' => $params->columnsNumber,
				'itemsTotal' => $itemsTotal
			]
		]);
	}
	
	//Если переданы дополнительные данные
	if (!empty($params->placeholders)){
		//Парсим
		$snippetResult = \ddTools::parseText([
			'text' => $snippetResult,
			'data' => $params->placeholders
		]);
	}
}

return $snippetResult;
?>