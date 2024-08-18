<?php
namespace Cresenity\Laravel\CElement\Component;

use Cresenity\Laravel\CElement\Component;
use Cresenity\Laravel\CElement\Traits\Property\HeightPropertyTrait;
use Cresenity\Laravel\CElement\Traits\Property\WidthPropertyTrait;

class PdfViewer extends Component
{
    use WidthPropertyTrait,
        HeightPropertyTrait;
    protected $pdfUrl;

    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'iframe';
        $this->width = '100%';
        $this->height = '500px';
    }

    public function build()
    {
        $url = curl::base() . 'cresenity/pdf?file=' . $this->pdfUrl;
        $this->setAttr('src', $url);

        $this->setAttr('width', $this->width);

        $this->setAttr('height', $this->height);
    }

    public function setPdfUrl($url)
    {
        $this->pdfUrl = $url;

        return $this;
    }
}
