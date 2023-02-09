<?php
namespace App\Interfaces\API\V1\Dashboard;

interface StatsRepositoryInterface
{
    public function dashboardInsightsStats();
    public function dashboardConversionMetrics();
}