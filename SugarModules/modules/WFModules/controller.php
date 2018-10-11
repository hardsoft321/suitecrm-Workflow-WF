<?php

class WFModulesController extends SugarController
{
    public function action_createVardefs()
    {
        $this->bean->createUtilityVardefsFile();
    }

    public function post_createVardefs()
    {
        parent::post_save();
    }

    public function action_removeVardefs()
    {
        $this->bean->removeUtilityVardefsFile();
    }

    public function post_removeVardefs()
    {
        parent::post_save();
    }

    public function action_createHooks()
    {
        $this->bean->createLogicHooks();
    }

    public function post_createHooks()
    {
        parent::post_save();
    }

    public function action_removeHooks()
    {
        $this->bean->removeLogicHooks();
    }

    public function post_removeHooks()
    {
        parent::post_save();
    }
}
