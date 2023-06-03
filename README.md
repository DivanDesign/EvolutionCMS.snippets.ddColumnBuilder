# (MODX)EvolutionCMS.snippets.ddColumnBuilder

Выводит элементы (например: результаты Ditto, ddGetDucuments, ddGetMultipleField и т. п.) в несколько колонок, стараясь равномерно распределить количество.


## Requires

* PHP >= 5.4
* [(MODX)EvolutionCMS](https://github.com/evolution-cms/evolution) >= 1.1
* [(MODX)EvolutionCMS.libraries.ddTools](http://code.divandesign.biz/modx/ddtools) >= 0.60


## Installation

Elements → Snippets: Create a new snippet with the following data:

1. Snippet name: `ddColumnBuilder`.
2. Description: `<b>6.0</b> Выводит элементы (например: результаты Ditto, ddGetDucuments, ddGetMultipleField и т. п.) в несколько колонок, стараясь равномерно распределить количество.`.
3. Category: `Core`.
4. Parse DocBlock: `no`.
5. Snippet code (php): Insert content of the `ddColumnBuilder_snippet` file from the archive.


## Parameters description

* `source_items`
	* Desctription: The source items.
	* Valid values: `string`
	* **Required**
	
* `source_itemsDelimiter`
	* Desctription: The source items delimiter.
	* Valid values: `string`
	* Default value: `'<!--ddColumnBuilder-->'`
	
* `columnsNumber`
	* Desctription: The number of columns to return.
	* Valid values: `integer`
	* Default value: `1`
	
* `minItemsInColumn`
	* Desctription: The minimum number of items in one column.
	* Valid values:
		* `integer`
		* `0` — any
	* Default value: `0`
	
* `orderBy`
	* Desctription: How to sort items?
	* Valid values:
		* `'column'` — first fills up the first column, then second, etc (`[[1, 2, 3], [4, 5, 6], [7, 8, 9]]`)
		* `'row'` — fills up by rows (`[[1, 4, 7], [2, 5, 8], [3, 6, 9]]`)
	* Default value: `'column'`
	
* `tpls_column`
	* Desctription: The template for column rendering.
		
		Available placeholders:
		* `[+items+]` — items.
		* `[+columnNumber+]` — number of column.
		
	* Valid values:
		* `stringChunkName`
		* `string` — use inline templates starting with `@CODE:`
	* Default value: `'@CODE:<div>[+items+]</div>'`
	
* `tpls_columnLast`
	* Desctription: The template for last column rendering.
		
		Available placeholders:
		* `[+items+]` — items.
		* `[+columnNumber+]` — number of column.
		
	* Valid values:
		* `stringChunkName`
		* `string` — use inline templates starting with `@CODE:`
	* Default value: = `tpls_column`
	
* `tpls_outer`
	* Desctription: Wrapper template.
		
		Available placeholders:
		* `[+snippetResult+]` — the snippet result.
		* `[+columnsTotal+]` — the actual number of columns.
		* `[+itemsTotal+]` — the total number of getting `source_items`.
		
	* Valid values:
		* `stringChunkName`
		* `string` — use inline templates starting with `@CODE:`
	* Default value: — (is not used)
	
* `placeholders`
	* Desctription: Additional data has to be passed into the result string. Arrays are supported too: `some[a]=one&some[b]=two` => `[+some.a+]`, `[+some.b+]`; `some[]=one&some[]=two` => `[+some.0+]`, `[some.1]`.
	* Valid values:
		* `stringJsonObject` — as [JSON](https://en.wikipedia.org/wiki/JSON)
		* `stringQueryFormatted` — as [Query string](https://en.wikipedia.org/wiki/Query_string)
	* Default value: —


<link rel="stylesheet" type="text/css" href="https://raw.githack.com/DivanDesign/CSS.ddMarkdown/master/style.min.css" />