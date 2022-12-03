<?php
abstract class Report
{
    protected const chartColors = ['#22B14C', '#c23531', '#c5db20', '#f200e2', '#f7ab07','#42fbff', '#1e8ab5', '#bda29a','#6e7074', '#546570', '#c4ccd3'];

    public function getReportItemsHTML()
    {
        yield null;
    }
}