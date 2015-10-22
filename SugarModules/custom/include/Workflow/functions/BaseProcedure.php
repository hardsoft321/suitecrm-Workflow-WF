<?php
abstract class BaseProcedure {
    public $event_id;
    public $status1_data;
    public $status2_data;
    public $func_params;
    public abstract function doWork($bean);
}
