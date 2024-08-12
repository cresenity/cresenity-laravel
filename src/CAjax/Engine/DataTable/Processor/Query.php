<?php

defined('SYSPATH') or die('No direct access allowed.');

class CAjax_Engine_DataTable_Processor_Query extends CAjax_Engine_DataTable_Processor {
    use CAjax_Engine_DataTable_Trait_ProcessorTrait;
    use CAjax_Engine_DataTable_Trait_ProcessorQueryTrait;

    public function process() {
        $db = $this->db();

        $request = $this->input;

        $qProcess = $this->getFullQuery($withPagination = true);

        $resultQ = $db->query($qProcess);

        $data = $resultQ->result(false);
        $output = [
            'sEcho' => intval(carr::get($request, 'sEcho')),
            'iTotalRecords' => $this->getTotalRecord(),
            'iTotalDisplayRecords' => $this->getTotalFilteredRecord(),
            'aaData' => $this->populateAAData($data, $this->table(), $request, $js),
        ];

        $data = [
            'datatable' => $output,
            'js' => base64_encode($js),
        ];

        return $data;
    }
}
