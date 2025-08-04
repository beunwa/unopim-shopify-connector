<?php

namespace Webkul\Prestashop\Helpers\Iterator;

use Webkul\Prestashop\Traits\PrestashopRequest;

class AttributeIterator implements \Iterator
{
    use PrestashopRequest;

    private $page;

    private $currentPageData;

    private $currentKey;

    private $credential;

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
              $parameters = ['page' => $this->page, 'limit' => 50];

              $response = $this->requestPrestashopApiAction('attributes', $this->credential, $parameters);

              $edges = $response['body']['attributes'] ?? [];
              $this->currentPageData = $this->formatedAttributeAndOption($edges);
              // Update page
              $this->page = ! empty($edges) ? $this->page + 1 : null;

          } catch (\Exception $e) {
              error_log($e->getMessage());
          }

          $this->currentKey = 0;
      }

    /**
     * Formating Attribute and attriute Option
     */
    public function formatedAttributeAndOption(array $options): array
    {
        $optionsArray = [];
        foreach ($options as $option) {
            $productOptions = $option['node']['options'];
            foreach ($productOptions as $productOption) {
                if ($productOption['name'] == 'Title' && in_array('Default Title', $productOption['values'])) {
                    continue;
                }

                $modified_array = array_map(function ($string) {
                    return trim(preg_replace('/[^A-Za-z0-9]+/', '-', $string), '-');
                }, $productOption['values'] ?? []);

                $optionsArray[] = [
                    'name' => trim(preg_replace('/[^A-Za-z0-9]+/', '_', $productOption['name'])),
                    'type' => 'select',
                    'code' => $modified_array,
                ];
            }
        }

        return $optionsArray;
    }
}
