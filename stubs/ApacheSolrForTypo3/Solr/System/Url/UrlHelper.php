<?php
declare(strict_types=1);


namespace ApacheSolrForTypo3\Solr\System\Url;

if (class_exists('ApacheSolrForTypo3\Solr\System\Url\UrlHelper')) {
    return;
}


final class UrlHelper
{
    public function removeQueryParameter(string $parameterName): UrlHelper
    {
        return $this;
    }

    public function withoutQueryParameter(string $parameterName): UrlHelper
    {
        return $this;
    }

    public function getUrl(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return '';
    }
}
