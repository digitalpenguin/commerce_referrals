<?php
namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Widgets\GridWidget;
use modmore\Commerce\Admin\Util\Action;

class ReferrerGrid extends GridWidget {
    public $key = 'referrers-grid';
    public $defaultSort = 'name';

    public function getItems(array $options = array())
    {
        $items = [];

        $c = $this->adapter->newQuery('CommerceReferralsReferrer');
        $count = $this->adapter->getCount('CommerceReferralsReferrer', $c);
        $this->setTotalCount($count);

        $c->limit($options['limit'], $options['start']);
        $collection = $this->adapter->getCollection('CommerceReferralsReferrer', $c);

        foreach ($collection as $object) {
            $items[] = $this->prepareItem($object->toArray());
        }

        return $items;
    }

    public function getColumns(array $options = array())
    {
        return [
            /*[
                'name' => 'id',
                'primary' => true,
                'hidden' => true,
            ],*/
            [
                'name' => 'name',
                'title' => $this->adapter->lexicon('commerce_referrals.name'),
                'sortable' => true,
            ],
            [
                'name' => 'token',
                'title' => $this->adapter->lexicon('commerce_referrals.token'),
                'sortable' => true,
            ],
            [
                'name' => 'email',
                'title' => $this->adapter->lexicon('commerce_referrals.email'),
                'sortable' => true,
            ],
            [
                'name' => 'phone',
                'title' => $this->adapter->lexicon('commerce_referrals.phone'),
                'sortable' => true,
            ],
            [
                'name' => 'referrals',
                'title' => $this->adapter->lexicon('commerce_referrals.referrals'),
                'sortable' => true,
            ],
            [
                'name' => 'latest',
                'title' => $this->adapter->lexicon('commerce_referrals.latest'),
                'sortable' => true,
                'raw' => true,
            ],
            [
                'name' => 'comment',
                'title' => $this->adapter->lexicon('commerce_referrals.comment'),
                'sortable' => false,
            ],

        ];
    }

    public function getTopToolbar(array $options = array())
    {
        $toolbar = [];

        $toolbar[] = [
            'name' => 'add-referrer',
            'title' => $this->adapter->lexicon('commerce_referrals.referrer.add'),
            'type' => 'button',
            'link' => $this->adapter->makeAdminUrl('referrers/create'),
            'button_class' => 'commerce-ajax-modal',
            'icon_class' => 'icon-plus',
            'modal_title' => $this->adapter->lexicon('commerce_referrals.referrer.add'),
            'position' => 'top',
        ];

        $toolbar[] = [
            'name' => 'limit',
            'title' => $this->adapter->lexicon('commerce.limit'),
            'type' => 'textfield',
            'value' => (int)$options['limit'],
            'position' => 'bottom',
            'width' => 'two wide',
        ];

        return $toolbar;
    }

    public function prepareItem($item)
    {

        // Get most recent referral
        $c = $this->adapter->newQuery('CommerceReferralsReferral');
        $c->where([
            'referrer_id'   =>  $item['id']
        ]);
        $c->sortby('referred_on','DESC');
        $referral = $this->adapter->getObject('CommerceReferralsReferral',$c);
        if($referral) {
            $item['latest'] = '<a href="?namespace=commerce&a=index&ca=order&order=' . $referral->get('order') . '">' . $this->adapter->lexicon('commerce_referrals.referral.view_order_details') . '</a>';
        }

        // Add number of successful orders for this referrer
        $item['referrals'] = $this->adapter->getCount('CommerceReferralsReferral',['referrer_id'=>$item['id']]);

        $item['actions'] = [];

        $editUrl = $this->adapter->makeAdminUrl('referrers/update', ['id' => $item['id']]);
        $item['actions'][] = (new Action())
            ->setUrl($editUrl)
            ->setTitle($this->adapter->lexicon('commerce_referrals.referrer.edit'))
            ->setIcon('icon-edit');
        $deleteUrl = $this->adapter->makeAdminUrl('referrers/delete', ['id' => $item['id']]);
        $item['actions'][] = (new Action())
            ->setUrl($deleteUrl)
            ->setTitle($this->adapter->lexicon('commerce_referrals.referrer.delete'))
            ->setIcon('icon-trash');



        return $item;
    }
}
