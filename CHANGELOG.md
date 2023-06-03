# (MODX)EvolutionCMS.snippets.ddColumnBuilder changelog


## Version 6.0 (2019-10-02)
* \* Parameters → `tpls_outer` → Placeholders:
	* \+ `[+itemsTotal+]`: The new placeholder.
	* \* The following have been renamed:
		* \* `[+columnsNumber+]` → `[+columnsTotal+]`.
		* \* `[+result+]` → `[+snippetResult+]`.
* \* Fixed bug with an empty last column.
* \* Attention! Backward compatibility is broken.


## Version 5.0 (2017-07-06)
* \* Snippet is no longer works with MODX placeholders, instead just pass items glued by `source_itemsDelimiter` through the `source_items` parameter.
* \+ Parameters → `placeholders`: Added JSON format support.
* \- Parameters → `dittoId`: The parameter has been deleted.
* \* Parameters: The following have been renamed:
	* \* `source` → `source_items`.
	* \* `sourceDelimiter` → `source_itemsDelimiter`.
	* \* `rowsMin` → `minItemsInColumn`.
	* \* `columnTpl` → `tpls_column`.
	* \* `columnLastTpl` → `tpls_columnLast`.
	* \* `outerTpl` → `tpls_outer`.
* \+ Parameters → `tpls_column` is no longer required and by default is equal to `<div>[+items+]</div>`.
* \* The `[+rows+]` placeholder was renamed as `[+items+]`.
* \* Attention! Backward compatibility is broken.
* \* Attention! (MODX)EvolutionCMS.librararies.ddTools >= 0.20 is required.


## Version 4.1 (2016-10-16)
* \+ Parameters → `columnTpl` → Placeholders → `[+columnNumber+]`: The new placeholder.
* \+ Parameters → `placeholders`: The new parameter. Provides an ability to pass additional data into the `outerTpl` template.
* \+ Added support of the `@CODE:` keyword prefix in the snippet templates.
* \* Short array syntax is used because it's more convenient.
* \* Attention! PHP >= 5.4 is required.
* \* Attention! (MODX)EvolutionCMS >= 1.1 is required.


## Version 4.0 (2015-05-21)
* \* If the column is the last but only one, the `columnTpl` template is used.
* \* Parameters → `outerTpl` → Placeholders → `[+result+]`: Has been renamed from `[+wrapper+]`.
* \* Parameters → `columnTpl`, `columnLastTpl` → Placeholders → `[+rows+]`: Has been renamed from `[+wrapper+]`.


## Version 3.4 (2014-07-03)
* \+ Added ability to use custom data sources (see the `source` parameter).
* \* Fixed the column distribution algorithm when sorting by columns. A small number of items that is not a multiple of the number of columns could result in fewer columns than specified.
* \* Unix-style line breaks.


## Version 3.3 (2014-03-13)
* \+ Parameters → `outerTpl`:
	* \+ The new parameter. Allows output results through chunk.
	* \+ Placeholders → `[+columnsNumber+]`: The new placeholder. Contains the actual number of columns.


## Version 3.2.1 (2014-03-13)
* \* Fixed an error when accessing a non-existent array element (when the number of elements is less than the number of columns).


## Version 3.2 (2013-07-11)
* \+ Parameters → `orderBy`: The new parameter. Allows to sort items additionally by rows (from left to right, then from top to bottom).
* \* Fixed a bug with `rowsMin` (the variable was not used there, it turned out that the parameter was useless).


## Version 3.1 (2013-03-22)
* \* The snippet no longer runs Ditto, instead it gets Ditto results from spedial placeholder. Ditto should be run with save=`3` (to save the results to a placeholder) before ddGetColumnData call.
* \- Removed all Ditto-related parameters except `dittoId` (we need it).
* \- The `rowTpl`, `hereTpl` and `splitter` parameters have also been removed as unnecessary.


## Version 3.0 (2013-02-21)
* \* The snippets has been renamed as `ddGetColumnData`.
* \* Parameters → The following have been renamed:
	* \* `countColoumns` → `columnsNumber`.
	* \* `coloumnRowTpl` → `columnTpl`.
	* \* `coloumnLastTpl` → `columnLastTpl`.
* \+ Parameters → `rowsMin`: The new parameter. The minimum number of items in one column.


<link rel="stylesheet" type="text/css" href="https://raw.githack.com/DivanDesign/CSS.ddMarkdown/master/style.min.css" />
<style>ul{list-style:none;}</style>