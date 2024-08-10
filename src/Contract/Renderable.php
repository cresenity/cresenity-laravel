<?php
namespace Cresenity\Laravel\Contract;

/**
 * @author Hery Kurniawan
 * @license Ittron Global Teknologi <ittron.co.id>
 *
 * @since Jan 1, 2018, 4:36:28 PM
 */
interface Renderable {
    public function html($indent = 0);

    public function js($indent = 0);
}
