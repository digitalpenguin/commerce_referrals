<?php

namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Sections\SimpleSection;
use modmore\Commerce\Admin\Page;

class Update extends Page {
    public $key = 'referrers/update';
    public $title = 'commerce_referrals.referrer.edit';

    public function setUp()
    {
        $objectId = (int)$this->getOption('id', 0);
        $this->adapter->log(1,'id: '.$objectId);
        $exists = $this->adapter->getCount('CommerceReferralsReferrer', ['id' => $objectId]);

        if ($exists) {
            $section = new SimpleSection($this->commerce, [
                'title' => $this->title
            ]);
            $section->addWidget((new Form($this->commerce, ['isUpdate' => true, 'id' => $objectId]))->setUp());
            $this->addSection($section);
            return $this;
        }

        return $this->returnError($this->adapter->lexicon('commerce_referrals.referrer.referrer_not_found'));
    }
}