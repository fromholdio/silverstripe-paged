<?php

namespace Fromholdio\Paged\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\PaginatedList;

class PagedControllerExtension extends Extension
{
    /**
     * @var int
     */
    private static $paged_limit = 10;
    private static $paged_source_method = 'getPagedSourceList';

    /**
     * @return PaginatedList
     */
    public function getPagedList()
    {
        $sourceMethod = $this->owner->config()->get('paged_source_method');

        if (!is_string($sourceMethod)) {
            throw new \UnexpectedValueException(
                'Invalid type of $paged_source_method config on '
                . get_class($this->owner) . '. '
                . 'Expecting string but received ' . gettype($sourceMethod)
            );
        }

        if (!$this->owner->hasMethod($sourceMethod)) {
            throw new \BadMethodCallException(
                'The supplied $paged_source_method ' . $sourceMethod
                . ' does not exist on ' . get_class($this->owner)
                . 'PagedControllerExtension requires this to be a valid method.'
            );
        }

        $sourceList = $this->owner->$sourceMethod();

//        if (!is_a($sourceList, DataList::class)) {
//            throw new \UnexpectedValueException(
//                'Expected a DataList return from ' . get_class($this->owner) . '::' . $sourceMethod
//                . '(). Received ' . gettype($sourceList) . ' instead.'
//            );
//        }

        if ($sourceList) {
            $list = PaginatedList::create($sourceList, $this->getOwner()->getRequest());

            $limit = $this->owner->getPagedLimit();
            if ($limit === 0) {
                $limit = 999999999;
            }
            $list->setPageLength($limit);
        }
        else {
            $list = null;
        }

        if ($this->owner->hasMethod('updatePagedList')) {
            $list = $this->owner->updatePagedList($list);
        }

        return $list;
    }

    /**
     * @return int
     */
    public function getPagedLimit()
    {
        if ($this->owner->hasMethod('data')) {
            $data = $this->owner->data();
            if ($data->hasMethod('getPagedLimit')) {
                return (int) $data->getPagedLimit();
            }
        }

        $limit = $this->owner->config()->get('paged_limit');
        if ($this->owner->hasMethod('updatePagedLimit')) {
            $limit = $this->owner->updatePagedLimit($limit);
        }

        return (int) $limit;
    }

    /**
     * @return string|null
     * Returns the absolute link to the next page for use in the
     * page meta tags. This helps search engines find the pagination
     * and index all pages properly.
     * @example "<link rel="next" href="$PaginationAbsoluteNextLink">"
     */
    public function PaginationAbsoluteNextLink(): ?string
    {
        $pagedList = $this->getOwner()->getPagedList();
        return (!is_null($pagedList) && $pagedList->NotLastPage()) ? $pagedList->AbsoluteNextLink() : null;
    }

    /**
     * @return string|null
     * Returns the absolute link to the previous page for use in the
     * page meta tags. This helps search engines find the pagination
     * and index all pages properly.
     * @example "<link rel="prev" href="$PaginationAbsolutePrevLink">"
     */
    public function PaginationAbsolutePrevLink(): ?string
    {
        $pagedList = $this->getOwner()->getPagedList();
        return (!is_null($pagedList) && $pagedList->NotFirstPage()) ? $pagedList->AbsolutePrevLink() : null;
    }
}
