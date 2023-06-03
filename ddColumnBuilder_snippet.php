<?php
/**
 * ddColumnBuilder
 * @version 6.0 (2019-10-02)
 * 
 * @see README.md
 * 
 * @copyright 2010–2019 Ronef {@link https://Ronef.ru }
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