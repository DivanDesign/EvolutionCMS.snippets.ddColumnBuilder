<?php
/**
 * ddGetColumnData.php
 * @version 3.0 (2013-02-21)
 * 
 * @desc Выводит документы в несколько колонок по заданному шаблону.
 * 
 * @uses сниппет Ditto 2.1.
 * 
 * Основные параметры
 * @param $columnsNumber {integer} - Количество колонок. Default: 1.
 * @param $rowsMin {integer} - минимальное количество строк в столбце.
 * @param $columnTpl {string} - Шаблон колонки.
 * @param $columnLastTpl {string} - Шаблон последней колонки. Default: = $columnTpl.
 * @param $rowTpl {string} - Шаболон элемента. Доступные плэйсхолдеры: [+row_number+].
 * @param $hereTpl {string} - Шабон текущего элемента. Default: = $rowTpl.
 * @param $splitter {string} - Разделитель между элементами. Default: '_itemSpl_'.
 * 
 * Параметры Ditto
 * @param $startID {integer} - Папка, откуда берутся документы.
 * @param $sortBy {string} - Поле, по которому сортировать.
 * @param $sortDir {string} - Направление сортировки.
 * @param $showInMenuOnly {0; 1} - Показывать только документы видимые в меню. Default: 0.
 * @param $showPublishedOnly {0; 1} - Показывать только опубликованные документы. Default: 0.
 * @param $dittoId {integer} - Унакальный ID сессии Ditto.
 * @param $paginate {0; 1} - Включает / выключает разбиение по страницам. Default: 0.
 * @param $paginateAlwaysShowLinks {0; 1} - Показывать ли [+next+] и [+previous+] всегда. Default: 0.
 * @param $paginateSplitterCharacter {string} - Разделитель между страницами для постраничной навигации.
 * @param $tplPaginatePrevious {string} - Шаблон ссылки «Предудущая».
 * @param $tplPaginatePreviousOff {string} - Шаблон неактивной ссылки «Предыдущая».
 * @param $tplPaginateNext {string} - Шаблон ссылки «Следующая».
 * @param $tplPaginateNextOff {string} - Шаблон неактивной ссылки «Следующая».
 * @param $tplPaginatePage {string} - Шаблон ссылки на страницу.
 * @param $tplPaginateCurrentPage {string} - Шаблон текущей страницы.
 * @param $summarize {integer} - Количество элементов на странице.
 * 
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

$columnsNumber = isset($columnsNumber) ? $columnsNumber : 1;
//Если шаблон последней колонки, не задан — будет как и все
$columnLastTpl = isset($columnLastTpl) ? $columnLastTpl : $columnTpl;
//Шаблон текущего пункта, не задан — будет как все
$hereTpl = isset($hereTpl) ? $hereTpl : $rowTpl;
//Разделитель между значениями
$splitter = isset($splitter) ? $splitter : '_itemSpl_';
//Минимальное количество строк
$rowsMin = isset($rowsMin) ? intval($rowsMin) : 0;

$res = $modx->runSnippet('Ditto', array(
	'showInMenuOnly' => $showInMenuOnly,
	'showPublishedOnly' => $showPublishedOnly,
	'sortBy' => $sortBy,
	'sortDir' => $sortDir,
	'startID' => $startID,
	'tpl' => str_replace('[+row_number+]', '_rowNumber_', '@CODE:'.$modx->getChunk($rowTpl).$splitter),
	'tplCurrentDocument' => '@CODE:'.$modx->getChunk($hereTpl).$splitter,
	'id' => $dittoId,
	'paginate' => $paginate,
	'paginateAlwaysShowLinks' => $paginateAlwaysShowLinks,
	'paginateSplitterCharacter' => $paginateSplitterCharacter,
	'tplPaginatePrevious' => $tplPaginatePrevious,
	'tplPaginatePreviousOff' => $tplPaginatePreviousOff,
	'tplPaginateNext' => $tplPaginateNext,
	'tplPaginateNextOff' => $tplPaginateNextOff,
	'tplPaginatePage' => $tplPaginatePage,
	'tplPaginateCurrentPage' => $tplPaginateCurrentPage,
	'summarize' => $summarize
));

//Разбиваем результат на массив, фильтруем пустые элементы
$res = array_filter(explode($splitter,$res));

//Если что-то есть
if (count($res) > 0){
	//Перебираем все результаты, парсим номера строк
	foreach ($res as $k => $v){
		$res[$k] = str_replace('_rowNumber_', $k + 1, $v);
	}
	
	//Всего строк
	$rowsTotal = count($res);
	//Если задано минимальное количество строк
	if($rowsMin){
		//Количество столбцов при минимальном количестве строк
		$colsWithRowMin = ceil($rowsTotal/$rowsMin);
		
		//Если это количество меньше заданного
		if($colsWithRowMin < $columnsNumber) {
			//Тогда количество столбцов будет меньше заданного (логика)
			$columnsNumber = $colsWithRowMin;
		}
	}
	
	//Разбиваем массив на части (размер каждого = количество элементов/количество колонок), сохраняя оригинальную нумерацию
	$res = array_chunk($res, ceil($rowsTotal/$columnsNumber));
	
	$result = ''; $i = 0; $len = count($res);
	
	//Перебираем колонки
	while ($i < $len){
		if ($i == $len-1) $tpl = $columnLastTpl;
		else $tpl = $columnTpl;
		$result .= $modx->parseChunk($tpl, array('wrapper' => implode('', $res[$i])),'[+','+]');
		$i++;
	}
	
	return $result;
}
?>