<?php

namespace Fromholdio\Paged\Extensions;

use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\NumericField;

class PagedSiteTreeExtension extends SiteTreeExtension
{
    private static $paged_limit_insert_after = 'MenuTitle';

    private static $db = [
        'PagedLimit' => 'Int'
    ];

    private static $field_labels = [
        'PagedLimit' => 'Per Page Limit'
    ];

    /**
     * @return int
     */
    public function getPagedLimit()
    {
        $limit = $this->owner->dbObject('PagedLimit')->getValue();
        if ($this->owner->hasMethod('updatePagedLimit')) {
            $limit = $this->owner->updatePagedLimit();
        }
        return (int) $limit;
    }

    public function updateCMSFields(FieldList $fields)
    {
        $insertAfter = $this->owner->config()->get('paged_limit_insert_after');
        if ($insertAfter !== false) {
            $fields->insertAfter(
                $insertAfter,
                $this->owner->getPagedLimitField()
            );
        }
    }

    /**
     * @return FormField
     */
    public function getPagedLimitField()
    {
        $field = NumericField::create(
            'PagedLimit',
            $this->owner->fieldLabel('PagedLimit'),
            $this->getPagedDefaultLimit()
        );

        if ($this->owner->hasMethod('updatePagedLimitField')) {
            $field = $this->owner->updatePagedLimitField();
        }

        return $field;
    }

    /**
     * @return int
     */
    public function getPagedDefaultLimit()
    {
        $pagedControllerName = $this->owner->getControllerName();
        $default = Config::inst()->get($pagedControllerName, 'paged_limit');

        if ($this->owner->hasMethod('updatePagedDefaultLimit')) {
            $default = $this->owner->updatePagedDefaultLimit($default);
        }

        return (int) $default;
    }
}
