<?php
namespace App\Interfaces\Authentication;

use Illuminate\Http\Request;

interface LoginRepositoryInterface
{
    public function login(Request $request);
    public function details(Request $request);
    public function logout(Request $request);
}