<?php

namespace Webkul\Prestashop\Helpers\Iterator;

use Webkul\Prestashop\Traits\PrestashopRequest;

class CategoryIterator implements \Iterator
{
    use PrestashopRequest;

    private $page;                // Tracks the current page for pagination

    private $currentPageData;       // Holds data for the current page

    private $currentKey;            // Tracks the current index within the current page

    private $credential;            // Credentials for PrestaShop API

    private $mergedOptions;

    public function __construct($credential)
    {
        $this->credential = $credential;
        $this->page = 1;       // Start from first page
        $this->currentPageData = [];
        $this->currentKey = 0;
        $this->fetchByCursor();
    }

    public function current(): mixed
    {
        return $this->currentPageData[$this->currentKey] ?? null;
    }

    public function key(): mixed
    {
        return $this->currentKey;
    }

    public function next(): void
    {
        $this->currentKey++;

        if ($this->currentKey >= count($this->currentPageData)) {
            $this->fetchByCursor();
        }
    }

    public function rewind(): void
    {
        if ($this->currentKey == 0) {
            return;
        }
          $this->page = 1;       // Reset to the first page
          $this->currentPageData = [];
          $this->currentKey = 0;
          $this->fetchByCursor();     // Fetch the first page again
    }

    public function valid(): bool
    {
        return ! empty($this->currentPageData);
    }

      public function setCursor($cursor): void
      {
          $this->page = $cursor;
          $this->fetchByCursor();     // Fetch data based on the provided page
      }

      public function getCursor(): ?int
      {
          return $this->page;
      }

    private function fetchByCursor(): void
    {
        $this->currentPageData = [];
          try {
              $parameters = ['page' => $this->page, 'limit' => 10];

              $response = $this->requestPrestashopApiAction('categories', $this->credential, $parameters);

              $categories = $response['body']['categories'] ?? [];

              $this->currentPageData = $categories;
              // Update page
              $this->page = ! empty($categories) ? $this->page + 1 : null;

          } catch (\Exception $e) {
              error_log($e->getMessage());
          }

          $this->currentKey = 0;
      }
}
