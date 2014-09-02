<?php
abstract class BaseProcedure {
    public $status1_data;
    public $status2_data;
    public abstract function doWork($bean);
}
