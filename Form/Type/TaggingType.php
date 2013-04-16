<?php

namespace Zmc\DemoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

/**
 *
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */
class TaggingType extends AbstractType
{

	protected $eventSubscriber;

	public function __construct(EventSubscriberInterface $eventSubscriber)
	{
		$this->eventSubscriber = $eventSubscriber;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addEventSubscriber($this->eventSubscriber);
		if (isset($options['popular_limit']) && $options['popular_limit'] > 0) {
			$limit = $options['popular_limit'];
			$builder->add('popular', 'entity', array(
				'class' => $options['class'],
				'query_builder' => function (EntityRepository $er) use ($limit) {
					return $er->createQueryBuilder('o')
								->orderBy('o.popularity', 'DESC')
								->setMaxResults($limit)
							;
				},
				'expanded' => true,
				'multiple' => true,
				'virtual' => true,
				'mapped' => false,
			));

			$builder->add('other', 'entity', array(
				'class' => $options['class'],
				'query_builder' => function (EntityRepository $er) use ($limit) {
					$queryResponse = $er->createQueryBuilder('o')
								->addSelect('count(o.id)')
								->getQuery()->execute();
					$count = 0;
					if (isset($queryResponse[0][1]) && !is_object($queryResponse[0][1])) {
						$count = (int) $queryResponse[0][1];
					} 

					return $er->createQueryBuilder('o')
								->orderBy('o.popularity', 'ASC')
								->setMaxResults($count - $limit)
							;
				},
				'expanded' => false,
				'multiple' => true,
				'virtual' => true,
				'mapped' => false,
			));
		}
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'compound' => true,
	        'expanded' => false,
	        'multiple' => true,
	        'placeholder' => 'select.some.options',
	        'popular_limit' => 0,
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