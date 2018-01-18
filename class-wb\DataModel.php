<?php
# Boz-MW - Another MediaWiki API handler in PHP
# Copyright (C) 2017 Valerio Bozzolan
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

# Wikibase
namespace wb;

class DataModel {

	private $labels;
	private $descriptions;
	private $claims;

	public function __construct() {
		$this->labels       = new Labels();
		$this->descriptions = new Descriptions();
		$this->claims       = new Claims();
	}

	public function getLabels() {
		return $this->labels->getAll();
	}

	public function getDescriptions() {
		return $this->descriptions->getAll();
	}

	public function getClaims() {
		return $this->claims->getAll();
	}

	public function addClaim( $claim ) {
		$this->claims->add( $claim );
		return $this;
	}

	public function setClaims( $claims ) {
		$this->claims->set( $claims );
		return $this;
	}

	public function hasClaimsInProperty( $property ) {
		return $this->claims->haveProperty( $property );
	}

	public function getClaimsInProperty( $property ) {
		return $this->claims->getInProperty( $property );
	}

	public function countClaims() {
		return $this->claims->count();
	}

	public function hasLabelsInLanguage( $language ) {
		return $this->labels->have( $language );
	}

	/**
	 * Set, delete, preserve if it exists, a label.
	 */
	public function setLabel( $label ) {
		$this->labels->set( $label );
		return $this;
	}

	public function hasDescriptionsInLanguage( $language ) {
		return $this->descriptions->have( $language );
	}

	/**
	 * Set, delete, preserve if it exists, a description.
	 */
	public function setDescription( $description ) {
		$this->descriptions->set( $description );
		return $this;
	}

	public function get( $clear = false ) {
		$data = [
			'labels'       => $this->getLabels(),
			'descriptions' => $this->getDescriptions(),
			'claims'       => $this->getClaims()
		];
		foreach( $data as $k => $v ) {
			if( 0 === count( $v ) ) {
				unset( $data[ $k ] );
			}
		}
		if( $clear ) {
			$data['clear'] = true;
		}
		return $data;
	}

	public function getJSON( $args = null ) {
		return json_encode( $this->get(), $args );
	}

	public function getJSONClearing( $args = null ) {
		return json_encode( $this->get( true ), $args );
	}

	public static function createFromData( $data ) {
		$dataModel = new self();
		if( ! empty( $data['labels'] ) ) {
			foreach( $data['labels'] as $label ) {
				$dataModel->setLabel( Label::createFromData( $label ) );
			}
		}
		if( ! empty( $data['descriptions'] ) ) {
			foreach( $data['descriptions'] as $description ) {
				$dataModel->setDescription( Description::createFromData( $description ) );
			}
		}
		if( ! empty( $data['claims'] ) ) {
			foreach( $data['claims'] as $claims ) {
				foreach( $claims as $claim ) {
					$dataModel->addClaim( Claim::createFromData( $claim ) );
				}
			}
		}
		return $dataModel;
	}

	public static function createFromObject( $object ) {
		return self::createFromData( self::object2array( $object ) );
	}

	private static function object2array( $object ) {
		if( ! is_object( $object) && ! is_array( $object ) ) {
			return $object;
		}
		$array = [];
		foreach( $object as $k => $v ) {
			$array[ $k ] = self::object2array( $v );
		}
		return $array;
	}
}
