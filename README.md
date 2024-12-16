# silverstripe-paged

A small SilverStripe module that applies pagination functionalities to a `Controller` and its datalist/s.

* Extends the core `PaginatedList` to also have `AbsoluteNextLink` and `AbsolutePrevLink` (in addition to regular `NextLink` and `PrevLink`)
* Adds `PrevPageNum` and `NextPageNum` to `PaginatedList`
* Adds (`int`) `PagedLimit` to the extended `Page`
* Extends the `PageController` to get a `DataList`, wrap it in a `PaginatedList`, applies the `PagedLimit` and returns it

Note that you do not need to extend a `Page`, you can simply add the extension to a `Controller` that is already returning a `DataList` and the extension will provide the pagination capability.

This module is really just a timesaver for otherwise repetitive and often used functionality.

## Requirements

* [silverstripe-framework](https://github.com/silverstripe/silverstripe-cms) ^4 and ^5

## Installation

`composer require fromholdio/silverstripe-paged`

## Details & Usage

Install, and then apply:

* `PagedSiteTreeExtension` to your `Page` class (or subclass)
* `PagedControllerExtension` to your `PageController` class (or subclass)

On your extended `Controller`, you must set the following config variable to point the pagination to the source `DataList`:

```yml
MyNamespace\MyControllerClass:
  paged_source_method: 'getMyFullDataList'
```

If you do not have a page associated to the controller, you can define a per-page-limit on the controller too:

```yml
MyNamespace\MyControllerClass:
  paged_limit: 12
```

Review the source, you'll find some hooks in there to update the paginated list and or limit from your base/extended classes, too.

More thorough docs to come. In the meantime please submit questions as issues.

## To Do

* Better docs
