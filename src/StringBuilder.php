<?php
namespace Cresenity\Laravel;

class StringBuilder
{
    private $text = '';

    private $indent = 0;

    public function __construct($str = '')
    {
        $this->text = $str;
    }

    public static function factory()
    {
        return new StringBuilder();
    }

    /**
     * Set indentation of string.
     *
     * @param int $ind
     *
     * @return $this
     */
    public function setIndent($ind)
    {
        $this->indent = $ind;

        return $this;
    }

    /**
     * Get indentation of string.
     *
     * @return int
     */
    public function getIndent()
    {
        return $this->indent;
    }

    /**
     * Increment the indentation.
     *
     * @param int $n
     *
     * @return $this
     */
    public function incIndent($n = 1)
    {
        $this->indent += $n;

        return $this;
    }

    /**
     * Decrement the indentation.
     *
     * @param int $n
     *
     * @return $this
     */
    public function decIndent($n = 1)
    {
        $this->indent -= $n;

        return $this;
    }

    public function append($str)
    {
        $this->text .= $str;

        return $this;
    }

    public function appendln($str)
    {
        $this->text .= static::indent($this->indent);

        return $this->append($str);
    }

    public function br()
    {
        $this->text .= "\r\n";

        return $this;
    }

    public function text()
    {
        return $this->text;
    }

    public function __toString()
    {
        return $this->text;
    }

    public static function indent($n, $char = "\t")
    {
        return str_repeat($char, $n);
    }
}
