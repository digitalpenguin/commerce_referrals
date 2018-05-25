<?php
namespace DigitalPenguin\Referrals\Admin\Referrer\Validate;

use modmore\Commerce\Admin\Widgets\Form\Validation\Rule;

/**
 * Class Unique
 * Ensures the field value doesn't already exist.
 * @package modmore\Commerce\Admin\Widgets\Form\Validation
 */
class Unique extends Rule {

    protected $adapter;
    protected $fieldName;
    protected $classKey;
    protected $referrerId;

    public function __construct(\Commerce $commerce, $classKey ,$fieldName, $referrerId) {
        $this->adapter = $commerce->adapter;
        $this->fieldName = $fieldName;
        $this->classKey = $classKey;
        $this->referrerId  = $referrerId;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function isValid($value)
    {
        $count = $this->adapter->getCount($this->classKey,[
            $this->fieldName.':=' =>  $value,
            'AND:id:!=' =>  $this->referrerId
        ]);
        if($count > 0) {
            return 'commerce_referrals.validation.already_exists';
        }

        return true;
    }
}