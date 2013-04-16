<?php

namespace Zmc\DemoBundle\Form\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;

class TaggingTypeSubscriber implements EventSubscriberInterface
{

	protected $occupations;

	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::PRE_SET_DATA => 'onPreSetData',
			FormEvents::PRE_BIND => 'onPreBind',
			FormEvents::POST_BIND => 'onPostBind',
		);
	}

	public function onPreSetData(DataEvent $event)
	{
		$data = $event->getData();
		if ($data instanceof \Doctrine\ORM\PersistentCollection) {
			$this->occupations = $data->toArray();
		}
	}

	public function onPreBind(DataEvent $event)
	{
		if (count($event->getData()) > 0) {
			$data = array();
			foreach ($event->getData() as $subfield) {
				if (!is_array($subfield)) {
					return;
				}

				if (isset($subfield['popular'])) {
					$data[] = $subfield['popular'][0];
				} else if (isset($subfield['other'])) {
					$data[] = $subfield['other'][0];
				}

				// var_dump($subfield);
			}
			// var_dump($data);
			// die();
			$event->setData($data);
		}
	}

	public function onPostBind(DataEvent $event)
	{
		// die(var_dump($event->getData()));
	}

	public function getOccupations()
	{
		return $this->occupations;
	}
}