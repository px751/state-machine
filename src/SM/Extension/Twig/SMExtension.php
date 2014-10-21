<?php

/*
 * This file is part of the StateMachine package.
 *
 * (c) Alexandre Bacco
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SM\Extension\Twig;

use SM\Factory\FactoryInterface;

class SMExtension extends \Twig_Extension
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @{inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'sm_can' => new \Twig_Function_Method($this, 'can'),
            'sm_is' => new \Twig_Function_Method($this, 'is'),
            'sm_texts' => new \Twig_Function_Method($this, 'texts'),
        );
    }

    /**
     * @param object $object
     * @param string $transition
     * @param string $graph
     *
     * @return bool
     */
    public function can($object, $transition, $graph = 'default')
    {
        return $this->factory->get($object, $graph)->can($transition);
        
    }

    public function is($object, $state, $graph = 'default')
    {
        $objectSM = $this->factory->get($echangeur, $graph);

        return ($object->getState() == $state);
    }

    /**
     * Recupere le tabeau de configutaion des textes
     * 
     * @param  Echangeur $echangeur Echangeur en cours
     * @return array                Textes
     */
    public function texts($echangeur, $graph = 'default')
    {
        $echangeurMoi = $echangeur;
        $echange = $echangeur->getEchange();
        $echangeurAutre = $echange->getEchangeurAutre($echangeur->getClient());

        $echangeurSM = $this->factory->get($echangeur, $graph);

        $texts = $echangeurSM->getTexts();

        if ($echange->isPostal())
        {
            foreach ($texts as $text)
            {

                if ($text['states']['me'] == $echangeurMoi->getState()
                    && $echangeurAutre && $text['states']['other'] == $echangeurAutre->getState()
                    && isset($text['postal']) && $text['postal'] != 0)
                {
                    return $text;
                }
            }
        }

        foreach ($texts as $text)
        {
            if ($text['states']['me'] == $echangeurMoi->getState()
                && $echangeurAutre && $text['states']['other'] == $echangeurAutre->getState())
            {
                return $text;
            }
        }

        return NULL;
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'sm';
    }
}
