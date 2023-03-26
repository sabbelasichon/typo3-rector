<?php
declare(strict_types=1);


namespace TYPO3\CMS\Extbase\Annotation\ORM;

if (class_exists('TYPO3\CMS\Extbase\Annotation\ORM\Cascade')) {
    return;
}

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Cascade
{
    /**
     * @Enum({"remove"})
     *
     * Currently, Extbase does only support "remove".
     *
     * Other possible cascade operations would be: "persist", "merge", "detach", "refresh", "all"
     * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/working-with-associations.html#transitive-persistence-cascade-operations
     */
    public $value;

    /**
     * @param array{value?: mixed} $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->value = $values['value'];
        }
    }
}
