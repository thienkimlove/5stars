
<?php 

echo '<script type="text/javascript">var baseUrl = "'.$this->base.'";</script>';

/**
 * Include compiled less
 */
//echo $this->Html->css($this->layout.'/'.$this->request->controller.'/'.$this->request->action);

/**
 * Include global.js, jQuery and angulajs, jQuery UI
*/
echo $this->Html->script('lib/jquery-1.9.1.min');
echo $this->Html->script('lib/jquery.cookie');
echo $this->Html->script('lib/angular.min');
echo $this->Html->script('lib/jquery.autosize-min');
echo $this->Html->script('lib/jquery-ui-1.10.2.custom.min');
echo $this->Html->css('lib/jquery-ui/smoothness/jquery-ui-1.10.1.custom.min');
echo $this->Html->script('global');

/**
 * Include layout specific js
*/
if(is_file(APP.WEBROOT_DIR.DS.'js'.DS.'layout-'.$this->layout.'.js')) {
	echo $this->Html->script('layout-'.$this->layout);
}

/**
 * Incudes controller specific js
 */
if (is_file(APP.WEBROOT_DIR.DS.'js'.DS.$this->params['controller'].'.js')){
	echo $this->Html->script($this->params['controller']);
}

/**
 * Incudes action specific js
 */
if (is_file(APP.WEBROOT_DIR.DS.'js'.DS.$this->params['controller'].DS.$this->params['action'].'.js')){
	echo $this->Html->script($this->params['controller'].'/'.$this->params['action']);
}

/**
 * Incudes controller specific js and css libs
 */

?>