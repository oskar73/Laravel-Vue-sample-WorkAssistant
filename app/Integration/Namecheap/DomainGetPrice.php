<?php

namespace App\Integration\Namecheap {

    class DomainGetPrice extends \Namecheap\Command\ACommand
    {
        public $data = [];

        public function command()
        {
            return 'namecheap.users.getPricing';
        }

        public function params()
        {
            return [
                'ProductType' => 'DOMAIN',
                'ProductCategory' => null,
            ];
        }

        /**
         * Process domains array
         */
        protected function _postDispatch()
        {
            if (isset($this->_response->UserGetPricingResult->ProductType->ProductCategory->Product->Price)) {
                foreach ($this->_response->UserGetPricingResult->ProductType->ProductCategory->Product->Price as $item) {
                    $price = [];
                    foreach ($item->attributes() as $key => $value) {
                        $price[$key] = (string) $value;
                    }
                    $this->data[] = $price;
                }
            } else {
                $this->data = null;
            }
        }

        /**
         * Get/set method for domain list, limited to 1024 characters
         *
         * @param string|array $value
         *
         * @return mixed
         */
        public function domainName($value = null)
        {
            if (null !== $value) {
                $this->setParam('DomainName', (string) substr($value, 0, 70));

                return $this;
            }
            $this->getParam('DomainName');
        }
    }
}
