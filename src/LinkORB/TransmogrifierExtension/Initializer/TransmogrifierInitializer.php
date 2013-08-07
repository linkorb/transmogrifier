<?php

namespace LinkORB\TransmogrifierExtension\Initializer;

use Behat\Behat\Context\Initializer\InitializerInterface;
use Behat\Behat\Context\ContextInterface;
use LinkORB\TransmogrifierExtension\TransMogrifierContext;

/**
 * Transmogrifier contexts initializer.
 * Sets parameters to the Transmogrifier contexts.
 *
 * @author Joost Faassen <j.faassen@linkorb.com>
 */
class TransmogrifierInitializer implements InitializerInterface
{
    private $dbconf_dir;
    private $dataset_dir;

    /**
     * Initializes initializer.
     *
     */
    public function __construct($dataset_dir, $dbconf_dir)
    {
        $this->dataset_dir = $dataset_dir;
        $this->dbconf_dir = $dbconf_dir;
    }

    /**
     * Checks if initializer supports provided context.
     *
     * @param ContextInterface $context
     *
     * @return Boolean
     */
    public function supports(ContextInterface $context)
    {
        // if context/subcontext is an instance of TransMogrifierContext
        if ($context instanceof TransMogrifierContext) {
            return true;
        }

        return false;
    }

    /**
     * Initializes provided context.
     *
     * @param ContextInterface $context
     */
    public function initialize(ContextInterface $context)
    {
        $context->setDatasetDir($this->dataset_dir);
        $context->setDbConfDir($this->dbconf_dir);
    }
}
