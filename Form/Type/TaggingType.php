<?php

namespace Zmc\DemoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class TaggingType extends AbstractType
{

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
	        'expanded' => false,
	        'multiple' => true,
	        'placeholder' => 'select.some.options',
	        'popular_limit' => 1,
	    ));
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['placeholder'] = $options['placeholder'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent()
	{
		return 'entity';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'tagging';
	}
}