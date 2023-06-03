<?php
namespace ddColumnBuilder;

class Snippet extends \DDTools\Snippet {
	protected
		$version = '6.1.0',
		
		$params = [
			//Defaults
			'source_items' => [],
			'source_itemsDelimiter' => '<!--ddColumnBuilder-->',
			
			'columnsNumber' => 1,
			'minItemsInColumn' => 0,
			'orderBy' => 'column',
			
			'tpls_column' => '@CODE:<div>[+items+]</div>',
			'tpls_columnLast' => null,
			'tpls_outer' => '',
			'placeholders' => [],
		],
		
		$paramsTypes = [
			'columnsNumber' => 'integer',
			'minItemsInColumn' => 'integer',
		]
	;
	
	/**
	 * prepareParams
	 * @version 1.0 (2023-06-03)
	 * 
	 * @param $params {stdClass|arrayAssociative|stringJsonObject|stringHjsonObject|stringQueryFormatted}
	 * 
	 * @return {void}
	 */
	protected function prepareParams($params = []){
		//Call base method
		parent::prepareParams($params);
		
		if(!is_array($this->params->source_items)){
			$this->params->source_items = explode(
				$this->params->source_itemsDelimiter,
				$this->params->source_items
			);
		}
		
		if ($this->params->columnsNumber <= 0){
			$this->params->columnsNumber = 1;
		}
		
		if (is_null($this->params->tpls_columnLast)){
			$this->params->tpls_columnLast = $this->params->tpls_column;
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
			$this->params->{$paramName} = \ddTools::getTpl($this->params->{$paramName});
		}
	}
	
	/**
	 * run
	 * @version 1.0 (2023-06-03)
	 * 
	 * @return {string}
	 */
	public function run(){
		$result = '';
		
		//Всего строк
		$itemsTotal = count($this->params->source_items);
		
		//Если что-то есть
		if ($itemsTotal > 0){
			//Количество элементов в колонке (общее количество элементов / количество колонок)
			$itemsNumberInColumn = ceil($itemsTotal / $this->params->columnsNumber);
			
			//Если задано минимальное количество строк в колонке
			if ($this->params->minItemsInColumn){
				//Количество колонок при минимальном количестве строк
				$columnsNumberWithMinRows = ceil($itemsTotal / $this->params->minItemsInColumn);
				
				//Если это количество меньше заданного
				if ($columnsNumberWithMinRows < $this->params->columnsNumber){
					//Тогда элементов в колонке будет меньше заданного (логика)
					$itemsNumberInColumn = $this->params->minItemsInColumn;
					//И колонок тоже
					$this->params->columnsNumber = $columnsNumberWithMinRows;
				}
			}
			
			//Если сортировка по строкам
			if ($this->params->orderBy == 'row'){
				$resultArray = array_fill(
					0,
					$this->params->columnsNumber,
					[]
				);
				
				$i = 0;
				
				//Пробегаемся по результатам
				foreach (
					$this->params->source_items as
					$val
				){
					//Запоминаем уже готовые отпаршенные значения в нужную колонку
					$resultArray[$i][] = $val;
					
					$i++;
					
					if ($i == $this->params->columnsNumber){
						$i = 0;
					}
				}
			//В противном случае по колонкам
			}else{
				$resultArray = [];
				
				//Проходка по кол-ву колонок-1
				for (
					$i = 1;
					$i < $this->params->columnsNumber;
					$i++
				){
					//Заполняем колонку нужным кол-вом
					$resultArray[] = array_splice(
						$this->params->source_items,
						0,
						$itemsNumberInColumn
					);
					
					//Пересчет кол-ва в колонке для оставшегося кол-ва элементов и колонок
					$itemsNumberInColumn = ceil(
						count($this->params->source_items) /
						($this->params->columnsNumber - $i)
					);
				}
				
				if (count($this->params->source_items) > 0){
					//Последняя колонка с остатком
					$resultArray[] = $this->params->source_items;
				}
			}
			
			$i = 0;
			
			//Проверим на всякий случай. Вылет бывает, когда указываешь 2 колонки, а Ditto возвращает один элемент (который на 2 колонки не разделить).
			if ($this->params->columnsNumber > count($resultArray)){
				$this->params->columnsNumber = count($resultArray);
			}
			
			//Перебираем колонки
			while ($i < $this->params->columnsNumber){
				//Выбираем нужный шаблон (если колонка последняя, но не единственная)
				if (
					$this->params->columnsNumber > 1 &&
					$i == $this->params->columnsNumber - 1
				){
					$columnTpl = $this->params->tpls_columnLast;
				}else{
					$columnTpl = $this->params->tpls_column;
				}
				
				//Парсим колонку
				$result .= \ddTools::parseText([
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
			
			if (!empty($this->params->tpls_outer)){
				$result = \ddTools::parseText([
					'text' => $this->params->tpls_outer,
					'data' => [
						'snippetResult' => $result,
						'columnsTotal' => $this->params->columnsNumber,
						'itemsTotal' => $itemsTotal
					]
				]);
			}
			
			//Если переданы дополнительные данные
			if (!empty($this->params->placeholders)){
				//Парсим
				$result = \ddTools::parseText([
					'text' => $result,
					'data' => $this->params->placeholders
				]);
			}
		}
		
		return $result;
	}
}