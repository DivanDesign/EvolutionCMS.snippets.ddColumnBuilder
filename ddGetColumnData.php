<?php
/**
 * ddGetColumnData.php
 * @version 3.2.1 (2014-03-13)
 * 
 * @desc Выводит результаты Ditto в несколько колонок, стараясь равномерно распределить количество.
 * 
 * @note Сниппет берёт результаты Ditto из плэйсхолдера, так что перед его вызовом необходимо вызывать Ditto с параметром «save» == 3.
 * 
 * @uses Сниппет Ditto 2.1.
 * 
 * @param $columnsNumber {integer} - Количество колонок. Default: 1.
 * @param $rowsMin {integer} - Минимальное количество строк в одной колонке (0 — любое). Default: 0;
 * @param $orderBy {'column'; 'row'} - Порядок элементов: 'column' - сначала заполняется первая колонка, потом вторая и т.д. ([[1, 2, 3] [4, 5, 6] [7, 8, 9]]); 'row' - элементы располагаются по срокам ([[1, 4, 7] [2, 5, 8] [3, 6, 9]]). Default: 'column'.
 * @param $columnTpl {string: chunkName} - Шаблон колонки. Доступные плэйсхолдеры: [+wrapper+]. @required
 * @param $columnLastTpl {string: chunkName} - Шаблон последней колонки. Доступные плэйсхолдеры: [+wrapper+]. Default: = $columnTpl.
 * @param $outerTpl {string: chunkName} - Шаблон внешней обёртки. Доступные плэйсхолдеры: [+wrapper+]. Default: —.
 * @param $dittoId {integer} - Унакальный ID сессии Ditto. Default: ''.
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

//Количество колонок
$columnsNumber = (isset($columnsNumber) && is_numeric($columnsNumber) && $columnsNumber > 0) ? $columnsNumber : 1;
//Минимальное количество строк
$rowsMin = isset($rowsMin) ? intval($rowsMin) : 0;
//Сортировка между колонками
$orderBy = isset($orderBy) ? $orderBy : 'column';
//Если шаблон последней колонки, не задан — будет как и все
$columnLastTpl = isset($columnLastTpl) ? $columnLastTpl : $columnTpl;
//ID сессии Ditto
$dittoId = isset($dittoId) ? $dittoId.'_' : '';
//Получаем необходимые результаты дитто
$dittoRes = $modx->getPlaceholder($dittoId.'ditto_resource');

//Всего строк
$rowsTotal = count($dittoRes);

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
		foreach ($dittoRes as $key => $val){
			//Запоминаем уже готовые отпаршенные значения в нужную колонку
			$res[$i][] = $modx->getPlaceholder($dittoId.'item['.$key.']');
			
			$i++;
			if ($i == $columnsNumber){$i = 0;}
		}
	//В противном случае по колонкам
	}else{
		$res = array();
	
		//Пробегаемся по результатам
		foreach ($dittoRes as $key => $val){
			//Запоминаем уже готовые отпаршенные значения
			$res[] = $modx->getPlaceholder($dittoId.'item['.$key.']');
		}
		
		//Просто разбиваем массив на части, сохраняя оригинальную нумерацию
		$res = array_chunk($res, $elementsInColumnNumber);
	}
	
	$result = '';
	$i = 0;
	
	//Перебираем колонки
	while ($i < $columnsNumber){
		//Проверим на всякий случай, что значение есть. Вылет бывает, когда указываешь 2 колонки, а Ditto возвращает один элемент (который на 2 колонки не разделить).
		if (isset($res[$i])){
			//Выбираем нужный шаблон
			if ($i == $columnsNumber - 1){
				$tpl = $columnLastTpl;
			}else{
				$tpl = $columnTpl;
			}
			
			//Парсим колонку
			$result .= $modx->parseChunk($tpl, array('wrapper' => implode('', $res[$i])),'[+','+]');
		}
		$i++;
	}
	
	if (isset($outerTpl)){
		$result = $modx->parseChunk($outerTpl, array('wrapper' => $result), '[+', '+]');
	}
	
	return $result;
}
?>