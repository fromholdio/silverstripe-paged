<?php

namespace Fromholdio\Paged\Extensions;

use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;

class PaginatedListExtension extends Extension
{
    public function AbsoluteNextLink()
    {
        if ($this->owner->NotLastPage()) {
            return Director::absoluteURL(
                $this->owner->NextLink()
            );
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
}
