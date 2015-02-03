<?php
/**
 * Error 500.
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
 * @copyright	Copyright (c) 2015, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 * @package		MeTools\View\Errors
 */
?>
	
<?php
	if(!$this->get('title_for_layout'))
		$this->set('title_for_layout', __d('me_tools', 'Error'));
?>

<div class="errors view">
	<h2><?php echo $message; ?></h2>
	<p>
		<strong><?php echo __d('me_tools', 'Error'); ?>: </strong>
		<?php echo __d('me_tools', 'An internal error has occurred'); ?>
	</p>
	<?php
		if(Configure::read('debug'))
			echo $this->element('exception_stack_trace');
	?>
</div>