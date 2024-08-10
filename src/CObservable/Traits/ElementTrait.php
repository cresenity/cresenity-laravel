<?php

namespace Cresenity\Laravel\CObservable\Traits;

trait ElementTrait
{
    /**
     * Add Div &lt;div&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Div Div Element
     */
    public function addDiv($id = null)
    {
        $element = new CElement_Element_Div($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Label &lt;label&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Label Label Element
     */
    public function addLabel($id = null)
    {
        $element = new CElement_Element_Label($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Anchor Element &lt;a&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_A Anchor Element
     */
    public function addA($id = null)
    {
        $element = new CElement_Element_A($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 1 Element &lt;h1&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_H1 Heading 1 Element
     */
    public function addH1($id = null)
    {
        $element = new CElement_Element_H1($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 2 Element &lt;h2&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_H2 Heading 2 Element
     */
    public function addH2($id = null)
    {
        $element = new CElement_Element_H2($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 3 Element &lt;h3&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_H3 Heading 3 Element
     */
    public function addH3($id = null)
    {
        $element = new CElement_Element_H3($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 4 Element &lt;h4&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_H4 Heading 4 Element
     */
    public function addH4($id = null)
    {
        $element = new CElement_Element_H4($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 5 Element &lt;h5&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_H5 Heading 5 Element
     */
    public function addH5($id = null)
    {
        $element = new CElement_Element_H5($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Heading 6 Element &lt;h6&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_H6 Heading 6 Element
     */
    public function addH6($id = null)
    {
        $element = new CElement_Element_H6($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Button Element &lt;button&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Button Button Element
     */
    public function addButton($id = null)
    {
        $element = new CElement_Element_Button($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Paragraph Element &lt;p&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_P Paragraph Element
     */
    public function addP($id = null)
    {
        $element = new CElement_Element_P($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Ordered List Element &lt;ol&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Ol Ordered List Element
     */
    public function addOl($id = null)
    {
        $element = new CElement_Element_Ol($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Unordered List Element &lt;ul&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Ul Unordered List Element
     */
    public function addUl($id = null)
    {
        $element = new CElement_Element_Ul($id);
        //$element = CUlElement::factory($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Table Row Element &lt;tr&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Tr Table Row Element
     */
    public function addTr($id = null)
    {
        $element = new CElement_Element_Tr($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Table Cell Element &lt;td&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Td Table Cell Element
     */
    public function addTd($id = null)
    {
        $element = new CElement_Element_Td($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Code Element &lt;ul&gt.
     *
     * @param null|string $id optional
     *
     * @return CElement_Element_Code Code Element
     */
    public function addCode($id = null)
    {
        $element = new CElement_Element_Code($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add List Item Element &lt;li&gt.
     *
     * @param null|string $id
     *
     * @return CElement_Element_Li List Item Element
     */
    public function addLi($id = null)
    {
        $element = new CElement_Element_Li($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Iframe Element &lt;iframe&gt.
     *
     * @param null|string $id
     *
     * @return CElement_Element_Iframe Iframe Element
     */
    public function addIframe($id = null)
    {
        $element = new CElement_Element_Iframe($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Canvas Element &lt;canvas&gt.
     *
     * @param null|string $id
     *
     * @return CElement_Element_Canvas Canvas Element
     */
    public function addCanvas($id = null)
    {
        $element = new CElement_Element_Canvas($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Img Element &lt;img&gt.
     *
     * @param null|string $id
     *
     * @return CElement_Element_Img Img Element
     */
    public function addImg($id = null)
    {
        $element = new CElement_Element_Img($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Pre Element &lt;pre&gt.
     *
     * @param null|string $id
     *
     * @return CElement_Element_Pre Pre Element
     */
    public function addPre($id = null)
    {
        $element = new CElement_Element_Pre($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * Add Span Element &lt;span&gt.
     *
     * @param null|string $id
     *
     * @return CElement_Element_Span Span Element
     */
    public function addSpan($id = null)
    {
        $element = new CElement_Element_Span($id);
        $this->wrapper->add($element);

        return $element;
    }
}
