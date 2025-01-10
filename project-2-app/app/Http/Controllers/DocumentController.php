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
     *
     * @OA\Tag(
     *      name="auth",
     *      description="API Endpoints of users authentication"
     *  ),
     *
     * @OA\Tag(
     *        name="Admin Users Management",
     *        description="API Endpoints of admins"
     *   ),
     *
     * @OA\Tag(
     *      name="Admin Roles & Permissions",
     *      description="Admin Roles & Permissions"
     * ),
     *
     * @OA\Tag(
     *       name="Locations",
     *       description="API Endpoints of Locations"
     *  ),
     *
     */
    public function index(): string
    {
        return "Documentation API";
    }
}
