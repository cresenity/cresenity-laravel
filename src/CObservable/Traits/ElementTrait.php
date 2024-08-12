<?php

namespace Cresenity\Laravel\CObservable\Traits;

use Cresenity\Laravel\CElement\Element\A;
use Cresenity\Laravel\CElement\Element\Button;
use Cresenity\Laravel\CElement\Element\Canvas;
use Cresenity\Laravel\CElement\Element\Code;
use Cresenity\Laravel\CElement\Element\Div;
use Cresenity\Laravel\CElement\Element\H1;
use Cresenity\Laravel\CElement\Element\H2;
use Cresenity\Laravel\CElement\Element\H3;
use Cresenity\Laravel\CElement\Element\H4;
use Cresenity\Laravel\CElement\Element\H5;
use Cresenity\Laravel\CElement\Element\H6;
use Cresenity\Laravel\CElement\Element\Iframe;
use Cresenity\Laravel\CElement\Element\Img;
use Cresenity\Laravel\CElement\Element\Label;
use Cresenity\Laravel\CElement\Element\Li;
use Cresenity\Laravel\CElement\Element\Ol;
use Cresenity\Laravel\CElement\Element\P;
use Cresenity\Laravel\CElement\Element\Pre;
use Cresenity\Laravel\CElement\Element\Span;
use Cresenity\Laravel\CElement\Element\Td;
use Cresenity\Laravel\CElement\Element\Tr;
use Cresenity\Laravel\CElement\Element\Ul;

trait ElementTrait
{
    /**
     * Add Div &lt;div&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Div Div Element
     */
    public function addDiv($id = null)
    {
        $element = new Div($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Label &lt;label&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Label Label Element
     */
    public function addLabel($id = null)
    {
        $element = new Label($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Anchor Element &lt;a&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\A Anchor Element
     */
    public function addA($id = null)
    {
        $element = new A($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 1 Element &lt;h1&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\H1 Heading 1 Element
     */
    public function addH1($id = null)
    {
        $element = new H1($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 2 Element &lt;h2&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\H2 Heading 2 Element
     */
    public function addH2($id = null)
    {
        $element = new H2($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 3 Element &lt;h3&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\H3 Heading 3 Element
     */
    public function addH3($id = null)
    {
        $element = new H3($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 4 Element &lt;h4&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\H4 Heading 4 Element
     */
    public function addH4($id = null)
    {
        $element = new H4($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 5 Element &lt;h5&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\H5 Heading 5 Element
     */
    public function addH5($id = null)
    {
        $element = new H5($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 6 Element &lt;h6&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\H6 Heading 6 Element
     */
    public function addH6($id = null)
    {
        $element = new H6($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Button Element &lt;button&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Button Button Element
     */
    public function addButton($id = null)
    {
        $element = new Button($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Paragraph Element &lt;p&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\P Paragraph Element
     */
    public function addP($id = null)
    {
        $element = new P($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Ordered List Element &lt;ol&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Ol Ordered List Element
     */
    public function addOl($id = null)
    {
        $element = new Ol($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Unordered List Element &lt;ul&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Ul Unordered List Element
     */
    public function addUl($id = null)
    {
        $element = new Ul($id);
        //$element = CUlElement::factory($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Table Row Element &lt;tr&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Tr Table Row Element
     */
    public function addTr($id = null)
    {
        $element = new Tr($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Table Cell Element &lt;td&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Td Table Cell Element
     */
    public function addTd($id = null)
    {
        $element = new Td($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Code Element &lt;ul&gt.
     *
     * @param null|string $id optional
     *
     * @return \Cresenity\Laravel\CElement\Element\Code Code Element
     */
    public function addCode($id = null)
    {
        $element = new Code($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add List Item Element &lt;li&gt.
     *
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\Li List Item Element
     */
    public function addLi($id = null)
    {
        $element = new Li($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Iframe Element &lt;iframe&gt.
     *
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\Iframe Iframe Element
     */
    public function addIframe($id = null)
    {
        $element = new Iframe($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Canvas Element &lt;canvas&gt.
     *
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\Canvas Canvas Element
     */
    public function addCanvas($id = null)
    {
        $element = new Canvas($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Img Element &lt;img&gt.
     *
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\Img Img Element
     */
    public function addImg($id = null)
    {
        $element = new Img($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Pre Element &lt;pre&gt.
     *
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\Pre Pre Element
     */
    public function addPre($id = null)
    {
        $element = new Pre($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Span Element &lt;span&gt.
     *
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\Span Span Element
     */
    public function addSpan($id = null)
    {
        $element = new Span($id);
        $this->wrapper->add($element);

        return $element;
    }
}
