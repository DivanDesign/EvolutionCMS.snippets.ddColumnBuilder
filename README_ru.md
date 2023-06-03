# (MODX)EvolutionCMS.snippets.ddColumnBuilder

Выводит элементы (например, результаты ddGetDucuments, ddGetMultipleField, Ditto и т. п.) в несколько колонок, стараясь равномерно распределить количество.


## Использует

* PHP >= 5.4
* [(MODX)EvolutionCMS](https://github.com/evolution-cms/evolution) >= 1.1
* [(MODX)EvolutionCMS.libraries.ddTools](http://code.divandesign.ru/modx/ddtools) >= 0.60


## Установка


### Используя [(MODX)EvolutionCMS.libraries.ddInstaller](https://github.com/DivanDesign/EvolutionCMS.libraries.ddInstaller)

Просто вызовите следующий код в своих исходинках или модуле [Console](https://github.com/vanchelo/MODX-Evolution-Ajax-Console):

```php
//Подключение (MODX)EvolutionCMS.libraries.ddInstaller
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddInstaller/require.php'
);

//Установка (MODX)EvolutionCMS.snippets.ddColumnBuilder
\DDInstaller::install([
	'url' => 'https://github.com/DivanDesign/EvolutionCMS.snippets.ddColumnBuilder',
	'type' => 'snippet'
]);
```

* Если `ddColumnBuilder` отсутствует на вашем сайте, `ddInstaller` просто установит его.
* Если `ddColumnBuilder` уже есть на вашем сайте, `ddInstaller` проверит его версию и обновит, если нужно. 


### Вручную


#### 1. Элементы → Сниппеты: Создайте новый сниппет со следующими параметрами

1. Название сниппета: `ddColumnBuilder`.
2. Описание: `<b>6.1</b> Выводит элементы (например, результаты ddGetDucuments, ddGetMultipleField, Ditto и т. п.) в несколько колонок, стараясь равномерно распределить количество.`.
3. Категория: `Core`.
4. Анализировать DocBlock: `no`.
5. Код сниппета (php): Вставьте содержимое файла `ddColumnBuilder_snippet.php` из архива.


#### 2. Элементы → Управление файлами

1. Создайте новую папку `assets/snippets/ddColumnBuilder/`.
2. Извлеките содержимое архива в неё (кроме файла `ddColumnBuilder_snippet.php`).


## Описание параметров

* `source_items`
	* Описание: Исходные элементы.
	* Допустимые значения:
		* `string`
		* `array`
	* **Обязателен**
	
* `source_itemsDelimiter`
	* Описание: Разделитель элементов (используется только если `source_items` — строка).
	* Допустимые значения: `string`
	* Значение по умолчанию: `'<!--ddColumnBuilder-->'`
	
* `columnsNumber`
	* Описание: Количество возвращаемых колонок.
	* Допустимые значения: `integer`
	* Значение по умолчанию: `1`
	
* `minItemsInColumn`
	* Описание: Минимальное количество элементов в одной колонке
	* Допустимые значения:
		* `integer`
		* `0` — любое
	* Значение по умолчанию: `0`
	
* `orderBy`
	* Описание: Как сортировать элементы?
	* Допустимые значения:
		* `'column'` — сначала заполняется первая колонка, затем вторая и так далее (`[[1, 2, 3], [4, 5, 6], [7, 8, 9]]`)
		* `'row'` — заполняются по строкам (`[[1, 4, 7], [2, 5, 8], [3, 6, 9]]`)
	* Значение по умолчанию: `'column'`
	
* `tpls_column`
	* Описание: Шаблон вывода колонки.  
		Доступные плейсхолдеры:
		* `[+items+]` — элементы
		* `[+columnNumber+]` — номер колонки
	* Допустимые значения:
		* `stringChunkName`
		* `string` — передавать код напрямую без чанка можно начиная значение с `@CODE:`
	* Значение по умолчанию: `'@CODE:<div>[+items+]</div>'`
	
* `tpls_columnLast`
	* Описание: Шаблон вывода последней колонки.  
		Доступные плейсхолдеры:
		* `[+items+]` — элементы
		* `[+columnNumber+]` — номер колонки
	* Допустимые значения:
		* `stringChunkName`
		* `string` — передавать код напрямую без чанка можно начиная значение с `@CODE:`
	* Значение по умолчанию: = `tpls_column`
	
* `tpls_outer`
	* Описание: Шаблон обёртки.  
		Available placeholders:
		* `[+snippetResult+]` — результат сниппета
		* `[+columnsTotal+]` — фактическое количество столбцов
		* `[+itemsTotal+]` — общее количество полученных `source_items`
	* Допустимые значения:
		* `stringChunkName`
		* `string` — передавать код напрямую без чанка можно начиная значение с `@CODE:`
	* Значение по умолчанию: — (не используется)
	
* `placeholders`
	* Описание:
		Дополнительные данные, которые будут переданы результируюшую строку.  
		Вложенные объекты и массивы также поддерживаются:
		* `{"someOne": "1", "someTwo": "test" }` => `[+someOne+], [+someTwo+]`.
		* `{"some": {"a": "one", "b": "two"} }` => `[+some.a+]`, `[+some.b+]`.
		* `{"some": ["one", "two"] }` => `[+some.0+]`, `[+some.1+]`.
	* Допустимые значения:
		* `stringJsonObject` — в виде [JSON](https://ru.wikipedia.org/wiki/JSON)
		* `stringHjsonObject` — в виде [HJSON](https://hjson.github.io/)
		* `stringQueryFormatted` — в виде [Query string](https://en.wikipedia.org/wiki/Query_string)
		* Также может быть задан, как нативный PHP объект или массив (например, для вызовов через `\DDTools\Snippet::runSnippet`).
			* `arrayAssociative`
			* `object`
	* Значение по умолчанию: —


## Примеры


### Запустить сниппет через `\DDTools\Snippet::runSnippet` без DB и eval

```php
//Подключение (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//Запуск (MODX)EvolutionCMS.snippets.ddColumnBuilder
\DDTools\Snippet::runSnippet([
	'name' => 'ddColumnBuilder',
	'params' => [
		'source_items' => [
			'Item 1',
			'Item 2',
			'Item 3',
		],
		'columnsNumber' => 2
	]
]);
```


## Links

* [Telegram chat](https://t.me/dd_code)
* [Packagist](https://packagist.org/packages/dd/evolutioncms-snippets-ddcolumnbuilder)
* [GitHub](https://github.com/DivanDesign/EvolutionCMS.snippets.ddColumnBuilder)


<link rel="stylesheet" type="text/css" href="https://raw.githack.com/DivanDesign/CSS.ddMarkdown/master/style.min.css" />