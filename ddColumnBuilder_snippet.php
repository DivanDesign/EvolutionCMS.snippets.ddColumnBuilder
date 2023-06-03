<?php
/**
 * ddColumnBuilder
 * @version 6.1 (2023-06-03)
 * 
 * @see README.md
 * 
 * @copyright 2010–2023 Ronef {@link https://Ronef.ru }
 */

//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

return \DDTools\Snippet::runSnippet([
	'name' => 'ddColumnBuilder',
	'params' => $params
]);
?>