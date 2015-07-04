<?php
/**
 * @author: yevgen
 */

namespace Seine\Parser\DOMStyle;


class StylesheetElement
{
    /**
     * @var int
     */
    private $id;

    /**
     * OrderedStyleElement constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}