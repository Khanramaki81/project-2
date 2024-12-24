<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * @OA\PathItem(path="/api")
     *
     * @OA\Info(
     *      version="0.0.0",
     *      title="API Documentation"
     *  )
     */
    public function index(): string
    {
        return "Documentation API";
    }
}
