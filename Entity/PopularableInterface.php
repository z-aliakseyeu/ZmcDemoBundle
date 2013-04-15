<?php

namespace Zmc\DemoBundle\Entity;


/**
 * Popularity Interface for Entities
 *
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
interface PopularableInterface
{

	/**
	 * @return int
	 */
	public function getPopularity();

	/**
	 * @param int $popularity
	 */
	public function setPopularity($popularity);
}