# ddColumnBuilder changelog
## Version 5.0 (2017-07-06)
* \* Snippet is no longer works with MODX placeholders, instead just pass items glued by “source_itemsDelimiter” through the “source_items” parameter.
* \+ Added JSON format support for the “placeholders” parameter.
* \- The “dittoId” parameter was removed.
* \* The following parameters were renamed:
	* \* “source” → “source_items”.
	* \* “sourceDelimiter” → “source_itemsDelimiter”.
	* \* “rowsMin” → “minItemsInColumn”.
	* \* “orderBy” → “orderItemsBy”.
	* \* “columnTpl” → “tpls_column”.
	* \* “columnLastTpl” → “tpls_columnLast”.
	* \* “outerTpl” → “tpls_outer”.
* \* The “[+rows+]” placeholder was renamed as “[+items+]”.
* \* “tpls_column” is no longer required and by default is equal to “<div>[+items+]</div>”.
* \* Small optimization, refactoring and minor changes.
* \* Attention! MODXEvo.library.ddTools >= 0.20 is required.
* \* Attention! Backward compatibility is broken.

## Version 4.1 (2016-10-16)
* \+ The “[+columnNumber+]” placeholder is avaliable now in “columnTpl”.
* \+ Added an ability to pass additional data into the “outerTpl” template (see the “placeholders” parameter).
* \+ Added support of the “@CODE:” keyword prefix in the snippet templates.
* \* Short array syntax is used because it's more convenient.
* \* Attention! PHP >= 5.4 is required.
* \* Attention! MODXEvo >= 1.1 is required.

## Version 4.0 (2015-05-21)
* \* Если колонка последняя, но при этом единственная — используется шаблон «$columnTpl».
* \* Плэйсхолдер «[+wrapper+]» в шаблоне «$outerTpl» переименован в «[+result+]».
* \* Плэйсхолдер «[+wrapper+]» в шаблонах «$columnTpl» и «$columnLastTpl» переименован в «[+rows+]».

## Version 3.4 (2014-07-03)
* \+ Добавлена возможность использовать произвольный источники данных (см. параметр «source»).
* \* Исправлен алгоритм распределения по колонкам при сортировке по колонкам. При небольшом количестве элементов, не кратном количеству колонок, могло получиться меньше колонок, чем задано.
* \* Переносы строк в Unix стиле.

## Version 3.3 (2014-03-13)
* \+ Добавлена возможность возвращать результат сниппета в чанк (см. параметр «$outerTpl»).
* \+ При выводе в чанк «$outerTpl» добавлен плэйсхолдер «[+columnsNumber+]» содержащий фактическое количество колонок.

## Version 3.2.1 (2014-03-13)
* \* Исправлена ошибка при обращении к несуществующему элементу массива (когда количество элементов меньше, чем количество колонок).

## Version 3.2 (2013-07-11)
* \+ Добавлен параметр «$orderBy», позволяющий задать сортировку элементов по строкам (слева направо → сверху вниз).
* \* Исправлена ошибка с «$rowsMin» (переменная там не использовалась, получалось, что параметр бесполезен).
* \* Рефакторинг.

## Version 3.1 (2013-03-22)
* \* Сниппет теперь сам не запускает Ditto, а получает его результаты из плэйсхолдера. Ditto надо запускать с параметром save=\`3\` (чтобы результаты сохранялись в плэйсхолдер) перед вызовом ddGetColumnData.
* \- Удалены все параметры, связанные с Ditto, кроме «dittoId» (он нам пригодится).
* \- Также за ненадобностью удалены параметры «rowTpl», «hereTpl» и «splitter».

## Version 3.0 (2013-02-21)
* \* Сниппет переименован в «ddGetColumnData».
* \* Переименованы параметры:
	* \* «countColoumns» → «columnsNumber».
	* \* «coloumnRowTpl» → «columnTpl».
	* \* «coloumnLastTpl» → «columnLastTpl».
* \+ Добавлен параметр «rowsMin» — минимальное количество строк в полностью заполненном столбце.

<style>ul{list-style:none;}</style>