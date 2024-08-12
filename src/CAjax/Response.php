<?php
namespace Cresenity\Laravel\CAjax;

use Illuminate\Contracts\Support\Responsable;

class Response implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        if ($request->ajax()) {
        } else {
        }
    }
}
