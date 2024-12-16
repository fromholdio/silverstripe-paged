<?php

namespace Fromholdio\Paged\Extensions;

use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;

class PaginatedListExtension extends Extension
{
    public function IsFirstPage(): bool
    {
        return !($this->getOwner()->NotFirstPage());
    }

    public function AbsoluteNextLink()
    {
        if ($this->owner->NotLastPage()) {
            return Director::absoluteURL(
                $this->owner->NextLink()
            );
        }
    }

    public function NextPageNum()
    {
        if ($this->owner->NotLastPage()) {
            return $this->owner->CurrentPage() + 1;
        }
    }

    public function AbsolutePrevLink()
    {
        if ($this->owner->NotFirstPage()) {
            return Director::absoluteURL(
                $this->owner->PrevLink()
            );
        }
    }

    public function PrevPageNum()
    {
        if ($this->owner->NotFirstPage()) {
            return $this->owner->CurrentPage() - 1;
        }
    }
}
