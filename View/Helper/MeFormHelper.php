<?php
App::uses('MeToolsAppHelper', 'MeTools.View/Helper');

/**
 * Provide extended functionalities for forms.
 *
 * This file is part of MeTools.
 *
 * MeTools is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * MeTools is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with MeTools.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author		Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright	Copyright (c) 2013, Mirko Pagliai for Nova Atlantis Ltd
 * @license		AGPL License (http://www.gnu.org/licenses/agpl.txt)
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 * @package		MeTools.View.Helper
 */
class MeFormHelper extends MeToolsAppHelper {
	/**
	 * Helpers used
	 * @var array Helpers name
	 */
	public $helpers = array('Form', 'Html');

	/**
	 * Create a form. Rewrite <i>$this->Form->create()</i>
	 *
	 * Look at {@link http://api.cakephp.org/2.0/class-FormHelper.html#_create CakePHP Api}
	 * @param string $model The model object which the form is being defined for
	 * @param array $options An array of html attributes and options
	 * @return string A formatted opening FORM tag
	 */
	public function create($model=null, $options=array()) {
		return $this->Form->create($model, $options);
	}

	/**
	 * Create a (simple) button. Rewrite <i>$this->Form->button()</i>
	 *
	 * Look at {@link http://api.cakephp.org/2.0/class-FormHelper.html#_submit CakePHP Api}
	 * @param string $caption The label appearing on the button or an image
	 * @param array $options Array of options
	 * @return string Html
	 */
	public function button($caption, $options=array()) {
		//"type" option default "button"
		$options['type'] = empty($options['type']) ? 'button' : $options['type'];

		//Add the 'btn' class
		$options['class'] = empty($options['class']) ? 'btn' : $this->_cleanAttribute($options['class'].' btn');

		//Add bootstrap icon to the caption, if there's the 'icon' option
		$caption = !empty($options['icon']) ? '<i class="'.$this->_cleanAttribute($options['icon']).'"></i> '.$caption : $caption;
		unset($options['icon']);

		//Add the 'tooltip' rel
		$options['rel'] = empty($options['rel']) ? 'tooltip' : $this->_cleanAttribute($options['rel'].' tooltip');

		return $this->Form->button($caption, $options);
	}

	/**
	 * Close a form. Rewrite <i>$this->Form->end()</i> and use the <i>button()</i> method
	 *
	 * If $options is set, a submit button will be created. Options can be either a string or an array
	 *
	 * Look at {@link http://api.cakephp.org/2.0/class-FormHelper.html#_end CakePHP Api}
	 * @param mixed $options A string or an array for the submit button
	 * @return string a closing FORM tag, optional with a submit button
	 */
	public function end($options=null) {
		$submit = null;

		if(!empty($options)) {
			//If passed a string, the string will be the caption
			if(is_string($options))
				$submit = $this->submit($options);
			//Elseif, if passed the "label" option, this option will be the caption
			elseif(!empty($options['label'])) {
				$submit = $this->submit($options['label'], $options);
				unset($options['label']);
			}
		}

		return $submit.$this->Form->end();
	}

	/**
	 * Create a submit button. Rewrite <i>$this->Form->Submit()</i> and use the <i>button()</i> method
	 *
	 * Look at {@link http://api.cakephp.org/2.0/class-FormHelper.html#_submit CakePHP Api}
	 * @param string $caption The label appearing on the submit button or an image
	 * @param array $options Array of options
	 * @return string Html
	 */
	public function submit($caption, $options=array()) {
		//Static options for submit buttons
		$options['icon'] = 'icon-ok icon-white';
		$options['type'] = 'submit';

		//Add the 'btn' and 'btn-success' classes
		$options['class'] = empty($options['class']) ? 'btn btn-success' : $this->_cleanAttribute($options['class'].' btn btn-success');

		return $this->Html->tag('div', $this->button($caption, $options), array('class' => 'submit'));
	}
}