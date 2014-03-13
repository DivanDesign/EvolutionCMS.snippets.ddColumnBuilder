<?php
/**
 * ddGetColumnData.php
 * @version 3.1 (2013-03-22)
 * 
 * @desc Выводит результаты Ditto в несколько колонок, стараясь равномерно распределить количество.
 * 
 * @notes Сниппет берёт результаты Ditto из плэйсхолдера, так что перед его вызовом необходимо вызывать Ditto с параметром «save» == 3.
 * 
 * @uses Сниппет Ditto 2.1.
 * 
 * @param $columnsNumber {integer} - Количество колонок. Default: 1.
 * @param $rowsMin {integer} - Минимальное количество строк в одной колонке (0 — любое). Default: 0;
 * @param $columnTpl {string} - Шаблон колонки. Доступные плэйсхолдеры: [+wrapper+]. @required
 * @param $columnLastTpl {string} - Шаблон последней колонки. Доступные плэйсхолдеры: [+wrapper+]. Default: = $columnTpl.
 * @param $dittoId {integer} - Унакальный ID сессии Ditto. Default: ''.
 * 
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

//Количество колонок
$columnsNumber = isset($columnsNumber) ? $columnsNumber : 1;
//Минимальное количество строк
$rowsMin = isset($rowsMin) ? intval($rowsMin) : 0;
//Если шаблон последней колонки, не задан — будет как и все
$columnLastTpl = isset($columnLastTpl) ? $columnLastTpl : $columnTpl;
//ID сессии Ditto
$dittoId = isset($dittoId) ? $dittoId.'_' : '';
//Получаем необходимые результаты дитто
$dittoRes = $modx->getPlaceholder($dittoId.'ditto_resource');

//Если что-то есть
if (count($dittoRes) > 0){
	$res = array();
	
	//Пробегаемся по результатам
	foreach ($dittoRes as $key => $val){
		//Запоминаем уже готовые отпаршенные значения
		$res[] = $modx->getPlaceholder($dittoId.'item['.$key.']');
	}
	
	//Всего строк
	$rowsTotal = count($res);
	//Если задано минимальное количество строк
	if($rowsMin){
		//Количество столбцов при минимальном количестве строк
		$colsWithRowMin = ceil($rowsTotal / $rowsMin);
	
		//Если это количество меньше заданного
		if($colsWithRowMin < $columnsNumber){
			//Тогда количество столбцов будет меньше заданного (логика)
			$elementsInColumnNumber = $rowsMin;
		}
	}

	$elementsInColumnNumber = isset($elementsInColumnNumber)? $elementsInColumnNumber: ceil($rowsTotal/$columnsNumber);
	//Разбиваем массив на части (размер каждого = количество элементов/количество колонок), сохраняя оригинальную нумерацию
	$res = array_chunk($res, ceil($rowsTotal / $columnsNumber));
	
	$result = '';
	$i = 0;
	$len = count($res);
	
	//Перебираем колонки
	while ($i < $len){
		//Выбираем нужный шаблон
		if ($i == $len - 1){
			$tpl = $columnLastTpl;
		}else{
			$tpl = $columnTpl;
		}
		
		//Парсим колонку
		$result .= $modx->parseChunk($tpl, array('wrapper' => implode('', $res[$i])),'[+','+]');
		$i++;
	}
	
	return $result;
}
?>