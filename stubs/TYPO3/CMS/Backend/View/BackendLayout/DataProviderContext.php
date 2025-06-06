<?php

namespace TYPO3\CMS\Backend\View\BackendLayout;

if (class_exists('TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext')) {
    return;
}

final class DataProviderContext
{
    public int $pageId;
    public string $tableName;
    public string $fieldName;
    public array $data;
    public array $pageTsConfig;

    public function __construct(
        int $pageId = 0,
        string $tableName = '',
        string $fieldName = '',
        array $data = [],
        array $pageTsConfig = []
    ) {
        $this->pageId = $pageId;
        $this->tableName = $tableName;
        $this->fieldName = $fieldName;
        $this->data = $data;
        $this->pageTsConfig = $pageTsConfig;
    }

    public function getPageId(): int
    {
        return $this->pageId;
    }

    public function setPageId(int $pageId): self
    {
        return $this;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): self
    {
        return $this;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): self
    {
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        return $this;
    }

    public function getPageTsConfig(): array
    {
        return $this->pageTsConfig;
    }

    public function setPageTsConfig(array $pageTsConfig): self
    {
        return $this;
    }
}
