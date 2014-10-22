<?php
/**
 * Paginator.
 * 
 * Before using this element, remember to load the MePaginator helper provided by MeTools.
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
 * @copyright	Copyright (c) 2014, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 * @package		MeTools\View\Elements
 */
?>

<?php if($this->Paginator->hasPage(NULL, 2)): ?>
	<div class="text-center">
		<div class="hidden-xs">
			<ul class="pagination">
				<?php
					echo $this->Paginator->prev(sprintf('« %s', __d('me_tools', 'Previous')));
					echo $this->Paginator->numbers();
					echo $this->Paginator->next(sprintf('%s »', __d('me_tools', 'Next')));
				?>
			</ul>
		</div>
		<div class="visible-xs">
			<ul class="pagination">
				<?php
					echo $this->Paginator->prev('«');
					echo $this->Paginator->numbers(array('modulus' => '6'));
					echo $this->Paginator->next('»');
				?>
			</ul>
		</div>
	</div>
<?php endif; ?>