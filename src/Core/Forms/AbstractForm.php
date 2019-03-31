<?php

namespace Project\Core\Forms;

use Phalcon\Security;

/**
 * Class AbstractForm
 * @package Project\Core\Forms
 * @property Security $security
 */
abstract class AbstractForm extends \Phalcon\Forms\Form
{
    /**
     * @var string|null
     */
    protected $title = null;

    /**
     * @var string
     */
    protected $submitText = "Submit";

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSubmitText(): string
    {
        return $this->submitText;
    }
}
