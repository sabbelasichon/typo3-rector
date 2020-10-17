<?php

abstract class MyAbstractParentClass
{
    public function __wakeup()
    {
    }
}

class MyWakeupCallerClassKeepsIt extends MyAbstractParentClass
{
    public function __wakeup()
    {
        parent::__wakeup();
    }
}

?>
-----
<?php

abstract class MyAbstractParentClass
{
    public function __wakeup()
    {
    }
}

class MyWakeupCallerClassKeepsIt extends MyAbstractParentClass
{
    public function __wakeup()
    {
        parent::__wakeup();
    }
}

?>
