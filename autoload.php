<?php
# Boz-MW - Another MediaWiki API handler in PHP
# Copyright (C) 2018, 2019, 2020, 2021 Valerio Bozzolan
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

/**
 * Minimal autoload (without functions)
 *
 * This is useful if you like creating frameworks.
 *
 * If you love creating bots instead, see the 'autoload-with-laser-cannon.php'.
 */

/**
 * Autoload classes on-demand
 */
spl_autoload_register( function( $name ) {
	$path = __DIR__ . "/include/class-$name.php";
	if( is_file( $path ) ) {
		require $path;
	}
} );
