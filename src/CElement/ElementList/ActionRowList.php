<?php

namespace Cresenity\Laravel\CElement\ElementList;

use Cresenity\Laravel\CElement\Component\ActionRow;

class ActionRowList extends ActionList
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public static function factory($id = null)
    {
        /** @phpstan-ignore-next-line */
        return new static($id);
    }

    protected function applyStyleToChild()
    {
        $this->apply('style', $this->style, [ActionRow::class]);
    }

    /**
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Component\ActionRow
     */
    public function addAction($id = null)
    {
        $act = new ActionRow($id);
        $this->wrapper->add($act);

        return $act;
    }
}
