<?php

defined('SYSPATH') or die('No direct access allowed.');

interface CAjax_Engine_SelectSearch_ProcessorInterface {
    public function __construct(CAjax_Engine $engine);

    public function process();
}
