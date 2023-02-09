<?php
namespace App\Interfaces\Partner;

use Illuminate\Http\Request;

interface PartnerRepositoryInterface
{
    public function index();
    public function verifyEmail(Request $request);
    public function getPartnersDashboard();
    public function getTopPerformers();
}