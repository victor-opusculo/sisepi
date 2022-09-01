<?php
abstract class Report
{
    protected const chartColors = ['#22B14C', '#c23531', '#395a5e', '#d48265', '#91c7ae','#749f83', '#ca8622', '#bda29a','#6e7074', '#546570', '#c4ccd3'];

    public function getReportItemsHTML()
    {
        yield null;
    }
}