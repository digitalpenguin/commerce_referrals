<?php
namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Widgets\GridWidget;
use modmore\Commerce\Admin\Util\Action;

class ReferrerGrid extends GridWidget {
    public $key = 'referrers';
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
        $item['actions'] = [];

        //if (in_array($this->order->getState(), [\comOrder::STATE_CART, \comOrder::STATE_PROCESSING], true)) {
            $editUrl = $this->adapter->makeAdminUrl('referrers/update', ['id' => $item['id']]);
            $item['actions'][] = (new Action())
                ->setUrl($editUrl)
                ->setTitle($this->adapter->lexicon('commerce_referrals.referrer.edit'))
                ->setIcon('icon-edit');
            $deleteUrl = $this->adapter->makeAdminUrl('referrers/delete', ['id' => $item['id'], 'order' => $item['order']]);
            $item['actions'][] = (new Action())
                ->setUrl($deleteUrl)
                ->setTitle($this->adapter->lexicon('commerce_referrals.referrer.delete'))
                ->setIcon('icon-trash');
        //}


        return $item;
    }
}